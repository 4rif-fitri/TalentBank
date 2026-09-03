<?php

namespace App\Services;

use App\Constants\AppConstants;
use App\Models\Invitation;
use App\Models\Position;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

class InvitationService
{
    private const ADMINISTRATIVE_ROLES = ['Organization Admin', 'Recruiter'];

    // used to prevent updating invitation that are already accepted, rejected or withdrawn
    private const LOCKED_INVITATION_STATUSES = [
        AppConstants::INVITATION_STATUS['ACCEPTED'],
        AppConstants::INVITATION_STATUS['REJECTED'],
        AppConstants::INVITATION_STATUS['WITHDRAWN']
    ];

    // used to determine the columns to be returned for related models when fetching invitations
    private const PROFILE_RETURN_COLUMNS = 'id,name,profile_image,location,headline';
    private const POSITION_RETURN_COLUMNS = 'positions.id,position_title,organization_id';
    private const ORGANIZATION_RETURN_COLUMNS = 'organizations.id,company_name,organization_logo';

    private function getInvitationModel(int $invitationId, string $profileColumnName, int $userProfileId): Invitation
    {
        $invitation = Invitation::where([
            'id' => $invitationId,
            $profileColumnName => $userProfileId
        ])->first();

        if (!isset($invitation)) {
            throw new Exception('Invitation not found or access unauthorized.', Response::HTTP_NOT_FOUND);
        }

        if (in_array($invitation->invitation_status, self::LOCKED_INVITATION_STATUSES)) {
            throw new Exception('Invitation accepted, rejected or withdrawn cannot be updated anymore.', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $invitation;
    }

    private function updateInvitationStatus(int $invitationId, string $profileColumnName, int $receiverId, string $status): Invitation
    {
        $invitation = $this->getInvitationModel($invitationId, $profileColumnName, $receiverId);

        $invitation->update([
            'invitation_status' => $status,
            'updated_at' => now()
        ]);

        return $invitation;
    }

    /**
     * Returns invitations by receiver's user profile ID
     * 
     * @param int $receiverId
     * @return Collection<int, \stdClass>|\Illuminate\Database\Eloquent\Collection<int, Invitation>
     */
    public function getInvitationsByReceiverId(int $receiverId): Collection
    {
        return Invitation::with([
            'position:' . self::POSITION_RETURN_COLUMNS,
            'sender:' . self::PROFILE_RETURN_COLUMNS,
            'position.organization:' . self::ORGANIZATION_RETURN_COLUMNS
        ])->where('receiver_profile_id', $receiverId)->get();
    }

    /**
     * Returns invitations by sender's user profile ID
     * 
     * @param int $senderId
     * @return Collection<int, \stdClass>|\Illuminate\Database\Eloquent\Collection<int, Invitation>
     */
    public function getInvitationsBySenderId(int $senderId): Collection
    {
        return Invitation::with([
            'position:' . self::POSITION_RETURN_COLUMNS,
            'receiver:' . self::PROFILE_RETURN_COLUMNS,
            'position.organization:' . self::ORGANIZATION_RETURN_COLUMNS
        ])->where('sender_profile_id', $senderId)->get();
    }

    /**
     * Returns an invitation based on invitation ID given
     * 
     * @param int $invitationId
     * @param int $userProfileId
     * @throws Exception
     * @return Invitation|\Illuminate\Database\Eloquent\Builder<Invitation>|\stdClass
     */
    public function getInvitationById(int $invitationId, int $userProfileId): Invitation
    {
        $invitation = Invitation::with([
            'position',
            'receiver:' . self::PROFILE_RETURN_COLUMNS,
            'sender:' . self::PROFILE_RETURN_COLUMNS,
            'position.organization:' . self::ORGANIZATION_RETURN_COLUMNS
        ])
            ->where(function ($query) use ($userProfileId) {
                $query->where('sender_profile_id', $userProfileId)
                    ->orWhere('receiver_profile_id', $userProfileId);
            })
            ->find($invitationId);

        if (!isset($invitation)) {
            throw new Exception('Invitation not found or access unauthorized.', Response::HTTP_NOT_FOUND);
        }

        // to determine whether the current user is the sender or receiver for this invitation
        $invitation->user_role = $invitation->sender_profile_id === $userProfileId ? 'sender' : 'receiver';

        return $invitation;
    }

    /**
     * Returns invitations filtered by invitation status
     * 
     * @param string $invitationStatus
     * @param int $senderId
     * @return Collection<int, \stdClass>|\Illuminate\Database\Eloquent\Collection<int, Invitation>
     */
    public function getInvitationsByStatusAndSenderId(string $invitationStatus, int $senderId): Collection
    {
        if (!in_array($invitationStatus, AppConstants::INVITATION_STATUS)) {
            throw new Exception('Invalid invitation status provided.', Response::HTTP_BAD_REQUEST);
        }

        return Invitation::with([
            'position:' . self::POSITION_RETURN_COLUMNS,
            'receiver:' . self::PROFILE_RETURN_COLUMNS,
            'position.organization:' . self::ORGANIZATION_RETURN_COLUMNS
        ])
            ->where([
                'invitation_status' => $invitationStatus,
                'sender_profile_id' => $senderId
            ])->get();
    }

    /**
     * Creates a new invitation
     * 
     * @param array $data
     * @param int $senderId
     * @throws Exception
     * @return Invitation
     */
    public function createInvitation(array $data, int $senderId): Invitation
    {
        if ($data['receiver_profile_id'] === $senderId) {
            throw new Exception('Users cannot send invitations to themselves.', Response::HTTP_BAD_REQUEST);
        }

        // check if current user has an administrative role
        $isUserOrgAdmin = Position::query()
            ->join('organization_users as ou', 'positions.organization_id', '=', 'ou.organization_id')
            ->join('roles as r', 'r.id', '=', 'ou.role_id')
            ->whereIn('r.name', self::ADMINISTRATIVE_ROLES)
            ->where([
                'ou.user_profile_id' => $senderId,
                'positions.id' => $data['position_id']
            ])
            ->exists();

        if (!$isUserOrgAdmin) {
            throw new Exception('Unauthorized access.', Response::HTTP_FORBIDDEN);
        }

        // create a new invitation
        $invitation = Invitation::create([
            'sender_profile_id' => $senderId,
            'receiver_profile_id' => $data['receiver_profile_id'],
            'invitation_message' => $data['invitation_message'],
            'invitation_status' => AppConstants::INVITATION_STATUS['PENDING'],
            'expires_at' => $data['expires_at'],
            'position_id' => $data['position_id'],
        ]);

        return $invitation->load([
            'position:' . self::POSITION_RETURN_COLUMNS,
            'receiver:' . self::PROFILE_RETURN_COLUMNS
        ]);
    }

    /**
     * Updates existing invitation info (excluding status)
     * 
     * @param array $data
     * @param int $invitationId
     * @param int $senderId
     * @return Invitation
     */
    public function updateInvitation(array $data, int $invitationId, int $senderId): Invitation
    {
        $invitation = $this->getInvitationModel($invitationId, 'sender_profile_id', $senderId);

        $dataToUpdate = [
            'invitation_message' => $data['invitation_message'],
            'expires_at' => $data['expires_at'],
            'updated_at' => now()
        ];

        if (
            isset($data['expires_at']) &&
            Carbon::parse($data['expires_at'])->isFuture() &&
            $invitation->invitation_status === AppConstants::INVITATION_STATUS['EXPIRED']
        ) {
            $dataToUpdate['invitation_status'] = AppConstants::INVITATION_STATUS['PENDING'];
        }

        $invitation->update($dataToUpdate);

        return $invitation;
    }

    /**
     * Updates invitation status to Accepted
     * 
     * @param int $invitationId
     * @param int $receiverId
     * @return Invitation
     */
    public function acceptInvitation(int $invitationId, int $receiverId): Invitation
    {
        return $this->updateInvitationStatus($invitationId, 'receiver_profile_id', $receiverId, AppConstants::INVITATION_STATUS['ACCEPTED']);
    }

    /**
     * Updates invitation status to Rejected
     * 
     * @param int $invitationId
     * @param int $receiverId
     * @return Invitation
     */
    public function rejectInvitation(int $invitationId, int $receiverId): Invitation
    {
        return $this->updateInvitationStatus($invitationId, 'receiver_profile_id', $receiverId, AppConstants::INVITATION_STATUS['REJECTED']);
    }

    /**
     * Updates invitation status to Withdrawn
     * @param int $invitationId
     * @param int $senderId
     * @return Invitation
     */
    public function withdrawInvitation(int $invitationId, int $senderId): Invitation
    {
        return $this->updateInvitationStatus($invitationId, 'sender_profile_id', $senderId, AppConstants::INVITATION_STATUS['WITHDRAWN']);
    }
}
