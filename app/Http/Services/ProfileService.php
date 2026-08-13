<?php

namespace App\Http\Services;

use App\Models\profile;
use App\Models\UserProfile;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\ExpectationFailedException;
use Symfony\Component\HttpFoundation\File\Exception\UploadException;

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
     * Get profile data of profile by profile ID.
     * 
     * @param   int $userId
     * @return  UserProfile
     */
    public function getProfileDataByUserId($userId = null)
    {
        $profile = UserProfile::with([
            'organizationUsers' => function ($query) {
                $query->where('status', 1);
            },
            'organizationUsers.organization',
            'organizationUsers.role'
        ])
            ->where('user_id', $userId ?? Auth::id())
            ->first();

        if (!isset($profile)) {
            throw new Exception('profile not found with given ID.', Response::HTTP_NOT_FOUND);
        }

        return $profile;
    }

    private function getProfileModel()
    {
        $userId = Auth::id();

        $profile = UserProfile::where('user_id', $userId)->first();

        if (!isset($profile)) {
            throw new Exception('Profile not found with given profile ID.', Response::HTTP_NOT_FOUND);
        }

        return $profile;
    }

    /**
     * Update profile data of profile by profile ID.
     * Excluding password update.
     * 
     * @param   array $data 
     * @return  bool
     */
    public function updateProfileData(array $data)
    {
        $profile = $this->getProfileModel();

        $isEmailChanged = $data['email'] !== $profile->email;
        $isPhoneChanged = $data['phone_no'] !== $profile->phone_no;

        // if the email and phone_no changed, then check if the updated one already exists in db before updating
        if ($isEmailChanged || $isPhoneChanged) {
            $emailOrPhoneExists = UserProfile::where(function ($query) use ($data, $isEmailChanged, $isPhoneChanged) {
                if ($isEmailChanged) {
                    $query->where('email', $data['email']);
                }

                if ($isPhoneChanged && isset($data['phone_no'])) {
                    $query->orWhere('phone_no', $data['phone_no']);
                }
            })
                ->where('user_id', '<>', Auth::id())
                ->exists();

            if ($emailOrPhoneExists) {
                throw new Exception('Email or phone already exists.', Response::HTTP_CONFLICT);
            }
        }

        $result = $profile->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'headline' => $data['headline'] ?? $profile->headline,
            'location' => $data['location'] ?? $profile->location,
            'phone_no' => $data['phone_no'] ?? $profile->phone_no,
        ]);

        if (!$result) {
            throw new Exception('Failed to update profile.', Response::HTTP_BAD_REQUEST);
        }

        return $result;
    }

    /**
     * Update about field for user profile
     * @param string $about
     * @throws Exception
     * @return bool|int
     */
    public function updateAboutField(string $about)
    {
        $profile = $this->getProfileModel();

        $result = $profile->update(['about' => $about]);

        if (!$result) {
            throw new Exception('Failed to update about field.', Response::HTTP_BAD_REQUEST);
        }

        return $result;
    }

    /**
     * Upload profile image for user profile
     * @param UploadedFile $profileImage
     * @return bool|int
     */
    public function uploadProfileImage(UploadedFile $profileImage)
    {
        return $this->uploadImage($profileImage, 'profile_image', env('PROFILE_IMAGE_URL'));
    }

    /**
     * Upload cover image for user profile
     * @param UploadedFile $coverImage
     * @return bool|int
     */
    public function uploadCoverImage(UploadedFile $coverImage)
    {
        return $this->uploadImage($coverImage, 'cover_image', env('COVER_IMAGE_URL'));
    }

    private function uploadImage(UploadedFile $image, string $column, string $imagePath)
    {
        $profile = $this->getProfileModel();

        // delete existing image file
        if (isset($profile->{$column}) && File::exists($imagePath . $profile->{$column})) {
            File::delete($imagePath . $profile->{$column});
        }

        $filename = uniqid($column . '_') . '_' . str_replace(' ', '-', $image->getClientOriginalName());
        $image->move($imagePath, $filename);

        $results = $profile->update([$column => $filename]);

        return $results;
    }
}
