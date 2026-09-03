<?php

namespace App\Services;

use App\Constants\AppConstants;
use App\Helpers\CheckOrgRoleHelper;
use App\Models\JobOffer;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

class JobOfferService
{
    private const ADMINISTRATIVE_ROLES = ['Organization Admin', 'Recruiter'];
    private const LOCKED_STATUS = [
        AppConstants::JOB_OFFER_STATUS['ACCEPTED'],
        AppConstants::JOB_OFFER_STATUS['REJECTED'],
        AppConstants::JOB_OFFER_STATUS['WITHDRAWN'],
    ];

    // used to determine the columns to be returned for related models when fetching invitations
    private const INVITATION_RETURN_COLUMNS = 'invitations.id,position_id,receiver_profile_id,sender_profile_id';
    private const POSITION_RETURN_COLUMNS = 'positions.id,position_title';
    private const PROFILE_RETURN_COLUMNS = 'id,name,profile_image,location,headline';

    public function __construct(
        private readonly InvitationService $invitationService
    ) {
    }

    /**
     * Retrieves a job offer scoped to the given profile column/id and ensures it is still in an editable state
     *
     * @param int $jobOfferId
     * @param string $profileColumn
     * @param int $userProfileId
     * @return JobOffer
     * @throws Exception if job offer is not found, access is unauthorized, or job offer is locked
     */
    private function getJobOfferModel(int $jobOfferId, string $profileColumn, int $userProfileId): JobOffer
    {
        $jobOffer = JobOffer::with('invitation.position')
            ->where($profileColumn, $userProfileId)
            ->find($jobOfferId);

        if (!isset($jobOffer)) {
            throw new Exception('Job offer not found or access unauthorized.', Response::HTTP_NOT_FOUND);
        }

        if (in_array($jobOffer->offer_status, self::LOCKED_STATUS)) {
            throw new Exception('Job offer accepted, rejected or withdrawn cannot be updated anymore.', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $jobOffer;
    }

    /**
     * Updates the offer_status of a job offer after validating ownership and admin role
     *
     * @param int $jobOfferId
     * @param string $status
     * @param string $profileColumn
     * @param int $senderId
     * @return JobOffer
     * @throws Exception if access is unauthorized
     */
    private function updateJobOfferStatus(int $jobOfferId, string $status, string $profileColumn, int $senderId): JobOffer
    {
        $jobOffer = $this->getJobOfferModel($jobOfferId, $profileColumn, $senderId);

        $isUserAdmin = CheckOrgRoleHelper::userHasRoles($senderId, self::ADMINISTRATIVE_ROLES, $jobOffer->invitation->position->organization_id);

        if (!$isUserAdmin) {
            throw new Exception('Unauthorized access to update interview.', Response::HTTP_FORBIDDEN);
        }

        $jobOffer->update(['offer_status' => $status]);

        return $jobOffer->unsetRelation('invitation');
    }

    /**
     * Retrieves job offers where the given profile is the sender
     *
     * @param int $senderId
     * @return Collection
     */
    public function getJobOffersBySenderId(int $senderId): Collection
    {
        return JobOffer::with([
            'invitation:' . self::INVITATION_RETURN_COLUMNS,
            'invitation.position:' . self::POSITION_RETURN_COLUMNS,
            'invitation.receiver:' . self::PROFILE_RETURN_COLUMNS,
        ])
            ->whereHas('invitation', function ($query) use ($senderId) {
                $query->where('sender_profile_id', $senderId);
            })
            ->get();
    }

    /**
     * Retrieves job offers where the given profile is the receiver
     *
     * @param int $receiverId
     * @return Collection
     */
    public function getJobOffersByReceiverId(int $receiverId): Collection
    {
        return JobOffer::with([
            'invitation:' . self::INVITATION_RETURN_COLUMNS,
            'invitation.position:' . self::POSITION_RETURN_COLUMNS,
            'invitation.sender:' . self::PROFILE_RETURN_COLUMNS,
        ])
            ->whereHas('invitation', function ($query) use ($receiverId) {
                $query->where('receiver_profile_id', $receiverId);
            })
            ->get();
    }

    /**
     * Retrieves job offers matching the given status where the current user is the sender or receiver
     *
     * @param string $offerStatus
     * @param int $userProfileId
     * @return Collection
     */
    public function getJobOffersByStatus(string $offerStatus, int $userProfileId): Collection
    {
        return JobOffer::with([
            'invitation:' . self::INVITATION_RETURN_COLUMNS,
            'invitation.position:' . self::POSITION_RETURN_COLUMNS,
            'invitation.sender:' . self::PROFILE_RETURN_COLUMNS,
            'invitation.receiver:' . self::PROFILE_RETURN_COLUMNS,
        ])
            ->where('offer_status', $offerStatus)
            ->whereHas('invitation', function ($query) use ($userProfileId) {
                $query->where(function ($query) use ($userProfileId) {
                    $query->where('sender_profile_id', $userProfileId)
                        ->orWhere('receiver_profile_id', $userProfileId);
                });
            })
            ->get();
    }

    /**
     * Retrieves a job offer by job offer ID, accessible to either the sender or receiver
     *
     * @param int $jobOfferId
     * @param int $userProfileId
     * @return JobOffer
     * @throws Exception if job offer is not found or access is unauthorized
     */
    public function getJobOfferById(int $jobOfferId, int $userProfileId): JobOffer
    {
        $jobOffer = JobOffer::with([
            'invitation:' . self::INVITATION_RETURN_COLUMNS,
            'invitation.position:' . self::POSITION_RETURN_COLUMNS,
            'invitation.sender:' . self::PROFILE_RETURN_COLUMNS,
            'invitation.receiver:' . self::PROFILE_RETURN_COLUMNS,
        ])
            ->whereHas('invitation', function ($query) use ($userProfileId) {
                $query->where('sender_profile_id', $userProfileId)
                    ->orWhere('receiver_profile_id', $userProfileId);
            })
            ->find($jobOfferId);

        if (!isset($jobOffer)) {
            throw new Exception('Job offer not found or access unauthorized on job offer.', Response::HTTP_NOT_FOUND);
        }

        $jobOffer->user_role = $jobOffer->sender_profile_id === $userProfileId ? 'sender' : 'receiver';

        return $jobOffer;
    }

    /**
     * Creates a new job offer for the given invitation
     *
     * @param array $data
     * @param int $senderId
     * @return JobOffer
     * @throws Exception if the invitation does not belong to the current user, or the current user is not an admin role
     */
    public function createJobOffer(array $data, int $senderId): JobOffer
    {
        $invitation = $this->invitationService->getInvitationById($data['invitation_id'], $senderId);

        // check if invitation's sender is current user
        if ($invitation->sender_profile_id !== $senderId) {
            throw new Exception('Unauthorized access to create job offer for this invitation.', Response::HTTP_FORBIDDEN);
        }

        // check if current user is still an admin role of the current org
        $isUserAdmin = CheckOrgRoleHelper::userHasRoles($senderId, self::ADMINISTRATIVE_ROLES, $invitation->position->organization_id);

        if (!$isUserAdmin) {
            throw new Exception('Unauthorized access to create job offer.', Response::HTTP_FORBIDDEN);
        }

        // create job offer
        $jobOffer = JobOffer::create([
            'invitation_id' => $data['invitation_id'],
            'sender_profile_id' => $senderId,
            'receiver_profile_id' => $invitation->receiver_profile_id,
            'salary_amount' => $data['salary_amount'],
            'salary_period' => $data['salary_period'],
            'start_date' => $data['start_date'] ?? null,
            'end_date' => $data['end_date'] ?? null,
            'terms_and_conditions' => $data['terms_and_conditions'] ?? null,
            'benefits' => $data['benefits'] ?? null,
            'offer_status' => AppConstants::JOB_OFFER_STATUS['PENDING'],
            'expires_at' => $data['expires_at'],
        ]);

        return $jobOffer->load([
            'invitation:' . self::INVITATION_RETURN_COLUMNS,
            'invitation.position:' . self::POSITION_RETURN_COLUMNS,
            'invitation.receiver:' . self::PROFILE_RETURN_COLUMNS,
        ]);
    }

    /**
     * Updates existing job offer info (excluding status), only allowed by the sender with an admin role
     *
     * @param array $data
     * @param int $jobOfferId
     * @param int $senderId
     * @return JobOffer
     * @throws Exception if access is unauthorized
     */
    public function updateJobOffer(array $data, int $jobOfferId, int $senderId): JobOffer
    {
        $jobOffer = $this->getJobOfferModel($jobOfferId, 'sender_profile_id', $senderId);

        $isUserAdmin = CheckOrgRoleHelper::userHasRoles($senderId, self::ADMINISTRATIVE_ROLES, $jobOffer->invitation->position->organization_id);

        if (!$isUserAdmin) {
            throw new Exception('Unauthorized access to update job offer.', Response::HTTP_FORBIDDEN);
        }

        $jobOffer->update([
            'salary_amount' => $data['salary_amount'],
            'salary_period' => $data['salary_period'],
            'start_date' => $data['start_date'] ?? null,
            'end_date' => $data['end_date'] ?? null,
            'terms_and_conditions' => $data['terms_and_conditions'] ?? null,
            'benefits' => $data['benefits'] ?? null,
            'expires_at' => $data['expires_at'],
        ]);

        return $jobOffer->unsetRelation('invitation');
    }

    /**
     * Marks a job offer as accepted, scoped to the receiver
     *
     * @param int $jobOfferId
     * @param int $receiverId
     * @return JobOffer
     */
    public function acceptJobOffer(int $jobOfferId, int $receiverId): JobOffer
    {
        return $this->updateJobOfferStatus($jobOfferId, AppConstants::JOB_OFFER_STATUS['ACCEPTED'], 'receiver_profile_id', $receiverId);
    }

    /**
     * Marks a job offer as rejected, scoped to the receiver
     *
     * @param int $jobOfferId
     * @param int $receiverId
     * @return JobOffer
     */
    public function rejectJobOffer(int $jobOfferId, int $receiverId): JobOffer
    {
        return $this->updateJobOfferStatus($jobOfferId, AppConstants::JOB_OFFER_STATUS['REJECTED'], 'receiver_profile_id', $receiverId);
    }

    /**
     * Marks a job offer as withdrawn, scoped to the sender
     *
     * @param int $jobOfferId
     * @param int $senderId
     * @return JobOffer
     */
    public function withdrawJobOffer(int $jobOfferId, int $senderId): JobOffer
    {
        return $this->updateJobOfferStatus($jobOfferId, AppConstants::JOB_OFFER_STATUS['WITHDRAWN'], 'sender_profile_id', $senderId);
    }
}