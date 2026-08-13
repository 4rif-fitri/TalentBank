<?php

namespace App\Http\Services;

use App\Models\Programme;
use App\Models\User;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

class ProgrammeService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Gets the programmes joined by the user and filters them based on search query or session
     * Search query can be programme name, programme code and programme level
     * 
     * @param int $userId
     * @param string $search
     * @param string $session
     * @throws Exception
     * @return Collection
     */
    public function getProgrammesByUserId(int $userId, string $search = null, string $session = null)
    {
        $userExists = User::find($userId);

        if (!isset($userExists)) {
            throw new Exception('User not found with given ID.', Response::HTTP_NOT_FOUND);
        }

        return Programme::with([
            'enrollments' => function ($query) use ($userId) {
                $query->where('user_id', $userId);
            },
            'enrollments.semesters' => function ($query) use ($session) {
                // filter for session
                $query->when(isset($session), function ($query) use ($session) {
                    $query->where('session', $session);
                });
            },
            'enrollments.semesters.media',
        ])
            ->whereHas('enrollments', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->whereHas('enrollments.semesters', function ($query) use ($session) {
                // filter for session
                $query->when(isset($session), function ($query) use ($session) {
                    $query->where('session', $session);
                });
            })
            ->when(isset($search), function ($query) use ($search) {
                // filter for search query
                $query->where(function ($query) use ($search) {
                    $query->where('programme_name', 'LIKE', $search)
                        ->orWhere('programme_code', 'LIKE', $search)
                        ->orWhere('programme_level', 'LIKE', $search);
                });
            })
            ->get();
    }
}
