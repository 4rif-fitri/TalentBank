<?php

namespace App\Http\Controllers;

use App\Http\Helpers\ApiResponse;
use App\Http\Services\SemesterService;
use Exception;
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
     * @return \Illuminate\Http\RedirectResponse
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

            $results = $this->semesterService->uploadResults($validated, $request->file('result_file'), $id);

            return redirect()->back()->with(ApiResponse::success('Results uploaded successfully.', null)->toArray());
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }
}
