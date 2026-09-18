<?php

namespace App\Http\Controllers;

use App\Constants\AppConstants;
use App\Helpers\ApiResponse;
use App\Services\ResumeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class ResumeController extends Controller
{
    private const RESUME_CONTENT_SOURCE = [
        'education',
        'social_media_link',
        'user_skill',
        'user_language'
    ];

    public function __construct(
        private readonly ResumeService $resumeService
    ) {
    }

    /**
     * Handles request to get resumes by user profile ID
     * 
     * @param int $id
     * @return JsonResponse
     */
    public function getResumesByUserProfileId(int $id): JsonResponse
    {
        $resumes = $this->resumeService->getResumesByUserProfileId($id);
        return ApiResponse::success('Success.', $resumes)->toJsonResponse();
    }

    /**
     * Handles request to get resume by ID
     * 
     * @param int $id
     * @return JsonResponse
     */
    public function getResumeById(int $id): JsonResponse
    {
        $userProfileId = session('user_profile_id');
        $resume = $this->resumeService->getResumeById($id, $userProfileId);
        return ApiResponse::success('Success.', $resume)->toJsonResponse();
    }

    /**
     * Handles request to create resume
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'content_to_add' => ['required', 'array'],
            'content_to_add.*' => ['required', 'array'],
            'content_to_add.*.source_type' => ['required', 'string', Rule::in(self::RESUME_CONTENT_SOURCE)],
            'content_to_add.*.source_id' => ['required', 'int']
        ]);

        $userProfileId = session('user_profile_id');
        $resume = $this->resumeService->createResume($validated, $userProfileId);
        return ApiResponse::success('Resume created successfully.', $resume, Response::HTTP_CREATED)->toJsonResponse();
    }

    /**
     * Handles request to update resume content
     * 
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'content_to_add' => ['nullable', 'array', 'required_without:content_ids_to_delete'],
            'content_to_add.*' => ['nullable', 'array'],
            'content_to_add.*.source_type' => ['required', 'string', Rule::in(self::RESUME_CONTENT_SOURCE)],
            'content_to_add.*.source_id' => ['required', 'int'],

            'content_ids_to_delete' => ['nullable', 'array', 'required_without:content_to_add'],
            'content_ids_to_delete.*' => ['nullable', 'int'],
        ]);

        $userProfileId = session('user_profile_id');
        $resume = $this->resumeService->updateResumeContent($validated, $id, $userProfileId);
        return ApiResponse::success('Resume updated successfully.', $resume)->toJsonResponse();
    }

    /**
     * Handles request to delete resume and resume content
     * 
     * @param int $id
     * @return JsonResponse
     */
    public function delete(int $id): JsonResponse
    {
        $userProfileId = session('user_profile_id');
        $resume = $this->resumeService->deleteResume($id, $userProfileId);
        return ApiResponse::success('Resume deleted successfully.', $resume)->toJsonResponse();
    }
}
