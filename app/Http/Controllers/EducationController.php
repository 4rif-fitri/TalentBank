<?php

namespace App\Http\Controllers;

use App\Constants\AppConstants;
use App\Http\Helpers\ApiResponse;
use App\Services\EducationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class EducationController extends Controller
{
    public function __construct(
        private readonly EducationService $educationService
    ) {
    }

    private function validateEducationFields(Request $request): array
    {
        return $request->validate([
            'programme_id' => ['required', 'exists:programmes,id'],
            'description' => ['nullable', 'string'],
            'field_of_study_id' => ['nullable', 'exists:field_of_studies,id'],
            'qualification_id' => ['nullable', 'exists:qualifications,id'],
            'cgpa' => ['nullable', 'numeric', 'between:0,4.00', 'decimal:0,2'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date'],
            'enrollment_status' => ['required', 'string', Rule::in(AppConstants::ENROLLMENT_STATUS)],
            'media' => ['nullable']
        ]);
    }

    /**
     * Handles request to get education by user profile ID
     *
     * @param int $id
     * @return JsonResponse
     */
    public function getEducationByUserProfileId(int $id): JsonResponse
    {
        $education = $this->educationService->getEducationByUserProfileId($id);

        return ApiResponse::success('Success.', $education)->toJsonResponse();
    }

    /**
     * Handles request to get education by education ID
     *
     * @param int $id
     * @return JsonResponse
     */
    public function getEducationById(int $id): JsonResponse
    {
        $education = $this->educationService->getEducationById($id);

        return ApiResponse::success('Success.', $education)->toJsonResponse();
    }

    /**
     * Handles request to create a new education
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $this->validateEducationFields($request);
        $userProfileId = session('user_profile_id');

        $education = $this->educationService->createEducation($validated, $userProfileId);

        return ApiResponse::success('Education created successfully.', $education, Response::HTTP_CREATED)->toJsonResponse();
    }

    /**
     * Handles request to update existing education
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $this->validateEducationFields($request);
        $userProfileId = session('user_profile_id');

        $this->educationService->updateEducation($id, $validated, $userProfileId);

        return ApiResponse::success('Education updated successfully.', null)->toJsonResponse();
    }

    /**
     * Handles request to delete existing education
     *
     * @param int $id
     * @return JsonResponse
     */
    public function delete(int $id): JsonResponse
    {
        $userProfileId = session('user_profile_id');
        $this->educationService->deleteEducation($id, $userProfileId);

        return ApiResponse::success('Education deleted successfully.', null)->toJsonResponse();
    }

    /**
     * Handles request to get all field of studies
     *
     * @return JsonResponse
     */
    public function getAllFieldOfStudies(): JsonResponse
    {
        $fieldOfStudies = $this->educationService->getAllFieldOfStudies();
        return ApiResponse::success('Success.', $fieldOfStudies)->toJsonResponse();
    }

    /**
     * Handles request to get all qualifications

     * @return JsonResponse
     */
    public function getAllQualifications(): JsonResponse
    {
        $qualifications = $this->educationService->getAllQualifications();
        return ApiResponse::success('Success.', $qualifications)->toJsonResponse();
    }
}
