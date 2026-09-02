<?php

namespace App\Services;

use App\Models\Like;
use App\Models\Role;
use App\Models\User;
use App\Models\UserProfile;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
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
            'activeProgrammes.qualification',
            'activeProgrammes.fieldOfStudy',
            'socialMediaLinks.socialMedia',
            'userLanguages.language',
            'skills',
        ])
            ->find($userProfileId);

        if (!isset($profile)) {
            throw new Exception('Profile not found with given ID.', Response::HTTP_NOT_FOUND);
        }

        return $profile;
    }

    /**
     * Returns all student and alumni user profiles with search functionality
     * 
     * @param array $searchParams
     * @return LengthAwarePaginator
     */
    public function getAllStudentUserProfiles(array $searchParams): LengthAwarePaginator
    {
        return UserProfile::with([
            'skills',
            'activeProgrammes:id,programme_name,organization_id,duration_years',
            'activeProgrammes.organization:id,name',
            'activeProgrammes.qualifications',
        ])
            ->where(function ($query) use ($searchParams) {
                $query->when(isset($searchParams['name']) && filled($searchParams['name']), function ($query) use ($searchParams) {
                    $query->where('name', 'LIKE', '%' . $searchParams['name'] . '%');
                })
                    ->when(!empty($searchParams['skills']), function ($query) use ($searchParams) {
                        $query->whereHas('skills', function ($query) use ($searchParams) {
                            $query->whereIn('skills.id', $searchParams['skills']);
                        });
                    })
                    ->when(!empty($searchParams['languages']), function ($query) use ($searchParams) {
                        $query->whereHas('userLanguages', function ($query) use ($searchParams) {
                            $query->whereIn('language_id', $searchParams['languages']);
                        });
                    })
                    ->when(!empty($searchParams['programmes']), function ($query) use ($searchParams) {
                        $query->whereHas('education', function ($query) use ($searchParams) {
                            $query->whereIn('programme_id', $searchParams['programmes']);
                        });
                    })
                    ->whereHas('organizationUsers', function ($query) use ($searchParams) {
                        $query->when(!empty($searchParams['organizations']), function ($query) use ($searchParams) {
                            $query->whereIn('organization_id', $searchParams['organizations']);
                        })
                            ->whereIn('role_id', Role::whereIn('name', ['Student', 'Alumni'])->pluck('id')->toArray());
                    });
            })
            ->select('id', 'name', 'location', 'headline', 'profile_image')
            ->paginate(20);
    }

    /**
     * Returns user profiles that have been liked by the current user
     * 
     * @param int $userProfileId
     * @return Collection<int, \stdClass>|\Illuminate\Database\Eloquent\Collection<int, UserProfile>
     */
    public function getLikedUserProfiles(int $userProfileId): Collection
    {
        return UserProfile::with([
            'skills',
            'activeProgrammes:programmes.id,programme_name,organization_id,duration_years',
            'activeProgrammes.organization:id,name',
            'activeProgrammes.qualification',
        ])
            ->whereHas('likes', function ($query) use ($userProfileId) {
                $query->where('liker_user_profile_id', $userProfileId);
            })
            ->select('id', 'name', 'location', 'headline', 'profile_image')
            ->get();
    }

    /**
     * Update profile data of profile by profile ID.
     * Excluding password update.
     *
     * @param   array $data
     * @param   int $profileId
     * @return  UserProfile
     */
    public function updateProfileData(array $data, int $profileId): UserProfile
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

        DB::transaction(function () use ($data, $isEmailChanged, $isNameChanged, $profile) {
            $profile->update([
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
        });

        return $profile;
    }

    /**
     * Update about field for user profile
     * 
     * @param string $about
     * @param int $profileId
     * @throws Exception
     * @return UserProfile
     */
    public function updateAboutField(string $about, int $profileId): UserProfile
    {
        $profile = $this->getProfileModel($profileId);

        $result = $profile->update(['about' => $about]);

        if (!$result) {
            throw new Exception('Failed to update about field.', Response::HTTP_BAD_REQUEST);
        }

        return $profile;
    }

    /**
     * Upload profile image for user profile
     * 
     * @param UploadedFile $profileImage
     * @param int $profileId
     * @return UserProfile
     */
    public function uploadProfileImage(UploadedFile $profileImage, int $profileId): UserProfile
    {
        return $this->uploadImage($profileImage, 'profile_image', config('services.uploads_file_path.profile_image'), $profileId);
    }

    /**
     * Upload cover image for user profile
     * 
     * @param UploadedFile $coverImage
     * @param int $profileId
     * @return UserProfile
     */
    public function uploadCoverImage(UploadedFile $coverImage, int $profileId): UserProfile
    {
        return $this->uploadImage($coverImage, 'cover_image', config('services.uploads_file_path.cover_image'), $profileId);
    }

    private function uploadImage(UploadedFile $image, string $column, string $imagePath, int $profileId): UserProfile
    {
        $profile = $this->getProfileModel($profileId);

        // delete existing image file
        if (isset($profile->{$column})) {
            File::delete($imagePath . $profile->{$column});
        }

        $filename = uniqid($column . '_') . '_' . str_replace(' ', '-', $image->getClientOriginalName());
        $image->move($imagePath, $filename);

        $profile->update([$column => $filename]);

        return $profile;
    }

    /**
     * Like or unlike a profile
     * 
     * @param int $likerProfileId
     * @param int $likedProfileId
     * @throws Exception
     * @return bool
     */
    public function toggleLike(int $likerProfileId, int $likedProfileId): bool
    {
        if ($likerProfileId === $likedProfileId) {
            throw new Exception('You cannot like your own profile.', Response::HTTP_BAD_REQUEST);
        }

        $like = Like::where('liker_user_profile_id', $likerProfileId)
            ->where('liked_user_profile_id', $likedProfileId)
            ->first();

        if ($like) {
            // If the like already exists, remove it (unlike)
            $like->delete();
            return false;
        } else {
            // If the like does not exist, create it (like)
            Like::create([
                'liker_user_profile_id' => $likerProfileId,
                'liked_user_profile_id' => $likedProfileId,
            ]);
            return true;
        }
    }
}
