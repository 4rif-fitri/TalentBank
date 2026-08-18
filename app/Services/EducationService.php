<?php

namespace App\Services;

use App\Models\Education;
use App\Models\FieldOfStudy;
use App\Models\Qualification;
use App\Models\UserProfile;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class EducationService
{
    private MediaService $mediaService;

    /**
     * Create a new class instance.
     */
    public function __construct(MediaService $mediaService)
    {
        $this->mediaService = $mediaService;
    }

    /**
     * Gets all education by user profile ID
     * 
     * @param int $userProfileId
     * @throws Exception
     * @return \Illuminate\Database\Eloquent\Collection<int, Education>|\Illuminate\Support\Collection<int, \stdClass>
     */
    public function getEducationByUserProfileId(int $userProfileId)
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
            'media'
        ])
            ->where('user_profile_id', $userProfileId)
            ->get();
    }

    /**
     * Get education by education ID
     * 
     * @param int $id
     * @throws Exception
     * @return Education|\Illuminate\Database\Eloquent\Builder<Education>|\stdClass
     */
    public function getEducationById(int $id)
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
    public function createEducation(array $data)
    {
        $userProfileId = session('user_profile_id');

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

            $data['source_name'] = 'education';
            $data['source_id'] = $education->id;
            $data['file_path'] = config('services.uploads_file_path.education');

            $this->mediaService->createMedia($data);

            return $education;
        });

        return $education;
    }

    /**
     * Creates a new education for current user
     * 
     * @param int $educationId
     * @param array $data
     * @return bool|int
     */
    public function updateEducation(int $educationId, array $data)
    {
        $userProfileId = session('user_profile_id');

        $education = Education::where([
            ['id', $educationId],
            ['user_profile_id', $userProfileId],
        ])->first();

        if (!isset($education)) {
            throw new Exception('Education data not found with given ID.', Response::HTTP_NOT_FOUND);
        }

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

        return $result;
    }

    /**
     * Delete an existing education
     * (any semesters and media (attached to semester) attached to it will be deleted as well)
     * 
     * @param int $educationId
     * @return bool|int
     */
    public function deleteEducation(int $educationId)
    {
        $userProfileId = session('user_profile_id');

        $education = Education::where([
            ['id', $educationId],
            ['user_profile_id', $userProfileId],
        ])->first();

        if (!isset($education)) {
            throw new Exception('Education data not found with given ID.', Response::HTTP_NOT_FOUND);
        }

        $result = $education->delete();

        return $result;
    }

    /**
     * Get all field of studies from the database
     * 
     * @return \Illuminate\Database\Eloquent\Collection<int, FieldOfStudy>
     */
    public function getAllFieldOfStudies()
    {
        return FieldOfStudy::get();
    }

    /**
     * Get all qualifications from the database
     * 
     * @return \Illuminate\Database\Eloquent\Collection<int, Qualification>
     */
    public function getAllQualifications()
    {
        return Qualification::get();
    }
}
