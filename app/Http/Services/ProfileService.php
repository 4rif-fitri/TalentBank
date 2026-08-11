<?php

namespace App\Http\Services;

use App\Models\User;
use Exception;
use Illuminate\Http\Response;
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
     * @return  User
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
            throw new Exception('User not found with given ID.', Response::HTTP_NOT_FOUND);
        }

        return $user;
    }

    /**
     * Update profile data of user by user ID.
     * Excluding password update.
     * 
     * @param   array $data 
     * @return  bool
     */
    public function updateProfileData(array $data, ?UploadedFile $profileImage = null)
    {
        $userId = Auth::id() ?? 4;

        $user = User::find($userId);

        if (!isset($user)) {
            throw new Exception('User not found with given user ID.', Response::HTTP_NOT_FOUND);
        }

        $isEmailChanged = $data['email'] !== $user->email;
        $isPhoneChanged = $data['telno'] !== $user->telno;

        // if the email and telno changed, then check if the updated one already exists in db before updating
        if ($isEmailChanged || $isPhoneChanged) {
            $emailOrPhoneExists = User::where(function ($query) use ($data, $isEmailChanged, $isPhoneChanged) {
                if ($isEmailChanged) {
                    $query->where('email', $data['email']);
                }

                if ($isPhoneChanged && isset($data['telno'])) {
                    $query->orWhere('telno', $data['telno']);
                }
            })
                ->where('id', '<>', $userId)
                ->exists();

            if ($emailOrPhoneExists) {
                throw new Exception('Email or phone already exists.', Response::HTTP_CONFLICT);
            }
        }

        // upload user profile image
        if (isset($profileImage)) {
            $profileImagePath = 'uploads/profile_images';
            $filename = uniqid('profile_') . '_' . str_replace(' ', '-', $profileImage->getClientOriginalName());
            $profileImage->move(public_path($profileImagePath), $filename);
            $data['profile_image'] = $filename;
        }

        $result = $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'telno' => $data['telno'] ?? $user->telno,
            'address' => $data['address'] ?? $user->address,
            'profile_image' => $data['profile_image'] ?? $user->profile_image,
        ]);

        if (!$result) {
            throw new Exception('Failed to update profile.', Response::HTTP_BAD_REQUEST);
        }

        return $result;
    }
}
