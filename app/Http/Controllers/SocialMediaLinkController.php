<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Services\SocialMediaLinkService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SocialMediaLinkController extends Controller
{
    public function __construct(
        private readonly SocialMediaLinkService $socialMediaLinkService
    ) {
    }

    private function validateRequest(Request $request): array
    {
        return $request->validate([
            'social_media_id' => ['required', 'exists:social_media,id'],
            'link' => ['required', 'url']
        ]);
    }

    /**
     * Handles request for get all social media
     * 
     * @return JsonResponse
     */
    public function getAllSocialMedia(): JsonResponse
    {
        $socialMedia = $this->socialMediaLinkService->getAllSocialMedia();

        return ApiResponse::success('Success.', $socialMedia)->toJsonResponse();
    }

    /**
     * Handles request for creating new social media link
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $this->validateRequest($request);
        $userProfileId = session('user_profile_id');

        $link = $this->socialMediaLinkService->createSocialMediaLink($validated, $userProfileId);

        return ApiResponse::success('Social media link created successfully.', $link, Response::HTTP_CREATED)->toJsonResponse();
    }

    /**
     * Handles request for updating social media link
     * 
     * @param Request $request
     * @param int $id
     * @throws Exception
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $this->validateRequest($request);
        $userProfileId = session('user_profile_id');

        $socialMediaLink = $this->socialMediaLinkService->updateSocialMediaLink($validated, $id, $userProfileId);

        return ApiResponse::success('Social media link updated successfully.', $socialMediaLink)->toJsonResponse();
    }

    /**
     * Handles request for deleting social media link
     * 
     * @param int $id
     * @throws Exception
     * @return JsonResponse
     */
    public function delete(int $id): JsonResponse
    {
        $userProfileId = session('user_profile_id');

        $this->socialMediaLinkService->deleteSocialMediaLink($id, $userProfileId);

        return ApiResponse::success('Social media link deleted successfully.', null)->toJsonResponse();
    }
}
