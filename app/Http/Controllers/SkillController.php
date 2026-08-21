<?php

namespace App\Http\Controllers;

use App\Http\Helpers\ApiResponse;
use App\Services\SkillService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class SkillController extends Controller
{
    private const ALLOWED_SOURCE_TYPE = [
        'user_profile',
        'education',
        'experience',
        'project',
        'honors_award',
        'certification'
    ];

    public function __construct(
        private readonly SkillService $skillService
    ) {
    }

    private function validateUserSkillFields(Request $request): array
    {
        return $request->validate([
            'source_type' => ['required', Rule::in($this::ALLOWED_SOURCE_TYPE)],
            'source_id' => ['required', 'integer', 'min:1'],
            'skill_id' => ['required', 'exists:skills,id']
        ]);
    }

    /**
     * Handles request to get all skills available
     * 
     * @return JsonResponse
     */
    public function getAllSkills(): JsonResponse
    {
        $skills = $this->skillService->getAllSkills();

        return ApiResponse::success('Success.', $skills)->toJsonResponse();
    }

    /**
     * Handles request to create new user skill
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        // validate input
        $validated = $this->validateUserSkillFields($request);

        $userSkill = $this->skillService->createUserSkill($validated);

        return ApiResponse::success('User skill added successfully.', $userSkill, Response::HTTP_CREATED)->toJsonResponse();
    }

    /**
     * Handles request to update user skill
     * 
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $this->validateUserSkillFields($request);

        $userProfileId = session('user_profile_id');

        $userSkill = $this->skillService->updateUserSkill($validated, $id, $userProfileId);

        return ApiResponse::success('User skill updated successfully.', $userSkill)->toJsonResponse();
    }

    /**
     * Handles request to delete user skill
     * 
     * @param int $id
     * @return JsonResponse
     */
    public function delete(int $id): JsonResponse
    {
        $userProfileId = session('user_profile_id');
        $this->skillService->deleteUserSkill($id, $userProfileId);

        return ApiResponse::success('User skill deleted successfully.', null)->toJsonResponse();
    }
}
