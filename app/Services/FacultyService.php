<?php

namespace App\Services;

use App\Models\Faculty;
use App\Models\Organization;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

class FacultyService
{
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
        return Faculty::with('programmes')->find($facultyId);
    }

    /**
     * Get all faculties by organization ID.
     * 
     * @param array $data
     * 
     * @return Faculty
     */
    public function createFaculty(array $data): Faculty
    {
        $orgId = $data['organization_id'];

        // check if organization exists
        $orgExists = Organization::find($orgId)->exists();

        if (!$orgExists) {
            throw new Exception('Organization not found with given ID.', Response::HTTP_NOT_FOUND);
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
     * 
     * @return bool
     */
    public function updateFaculty(int $facultyId, array $data): bool
    {
        $faculty = Faculty::find($facultyId);

        if (!isset($faculty)) {
            throw new Exception('Faculty not found with given ID.', Response::HTTP_NOT_FOUND);
        }

        $result = $faculty->update([
            'faculty_name' => $data['faculty_name'],
            'faculty_code' => $data['faculty_code']
        ]);

        return $result;
    }
}
