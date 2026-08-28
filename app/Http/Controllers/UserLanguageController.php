<?php

namespace App\Http\Controllers;

use App\Constants\AppConstants;
use App\Helpers\ApiResponse;
use App\Services\UserLanguageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class UserLanguageController extends Controller
{
    public function __construct(
        private readonly UserLanguageService $userLanguageService
    ) {
    }

    private function validateUserLanguageFields(Request $request): array
    {
        return $request->validate([
            'language_id' => ['required', 'exists:languages,id'],
            'proficiency_level' => ['required', Rule::in(AppConstants::PROFICIENCY_LEVELS)]
        ]);
        ;
    }

    /**
     * Handles request to get all available languages
     * 
     * @return JsonResponse
     */
    public function getAllLanguages(): JsonResponse
    {
        $languages = $this->userLanguageService->getAllLanguages();

        return ApiResponse::success('Success', $languages)->toJsonResponse();
    }

    /**
     * Handles request to create new user language
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $this->validateUserLanguageFields($request);

        $userProfileId = session('user_profile_id');

        $userLanguage = $this->userLanguageService->createUserLanguage($validated, $userProfileId);

        return ApiResponse::success('Language added to user profile successfully.', $userLanguage, Response::HTTP_CREATED)->toJsonResponse();
    }

    /**
     * Handles request to update user language
     * 
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $this->validateUserLanguageFields($request);

        $userProfileId = session('user_profile_id');

        $userLanguage = $this->userLanguageService->updateUserLanguage($validated, $id, $userProfileId);

        return ApiResponse::success('Language updated successfully.', $userLanguage)->toJsonResponse();
    }

    /**
     * Handles request to delete user language
     * 
     * @param int $id
     * @return JsonResponse
     */
    public function delete(int $id): JsonResponse
    {
        $userProfileId = session('user_profile_id');

        $this->userLanguageService->deleteUserLanguage($id, $userProfileId);

        return ApiResponse::success('Language deleted successfully.', null)->toJsonResponse();
    }
}
