<?php

namespace App\Http\Controllers;

use App\Http\Helpers\ApiResponse;
use App\Http\Services\OrganizationService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

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
        $organizations = $this->organizationService->getAllOrganizations();

        return ApiResponse::success('Success.', $organizations)->toJsonResponse();
    }

    /**
     * Handle request to create new organization
     * 
     * @param   Request $request
     * @return  JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $validated = $this->validateOrganizationData($request);

            $organization = $this->organizationService->createOrganization($validated);

            return ApiResponse::success('Organization created successfully.', $organization, 201)->toJsonResponse();
        } catch (ValidationException $e) {
            return ApiResponse::error($e->getMessage(), Response::HTTP_BAD_REQUEST)->toJsonResponse();
        } catch (Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode())->toJsonResponse();
        }
    }

    /**
     * Handle request for updating existing organization info
     * 
     * @param   Request $request
     * @return  JsonResponse
     */
    public function update(Request $request, int $orgId)
    {
        try {
            $validated = $this->validateOrganizationData($request);

            if (!isset($orgId)) {
                throw new Exception('Organization ID is required.', Response::HTTP_BAD_REQUEST);
            }

            $this->organizationService->updateOrganization($validated, $orgId);

            return ApiResponse::success('Organization updated successfully.', null)->toJsonResponse();
        } catch (ValidationException $e) {
            return ApiResponse::error($e->getMessage(), Response::HTTP_BAD_REQUEST)->toJsonResponse();
        } catch (Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode())->toJsonResponse();
        }
    }

    /**
     * Handle request for getting all organization types
     * 
     * @return  JsonResponse
     */
    public function getAllOrganizationTypesJson()
    {
        $organizationTypes = $this->organizationService->getAllOrganizationTypes();
        return ApiResponse::success('Success.', $organizationTypes)->toJsonResponse();
    }

    /**
     * Handle request for getting all industry categories
     * 
     * @return  JsonResponse
     */
    public function getAllIndustryCategoriesJson()
    {
        $industryCategories = $this->organizationService->getAllIndustryCategories();
        return ApiResponse::success('Success.', $industryCategories)->toJsonResponse();
    }

    /**
     * Handle request for getting all industry sectors
     * 
     * @return  JsonResponse
     */
    public function getAllIndustrySectorsJson()
    {
        $industrySectors = $this->organizationService->getAllIndustrySectors();
        return ApiResponse::success('Success.', $industrySectors)->toJsonResponse();
    }
}
