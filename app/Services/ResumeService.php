<?php

namespace App\Services;

use App\Models\Resume;
use App\Models\ResumeContent;
use Exception;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ResumeService
{
    private function getResumeModel(int $resumeId, int $userProfileId): Resume
    {
        $resume = Resume::where([
            'id' => $resumeId,
            'user_profile_id' => $userProfileId,
        ])->first();

        if (!isset($resume)) {
            throw new Exception('Resume not found or access unauthorized.', Response::HTTP_NOT_FOUND);
        }

        return $resume;
    }

    private function validateSources(array $data): void
    {
        $morphMap = Relation::morphMap();

        // check if source type names are valid
        $sourceTypes = array_column($data, 'source_type');

        if (!empty(array_diff($sourceTypes, array_keys($morphMap)))) {
            throw new Exception('Invalid source type.', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // check if foreign ID given for source is valid
        $groupedSourceType = collect($data)->groupBy('source_type')->map(function ($type) {
            return $type->pluck('source_id')->unique()->values()->toArray();
        });

        foreach ($groupedSourceType as $sourceType => $sourceIds) {
            $modelClass = (new $morphMap[$sourceType]);
            $sourceIdsCountInDb = $modelClass->whereIn('id', $sourceIds)->lockForUpdate()->count();

            if ($sourceIdsCountInDb !== count($sourceIds)) {
                throw new Exception('Source ID not found for ' . $morphMap[$sourceType], Response::HTTP_NOT_FOUND);
            }
        }
    }

    /**
     * Gets resumes by user profile ID
     * 
     * @param int $userProfileId
     * @return Collection<int, \stdClass>|\Illuminate\Database\Eloquent\Collection<int, Resume>
     */
    public function getResumesByUserProfileId(int $userProfileId): Collection
    {
        return Resume::where('user_profile_id', $userProfileId)->get();
    }

    /**
     * Gets resume by resume ID
     * 
     * @param int $resumeId
     * @param int $userProfileId
     * @throws Exception
     * @return Resume|\stdClass
     */
    public function getResumeById(int $resumeId, int $userProfileId): Resume
    {
        $resume = Resume::with([
            'education.programme.organization:id,company_name,organization_logo',
            'userProfile',
            'userLanguages.language',
            'socialMediaLinks.socialMedia',
            'userSkills.skill',
        ])
            ->find($resumeId);

        if (!isset($resume)) {
            throw new Exception('Resume not found with given ID.', Response::HTTP_NOT_FOUND);
        }

        // set the role of the current user for this resume
        $resume->user_role = $resume->user_profile_id === $userProfileId ? 'owner' : 'viewer';

        return $resume;
    }

    /**
     * Creates new resume with resume content
     * 
     * @param array $data
     * @param int $userProfileId
     * @return Resume
     */
    public function createResume(array $data, int $userProfileId): Resume
    {
        $resume = DB::transaction(function () use ($data, $userProfileId) {
            $this->validateSources($data['content_to_add']);

            // create resume
            $resume = Resume::create([
                'user_profile_id' => $userProfileId
            ]);

            $insertRecord = [];

            // create resume content
            foreach ($data['content_to_add'] as $source) {
                $insertRecord[] = [
                    'source_type' => $source['source_type'],
                    'source_id' => $source['source_id'],
                    'resume_id' => $resume->id
                ];
            }

            if (!empty($insertRecord)) {
                ResumeContent::insert($insertRecord);
            }

            return $resume;
        });

        return $resume;
    }

    /**
     * Updates resume by adding or removing resume content
     * 
     * @param array $data
     * @param int $resumeId
     * @param int $userProfileId
     * @throws Exception
     * @return Resume|\stdClass
     */
    public function updateResumeContent(array $data, int $resumeId, int $userProfileId): Resume
    {
        $resume = $this->getResumeModel($resumeId, $userProfileId);

        $resumeContent = ResumeContent::where('resume_id', $resumeId)
            ->get()
            ->map(function ($row) {
                return $row->source_type . ':' . $row->source_id;
            })
            ->flip();

        DB::transaction(function () use ($data, $resume, $resumeContent, $resumeId) {
            $contentToAdd = $data['content_to_add'] ?? [];
            $contentIdsToDelete = $data['content_ids_to_delete'] ?? [];

            $this->validateSources($contentToAdd);

            // update resume updated_at
            $resume->update([
                'updated_at' => now()
            ]);

            $insertRecord = [];

            // add content for resume
            foreach ($contentToAdd as $sourceToAdd) {
                $key = $sourceToAdd['source_type'] . ':' . $sourceToAdd['source_id'];

                if (isset($resumeContent[$key])) {
                    throw new Exception('Content already exists in resume.', Response::HTTP_CONFLICT);
                }

                $insertRecord[] = [
                    'source_type' => $sourceToAdd['source_type'],
                    'source_id' => $sourceToAdd['source_id'],
                    'resume_id' => $resumeId
                ];
            }

            if (!empty($insertRecord)) {
                ResumeContent::insert($insertRecord);
            }

            if (!empty($contentIdsToDelete)) {
                // remove content of resume
                ResumeContent::where('resume_id', $resumeId)
                    ->whereIn('id', $data['content_ids_to_delete'])
                    ->delete();
            }
        });

        return $resume;
    }

    /**
     * Deletes a resume and its resume content
     * 
     * @param int $resumeId
     * @param int $userProfileId
     * @return Resume
     */
    public function deleteResume(int $resumeId, int $userProfileId): Resume
    {
        $resume = $this->getResumeModel($resumeId, $userProfileId);

        DB::transaction(function () use ($resume, $resumeId) {
            ResumeContent::where('resume_id', $resumeId)->delete();
            $resume->delete();
        });

        return $resume;
    }
}
