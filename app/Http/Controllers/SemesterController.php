<?php

namespace App\Http\Controllers;

use App\Http\Helpers\ApiResponse;
use App\Services\SemesterService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SemesterController extends Controller
{
    public function __construct(
        private readonly SemesterService $semesterService
    ) {
    }

    /**
     * Handles request to upload results file
     * 
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function uploadResults(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'result_file' => ['required', 'mimes:pdf', 'max:2048'],
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        if (!$request->hasFile('result_file')) {
            throw new Exception('A file must be uploaded.', Response::HTTP_BAD_REQUEST);
        }

        $userProfileId = session('user_profile_id');

        $resultFile = $this->semesterService->uploadResults($validated, $request->file('result_file'), $id, $userProfileId);

        return ApiResponse::success('Result file uploaded successfully.', $resultFile)->toJsonResponse();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'education_id' => ['required', 'exists:education,id'],
            'gpa' => ['required', 'numeric', 'between:0,4.00', 'decimal:0,2'],
            'session' => ['required', 'string']
        ]);

        $semester = $this->semesterService->createSemester($validated);

        return ApiResponse::success('Semester created successfully.', $semester, Response::HTTP_CREATED)->toJsonResponse();
    }

    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'gpa' => ['required', 'numeric', 'between:0,4.00', 'decimal:0,2'],
            'session' => ['required', 'string']
        ]);

        $userProfileId = session('user_profile_id');

        $semester = $this->semesterService->updateSemester($validated, $id, $userProfileId);

        return ApiResponse::success('Semester updated successfully.', $semester)->toJsonResponse();
    }
}
