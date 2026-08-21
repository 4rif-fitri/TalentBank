<?php

namespace App\Services;

use App\Models\Skill;
use App\Models\UserSkill;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

class SkillService
{
    private function verifySkillOwnership(UserSkill|Collection $userSkills, int $userProfileId): void
    {
        $userSkills = $userSkills instanceof Collection ? $userSkills : collect([$userSkills]);
        $userSkills->loadMissing('source');

        // get the user profile ID
        foreach ($userSkills as $userSkill) {
            $ownerProfileId = $userSkill->source_type === 'user_profile' ?
                $userSkill?->source_id :
                $userSkill->source?->user_profile_id;

            if ($ownerProfileId != $userProfileId) {
                throw new Exception('Unauthorized access to this skill.', Response::HTTP_FORBIDDEN);
            }
        }
    }

    private function getUserSkillModelById(int $userSkillId, string $sourceType, int $sourceId, int $userProfileId): UserSkill
    {
        $userSkill = UserSkill::with('source')
            ->where([
                'id' => $userSkillId,
                'source_type' => $sourceType,
                'source_id' => $sourceId
            ])
            ->first();

        if (!isset($userSkill)) {
            throw new Exception('User skill not found with given ID.', Response::HTTP_NOT_FOUND);
        }

        // check if the user skill belongs to this user
        $this->verifySkillOwnership($userSkill, $userProfileId);

        return $userSkill;
    }

    private function checkUserSkillsExists(array|int $skillIds, string $sourceType, int $sourceId, array|int|null $userSkillIds = null): void
    {
        $skillIds = (array) $skillIds;
        $userSkillIds = (array) $userSkillIds;

        $userSkillExists = UserSkill::whereIn('skill_id', $skillIds)
            ->where('source_type', $sourceType)
            ->where('source_id', $sourceId)
            ->when(!empty($userSkillIds), function ($query) use ($userSkillIds) {
                $query->whereNotIn('id', $userSkillIds);
            })
            ->exists();

        if ($userSkillExists) {
            throw new Exception('User skill(s) already exist in profile.', Response::HTTP_CONFLICT);
        }
    }

    /**
     * Returns all skills available
     * 
     * @return \Illuminate\Database\Eloquent\Collection<int, Skill>
     */
    public function getAllSkills(): Collection
    {
        return Skill::all();
    }

    /**
     * Creates a new user skill
     * 
     * @param array $data
     * @throws Exception
     * @return UserSkill
     */
    public function createUserSkill(array $data): UserSkill
    {
        // check if user skill already exists
        $this->checkUserSkillsExists($data['skill_id'], $data['source_type'], $data['source_id']);

        // create a new user skill
        $userSkill = UserSkill::create([
            'source_type' => $data['source_type'],
            'source_id' => $data['source_id'],
            'skill_id' => $data['skill_id'],
        ]);

        return $userSkill;
    }

    /**
     * Creates multiple new user skills
     * (meant to be used by other services to create their respective skills)
     * 
     * @param array $data  // new user_skills to be inserted
     * @throws Exception
     * @return bool
     */
    public function createUserSkills(array $data, string $sourceType, int $sourceId): bool
    {
        if (empty($data)) {
            return true;
        }

        $insertRecord = [];

        // check if user skill already exists
        $this->checkUserSkillsExists($data, $sourceType, $sourceId);

        foreach ($data as $newSkillId) {
            $insertRecord[] = [
                'source_type' => $sourceType,
                'source_id' => $sourceId,
                'skill_id' => $newSkillId
            ];
        }

        // create a new user skill
        $userSkill = UserSkill::insert($insertRecord);

        return $userSkill;
    }

    /**
     * Updates an existing user skill
     * 
     * @param array $data
     * @param int $userSkillId
     * @param int $userProfileId
     * @return bool
     */
    public function updateUserSkill(array $data, int $userSkillId, int $userProfileId): bool
    {
        // get user skill model
        $userSkill = $this->getUserSkillModelById($userSkillId, $data['source_type'], $data['source_id'], $userProfileId);

        // check if user skill already exists in profile
        $this->checkUserSkillsExists($data['skill_id'], $data['source_type'], $data['source_id'], $userSkillId);

        // update user skill
        $result = $userSkill->update([
            'skill_id' => $data['skill_id'],
        ]);

        return $result;
    }

    /**
     * Updates multiple existing user skills
     * (meant to be used by other services to update their respective skills)
     * 
     * @param array $data
     * @param int $userProfileId
     * @return bool|int
     */
    public function updateUserSkills(array $data, int $userProfileId, string $sourceType, int $sourceId): bool
    {
        if (empty($data)) {
            return true;
        }

        $updateRecord = [];
        $userSkillIds = array_column($data, 'id');
        $skillIds = array_column($data, 'skill_id');

        // validate data given
        $userSkills = UserSkill::with('source')->whereIn('id', $userSkillIds)->get();
        $this->verifySkillOwnership($userSkills, $userProfileId);
        $this->checkUserSkillsExists($skillIds, $sourceType, $sourceId, $userSkillIds);

        // prepare array to update
        foreach ($data as $userSkill) {
            $updateRecord[] = [
                'id' => $userSkill['id'],
                'source_type' => $sourceType,
                'source_id' => $sourceId,
                'skill_id' => $userSkill['skill_id']
            ];
        }

        // update records
        UserSkill::upsert($updateRecord, ['id'], ['skill_id']);

        return true;
    }

    /**
     * Deletes an existing user skill
     * 
     * @param int $userSkillId
     * @param int $userProfileId
     * @return bool
     */
    public function deleteUserSkill(int $userSkillId, int $userProfileId): bool
    {
        // get user skill model
        $userSkill = UserSkill::with('source')->find($userSkillId);

        if (!isset($userSkill)) {
            throw new Exception('User skill not found with given ID.', Response::HTTP_NOT_FOUND);
        }

        // check if this user skill belongs to this user
        $this->verifySkillOwnership($userSkill, $userProfileId);

        // delete user skill
        $result = $userSkill->delete();

        return $result;
    }

    /**
     * Deletes multiple existing user skills by user skill IDs
     * (meant to be used by other services to delete their respective skills)
     * 
     * @param array $userSkillIds
     * @param int $userProfileId
     * @return bool
     */
    public function deleteUserSkillsByIds(array $userSkillIds, int $userProfileId): bool
    {
        if (empty($userSkillIds)) {
            return true;
        }

        $userSkills = UserSkill::with('source')->whereIn('id', $userSkillIds)->get();

        // verify if the user skills belong to the current user
        $this->verifySkillOwnership($userSkills, $userProfileId);

        $result = UserSkill::whereIn('id', $userSkillIds)->delete();

        return $result;
    }

    /**
     * Deletes multiple existing user skills by source type and source ID
     * (meant to be used by other services to update their respective skills)
     * 
     * @param string $sourceType
     * @param int $sourceId
     * @return bool
     */
    public function deleteUserSkillsBySource(string $sourceType, int $sourceId): bool
    {
        // delete user skill
        $result = UserSkill::where([
            'source_type' => $sourceType,
            'source_id' => $sourceId
        ])
            ->delete();

        return $result;
    }
}
