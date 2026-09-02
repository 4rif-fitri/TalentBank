<?php

namespace App\Http\Controllers;

use App\Constants\AppConstants;
use App\Helpers\ApiResponse;
use App\Services\InterviewService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class InterviewController extends Controller
{
    public function __construct(
        private readonly InterviewService $interviewService
    ) {}

    /**
     * Handles request to get interviews where current user is the sender
     *
     * @return JsonResponse
     */
    public function getInterviewsBySenderId(): JsonResponse
    {
        $senderId = session('user_profile_id');
        $interviews = $this->interviewService->getInterviewsBySenderId($senderId);

        return ApiResponse::success('Success.', $interviews)->toJsonResponse();
    }

    /**
     * Handles request to get interviews where current user is the receiver
     *
     * @return JsonResponse
     */
    public function getInterviewsByReceiverId(): JsonResponse
    {
        $receiverId = session('user_profile_id');
        $interviews = $this->interviewService->getInterviewsByReceiverId($receiverId);

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
     * Handles request to get interview by status
     * 
     * @param string $status
     * @return JsonResponse
     */
    public function getInterviewsByStatus(string $status): JsonResponse
    {
        $userProfileId = session('user_profile_id');
        $interviews = $this->interviewService->getInterviewsByStatus($status, $userProfileId);

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
            'invitation_id' => ['required', 'integer', 'exists:invitations,id'],
            'scheduled_at' => ['required', 'date', 'after:now'],
            'interview_mode' => ['required', 'string', Rule::in(AppConstants::INTERVIEW_MODES)],
            'location' => ['nullable', 'string', 'required_if:interview_mode,On-site'],
            'meeting_url' => ['nullable', 'string', 'url', 'required_if:interview_mode,Online'],
            'recruiter_comment' => ['nullable', 'string'],
        ]);

        $senderId = session('user_profile_id');
        $interview = $this->interviewService->createInterview($validated, $senderId);

        return ApiResponse::success('Interview created successfully.', $interview)->toJsonResponse();
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
            'scheduled_at' => ['required', 'date', 'after:now'],
            'interview_mode' => ['required', 'string', Rule::in(AppConstants::INTERVIEW_MODES)],
            'location' => ['nullable', 'string'],
            'meeting_url' => ['nullable', 'string', 'url'],
            'interview_result' => ['required', 'string', Rule::in(AppConstants::INTERVIEW_RESULTS)],
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
