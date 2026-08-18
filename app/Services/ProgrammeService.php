<?php

namespace App\Services;

use App\Models\Organization;
use App\Models\Programme;
use App\Models\User;
use App\Models\UserProfile;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

class ProgrammeService
{
    /**
     * Gets the programmes joined by the user and filters them based on search query or session
     * Search query can be programme name, programme code and programme level
     * 
     * @param int $userProfileId
     * @param ?string $search
     * @param ?string $session
     * @return Collection
     */
    public function getProgrammesByUserProfileId(int $userProfileId, ?string $search = null, ?string $session = null): Collection
    {
        return Programme::with([
            'education' => function ($query) use ($userProfileId) {
                $query->where('user_profile_id', $userProfileId);
            },
            'education.semesters' => function ($query) use ($session) {
                // filter for session
                $query->when(isset($session), function ($query) use ($session) {
                    $query->where('session', $session);
                });
            },
            'education.semesters.media',
        ])
            ->whereHas('education', function ($query) use ($userProfileId) {
                $query->where('user_profile_id', $userProfileId);
            })
            ->whereHas('education.semesters', function ($query) use ($session) {
                // filter for session
                $query->when(isset($session), function ($query) use ($session) {
                    $query->where('session', $session);
                });
            })
            ->when(isset($search), function ($query) use ($search) {
                // filter for search query
                $query->where(function ($query) use ($search) {
                    $query->where('programme_name', 'LIKE', "%$search%")
                        ->orWhere('programme_code', 'LIKE', "%$search%")
                        ->orWhere('programme_level', 'LIKE', "%$search%");
                });
            })
            ->get();
    }

    /**
     * Get programmes by organization ID.
     * 
     * @param int $organizationId
     * @return Collection
     */
    public function getProgrammesByOrgId(int $organizationId): Collection
    {
        $orgExists = Organization::where('id', $organizationId)->exists();

        if (!$orgExists) {
            throw new Exception('Organization not found with given ID.', Response::HTTP_NOT_FOUND);
        }

        return Programme::whereHas('organization', function ($query) use ($organizationId) {
            $query->where('organizations.id', $organizationId);
        })->get();
    }
}
