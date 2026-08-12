<?php

namespace App\Http\Controllers;

use App\Http\Helpers\ApiResponse;
use App\Http\Services\FacultyService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FacultyController extends Controller
{
    private FacultyService $facultyService;

    public function __construct(FacultyService $facultyService)
    {
        $this->facultyService = $facultyService;
    }

    /**
     * Handle request to get all faculties by organization ID.
     * 
     * @param Request $request
     * 
     * @return JsonResponse
     */
    public function getFacultiesByOrgIdJson(Request $request)
    {
        try {
            $orgId = $request->input('org_id');

            if (!isset($orgId)) {
                return ApiResponse::error('Organization ID required.')->toJsonResponse();
            }

            $faculties = $this->facultyService->getFacultiesByOrgId($orgId);

            return ApiResponse::success('Success.', $faculties)->toJsonResponse();
        } catch (Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode())->toJsonResponse();
        }
    }

    /**
     * Handle request to get faculty by faculty ID.
     * 
     * @param int $id
     * 
     * @return JsonResponse
     */
    public function getFacultyByIdJson(int $id)
    {
        try {
            if (!isset($id)) {
                return ApiResponse::error('Faculty ID required.')->toJsonResponse();
            }

            $faculty = $this->facultyService->getFacultyById($id);

            return ApiResponse::success('Success.', $faculty)->toJsonResponse();
        } catch (Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode())->toJsonResponse();
        }
    }

    /**
     * Handle request to create a new faculty.
     * 
     * @param Request $request
     * 
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'organization_id' => ['required', 'exists:organizations,id'],
                'faculty_name' => ['required', 'string'],
                'faculty_code' => ['required', 'string'],
            ]);

            $faculty = $this->facultyService->createFaculty($validated);

            return redirect()->back()->with(ApiResponse::success('Faculty created successfully', $faculty, 201)->toArray());
        } catch (Exception $e) {
            return redirect()->back()->withErrors('Failed to create faculty. ' . $e->getMessage(), $e->getCode());
        }
    }

    /**
     * Handle request to update an existing faculty info.
     * 
     * @param Request $request
     * 
     * @return RedirectResponse
     */
    public function update(Request $request, int $id)
    {
        try {
            $validated = $request->validate([
                'faculty_name' => ['required', 'string'],
                'faculty_code' => ['required', 'string'],
            ]);

            $this->facultyService->updateFaculty($id, $validated);

            return redirect()->back()->with(ApiResponse::success('Faculty updated successfully', null)->toArray());
        } catch (Exception $e) {
            return redirect()->back()->withErrors('Failed to update faculty. ' . $e->getMessage(), $e->getCode());
        }
    }
}
