<?php

namespace App\Services;

use App\Models\Skill;
use App\Models\UserSkill;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

class SkillService
{
    private function verifySkillOwnership(UserSkill $userSkill, int $userProfileId): void
    {
        // get the user profile ID
        $ownerProfileId = $userSkill->source_type == 'user_profile' ?
            $userSkill?->source_id :
            $userSkill->source?->user_profile_id;

        if ($ownerProfileId != $userProfileId) {
            throw new Exception('Unauthorized access to this skill.', Response::HTTP_FORBIDDEN);
        }
    }

    private function getUserSkillModelById(int $userSkillId, string $sourceType, int $sourceId, int $userProfileId): UserSkill
    {
        $userSkill = UserSkill::where([
            'id' => $userSkillId,
            'source_type' => $sourceType,
            'source_id' => $sourceId
        ])->first();

        if (!isset($userSkill)) {
            throw new Exception('User skill not found with given ID.', Response::HTTP_NOT_FOUND);
        }

        // check if the user skill belongs to this user
        $this->verifySkillOwnership($userSkill, $userProfileId);

        return $userSkill;
    }

    private function checkUserSkillExists(int $skillId, string $sourceType, int $sourceId, ?int $userSkillId = null): void
    {
        $userSkillExists = UserSkill::where([
            'source_type' => $sourceType,
            'source_id' => $sourceId,
            'skill_id' => $skillId,
        ])
            ->when(isset($userSkillId), function ($query) use ($userSkillId) {
                $query->where('id', '<>', $userSkillId);
            })
            ->exists();

        if ($userSkillExists) {
            throw new Exception('User skill already exist in profile.', Response::HTTP_CONFLICT);
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
        $this->checkUserSkillExists($data['skill_id'], $data['source_type'], $data['source_id']);

        // create a new user skill
        $userSkill = UserSkill::create([
            'source_type' => $data['source_type'],
            'source_id' => $data['source_id'],
            'skill_id' => $data['skill_id'],
        ]);

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
        $this->checkUserSkillExists($data['skill_id'], $data['source_type'], $data['source_id'], $userSkillId);

        // update user skill
        $result = $userSkill->update([
            'skill_id' => $data['skill_id'],
        ]);

        return $result;
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
        $userSkill = UserSkill::find($userSkillId);

        // check if this user skill belongs to this user
        $this->verifySkillOwnership($userSkill, $userProfileId);

        if (!isset($userSkill)) {
            throw new Exception('User skill not found with given ID.', Response::HTTP_NOT_FOUND);
        }

        // delete user skill
        $result = $userSkill->delete();

        return $result;
    }
}
