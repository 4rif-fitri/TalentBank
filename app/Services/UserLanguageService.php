<?php

namespace App\Services;

use App\Models\Language;
use App\Models\UserLanguage;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

class UserLanguageService
{
    private function checkUserLanguageExists(int $languageId, int $userProfileId, ?int $userLanguageId = null): void
    {
        $userLanguageExists = UserLanguage::where([
            'user_profile_id' => $userProfileId,
            'language_id' => $languageId
        ])
            ->when(isset($userLanguageId), function ($query) use ($userLanguageId) {
                $query->where('id', '<>', $userLanguageId);
            })
            ->exists();

        if ($userLanguageExists) {
            throw new Exception('Language already exists in profile.', Response::HTTP_CONFLICT);
        }
    }

    private function getUserLanguageModel(int $userLanguageId, int $userProfileId): UserLanguage
    {
        $userLanguage = UserLanguage::where([
            'id' => $userLanguageId,
            'user_profile_id' => $userProfileId,
        ])->first();

        if (!isset($userLanguage)) {
            throw new Exception('Language not found in profile.', Response::HTTP_NOT_FOUND);
        }

        return $userLanguage;
    }

    /**
     * Returns all the available languages
     * 
     * @return \Illuminate\Database\Eloquent\Collection<int, Language>
     */
    public function getAllLanguages(): Collection
    {
        return Language::all();
    }

    /**
     * Creates a new UserLanguage
     * 
     * @param array $data
     * @param int $userProfileId
     * @throws Exception
     * @return UserLanguage
     */
    public function createUserLanguage(array $data, int $userProfileId): UserLanguage
    {
        // check if user language exists
        $this->checkUserLanguageExists($data['language_id'], $userProfileId);

        // create user language
        $userLanguage = UserLanguage::create([
            'user_profile_id' => $userProfileId,
            'language_id' => $data['language_id'],
            'proficiency_level' => $data['proficiency_level']
        ]);

        return $userLanguage;
    }

    /**
     * Updates an existing user language
     * 
     * @param array $data
     * @param int $userLanguageId
     * @param int $userProfileId
     * @return bool
     */
    public function updateUserLanguage(array $data, int $userLanguageId, int $userProfileId): bool
    {
        // get user language model
        $userLanguage = $this->getUserLanguageModel($userLanguageId, $userProfileId);

        // check if user language exists
        $this->checkUserLanguageExists($data['language_id'], $userProfileId, $userLanguageId);

        // update language
        $result = $userLanguage->update([
            'language_id' => $data['language_id'],
            'proficiency_level' => $data['proficiency_level']
        ]);

        return $result;
    }

    /**
     * Deletes an existing user language
     * 
     * @param int $userLanguageId
     * @param int $userProfileId
     * @return bool
     */
    public function deleteUserLanguage(int $userLanguageId, int $userProfileId): bool
    {
        $userLanguage = $this->getUserLanguageModel($userLanguageId, $userProfileId);

        $result = $userLanguage->delete();

        return $result;
    }
}
