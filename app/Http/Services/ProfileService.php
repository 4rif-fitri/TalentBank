<?php

namespace App\Http\Services;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get profile data of user by user ID.
     * 
     * @param   int $userId
     * @return  array
     */
    public function getProfileDataByUserId($userId = null)
    {
        $user = User::with([
            'organizationUsers' => function ($query) {
                $query->where('status', 1);
            },
            'organizationUsers.organization',
            'organizationUsers.role'
        ])
            ->find($userId ?? Auth::id());

        if (!isset($user)) {
            return ['status' => 404, 'message' => 'User not found with given ID.'];
        }

        return ['status' => 200, 'message' => 'User found.', 'user' => $user];
    }

    /**
     * Update profile data of user by user ID.
     * Excluding password update.
     * 
     * @param   array $data 
     * @return  array
     */
    public function updateProfileData(array $data, ?UploadedFile $profileImage = null)
    {
        $userId = Auth::id() ?? 4;

        $emailOrPhoneExists = User::where(function ($query) use ($data) {
            $query->where('email', $data['email'])
                ->orWhere('telno', $data['telno']);
        })
            ->where('id', '<>', $userId)
            ->exists();

        if ($emailOrPhoneExists) {
            return ['status' => 409, 'message' => 'Email or phone already exists.'];
        }

        $user = User::find($userId);

        if (!isset($user)) {
            return ['status' => 409, 'message' => 'User not found with given user ID.'];
        }

        // upload user profile image
        if (isset($profileImage)) {
            $profileImagePath = 'uploads/profile_images';
            $filename = uniqid('profile_') . '_' . str_replace(' ', '-', $profileImage->getClientOriginalName());
            $profileImage->move(public_path($profileImagePath), $filename);
            $data['profile_image'] = $filename;
        }

        $result = $user
            ->update([
                'name' => $data['name'],
                'email' => $data['email'],
                'telno' => $data['telno'] ?? $user->telno,
                'address' => $data['address'] ?? $user->address,
                'profile_image' => $data['profile_image'] ?? $user->profile_image,
            ]);

        if (!$result) {
            return ['status' => 400, 'message' => 'Failed to update profile.'];
        }

        return ['status' => 200, 'message' => 'Profile updated successfully.'];
    }
}
