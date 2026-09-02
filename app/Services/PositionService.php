<?php

namespace App\Services;

use App\Helpers\CheckOrgRoleHelper;
use App\Models\Position;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

class PositionService
{
    private const ADMINISTRATIVE_ROLES = ['Organization Admin', 'Recruiter'];

    private function getPositionModel(int $positionId, int $userProfileId): Position
    {
        // TODO: Refactor
        $position = Position::where('id', $positionId)
            ->whereHas('organization.organizationUsers', function ($query) use ($userProfileId) {
                $query->where('user_profile_id', $userProfileId)
                    ->whereHas('role', function ($q) {
                        $q->whereIn('name', self::ADMINISTRATIVE_ROLES);
                    });
            })->first();

        if (!$position) {
            throw new Exception('Position not found or access unauthorized.', Response::HTTP_NOT_FOUND);
        }

        return $position;
    }

    /**
     * Returns all positions available
     * 
     * @return \Illuminate\Database\Eloquent\Collection<int, Position>
     */
    public function getAllPositions(): Collection
    {
        return Position::all();
    }

    /**
     * Returns all positions within an organization by organization ID
     * 
     * @param int $orgId
     * @return Collection<int, \stdClass>|\Illuminate\Database\Eloquent\Collection<int, Position>
     */
    public function getPositionsByOrgId(int $orgId): Collection
    {
        return Position::where('organization_id', $orgId)->get();
    }

    /**
     * Returns the position by position ID
     * 
     * @param int $positionId
     * @throws Exception
     * @return Position|\Illuminate\Database\Eloquent\Builder<Position>|\stdClass
     */
    public function getPositionById(int $positionId): Position
    {
        $position = Position::with([
            'shortlistUsers:id,name',
            'shortlistUsers.skills',
        ])->find($positionId);

        if (!isset($position)) {
            throw new Exception('Position not found with given ID.', Response::HTTP_NOT_FOUND);
        }

        return $position;
    }

    /**
     * Creates a new position
     * 
     * @param array $data
     * @param int $userProfileId
     * @throws Exception
     * @return Position
     */
    public function createPosition(array $data, int $userProfileId): Position
    {
        // check if user is a recruiter or organization admin in this organization
        $isUserRecuiterInOrg = CheckOrgRoleHelper::userHasRoles($userProfileId, self::ADMINISTRATIVE_ROLES, $data['organization_id']);

        if (!$isUserRecuiterInOrg) {
            throw new Exception('Unauthorized access.', Response::HTTP_FORBIDDEN);
        }

        // create new position
        $position = Position::create([
            'organization_id' => $data['organization_id'],
            'user_profile_id' => $userProfileId,
            'position_title' => $data['position_title'],
            'employment_type' => $data['employment_type'],
            'department' => $data['department'],
            'work_location' => $data['work_location'],
            'vacancies' => $data['vacancies'],
            'description' => $data['description'],
        ]);

        return $position;
    }

    /**
     * Updates an existing position info
     * 
     * @param array $data
     * @param int $positionId
     * @param int $userProfileId
     * @return Position
     */
    public function updatePosition(array $data, int $positionId, int $userProfileId): Position
    {
        // get position model
        $position = $this->getPositionModel($positionId, $userProfileId);

        $position->update([
            'position_title' => $data['position_title'],
            'employment_type' => $data['employment_type'],
            'department' => $data['department'],
            'work_location' => $data['work_location'],
            'vacancies' => $data['vacancies'],
            'description' => $data['description'],
        ]);

        return $position;
    }
}
