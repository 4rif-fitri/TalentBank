<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Services\FacultyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FacultyController extends Controller
{
    public function __construct(
        private readonly FacultyService $facultyService
    ) {
    }

    /**
     * Handle request to get all faculties by organization ID.
     * 
     * @param Request $request
     * @param int $id
     * 
     * @return JsonResponse
     */
    public function getFacultiesByOrgId(Request $request, int $id): JsonResponse
    {
        $faculties = $this->facultyService->getFacultiesByOrgId($id);

        return ApiResponse::success('Success.', $faculties)->toJsonResponse();
    }

    /**
     * Handle request to get faculty by faculty ID.
     * 
     * @param int $id
     * 
     * @return JsonResponse
     */
    public function getFacultyById(int $id): JsonResponse
    {
        $faculty = $this->facultyService->getFacultyById($id);

        return ApiResponse::success('Success.', $faculty)->toJsonResponse();
    }

    /**
     * Handle request to create a new faculty.
     * 
     * @param Request $request
     * 
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'organization_id' => ['required', 'exists:organizations,id'],
            'faculty_name' => ['required', 'string'],
            'faculty_code' => ['required', 'string'],
        ]);

        $faculty = $this->facultyService->createFaculty($validated);

        return ApiResponse::success('Faculty created successfully.', $faculty, Response::HTTP_CREATED)->toJsonResponse();
    }

    /**
     * Handle request to update an existing faculty info.
     * 
     * @param Request $request
     * 
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'faculty_name' => ['required', 'string'],
            'faculty_code' => ['required', 'string'],
        ]);

        $faculty = $this->facultyService->updateFaculty($id, $validated);

        return ApiResponse::success('Faculty updated successfully.', $faculty)->toJsonResponse();
    }
}
