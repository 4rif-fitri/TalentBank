<?php

namespace App\Http\Controllers;

use App\Http\Helpers\ApiResponse;
use App\Http\Services\SemesterService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SemesterController extends Controller
{
    private SemesterService $semesterService;

    public function __construct(SemesterService $semesterService)
    {
        $this->semesterService = $semesterService;
    }

    /**
     * Handles request to upload results file
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function uploadResults(Request $request, int $id)
    {
        try {
            $validated = $request->validate([
                'result_file' => ['required', 'mimes:pdf', 'max:2048'],
                'title' => ['nullable', 'string', 'max:255'],
                'description' => ['nullable', 'string'],
            ]);

            if (!$request->hasFile('result_file')) {
                throw new Exception('A file must be uploaded.', Response::HTTP_BAD_REQUEST);
            }

            if (!isset($id)) {
                throw new Exception('Semester ID is required.', Response::HTTP_BAD_REQUEST);
            }

            $this->semesterService->uploadResults($validated, $request->file('result_file'), $id);

            return ApiResponse::success('File uploaded successfully.', null)->toJsonResponse();
        } catch (Exception $e) {
            return ApiResponse::error($e->getMessage(), ApiResponse::getValidatedStatusCode($e))->toJsonResponse();
        }
    }
}
