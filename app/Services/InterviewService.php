<?php

namespace App\Services;

use App\Constants\AppConstants;
use App\Helpers\CheckOrgRoleHelper;
use App\Models\Interview;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

class InterviewService
{
    private const ADMINISTRATIVE_ROLES = ['Organization Admin', 'Recruiter'];
    private const LOCKED_STATUS = [AppConstants::INTERVIEW_STATUS['COMPLETED'], AppConstants::INTERVIEW_STATUS['CANCELLED']];

    public function __construct(
        private readonly InvitationService $invitationService
    ) {
    }

    private function getInterviewModel(int $interviewId, int $senderId): Interview
    {
        $interview = Interview::with('invitation.position')
            ->whereHas('invitation', function ($query) use ($senderId) {
                $query->where('sender_profile_id', $senderId);
            })->find($interviewId);

        if (!isset($interview)) {
            throw new Exception('Interview not found or access unauthorized.', Response::HTTP_NOT_FOUND);
        }

        if (in_array($interview->interview_status, self::LOCKED_STATUS)) {
            throw new Exception('Interview completed or cancelled cannot be updated anymore.', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $interview;
    }

    private function updateInterviewStatus(int $interviewId, string $status, int $senderId): Interview
    {
        $interview = $this->getInterviewModel($interviewId, $senderId);

        $isUserAdmin = CheckOrgRoleHelper::userHasRoles($senderId, self::ADMINISTRATIVE_ROLES, $interview->invitation->position->organization_id);

        if (!$isUserAdmin) {
            throw new Exception('Unauthorized access to update interview.', Response::HTTP_FORBIDDEN);
        }

        $interview->update(['interview_status' => $status]);

        return $interview->unsetRelation('invitation');
    }

    public function getInterviewsBySenderId(int $senderId): Collection
    {
        return Interview::with([
            'invitation:id,position_id,receiver_profile_id',
            'invitation.position:id,position_title',
            'invitation.receiver:id,name,profile_image'
        ])
            ->whereHas('invitation', function ($query) use ($senderId) {
                $query->where('sender_profile_id', $senderId);
            })
            ->get();
    }

    public function getInterviewsByReceiverId(int $receiverId): Collection
    {
        return Interview::with([
            'invitation:id,position_id,receiver_profile_id',
            'invitation.position:id,position_title',
            'invitation.receiver:id,name,profile_image'
        ])
            ->whereHas('invitation', function ($query) use ($receiverId) {
                $query->where('receiver_profile_id', $receiverId);
            })
            ->get();
    }

    public function getInterviewById(int $interviewId, int $userProfileId): Interview
    {
        $interview = Interview::with([
            'invitation:id,position_id,receiver_profile_id,sender_profile_id',
            'invitation.position',
            'invitation.receiver:id,name,profile_image',
            'invitation.sender:id,name,profile_image'
        ])
            ->whereHas('invitation', function ($query) use ($userProfileId) {
                $query->where('sender_profile_id', $userProfileId)
                    ->orWhere('receiver_profile_id', $userProfileId);
            })
            ->find($interviewId);

        if (!isset($interview)) {
            throw new Exception('Interview not found or access unauthorized on interview.', Response::HTTP_NOT_FOUND);
        }

        $interview->user_role = $interview->invitation->sender_profile_id === $userProfileId ? 'sender' : 'receiver';

        return $interview;
    }

    public function createInterview(array $data, int $senderId): Interview
    {
        $invitation = $this->invitationService->getInvitationById($data['invitation_id'], $senderId);

        // check if invitation's sender is current user
        if ($invitation->sender_profile_id !== $senderId) {
            throw new Exception('Unauthorized access to create interview for this invitation.', Response::HTTP_FORBIDDEN);
        }

        // check if current user is still an admin role of the current org
        $isUserAdmin = CheckOrgRoleHelper::userHasRoles($senderId, self::ADMINISTRATIVE_ROLES, $invitation->position->organization_id);

        if (!$isUserAdmin) {
            throw new Exception('Unauthorized access to create interview.', Response::HTTP_FORBIDDEN);
        }

        // create interview
        $interview = Interview::create([
            'invitation_id' => $data['invitation_id'],
            'scheduled_at' => $data['scheduled_at'],
            'interview_mode' => $data['interview_mode'],
            'location' => $data['location'],
            'meeting_url' => $data['meeting_url'],
            'interview_status' => AppConstants::INTERVIEW_STATUS['SCHEDULED'],
            'interview_result' => AppConstants::INTERVIEW_RESULTS['PENDING'],
            'recruiter_comment' => $data['recruiter_comment'],
        ]);

        return $interview->load([
            'invitation:id,position_id,receiver_profile_id',
            'invitation.position:id,position_title',
            'invitation.receiver:id,name,profile_image'
        ]);
    }

    public function updateInterview(array $data, int $interviewId, int $senderId): Interview
    {
        $interview = $this->getInterviewModel($interviewId, $senderId);

        $isUserAdmin = CheckOrgRoleHelper::userHasRoles($senderId, self::ADMINISTRATIVE_ROLES, $interview->invitation->position->organization_id);

        if (!$isUserAdmin) {
            throw new Exception('Unauthorized access to update interview.', Response::HTTP_FORBIDDEN);
        }

        $interview->update([
            'scheduled_at' => $data['scheduled_at'],
            'interview_mode' => $data['interview_mode'],
            'location' => $data['location'],
            'meeting_url' => $data['meeting_url'],
            'interview_result' => $data['interview_result'],
            'recruiter_comment' => $data['recruiter_comment'],
        ]);

        return $interview->unsetRelation('invitation');
    }

    public function completeInterview(int $interviewId, int $senderId): Interview
    {
        return $this->updateInterviewStatus($interviewId, AppConstants::INTERVIEW_STATUS['COMPLETED'], $senderId);
    }

    public function cancelInterview(int $interviewId, int $senderId): Interview
    {
        return $this->updateInterviewStatus($interviewId, AppConstants::INTERVIEW_STATUS['CANCELLED'], $senderId);
    }
}
