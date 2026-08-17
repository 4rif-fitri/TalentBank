<?php

namespace App\Http\Controllers;

use App\Http\Helpers\ApiResponse;
use App\Http\Services\EducationService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class EducationController extends Controller
{
    private EducationService $educationService;

    public function __construct(EducationService $educationService)
    {
        $this->educationService = $educationService;
    }

    private function validateEducationFields($request)
    {
        return $request->validate([
            'programme_id' => ['required', 'exists:programmes,id'],
            'description' => ['nullable', 'string'],
            'field_of_study_id' => ['nullable', 'exists:field_of_studies,id'],
            'qualification_id' => ['nullable', 'exists:qualifications,id'],
            'cgpa' => ['nullable', 'numeric', 'between:0,4.00', 'decimal:0,2'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date'],
            'enrollment_status' => ['required', 'string', Rule::in(['Active', 'Graduated', 'Deferred', 'Withdrawn'])],
        ]);
    }

    /**
     * Handles request to get education by user profile ID
     *
     * @param int $id
     * @throws Exception
     * @return \Illuminate\Http\JsonResponse
     */
    public function getEducationByUserProfileId(int $id)
    {
        try {
            if (!isset($id)) {
                throw new Exception('User profile ID required.');
            }

            $education = $this->educationService->getEducationByUserProfileId($id);

            return ApiResponse::success('Success.', $education)->toJsonResponse();
        } catch (Exception $e) {
            return ApiResponse::error($e->getMessage(), ApiResponse::getValidatedStatusCode($e))->toJsonResponse();
        }
    }

    /**
     * Handles request to get education by education ID
     *
     * @param int $id
     * @throws Exception
     * @return \Illuminate\Http\JsonResponse
     */
    public function getEducationById(int $id)
    {
        try {
            if (!isset($id)) {
                throw new Exception('Education ID required.');
            }

            $education = $this->educationService->getEducationById($id);

            return ApiResponse::success('Success.', $education)->toJsonResponse();
        } catch (Exception $e) {
            return ApiResponse::error($e->getMessage(), ApiResponse::getValidatedStatusCode($e))->toJsonResponse();
        }
    }

    /**
     * Handles request to create a new education
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $validated = $this->validateEducationFields($request);

            $education = $this->educationService->createEducation($validated);

            return ApiResponse::success('Education created successfully.', $education, Response::HTTP_CREATED)->toJsonResponse();
        } catch (Exception $e) {
            return ApiResponse::error($e->getMessage(), ApiResponse::getValidatedStatusCode($e))->toJsonResponse();
        }
    }

    /**
     * Handles request to update existing education
     *
     * @param Request $request
     * @param int $id
     * @throws Exception
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, int $id)
    {
        try {
            $validated = $this->validateEducationFields($request);

            if (!isset($id)) {
                throw new Exception('User profile ID required.');
            }

            $this->educationService->updateEducation($id, $validated);

            return ApiResponse::success('Education updated successfully.', null)->toJsonResponse();
        } catch (Exception $e) {
            return ApiResponse::error($e->getMessage(), ApiResponse::getValidatedStatusCode($e))->toJsonResponse();
        }
    }

    /**
     * Handles request to delete existing education
     *
     * @param int $id
     * @throws Exception
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(int $id)
    {
        try {
            if (!isset($id)) {
                throw new Exception('User profile ID required.');
            }

            $this->educationService->deleteEducation($id);

            return ApiResponse::success('Education deleted successfully.', null)->toJsonResponse();
        } catch (Exception $e) {
            return ApiResponse::error($e->getMessage(), ApiResponse::getValidatedStatusCode($e))->toJsonResponse();
        }
    }

    /**
     * Handles request to get all field of studies
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllFieldOfStudies()
    {
        try {
            $fieldOfStudies = $this->educationService->getAllFieldOfStudies();
            return ApiResponse::success('Success.', $fieldOfStudies)->toJsonResponse();
        } catch (Exception $e) {
            return ApiResponse::error($e->getMessage(), ApiResponse::getValidatedStatusCode($e))->toJsonResponse();
        }
    }

    /**
     * Handles request to get all qualifications

     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllQualifications()
    {
        try {
            $qualifications = $this->educationService->getAllQualifications();
            return ApiResponse::success('Success.', $qualifications)->toJsonResponse();
        } catch (Exception $e) {
            return ApiResponse::error($e->getMessage(), ApiResponse::getValidatedStatusCode($e))->toJsonResponse();
        }
    }
}
