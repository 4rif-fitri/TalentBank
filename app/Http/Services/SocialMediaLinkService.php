<?php

namespace App\Http\Services;

use App\Models\SocialMedia;
use App\Models\SocialMediaLink;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

class SocialMediaLinkService
{
    private function getSocialMediaLinkModelById(int $linkId, int $userProfileId): SocialMediaLink
    {
        $socialMediaLink = SocialMediaLink::where([
            ['user_profile_id', $userProfileId],
            ['id', $linkId]
        ])->first();

        if (!isset($socialMediaLink)) {
            throw new Exception('Social media link not found with given ID.', Response::HTTP_NOT_FOUND);
        }

        return $socialMediaLink;
    }

    private function checkSocialMediaLinkExists(int $socialMediaId, int $userProfileId, ?int $linkId = null): void
    {
        $linkExists = SocialMediaLink::where([
            'social_media_id' => $socialMediaId,
            'user_profile_id' => $userProfileId
        ])
            ->when(isset($linkId), function ($query) use ($linkId) {
                $query->where('id', '<>', $linkId);
            })
            ->exists();

        if ($linkExists) {
            throw new Exception('Social media link already exists.', Response::HTTP_CONFLICT);
        }
    }

    /**
     * Returns all the available social media
     * 
     * @return \Illuminate\Database\Eloquent\Collection<int, SocialMedia>
     */
    public function getAllSocialMedia(): Collection
    {
        return SocialMedia::all();
    }

    /**
     * Creates a new social media link
     * 
     * @param array $data
     * @param int $userProfileId
     * @return SocialMediaLink
     */
    public function createSocialMediaLink(array $data, int $userProfileId): SocialMediaLink
    {
        $this->checkSocialMediaLinkExists($data['social_media_id'], $userProfileId);

        $socialMediaLink = SocialMediaLink::create([
            'user_profile_id' => $userProfileId,
            'social_media_id' => $data['social_media_id'],
            'link' => $data['link']
        ]);

        return $socialMediaLink;
    }

    /**
     * Updates existing social media link
     * 
     * @param array $data
     * @param int $linkId
     * @param int $userProfileId
     * @throws Exception
     * @return bool|int
     */
    public function updateSocialMediaLink(array $data, int $linkId, int $userProfileId): bool
    {
        $socialMediaLink = $this->getSocialMediaLinkModelById($linkId, $userProfileId);

        $this->checkSocialMediaLinkExists($data['social_media_id'], $userProfileId, $linkId);

        $result = $socialMediaLink->update([
            'social_media_id' => $data['social_media_id'],
            'link' => $data['link'],
        ]);

        return $result;
    }

    /**
     * Deletes existing social media link
     * 
     * @param int $linkId
     * @param int $userProfileId
     * @throws Exception
     * @return bool|int
     */
    public function deleteSocialMediaLink(int $linkId, int $userProfileId): bool
    {
        $socialMediaLink = $this->getSocialMediaLinkModelById($linkId, $userProfileId);

        $result = $socialMediaLink->delete();

        return $result;
    }
}
