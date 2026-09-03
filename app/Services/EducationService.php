<?php

namespace App\Services;

use App\Constants\AppConstants;
use App\Models\Education;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class EducationService
{
    private const ORGANIZATION_RETURN_COLUMNS = 'id,company_name,organization_logo';

    /**
     * Create a new class instance.
     */
    public function __construct(
        private readonly MediaService $mediaService,
        private readonly SkillService $skillService
    ) {}

    private function uploadImages(int $educationId, array $data, int $userProfileId): void
    {
        $data['source_name'] = 'education';
        $data['source_id'] = $educationId;
        $data['file_path'] = config('services.uploads_file_path.education');

        if (isset($data['media'])) {
            $this->mediaService->createMedia($data, $userProfileId);
        }

        if (isset($data['deleted_media_ids'])) {
            $this->mediaService->deleteMediaByIds($data['deleted_media_ids'], $userProfileId, config('services.uploads_file_path.education'));
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
        return Education::with([
            'programme',
            'programme.organization:' . self::ORGANIZATION_RETURN_COLUMNS,
            'programme.qualification',
            'programme.fieldOfStudy',
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
            'programme',
            'programme.organization:' . self::ORGANIZATION_RETURN_COLUMNS,
            'programme.qualification',
            'programme.fieldOfStudy',
            'media',
            'skills'
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
                'cgpa' => $data['cgpa'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'enrollment_status' => $data['enrollment_status'],
                'verification_status' => AppConstants::VERIFICATION_STATUS['PENDING'],  // initially pending for admin to approve
            ]);

            $this->uploadImages($education->id, $data, $userProfileId);

            $this->skillService->createUserSkills($data['new_skill_ids'] ?? [], 'education', $education->id);

            return $education;
        });

        return $education->load([
            'programme.organization:' . self::ORGANIZATION_RETURN_COLUMNS,
            'programme.qualification',
            'programme.fieldOfStudy',
            'media'
        ]);
    }

    /**
     * Creates a new education for current user
     *
     * @param int $educationId
     * @param array $data
     * @return Education
     */
    public function updateEducation(int $educationId, array $data, int $userProfileId): Education
    {
        $education = Education::where([
            ['id', $educationId],
            ['user_profile_id', $userProfileId],
        ])->first();

        if (!isset($education)) {
            throw new Exception('Education data not found or access unauthorized.', Response::HTTP_FORBIDDEN);
        }

        DB::transaction(function () use ($data, $education, $userProfileId) {
            // update education
            $education->update([
                'programme_id' => $data['programme_id'],
                'description' => $data['description'],
                'cgpa' => $data['cgpa'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'enrollment_status' => $data['enrollment_status'],
            ]);

            // update images
            $this->uploadImages($education->id, $data, $userProfileId);

            // update skills
            if (!empty($data['new_skill_ids'])) {
                $this->skillService->createUserSkills($data['new_skill_ids'], 'education', $education->id);
            }

            if (!empty($data['updated_user_skills'])) {
                $this->skillService->updateUserSkills($data['updated_user_skills'], $userProfileId, 'education', $education->id);
            }

            if (!empty($data['deleted_user_skill_ids'])) {
                $this->skillService->deleteUserSkillsByIds($data['deleted_user_skill_ids'], $userProfileId);
            }
        });

        return $education->load([
            'programme.organization:' . self::ORGANIZATION_RETURN_COLUMNS,
            'programme.qualification',
            'programme.fieldOfStudy',
            'media',
            'skills'
        ]);
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
        $education = Education::with('skills')
            ->where([
                ['id', $educationId],
                ['user_profile_id', $userProfileId],
            ])->first();

        if (!isset($education)) {
            throw new Exception('Education data not found or access unauthorized.', Response::HTTP_NOT_FOUND);
        }

        $result = DB::transaction(function () use ($education) {
            // delete media before deleting education (prevent orphaned data and files)
            $this->mediaService->deleteMediaBySource('education', $education->id, config('services.uploads_file_path.education'));

            // delete skills before deleting education (prevent orphaned data)
            $this->skillService->deleteUserSkillsBySource('education', $education->id);

            $result = $education->delete();

            return $result;
        });

        return $result;
    }
}
