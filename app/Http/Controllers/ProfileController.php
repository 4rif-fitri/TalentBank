<?php

namespace App\Http\Controllers;

use App\Http\Helpers\ApiResponse;
use App\Http\Services\ProfileService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

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
     * get the profile data by given user ID 
     * (or current user ID if one is not provided)
     * 
     * @param   Request $request
     * @return  JsonResponse
     */
    public function getProfileDataByUserIdJson(Request $request)
    {
        if (!$request->ajax()) {
            return ApiResponse::error('Ajax request required.')->toJsonResponse();
        }

        try {
            $userId = $request->input('user_id');

            $profile = $this->profileService->getProfileDataByUserId($userId);

            return ApiResponse::success('Success.', $profile)->toJsonResponse();
        } catch (Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode())->toJsonResponse();
        }
    }

    /**
     * update the profile data by given user ID 
     * (or current user ID if one is not provided)
     * 
     * @param   Request $request
     * @return  RedirectResponse
     */
    public function update(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255'],
                'telno' => ['nullable', 'string', 'max:11'],
                'address' => ['nullable', 'string', 'max:255'],
                'profile_image' => ['nullable', 'image', 'mimes:png,jpeg,jpg', 'max:2048'],
            ]);

            $this->profileService->updateProfileData($validated, $request->file('profile_image'));

            return redirect()->back()->with(ApiResponse::success('Profile updated successfully.', null)->toArray());

        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }
}
