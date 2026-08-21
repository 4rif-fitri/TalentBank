<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserProfile;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ProfileService
{
    private function getProfileModel(int $profileId): UserProfile
    {
        $profile = UserProfile::find($profileId);

        if (!isset($profile)) {
            throw new Exception('Profile not found with given profile ID.', Response::HTTP_NOT_FOUND);
        }

        return $profile;
    }

    /**
     * Get profile data of profile by profile ID.
     * 
     * @param   int $userProfileId
     * @return  UserProfile
     */
    public function getProfileDataByProfileId(int $userProfileId): UserProfile
    {
        $profile = UserProfile::with([
            'organizationUsers' => function ($query) {
                $query->where('status', 1);
            },
            'organizationUsers.organization',
            'organizationUsers.role',
            'activeProgrammes.organization',
            'socialMediaLinks.socialMedia',
            'userLanguages.language',
            'skills'
        ])
            ->find($userProfileId);

        if (!isset($profile)) {
            throw new Exception('Profile not found with given ID.', Response::HTTP_NOT_FOUND);
        }

        return $profile;
    }

    /**
     * Update profile data of profile by profile ID.
     * Excluding password update.
     *
     * @param   array $data
     * @param   int $profileId
     * @return  bool
     */
    public function updateProfileData(array $data, int $profileId): bool
    {
        $profile = $this->getProfileModel($profileId);

        $isNameChanged = $data['name'] !== $profile->name;
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
                ->where('id', '<>', $profileId)
                ->exists();

            if ($emailOrPhoneExists) {
                throw new Exception('Email or phone already exists.', Response::HTTP_CONFLICT);
            }
        }

        $result = DB::transaction(function () use ($data, $isEmailChanged, $isNameChanged, $profile) {
            $result = $profile->update([
                'name' => $data['name'],
                'email' => $data['email'],
                'headline' => $data['headline'],
                'location' => $data['location'],
                'phone_no' => $data['phone_no'],
            ]);

            if ($isNameChanged || $isEmailChanged) {
                User::find(Auth::id())->update([
                    'name' => $data['name'],
                    'email' => $data['email']
                ]);
            }

            return $result;
        });

        Cache::forget('profile_' . $profileId);

        if (!$result) {
            throw new Exception('Failed to update profile.', Response::HTTP_BAD_REQUEST);
        }

        return $result;
    }

    /**
     * Update about field for user profile
     * 
     * @param string $about
     * @param int $profileId
     * @throws Exception
     * @return bool
     */
    public function updateAboutField(string $about, int $profileId): bool
    {
        $profile = $this->getProfileModel($profileId);

        $result = $profile->update(['about' => $about]);

        if (!$result) {
            throw new Exception('Failed to update about field.', Response::HTTP_BAD_REQUEST);
        }

        return $result;
    }

    /**
     * Upload profile image for user profile
     * 
     * @param UploadedFile $profileImage
     * @param int $profileId
     * @return bool
     */
    public function uploadProfileImage(UploadedFile $profileImage, int $profileId): bool
    {
        return $this->uploadImage($profileImage, 'profile_image', config('services.uploads_file_path.profile_image'), $profileId);
    }

    /**
     * Upload cover image for user profile
     * 
     * @param UploadedFile $coverImage
     * @param int $profileId
     * @return bool
     */
    public function uploadCoverImage(UploadedFile $coverImage, int $profileId): bool
    {
        return $this->uploadImage($coverImage, 'cover_image', config('services.uploads_file_path.cover_image'), $profileId);
    }

    private function uploadImage(UploadedFile $image, string $column, string $imagePath, int $profileId): bool
    {
        $profile = $this->getProfileModel($profileId);

        // delete existing image file
        if (isset($profile->{$column})) {
            File::delete($imagePath . $profile->{$column});
        }

        $filename = uniqid($column . '_') . '_' . str_replace(' ', '-', $image->getClientOriginalName());
        $image->move($imagePath, $filename);

        $results = $profile->update([$column => $filename]);

        return $results;
    }
}
