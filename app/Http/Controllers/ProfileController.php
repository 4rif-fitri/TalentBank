<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Services\ProfileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function __construct(
        private readonly ProfileService $profileService
    ) {
    }

    /**
     * Handles request to get profile data based on given user ID 
     * (or current user ID if one is not provided)
     * 
     * @param   int $id
     * @return  JsonResponse
     */
    public function getProfileDataByProfileId(int $id): JsonResponse
    {
        $profile = $this->profileService->getProfileDataByProfileId($id);

        return ApiResponse::success('Success.', $profile)->toJsonResponse();
    }

    /**
     * Handles request to get all student and alumni user profiles
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function getAllStudentUserProfiles(Request $request): JsonResponse
    {
        $searchParams = $request->query('searchParams', []);
        $profiles = $this->profileService->getAllStudentUserProfiles($searchParams);
        return ApiResponse::success('Success.', $profiles)->toJsonResponse();
    }

    /**
     * Handles request to update profile data for current logged in user
     * 
     * @param   Request $request
     * @return  JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'headline' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'phone_no' => ['nullable', 'string', 'max:11'],
        ]);

        $profileId = session('user_profile_id');

        $profile = $this->profileService->updateProfileData($validated, $profileId);

        return ApiResponse::success('Profile updated successfully.', $profile)->toJsonResponse();
    }

    /**
     * Handles request to update the about field of a user profile
     * @param Request $request
     * @return JsonResponse
     */
    public function updateAboutField(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'about' => ['nullable', 'max:255']
        ]);

        $profileId = session('user_profile_id');

        $profile = $this->profileService->updateAboutField($validated['about'], $profileId);

        return ApiResponse::success('About saved successfully.', $profile)->toJsonResponse();
    }

    /**
     * Handles request to upload user' profile image
     * @param Request $request
     * @return JsonResponse
     */
    public function uploadProfileImage(Request $request): JsonResponse
    {
        $request->validate([
            'profile_image' => ['required', 'image', 'max:2048']
        ]);

        $file = $request->file('profile_image');
        $profileId = session('user_profile_id');

        $profile = $this->profileService->uploadProfileImage($file, $profileId);

        return ApiResponse::success('Profile image uploaded successfully.', $profile)->toJsonResponse();
    }

    /**
     * Handles request to upload user' cover image
     * @param Request $request
     * @return JsonResponse
     */
    public function uploadCoverImage(Request $request): JsonResponse
    {
        $request->validate([
            'cover_image' => ['required', 'image', 'max:2048']
        ]);

        $file = $request->file('cover_image');
        $profileId = session('user_profile_id');

        $profile = $this->profileService->uploadCoverImage($file, $profileId);

        return ApiResponse::success('Cover image uploaded successfully.', $profile)->toJsonResponse();
    }

    /**
     * Handles request to like or unlike profile
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function toggleLike(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'liked_user_profile_id' => ['required', 'integer'],
        ]);

        $likerProfileId = session('user_profile_id');
        $likedProfileId = $validated['liked_user_profile_id'];

        $isLiked = $this->profileService->toggleLike($likerProfileId, $likedProfileId);

        return ApiResponse::success($isLiked ? 'Profile liked successfully.' : 'Profile unliked successfully.', ['is_liked' => $isLiked])->toJsonResponse();
    }
}
