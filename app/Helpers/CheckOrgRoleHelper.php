<?php

namespace App\Helpers;

use App\Models\OrganizationUser;

class CheckOrgRoleHelper
{
    public static function userHasRoles(int $userProfileId, array $roles, int $orgId): bool
    {
        return OrganizationUser::whereHas('role', function ($query) use ($roles) {
            $query->whereIn('name', $roles);
        })
            ->where([
                'user_profile_id' => $userProfileId,
                'organization_id' => $orgId,
                'status' => 1
            ])
            ->exists();
    }
}
