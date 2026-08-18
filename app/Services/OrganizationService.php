<?php

namespace App\Services;

use App\Models\IndustryCategory;
use App\Models\IndustrySector;
use App\Models\Organization;
use App\Models\OrganizationType;
use App\Models\OrganizationUser;
use App\Models\Role;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class OrganizationService
{
    /**
     * Get all organizations
     * 
     * @return  Collection
     */
    public function getAllOrganizations(): Collection
    {
        return Organization::all();
    }

    /**
     * Create new organization
     * 
     * @param   array $data
     * 
     * @return  Organization
     */
    public function createOrganization(array $data, int $userProfileId): Organization
    {
        // check if company's ssm number exists
        $ssmNumberExists = Organization::where('ssm_number', $data['ssm_number'])->exists();

        if ($ssmNumberExists) {
            throw new Exception('SSM number already taken.', Response::HTTP_CONFLICT);
        }

        $role = Role::where('name', 'Organization Admin')->first();

        if (!isset($role)) {
            throw new Exception('Role not found with given ID.', Response::HTTP_NOT_FOUND);
        }

        $organization = DB::transaction(function () use ($data, $userProfileId, $role) {
            // insert new organization
            $organization = Organization::create([
                'company_name' => $data['company_name'],
                'ssm_number' => $data['ssm_number'],
                'industry_category_id' => $data['industry_category_id'],
                'address' => $data['address'],
                'postcode' => $data['postcode'],
                'city' => $data['city'],
                'state' => $data['state'],
                'website' => $data['website'],
                'company_email' => $data['company_email'],
                'company_phone' => $data['company_phone'],
                'description' => $data['description'],
                'industry_sector_id' => $data['industry_sector_id'],
                'organization_type_id' => $data['organization_type_id'],
            ]);

            // insert new organization_user
            OrganizationUser::create([
                'organization_id' => $organization->id,
                'user_profile_id' => $userProfileId,
                'role_id' => $role->id,
                'status' => 1
            ]);

            return $organization;
        });

        if (!isset($organization)) {
            throw new Exception('Organization not found with given ID.', Response::HTTP_NOT_FOUND);
        }

        return $organization;
    }

    /**
     * Update existing organization info
     * 
     * @param   array $data
     * @param   int $orgId
     * 
     * @return  bool
     */
    public function updateOrganization(array $data, int $orgId): bool
    {
        $organization = Organization::find($orgId);

        if (!isset($organization)) {
            throw new Exception('Organization not found with given ID.', Response::HTTP_NOT_FOUND);
        }

        $ssmNumberExists = Organization::where([
            ['ssm_number', $data['ssm_number']],
            ['id', '<>', $orgId]
        ])->exists();

        if ($ssmNumberExists) {
            throw new Exception('SSM number already taken.', Response::HTTP_CONFLICT);
        }

        $updated = $organization->update([
            'company_name' => $data['company_name'],
            'ssm_number' => $data['ssm_number'],
            'industry_category_id' => $data['industry_category_id'],
            'address' => $data['address'],
            'postcode' => $data['postcode'],
            'city' => $data['city'],
            'state' => $data['state'],
            'website' => $data['website'],
            'company_email' => $data['company_email'],
            'company_phone' => $data['company_phone'],
            'description' => $data['description'],
            'industry_sector_id' => $data['industry_sector_id'],
            'organization_type_id' => $data['organization_type_id'],
        ]);

        return $updated;
    }

    /**
     * Get all organization types
     * 
     * @return  Collection
     */
    public function getAllOrganizationTypes(): Collection
    {
        return OrganizationType::all();
    }

    /**
     * Get all industry categories
     * 
     * @return  Collection
     */
    public function getAllIndustryCategories(): Collection
    {
        return IndustryCategory::all();
    }

    /**
     * Get all industry sectors
     * 
     * @return  Collection
     */
    public function getAllIndustrySectors(): Collection
    {
        return IndustrySector::all();
    }
}
