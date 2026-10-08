<?php

namespace App\Http\Controllers;

use App\Constants\AppConstants;
use App\Helpers\ApiResponse;
use App\Services\InterviewService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class InterviewController extends Controller
{
    public function __construct(
        private readonly InterviewService $interviewService
    ) {
    }

    /**
     * Handles request to get interviews by status where current user is the interviewer
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function getInterviewsByStatusAndInterviewerId(Request $request): JsonResponse
    {
        $interviewerId = session('user_profile_id');
        $status = $request->query('status');
        $interviews = $this->interviewService->getInterviewsByStatusAndInterviewerId($interviewerId, $status);

        return ApiResponse::success('Success.', $interviews)->toJsonResponse();
    }

    /**
     * Handles request to get interviews by status where current user is the interviewee
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function getInterviewsByStatusAndIntervieweeId(Request $request): JsonResponse
    {
        $intervieweeId = session('user_profile_id');
        $status = $request->query('status');
        $interviews = $this->interviewService->getInterviewsByStatusAndIntervieweeId($intervieweeId, $status);

        return ApiResponse::success('Success.', $interviews)->toJsonResponse();
    }

    /**
     * Handles request to get interview by interview ID
     *
     * @param int $id
     * @return JsonResponse
     */
    public function getInterviewById(int $id): JsonResponse
    {
        $userProfileId = session('user_profile_id');
        $interview = $this->interviewService->getInterviewById($id, $userProfileId);

        return ApiResponse::success('Success.', $interview)->toJsonResponse();
    }

    /**
     * Handles request to get interviews by position ID and interviewee's profile ID
     * 
     * @param int $intervieweeId
     * @param int $positionId
     * @return JsonResponse
     */
    public function getInterviewsByPositionIdAndIntervieweeId(int $intervieweeId, int $positionId): JsonResponse
    {
        $currentUserProfileId = session('user_profile_id');
        $interviews = $this->interviewService->getInterviewsByPositionIdAndIntervieweeId($intervieweeId, $positionId, $currentUserProfileId);
        return ApiResponse::success('Success.', $interviews)->toJsonResponse();
    }

    /**
     * Handles request to create a new interview
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'position_id' => ['required', 'integer', 'exists:positions,id'],
            'scheduled_at' => ['required', 'date', 'after:now'],
            'interview_mode' => ['required', 'string', Rule::in(AppConstants::INTERVIEW_MODES)],
            'location' => ['nullable', 'string', 'required_if:interview_mode,' . AppConstants::INTERVIEW_MODES[1]],
            'meeting_url' => ['nullable', 'string', 'url', 'required_if:interview_mode,' . AppConstants::INTERVIEW_MODES[0]],
            'recruiter_comment' => ['nullable', 'string'],
            'interviewee_profile_id' => ['required', 'integer', 'exists:user_profiles,id'],
        ]);

        $senderId = session('user_profile_id');
        $interview = $this->interviewService->createInterview($validated, $senderId);

        return ApiResponse::success('Interview created successfully.', $interview, Response::HTTP_CREATED)->toJsonResponse();
    }

    /**
     * Handles request to update existing interview info (excluding status)
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'scheduled_at' => ['required', 'date', 'after:now'],
            'interview_mode' => ['required', 'string', Rule::in(AppConstants::INTERVIEW_MODES)],
            'location' => ['nullable', 'string', 'required_if:interview_mode,' . AppConstants::INTERVIEW_MODES[1]],
            'meeting_url' => ['nullable', 'string', 'url', 'required_if:interview_mode,' . AppConstants::INTERVIEW_MODES[0]],
            'interview_result' => ['nullable', 'string', Rule::in(AppConstants::INTERVIEW_RESULTS)],
            'recruiter_comment' => ['nullable', 'string'],
        ]);

        $senderId = session('user_profile_id');
        $interview = $this->interviewService->updateInterview($validated, $id, $senderId);

        return ApiResponse::success('Interview updated successfully.', $interview)->toJsonResponse();
    }

    /**
     * Handles request to mark interview as completed
     *
     * @param int $id
     * @return JsonResponse
     */
    public function completeInterview(int $id): JsonResponse
    {
        $senderId = session('user_profile_id');
        $interview = $this->interviewService->completeInterview($id, $senderId);

        return ApiResponse::success('Interview marked as completed.', $interview)->toJsonResponse();
    }

    /**
     * Handles request to cancel an interview
     *
     * @param int $id
     * @return JsonResponse
     */
    public function cancelInterview(int $id): JsonResponse
    {
        $senderId = session('user_profile_id');
        $interview = $this->interviewService->cancelInterview($id, $senderId);

        return ApiResponse::success('Interview cancelled.', $interview)->toJsonResponse();
    }
}
