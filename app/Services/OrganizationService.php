<?php

namespace App\Services;

use App\Helpers\CheckOrgRoleHelper;
use App\Models\IndustryCategory;
use App\Models\IndustrySector;
use App\Models\Organization;
use App\Models\OrganizationType;
use App\Models\OrganizationUser;
use App\Models\Role;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class OrganizationService
{
    private const CACHE_TIME_HOURS = 1;

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
                'website' => $data['website'] ?? null,
                'company_email' => $data['company_email'],
                'company_phone' => $data['company_phone'],
                'description' => $data['description'] ?? null,
                'organization_logo' => $data['organization_logo'] ?? null,
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
     * @param   int $userProfileId
     * 
     * @return  Organization
     */
    public function updateOrganization(array $data, int $orgId, int $userProfileId): Organization
    {
        $organization = Organization::where('id', $orgId)
            ->whereHas('organizationUsers', function ($query) use ($userProfileId) {
                $query->whereHas('role', function ($query) {
                    $query->where('name', 'Organization Admin');
                })
                    ->where('user_profile_id', $userProfileId);
            })
            ->first();

        if (!isset($organization)) {
            throw new Exception('Organization not found or access unauthorized.', Response::HTTP_FORBIDDEN);
        }

        $ssmNumberExists = Organization::where([
            ['ssm_number', $data['ssm_number']],
            ['id', '<>', $orgId]
        ])->exists();

        if ($ssmNumberExists) {
            throw new Exception('SSM number already taken.', Response::HTTP_CONFLICT);
        }

        $organization->update([
            'company_name' => $data['company_name'],
            'ssm_number' => $data['ssm_number'],
            'industry_category_id' => $data['industry_category_id'],
            'address' => $data['address'],
            'postcode' => $data['postcode'],
            'city' => $data['city'],
            'state' => $data['state'],
            'website' => $data['website'] ?? null,
            'company_email' => $data['company_email'],
            'company_phone' => $data['company_phone'],
            'description' => $data['description'] ?? null,
            'organization_logo' => $data['organization_logo'] ?? null,
            'industry_sector_id' => $data['industry_sector_id'],
            'organization_type_id' => $data['organization_type_id'],
        ]);

        return $organization;
    }

    public function uploadOrganizationLogo(UploadedFile $file, int $orgId, int $userProfileId): Organization
    {
        $organization = Organization::find($orgId);

        if (!isset($organization)) {
            throw new Exception('Organization not found with given ID.', Response::HTTP_NOT_FOUND);
        }

        $isUserAdmin = CheckOrgRoleHelper::userHasRoles($userProfileId, ['Organization Admin'], $orgId);

        if (!$isUserAdmin) {
            throw new Exception('Unauthorized access to upload organization logo for this organization.', Response::HTTP_FORBIDDEN);
        }

        $oldLogo = $organization->organization_logo;

        // upload the organization logo
        $filename = uniqid('org_logo_') . '_' . $file->getClientOriginalName();
        $filePath = config('services.uploads_file_path.organization_logos');
        $file->move($filePath, $filename);

        // update organization_logo field for organization
        $organization->update([
            'organization_logo' => $filename,
        ]);

        // delete existing organization logo
        if ($oldLogo) {
            File::delete(config('services.uploads_file_path.organization_logos') . '/' . $oldLogo);
        }

        return $organization;
    }

    /**
     * Get all organization types
     * 
     * @return  Collection
     */
    public function getAllOrganizationTypes(): Collection
    {
        return Cache::remember('organization_types', now()->addHours(self::CACHE_TIME_HOURS), function () {
            return OrganizationType::all();
        });
    }

    /**
     * Get all industry categories
     * 
     * @return  Collection
     */
    public function getAllIndustryCategories(): Collection
    {
        return Cache::remember('industry_categories', now()->addHours(self::CACHE_TIME_HOURS), function () {
            return IndustryCategory::all();
        });
    }

    /**
     * Get all industry sectors
     * 
     * @return  Collection
     */
    public function getAllIndustrySectors(): Collection
    {
        return Cache::remember('industry_sectors', now()->addHours(self::CACHE_TIME_HOURS), function () {
            return IndustrySector::all();
        });
    }
}
