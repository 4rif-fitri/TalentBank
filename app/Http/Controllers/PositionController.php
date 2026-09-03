<?php

namespace App\Http\Controllers;

use App\Constants\AppConstants;
use App\Helpers\ApiResponse;
use App\Services\PositionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class PositionController extends Controller
{
    public function __construct(
        private readonly PositionService $positionService
    ) {
    }

    /**
     * Handles request to get all positions available
     * 
     * @return JsonResponse
     */
    public function getAllPositions(): JsonResponse
    {
        $positions = $this->positionService->getAllPositions();
        return ApiResponse::success('Success.', $positions)->toJsonResponse();
    }

    /**
     * Handles request to get positions by organization ID
     * 
     * @param int $id
     * @return JsonResponse
     */
    public function getPositionsByOrgId(int $id): JsonResponse
    {
        $positions = $this->positionService->getPositionsByOrgId($id);
        return ApiResponse::success('Success.', $positions)->toJsonResponse();
    }

    /**
     * Handles request to get position by ID
     * 
     * @param int $id
     * @return JsonResponse
     */
    public function getPositionById(int $id): JsonResponse
    {
        $userProfileId = session('user_profile_id');
        $position = $this->positionService->getPositionById($id, $userProfileId);
        return ApiResponse::success('Success.', $position)->toJsonResponse();
    }

    /**
     * Handles request to create a new position
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'organization_id' => ['required', 'integer', 'exists:organizations,id'],
            'position_title' => ['required', 'string', 'max:255'],
            'employment_type' => ['required', 'string', Rule::in(AppConstants::EMPLOYMENT_TYPES)],
            'department' => ['required', 'string', 'max:255'],
            'work_location' => ['required', 'string', 'max:255'],
            'vacancies' => ['required', 'integer', 'min:1'],
            'description' => ['nullable', 'string'],
        ]);

        $userProfileId = session('user_profile_id');
        $position = $this->positionService->createPosition($validated, $userProfileId);

        return ApiResponse::success('Position created successfully.', $position, Response::HTTP_CREATED)->toJsonResponse();
    }

    /**
     * Handles request to update position info
     * 
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'position_title' => ['required', 'string', 'max:255'],
            'employment_type' => ['required', 'string', Rule::in(AppConstants::EMPLOYMENT_TYPES)],
            'department' => ['required', 'string', 'max:255'],
            'work_location' => ['required', 'string', 'max:255'],
            'vacancies' => ['required', 'integer', 'min:1'],
            'description' => ['nullable', 'string'],
        ]);

        $userProfileId = session('user_profile_id');
        $position = $this->positionService->updatePosition($validated, $id, $userProfileId);

        return ApiResponse::success('Position updated successfully.', $position)->toJsonResponse();
    }
}
