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

    private function getPositionModel(int $positionId): Position
    {
        $position = Position::find($positionId);

        if (!$position) {
            throw new Exception('Position not found.', Response::HTTP_NOT_FOUND);
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
    public function getPositionById(int $positionId, int $userProfileId): Position
    {
        $position = Position::with([
            'shortlistUsers' => function ($query) use ($positionId) {
                $query->select(
                    'user_profiles.id',
                    'user_profiles.name',
                    'user_profiles.location',
                    'user_profiles.profile_image',
                    'user_profiles.headline'
                )
                    ->withCount([
                        'receivedInvitations as invitations_count' => function ($query) use ($positionId) {
                            $query->where('position_id', $positionId);
                        }
                    ])
                    ->withCount([
                        'receivedInterviews as interviews_count' => function ($query) use ($positionId) {
                            $query->where('invitations.position_id', $positionId);
                        }
                    ]);
            },
            'shortlistUsers.skills',
        ])->find($positionId);

        if (!isset($position)) {
            throw new Exception('Position not found with given ID.', Response::HTTP_NOT_FOUND);
        }

        $isUserAdmin = CheckOrgRoleHelper::userHasRoles($userProfileId, self::ADMINISTRATIVE_ROLES, $position->organization_id);

        if (!$isUserAdmin) {
            throw new Exception('Unauthorized access to view this position.', Response::HTTP_FORBIDDEN);
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
        $isUserAdmin = CheckOrgRoleHelper::userHasRoles($userProfileId, self::ADMINISTRATIVE_ROLES, $data['organization_id']);

        if (!$isUserAdmin) {
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
        $position = $this->getPositionModel($positionId);

        // check if user is a recruiter or organization admin in this organization
        $isUserAdmin = CheckOrgRoleHelper::userHasRoles($userProfileId, self::ADMINISTRATIVE_ROLES, $position->organization_id);

        if (!$isUserAdmin) {
            throw new Exception('Unauthorized access.', Response::HTTP_FORBIDDEN);
        }

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
