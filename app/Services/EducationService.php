<?php

namespace App\Services;

use App\Models\Education;
use App\Models\FieldOfStudy;
use App\Models\Qualification;
use App\Models\UserProfile;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class EducationService
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        private readonly MediaService $mediaService
    ) {
    }

    private function uploadImages(int $educationId, array $data, int $userProfileId): void
    {
        $data['source_name'] = 'education';
        $data['source_id'] = $educationId;
        $data['file_path'] = config('services.uploads_file_path.education');

        if (isset($data['media'])) {
            $this->mediaService->createMedia($data, $userProfileId);
        }
    }

    /**
     * Gets all education by user profile ID
     * 
     * @param int $userProfileId
     * @throws Exception
     * @return Collection<int, Education>|\Illuminate\Support\Collection<int, \stdClass>
     */
    public function getEducationByUserProfileId(int $userProfileId): Collection
    {
        $userExists = UserProfile::where('id', $userProfileId)->exists();

        if (!$userExists) {
            throw new Exception('User not found with given ID.', Response::HTTP_NOT_FOUND);
        }

        return Education::with([
            'fieldOfStudy',
            'qualification',
            'programme',
            'programme.organization',
            'media',
            'skills'
        ])
            ->where('user_profile_id', $userProfileId)
            ->get();
    }

    /**
     * Get education by education ID
     * 
     * @param int $id
     * @throws Exception
     * @return Education
     */
    public function getEducationById(int $id): Education
    {
        $education = Education::with([
            'fieldOfStudy',
            'qualification',
            'programme',
            'programme.organization',
            'media'
        ])
            ->find($id);

        if (!isset($education)) {
            throw new Exception('Education record not found with given ID.', Response::HTTP_NOT_FOUND);
        }

        return $education;
    }

    /**
     * Creates a new education for current user
     * 
     * @param array $data
     * @return Education
     */
    public function createEducation(array $data, int $userProfileId): Education
    {
        $education = DB::transaction(function () use ($data, $userProfileId) {
            $education = Education::create([
                'user_profile_id' => $userProfileId,
                'programme_id' => $data['programme_id'],
                'description' => $data['description'],
                'field_of_study_id' => $data['field_of_study_id'],
                'qualification_id' => $data['qualification_id'],
                'cgpa' => $data['cgpa'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'enrollment_status' => $data['enrollment_status'],
                'verification_status' => 'Pending',  // initially pending for admin to approve
            ]);

            $this->uploadImages($education->id, $data, $userProfileId);

            return $education;
        });

        return $education;
    }

    /**
     * Creates a new education for current user
     * 
     * @param int $educationId
     * @param array $data
     * @return bool
     */
    public function updateEducation(int $educationId, array $data, int $userProfileId): bool
    {
        $education = Education::where([
            ['id', $educationId],
            ['user_profile_id', $userProfileId],
        ])->first();

        if (!isset($education)) {
            throw new Exception('Education data not found with given ID.', Response::HTTP_NOT_FOUND);
        }

        $result = DB::transaction(function () use ($data, $education, $userProfileId) {
            $result = $education->update([
                'programme_id' => $data['programme_id'],
                'description' => $data['description'],
                'field_of_study_id' => $data['field_of_study_id'],
                'qualification_id' => $data['qualification_id'],
                'cgpa' => $data['cgpa'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'enrollment_status' => $data['enrollment_status'],
            ]);

            $this->uploadImages($education->id, $data, $userProfileId);

            return $result;
        });


        return $result;
    }

    /**
     * Delete an existing education
     * (any semesters and media (attached to semester) attached to it will be deleted as well)
     * 
     * @param int $educationId
     * @return bool|
     */
    public function deleteEducation(int $educationId, int $userProfileId): bool
    {
        $education = Education::where([
            ['id', $educationId],
            ['user_profile_id', $userProfileId],
        ])->first();

        if (!isset($education)) {
            throw new Exception('Education data not found with given ID.', Response::HTTP_NOT_FOUND);
        }

        $result = DB::transaction(function () use ($education) {
            $this->mediaService->deleteMediaBySource('education', $education->id, config('services.uploads_file_path.education'));

            $result = $education->delete();

            return $result;
        });

        return $result;
    }

    /**
     * Get all field of studies from the database
     * 
     * @return Collection<int, FieldOfStudy>
     */
    public function getAllFieldOfStudies(): Collection
    {
        return FieldOfStudy::get();
    }

    /**
     * Get all qualifications from the database
     * 
     * @return Collection<int, Qualification>
     */
    public function getAllQualifications(): Collection
    {
        return Qualification::get();
    }
}
