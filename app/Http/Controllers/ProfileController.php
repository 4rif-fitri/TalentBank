<?php

namespace App\Http\Controllers;

use App\Http\Services\ProfileService;
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
            return response()->json(['error' => 'Ajax request required.']);
        }

        $userId = $request->input('user_id');

        $profile = $this->profileService->getProfileDataByUserId($userId);

        return response()->json(['profile' => $profile]);
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
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'telno' => ['nullable', 'string', 'max:11'],
            'address' => ['nullable', 'string', 'max:255'],
            'profile_image' => ['nullable', 'image', 'mimes:png,jpeg,jpg', 'max:2048'],
        ]);

        $result = $this->profileService->updateProfileData($validated, $request->file('profile_image'));

        if ($result['status'] == 200) {
            return redirect()->back()->with(['success' => $result['message']]);
        }

        return redirect()->back()->withErrors($result['message']);
    }
}
