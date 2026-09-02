<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Services\ShortlistService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ShortlistController extends Controller
{
    public function __construct(
        private readonly ShortlistService $shortlistService
    ) {
    }

    /**
     * Shortlist a user profile for a position.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_profile_id' => ['required', 'integer', 'exists:user_profiles,id'],
            'position_id' => ['required', 'integer', 'exists:positions,id'],
        ]);

        $shortlist = $this->shortlistService->createShortlist($validated['position_id'], $validated['user_profile_id']);

        return ApiResponse::success('User profile shortlisted successfully.', $shortlist, Response::HTTP_CREATED)->toJsonResponse();
    }

    /**
     * Remove a shortlist entry.
     */
    public function delete(int $shortlistId): JsonResponse
    {
        $userProfileId = session('user_profile_id');
        $shortlist = $this->shortlistService->deleteShortlist($shortlistId, $userProfileId);

        return ApiResponse::success('User profile shortlisted successfully.', $shortlist)->toJsonResponse();
    }
}
