<?php

namespace App\Services;

use App\Helpers\CheckOrgRoleHelper;
use App\Models\Faculty;
use App\Models\Organization;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

class FacultyService
{
    private const ORG_ADMIN_ROLES = ['Organization Admin'];

    /**
     * Get all faculties by organization ID.
     * 
     * @param int $organizationId
     * 
     * @return Collection
     */
    public function getFacultiesByOrgId(int $organizationId): Collection
    {
        return Faculty::with('programmes')->where('organization_id', $organizationId)->get();
    }

    /**
     * Get faculty by faculty ID.
     * 
     * @param int $facultyId
     * 
     * @return Faculty
     */
    public function getFacultyById(int $facultyId): Faculty
    {
        $faculty = Faculty::with('programmes')->find($facultyId);

        if (!isset($faculty)) {
            throw new Exception('Faculty not found with given ID.', Response::HTTP_NOT_FOUND);
        }

        return $faculty;
    }

    /**
     * Get all faculties by organization ID.
     * 
     * @param array $data
     * @param int $userProfileId
     * 
     * @return Faculty
     */
    public function createFaculty(array $data, int $userProfileId): Faculty
    {
        $orgId = $data['organization_id'];

        // check if organization exists
        $isOrgExists = Organization::where('id', $orgId)->exists();

        if (!$isOrgExists) {
            throw new Exception('Organization not found with given ID.', Response::HTTP_NOT_FOUND);
        }

        $isUserAdmin = CheckOrgRoleHelper::userHasRoles($userProfileId, self::ORG_ADMIN_ROLES, $orgId);

        if (!$isUserAdmin) {
            throw new Exception('Unauthorized access to create faculty in this organization.', Response::HTTP_FORBIDDEN);
        }

        // create new faculty
        $faculty = Faculty::create([
            'organization_id' => $orgId,
            'faculty_name' => $data['faculty_name'],
            'faculty_code' => $data['faculty_code']
        ]);

        return $faculty;
    }

    /**
     * Update faculty info by faculty ID.
     * 
     * @param int $facultyId
     * @param array $data
     * @param int $userProfileId
     * 
     * @return Faculty
     */
    public function updateFaculty(int $facultyId, array $data, int $userProfileId): Faculty
    {
        $faculty = Faculty::find($facultyId);

        if (!isset($faculty)) {
            throw new Exception('Faculty not found with given ID.', Response::HTTP_NOT_FOUND);
        }

        $isUserAdmin = CheckOrgRoleHelper::userHasRoles($userProfileId, self::ORG_ADMIN_ROLES, $faculty->organization_id);

        if (!$isUserAdmin) {
            throw new Exception('Unauthorized access to update faculty in this organization.', Response::HTTP_FORBIDDEN);
        }

        $faculty->update([
            'faculty_name' => $data['faculty_name'],
            'faculty_code' => $data['faculty_code']
        ]);

        return $faculty;
    }
}
