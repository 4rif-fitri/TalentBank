<?php

namespace App\Http\Controllers;

use App\Http\Helpers\ApiResponse;
use App\Http\Services\ProfileService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProfileController extends Controller
{
    private ProfileService $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    public function index()
    {
        return view();
    }

    /**
     * Handles request to get profile data based on given user ID 
     * (or current user ID if one is not provided)
     * 
     * @param   int $id
     * @return  JsonResponse
     */
    public function getProfileDataByProfileIdJson(int $id)
    {
        try {
            $profile = $this->profileService->getProfileDataByProfileIdJson($id);

            return ApiResponse::success('Success.', $profile)->toJsonResponse();
        } catch (Exception $e) {
            return ApiResponse::error($e->getMessage(), ApiResponse::getValidatedStatusCode($e))->toJsonResponse();
        }
    }

    /**
     * Handles request to update profile data for current logged in user
     * 
     * @param   Request $request
     * @return  JsonResponse
     */
    public function update(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255'],
                'headline' => ['nullable', 'string', 'max:255'],
                'location' => ['nullable', 'string', 'max:255'],
                'phone_no' => ['nullable', 'string', 'max:11'],
            ]);

            $this->profileService->updateProfileData($validated);

            return ApiResponse::success('Profile updated successfully.', null)->toJsonResponse();
        } catch (Exception $e) {
            return ApiResponse::error($e->getMessage(), ApiResponse::getValidatedStatusCode($e))->toJsonResponse();
        }
    }

    /**
     * Handles request to update the about field of a user profile
     * @param Request $request
     * @return JsonResponse
     */
    public function updateAboutField(Request $request)
    {
        try {
            $validated = $request->validate([
                'about' => ['nullable', 'max:255']
            ]);

            $this->profileService->updateAboutField($validated['about']);

            return ApiResponse::success('About saved successfully.', null)->toJsonResponse();
        } catch (Exception $e) {
            return ApiResponse::error($e->getMessage(), ApiResponse::getValidatedStatusCode($e))->toJsonResponse();
        }
    }

    /**
     * Handles request to upload user' profile image
     * @param Request $request
     * @return JsonResponse
     */
    public function uploadProfileImage(Request $request)
    {
        try {
            $request->validate([
                'profile_image' => ['required', 'image', 'max:2048']
            ]);

            $file = $request->file('profile_image');

            $this->profileService->uploadProfileImage($file);

            return ApiResponse::success('Profile image uploaded successfully.', null)->toJsonResponse();
        } catch (Exception $e) {
            return ApiResponse::error($e->getMessage(), ApiResponse::getValidatedStatusCode($e))->toJsonResponse();
        }
    }

    /**
     * Handles request to upload user' cover image
     * @param Request $request
     * @return JsonResponse
     */
    public function uploadCoverImage(Request $request)
    {
        try {
            $request->validate([
                'cover_image' => ['required', 'image', 'max:2048']
            ]);

            $file = $request->file('cover_image');

            $this->profileService->uploadCoverImage($file);

            return ApiResponse::success('Cover image uploaded successfully.', null)->toJsonResponse();
        } catch (Exception $e) {
            return ApiResponse::error($e->getMessage(), ApiResponse::getValidatedStatusCode($e))->toJsonResponse();
        }
    }
}
