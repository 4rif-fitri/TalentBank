<?php

namespace App\Http\Controllers;

use App\Http\Helpers\ApiResponse;
use App\Services\OrganizationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OrganizationController extends Controller
{
    public function __construct(
        private readonly OrganizationService $organizationService
    ) {
    }

    /**
     * Helper function to validate organization data from request
     * 
     * @param   Request $request
     * @return  array
     */
    private function validateOrganizationData(Request $request): array
    {
        return $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'ssm_number' => ['required', 'string', 'max:255'],
            'industry_category_id' => ['required', 'int', 'exists:industry_categories,id'],
            'address' => ['required', 'string', 'max:255'],
            'postcode' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'state' => ['required', 'string', 'max:255'],
            'website' => ['nullable', 'url', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
            'company_email' => ['required', 'email', 'max:255'],
            'company_phone' => ['required', 'string', 'max:255'],
            'industry_sector_id' => ['required', 'int', 'exists:industry_sectors,id'],
            'organization_type_id' => ['required', 'int', 'exists:organization_types,id'],
        ]);
    }

    /**
     * Handle request to get all organizations
     * 
     * @return  JsonResponse
     */
    public function getAllOrganizations(): JsonResponse
    {
        $organizations = $this->organizationService->getAllOrganizations();

        return ApiResponse::success('Success.', $organizations)->toJsonResponse();
    }

    /**
     * Handle request to create new organization
     * 
     * @param   Request $request
     * @return  JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $this->validateOrganizationData($request);
        $userProfileId = session('user_profile_id');

        $organization = $this->organizationService->createOrganization($validated, $userProfileId);

        return ApiResponse::success('Organization created successfully.', $organization, Response::HTTP_CREATED)->toJsonResponse();
    }

    /**
     * Handle request for updating existing organization info
     * 
     * @param   Request $request
     * @return  JsonResponse
     */
    public function update(Request $request, int $orgId): JsonResponse
    {
        $validated = $this->validateOrganizationData($request);
        $userProfileId = session('user_profile_id');

        $this->organizationService->updateOrganization($validated, $orgId, $userProfileId);

        return ApiResponse::success('Organization updated successfully.', null)->toJsonResponse();
    }

    /**
     * Handle request for getting all organization types
     * 
     * @return  JsonResponse
     */
    public function getAllOrganizationTypes(): JsonResponse
    {
        $organizationTypes = $this->organizationService->getAllOrganizationTypes();
        return ApiResponse::success('Success.', $organizationTypes)->toJsonResponse();
    }

    /**
     * Handle request for getting all industry categories
     * 
     * @return  JsonResponse
     */
    public function getAllIndustryCategories(): JsonResponse
    {
        $industryCategories = $this->organizationService->getAllIndustryCategories();
        return ApiResponse::success('Success.', $industryCategories)->toJsonResponse();
    }

    /**
     * Handle request for getting all industry sectors
     * 
     * @return  JsonResponse
     */
    public function getAllIndustrySectors(): JsonResponse
    {
        $industrySectors = $this->organizationService->getAllIndustrySectors();
        return ApiResponse::success('Success.', $industrySectors)->toJsonResponse();
    }
}
