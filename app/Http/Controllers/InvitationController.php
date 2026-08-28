<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Services\InvitationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class InvitationController extends Controller
{
    public function __construct(
        private readonly InvitationService $invitationService
    ) {
    }

    /**
     * Handles request to get invitations receiver's user profile ID
     * 
     * @return JsonResponse
     */
    public function getInvitationsByReceiverId(): JsonResponse
    {
        $userProfileId = session('user_profile_id');
        $invitations = $this->invitationService->getInvitationsByReceiverId($userProfileId);
        return ApiResponse::success('Success.', $invitations)->toJsonResponse();
    }

    /**
     * Handles request to get invitations sender's user profile ID
     * 
     * @return JsonResponse
     */
    public function getInvitationsBySenderId(): JsonResponse
    {
        $userProfileId = session('user_profile_id');
        $invitations = $this->invitationService->getInvitationsBySenderId($userProfileId);
        return ApiResponse::success('Success.', $invitations)->toJsonResponse();
    }

    /**
     * Handles request to get invitation by invitation ID
     * 
     * @param int $id
     * @return JsonResponse
     */
    public function getInvitationById(int $id): JsonResponse
    {
        $userProfileId = session('user_profile_id');
        $invitation = $this->invitationService->getInvitationById($id, $userProfileId);
        return ApiResponse::success('Success.', $invitation)->toJsonResponse();
    }

    /**
     * Handles request to get invitations by status and sender's user profile ID
     * 
     * @param string $status
     * @return JsonResponse
     */
    public function getInvitationsByStatusAndSenderId(string $status): JsonResponse
    {
        $userProfileId = session('user_profile_id');
        $invitations = $this->invitationService->getInvitationsByStatusAndSenderId($status, $userProfileId);
        return ApiResponse::success('Success.', $invitations)->toJsonResponse();
    }

    /**
     * Handles request to create new invitation
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'receiver_profile_id' => ['required', 'integer', 'exists:user_profiles,id'],
            'invitation_message' => ['required', 'string', 'max:1000'],
            'expires_at' => ['required', 'date', 'after:now'],
            'position_id' => ['required', 'integer', 'exists:positions,id'],
        ]);

        $userProfileId = session('user_profile_id');

        $invitation = $this->invitationService->createInvitation($validated, $userProfileId);

        return ApiResponse::success('Invitation created successfully.', $invitation, Response::HTTP_CREATED)->toJsonResponse();
    }

    /**
     * Handles request to update existing invitation info (excluding invitation status)
     * 
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'invitation_message' => ['required', 'string', 'max:1000'],
            'expires_at' => ['required', 'date', 'after:now'],
        ]);

        $userProfileId = session('user_profile_id');
        $invitation = $this->invitationService->updateInvitation($validated, $id, $userProfileId);

        return ApiResponse::success('Invitation updated successfully.', $invitation)->toJsonResponse();
    }

    /**
     * Handles request to accept invitation (change invitation status)
     * 
     * @param int $id
     * @return JsonResponse
     */
    public function acceptInvitation(int $id): JsonResponse
    {
        $userProfileId = session('user_profile_id');
        $invitation = $this->invitationService->acceptInvitation($id, $userProfileId);
        return ApiResponse::success('Invitation status updated successfully.', $invitation)->toJsonResponse();
    }

    /**
     * Handles request to reject invitation (change invitation status)
     * 
     * @param int $id
     * @return JsonResponse
     */
    public function rejectInvitation(int $id): JsonResponse
    {
        $userProfileId = session('user_profile_id');
        $invitation = $this->invitationService->rejectInvitation($id, $userProfileId);
        return ApiResponse::success('Invitation status updated successfully.', $invitation)->toJsonResponse();
    }

    /**
     * Handles request to withdraw invitation (change invitation status)
     * 
     * @param int $id
     * @return JsonResponse
     */
    public function withdrawInvitation(int $id): JsonResponse
    {
        $userProfileId = session('user_profile_id');
        $invitation = $this->invitationService->withdrawInvitation($id, $userProfileId);
        return ApiResponse::success('Invitation status updated successfully.', $invitation)->toJsonResponse();
    }
}
