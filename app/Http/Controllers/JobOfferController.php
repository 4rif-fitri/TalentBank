<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Services\JobOfferService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JobOfferController extends Controller
{
    public function __construct(
        private readonly JobOfferService $jobOfferService
    ) {
    }

    /**
     * Handles request to get job offers where current user is the sender
     *
     * @return JsonResponse
     */
    public function getJobOffersBySenderId(): JsonResponse
    {
        $senderId = session('user_profile_id');
        $jobOffers = $this->jobOfferService->getJobOffersBySenderId($senderId);

        return ApiResponse::success('Success.', $jobOffers)->toJsonResponse();
    }

    /**
     * Handles request to get job offers where current user is the receiver
     *
     * @return JsonResponse
     */
    public function getJobOffersByReceiverId(): JsonResponse
    {
        $receiverId = session('user_profile_id');
        $jobOffers = $this->jobOfferService->getJobOffersByReceiverId($receiverId);

        return ApiResponse::success('Success.', $jobOffers)->toJsonResponse();
    }

    /**
     * Handles request to get job offers by status for the current user
     *
     * @param string $status
     * @return JsonResponse
     */
    public function getJobOffersByStatus(string $status): JsonResponse
    {
        $userProfileId = session('user_profile_id');
        $jobOffers = $this->jobOfferService->getJobOffersByStatus($status, $userProfileId);

        return ApiResponse::success('Success.', $jobOffers)->toJsonResponse();
    }

    /**
     * Handles request to get job offer by job offer ID
     *
     * @param int $id
     * @return JsonResponse
     */
    public function getJobOfferById(int $id): JsonResponse
    {
        $userProfileId = session('user_profile_id');
        $jobOffer = $this->jobOfferService->getJobOfferById($id, $userProfileId);

        return ApiResponse::success('Success.', $jobOffer)->toJsonResponse();
    }

    /**
     * Handles request to create a new job offer
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'invitation_id' => ['required', 'integer', 'exists:invitations,id'],
            'salary_amount' => ['required', 'numeric', 'min:0'],
            'salary_period' => ['required', 'string'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'terms_and_conditions' => ['nullable', 'string'],
            'benefits' => ['nullable', 'string'],
            'expires_at' => ['required', 'date', 'after:now'],
        ]);

        $senderId = session('user_profile_id');
        $jobOffer = $this->jobOfferService->createJobOffer($validated, $senderId);

        return ApiResponse::success('Job offer created successfully.', $jobOffer)->toJsonResponse();
    }

    /**
     * Handles request to update existing job offer info
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'salary_amount' => ['required', 'numeric', 'min:0'],
            'salary_period' => ['required', 'string'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'terms_and_conditions' => ['nullable', 'string'],
            'benefits' => ['nullable', 'string'],
            'expires_at' => ['required', 'date', 'after:now'],
        ]);

        $senderId = session('user_profile_id');
        $jobOffer = $this->jobOfferService->updateJobOffer($validated, $id, $senderId);

        return ApiResponse::success('Job offer updated successfully.', $jobOffer)->toJsonResponse();
    }

    /**
     * Handles request to accept a job offer
     *
     * @param int $id
     * @return JsonResponse
     */
    public function acceptJobOffer(int $id): JsonResponse
    {
        $receiverId = session('user_profile_id');
        $jobOffer = $this->jobOfferService->acceptJobOffer($id, $receiverId);

        return ApiResponse::success('Job offer accepted.', $jobOffer)->toJsonResponse();
    }

    /**
     * Handles request to reject a job offer
     *
     * @param int $id
     * @return JsonResponse
     */
    public function rejectJobOffer(int $id): JsonResponse
    {
        $receiverId = session('user_profile_id');
        $jobOffer = $this->jobOfferService->rejectJobOffer($id, $receiverId);

        return ApiResponse::success('Job offer rejected.', $jobOffer)->toJsonResponse();
    }

    /**
     * Handles request to withdraw a job offer
     *
     * @param int $id
     * @return JsonResponse
     */
    public function withdrawJobOffer(int $id): JsonResponse
    {
        $senderId = session('user_profile_id');
        $jobOffer = $this->jobOfferService->withdrawJobOffer($id, $senderId);

        return ApiResponse::success('Job offer withdrawn.', $jobOffer)->toJsonResponse();
    }
}