<?php

namespace App\Services;

use App\Helpers\CheckOrgRoleHelper;
use App\Models\Position;
use App\Models\Shortlist;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

class ShortlistService
{
    private const ADMINISTRATIVE_ROLES = ['Organization Admin', 'Recruiter'];

    /**
     * Returns shortlisted positions filtered by user profile ID
     * 
     * @param int $userProfileId
     * @return array
     */
    public function getShortlistedPositions(int $userProfileId, int $shortlistedProfileId, int $orgId): array
    {
        $isUserAdmin = CheckOrgRoleHelper::userHasRoles($userProfileId, self::ADMINISTRATIVE_ROLES, $orgId);

        if (!$isUserAdmin) {
            throw new Exception('Unauthorized access to view shortlisted positions.', Response::HTTP_FORBIDDEN);
        }

        $shortlistedPositions = Shortlist::whereHas('position', function ($query) use ($orgId, $userProfileId) {
            $query->where([
                'organization_id' => $orgId,
                'user_profile_id' => $userProfileId
            ]);
        })
            ->where('user_profile_id', $shortlistedProfileId)
            ->get();

        return $shortlistedPositions->pluck('position_id')->toArray();
    }

    /**
     * Creates a new shortlist entry
     * 
     * @param int $positionId
     * @param int $userProfileId
     * @throws Exception
     * @return Shortlist
     */
    public function createShortlist(int $positionId, int $userProfileId): Shortlist
    {
        $position = Position::findOrFail($positionId);

        // Check if the user an admin of the current organization
        $isUserAdmin = CheckOrgRoleHelper::userHasRoles($position->user_profile_id, self::ADMINISTRATIVE_ROLES, $position->organization_id);

        if (!$isUserAdmin) {
            throw new Exception('Unauthorized access to shortlist user for this position.', Response::HTTP_FORBIDDEN);
        }

        // Check if the user is already shortlisted for the position
        $existingShortlist = Shortlist::where('position_id', $positionId)
            ->where('user_profile_id', $userProfileId)
            ->first();

        if ($existingShortlist) {
            throw new Exception('User is already shortlisted for this position.', Response::HTTP_CONFLICT);
        }

        // Create a new shortlist entry
        $shortlist = Shortlist::create([
            'position_id' => $positionId,
            'user_profile_id' => $userProfileId,
        ]);

        return $shortlist;
    }

    /**
     * Deletes an existing shortlist entry
     * 
     * @param int $shortlistId
     * @param int $userProfileId
     * @throws Exception
     * @return Shortlist|\Illuminate\Database\Eloquent\Builder<Shortlist>|\stdClass
     */
    public function deleteShortlist(int $shortlistId, int $userProfileId): Shortlist
    {
        $shortlist = Shortlist::with('position:id,organization_id')->find($shortlistId);

        if (!isset($shortlist)) {
            throw new Exception('Shortlist entry not found.', Response::HTTP_NOT_FOUND);
        }

        // Check if the user is an admin of the current organization
        $isUserAdmin = CheckOrgRoleHelper::userHasRoles($userProfileId, self::ADMINISTRATIVE_ROLES, $shortlist->position->organization_id);

        if (!$isUserAdmin) {
            throw new Exception('User does not have permission to delete this shortlist entry.', Response::HTTP_FORBIDDEN);
        }

        // Delete the shortlist entry
        $shortlist->delete();

        return $shortlist;
    }
}
