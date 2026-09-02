<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Services\ProgrammeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProgrammeController extends Controller
{
    public function __construct(
        private readonly ProgrammeService $programmeService
    ) {
    }

    /**
     * Handles request for get all programmes with education and semesters
     * 
     * @param Request $request
     * @param int $id
     * 
     * @return JsonResponse
     */
    public function getProgrammesByUserProfileId(int $id, Request $request): JsonResponse
    {
        $search = $request->input('search');
        $session = $request->input('session');

        $programmes = $this->programmeService->getProgrammesByUserProfileId($id, $search, $session);

        return ApiResponse::success('Success', $programmes)->toJsonResponse();
    }

    /**
     * Handles request to get programmes by organization ID.
     * 
     * @param int $orgId
     * @return JsonResponse
     */
    public function getProgrammesByOrgId(int $orgId): JsonResponse
    {
        $programmes = $this->programmeService->getProgrammesByOrgId($orgId);

        return ApiResponse::success('Success.', $programmes)->toJsonResponse();
    }

    /**
     * Handles request to get all field of studies.
     *
     * @return JsonResponse
     */
    public function getAllFieldOfStudies(): JsonResponse
    {
        $fieldOfStudies = $this->programmeService->getAllFieldOfStudies();

        return ApiResponse::success('Success.', $fieldOfStudies)->toJsonResponse();
    }

    /**
     * Handles request to get all qualifications.
     *
     * @return JsonResponse
     */
    public function getAllQualifications(): JsonResponse
    {
        $qualifications = $this->programmeService->getAllQualifications();

        return ApiResponse::success('Success.', $qualifications)->toJsonResponse();
    }
}
