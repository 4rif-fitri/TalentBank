<?php

namespace App\Http\Controllers;

use App\Http\Helpers\ApiResponse;
use App\Http\Services\ProgrammeService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProgrammeController extends Controller
{
    private ProgrammeService $programmeService;

    public function __construct(ProgrammeService $programmeService)
    {
        $this->programmeService = $programmeService;
    }

    /**
     * Handles request for get all programmes with education and semesters
     * 
     * @param Request $request
     * @param int $userId
     * 
     * @return JsonResponse
     */
    public function getProgrammesByUserIdJson(int $userId, Request $request)
    {
        try {
            $search = $request->input('search');
            $session = $request->input('session');

            if (!isset($userId)) {
                throw new Exception('User ID required.', Response::HTTP_BAD_REQUEST);
            }

            $programmes = $this->programmeService->getProgrammesByUserId($userId, $search, $session);

            return ApiResponse::success('Success', $programmes)->toJsonResponse();
        } catch (Exception $e) {
            return ApiResponse::error('Failed to get programmes. ' . $e->getMessage(), ApiResponse::getValidatedStatusCode($e))->toJsonResponse();
        }
    }

    /**
     * Handles request to get programmes by organization ID.
     * 
     * @param int $orgId
     * @throws Exception
     * @return JsonResponse
     */
    public function getProgrammesByOrgId(int $orgId)
    {
        try {
            if (!isset($orgId)) {
                throw new Exception('Organization ID required.', Response::HTTP_BAD_REQUEST);
            }

            $programmes = $this->programmeService->getProgrammesByOrgId($orgId);

            return ApiResponse::success('Success.', $programmes)->toJsonResponse();
        } catch (Exception $e) {
            return ApiResponse::error('Failed to get programmes. ' . $e->getMessage(), ApiResponse::getValidatedStatusCode($e))->toJsonResponse();
        }
    }
}
