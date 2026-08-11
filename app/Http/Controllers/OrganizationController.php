<?php

namespace App\Http\Controllers;

use App\Http\Helpers\ApiResponse;
use App\Http\Services\OrganizationService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    private OrganizationService $organizationService;

    public function __construct(OrganizationService $organizationService)
    {
        $this->organizationService = $organizationService;
    }

    /**
     * Helper function to validate organization data from request
     * 
     * @param   Request $request
     * @return  array
     */
    private function validateOrganizationData(Request $request)
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
     * @param   Request $request
     * @return  JsonResponse
     */
    public function getAllOrganizationsJson(Request $request)
    {
        if (!$request->ajax()) {
            return ApiResponse::error('Ajax request required.', 405);
        }

        $organizations = $this->organizationService->getAllOrganizations();

        return ApiResponse::success('Success.', $organizations);
    }

    /**
     * Handle request to create new organization
     * 
     * @param   Request $request
     * @return  RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            $validated = $this->validateOrganizationData($request);

            $organization = $this->organizationService->createOrganization($validated);

            return redirect()->back()->with(['success' => 'Organization created successfully.', 'data' => $organization]);
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    /**
     * Handle request for updating existing organization info
     * 
     * @param   Request $request
     * @return  RedirectResponse
     */
    public function update(Request $request)
    {
        try {
            $validated = $this->validateOrganizationData($request);
            $orgId = $request->input('org_id');

            if (!isset($orgId)) {
                return redirect()->back()->withErrors('Organization ID is required.');
            }

            $this->organizationService->updateOrganization($validated, $orgId);

            return redirect()->back()->with(['success' => 'Organization updated successfully.']);
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    /**
     * Handle request for getting all organization types
     * 
     * @return  JsonResponse
     */
    public function getAllOrganizationTypesJson()
    {
        return $this->organizationService->getAllOrganizationTypes();
    }

    /**
     * Handle request for getting all industry categories
     * 
     * @return  JsonResponse
     */
    public function getAllIndustryCategoriesJson()
    {
        return $this->organizationService->getAllIndustryCategories();
    }

    /**
     * Handle request for getting all industry sectors
     * 
     * @return  JsonResponse
     */
    public function getAllIndustrySectorsJson()
    {
        return $this->organizationService->getAllIndustrySectors();
    }
}
