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

    // used to determine the columns to be returned for related models
    private const POSITION_RETURN_COLUMNS = 'positions.id,position_title,organization_id,department,employment_type';
    private const PROFILE_RETURN_COLUMNS = 'id,name,profile_image,location,headline';
    private const ORGANIZATION_RETURN_COLUMNS = 'id,company_name,organization_logo';

    public function __construct(
        private readonly PositionService $positionService
    ) {
    }

    private function getInterviewModel(int $interviewId, int $interviewerId): Interview
    {
        $interview = Interview::with('position:' . self::POSITION_RETURN_COLUMNS)
            ->where('interviewer_profile_id', $interviewerId)
            ->find($interviewId);

        if (!isset($interview)) {
            throw new Exception('Interview not found or access unauthorized.', Response::HTTP_NOT_FOUND);
        }

        if (in_array($interview->interview_status, self::LOCKED_STATUS)) {
            throw new Exception('Interview completed or cancelled cannot be updated anymore.', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $interview;
    }

    private function updateInterviewStatus(int $interviewId, string $status, int $interviewerId): Interview
    {
        $interview = $this->getInterviewModel($interviewId, $interviewerId);

        $isUserAdmin = CheckOrgRoleHelper::userHasRoles($interviewerId, self::ADMINISTRATIVE_ROLES, $interview->position->organization_id);

        if (!$isUserAdmin) {
            throw new Exception('Unauthorized access to update interview.', Response::HTTP_FORBIDDEN);
        }

        $interview->update(['interview_status' => $status]);

        return $interview->unsetRelation('position');
    }

    /**
     * Returnes interview based on interviewer's user profile ID
     * 
     * @param int $interviewerId
     * @return Collection<int, \stdClass>|\Illuminate\Database\Eloquent\Collection<int, Interview>
     */
    public function getInterviewsByStatusAndInterviewerId(int $interviewerId, ?string $status): Collection
    {
        return Interview::with([
            'position:' . self::POSITION_RETURN_COLUMNS,
            'interviewee:' . self::PROFILE_RETURN_COLUMNS,
            'position.organization:' . self::ORGANIZATION_RETURN_COLUMNS,
        ])
            ->where('interviewer_profile_id', $interviewerId)
            ->when(isset($status), function ($query) use ($status) {
                $query->where('interview_status', $status);
            })
            ->get();
    }

    /**
     * Returns interview based on interviewee's user profile ID
     * 
     * @param int $intervieweeId
     * @return Collection<int, \stdClass>|\Illuminate\Database\Eloquent\Collection<int, Interview>
     */
    public function getInterviewsByStatusAndIntervieweeId(int $intervieweeId, ?string $status): Collection
    {
        return Interview::with([
            'position:' . self::POSITION_RETURN_COLUMNS,
            'interviewer:' . self::PROFILE_RETURN_COLUMNS,
            'position.organization:' . self::ORGANIZATION_RETURN_COLUMNS,
        ])
            ->where('interviewee_profile_id', $intervieweeId)
            ->when(isset($status), function ($query) use ($status) {
                $query->where('interview_status', $status);
            })
            ->get();
    }

    /**
     * Returns interview based on interview ID
     * 
     * @param int $interviewId
     * @param int $userProfileId
     * @throws Exception
     * @return Interview|\Illuminate\Database\Eloquent\Builder<Interview>
     */
    public function getInterviewById(int $interviewId, int $userProfileId): Interview
    {
        $interview = Interview::with([
            'position:' . self::POSITION_RETURN_COLUMNS,
            'interviewee:' . self::PROFILE_RETURN_COLUMNS,
            'interviewer:' . self::PROFILE_RETURN_COLUMNS,
            'position.organization:' . self::ORGANIZATION_RETURN_COLUMNS,
        ])
            ->where(function ($query) use ($userProfileId) {
                $query->where('interviewer_profile_id', $userProfileId)
                    ->orWhere('interviewee_profile_id', $userProfileId);
            })
            ->find($interviewId);

        if (!isset($interview)) {
            throw new Exception('Interview not found or access unauthorized on interview.', Response::HTTP_NOT_FOUND);
        }

        $interview->user_role = $interview->interviewer_profile_id === $userProfileId ? 'interviewer' : 'interviewee';

        return $interview;
    }

    /**
     * Returns interview filtered by interview status
     * 
     * @param string $interviewStatus
     * @param int $userProfileId
     * @throws Exception
     * @return Collection<int, \stdClass>|\Illuminate\Database\Eloquent\Collection<int, Interview>
     */
    // public function getInterviewsByStatus(string $interviewStatus, int $userProfileId): Collection
    // {
    //     if (!in_array($interviewStatus, AppConstants::INTERVIEW_STATUS)) {
    //         throw new Exception('Invalid interview status.', Response::HTTP_BAD_REQUEST);
    //     }

    //     return Interview::with([
    //         'position:' . self::POSITION_RETURN_COLUMNS,
    //         'interviewee:' . self::PROFILE_RETURN_COLUMNS,
    //         'position.organization:' . self::ORGANIZATION_RETURN_COLUMNS,
    //     ])
    //         ->where('interview_status', $interviewStatus)
    //         ->get();
    // }

    /**
     * Creates a new interview
     * 
     * @param array $data
     * @param int $interviewerId
     * @throws Exception
     * @return Interview
     */
    public function createInterview(array $data, int $interviewerId): Interview
    {
        $position = $this->positionService->getPositionById($data['position_id'], $interviewerId);

        // check if current user is still an admin role of the current org
        $isUserAdmin = CheckOrgRoleHelper::userHasRoles($interviewerId, self::ADMINISTRATIVE_ROLES, $position->organization_id);

        if (!$isUserAdmin) {
            throw new Exception('Unauthorized access to create interview.', Response::HTTP_FORBIDDEN);
        }

        // create interview
        $interview = Interview::create([
            'scheduled_at' => $data['scheduled_at'],
            'interview_mode' => $data['interview_mode'],
            'location' => $data['location'] ?? null,
            'meeting_url' => $data['meeting_url'] ?? null,
            'interview_status' => AppConstants::INTERVIEW_STATUS['SCHEDULED'],
            'interview_result' => AppConstants::INTERVIEW_RESULTS['PENDING'],
            'recruiter_comment' => $data['recruiter_comment'] ?? null,
            'position_id' => $data['position_id'],
            'interviewee_profile_id' => $data['interviewee_profile_id'],
            'interviewer_profile_id' => $interviewerId,
        ]);

        return $interview->load([
            'position:' . self::POSITION_RETURN_COLUMNS,
            'interviewee:' . self::PROFILE_RETURN_COLUMNS
        ]);
    }

    /**
     * Updates an existing interview (excluding status)
     * 
     * @param array $data
     * @param int $interviewId
     * @param int $interviewerId
     * @throws Exception
     * @return Interview
     */
    public function updateInterview(array $data, int $interviewId, int $interviewerId): Interview
    {
        $interview = $this->getInterviewModel($interviewId, $interviewerId);

        $isUserAdmin = CheckOrgRoleHelper::userHasRoles($interviewerId, self::ADMINISTRATIVE_ROLES, $interview->position->organization_id);

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

        return $interview->unsetRelation('position');
    }

    /**
     * Marks the interview as complete
     * 
     * @param int $interviewId
     * @param int $interviewerId
     * @return Interview
     */
    public function completeInterview(int $interviewId, int $interviewerId): Interview
    {
        return $this->updateInterviewStatus($interviewId, AppConstants::INTERVIEW_STATUS['COMPLETED'], $interviewerId);
    }

    /**
     * Cancels the interview
     * 
     * @param int $interviewId
     * @param int $interviewerId
     * @return Interview
     */
    public function cancelInterview(int $interviewId, int $interviewerId): Interview
    {
        return $this->updateInterviewStatus($interviewId, AppConstants::INTERVIEW_STATUS['CANCELLED'], $interviewerId);
    }
}
