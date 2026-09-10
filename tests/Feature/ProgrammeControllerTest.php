<?php

namespace Tests\Feature;

use App\Constants\AppConstants;
use App\Models\Education;
use App\Models\Faculty;
use App\Models\FieldOfStudy;
use App\Models\Media;
use App\Models\Organization;
use App\Models\Programme;
use App\Models\Qualification;
use App\Models\Semester;
use App\Models\User;
use App\Models\UserProfile;
use Database\Seeders\FacultySeeder;
use Database\Seeders\FieldOfStudySeeder;
use Database\Seeders\IndustryCategorySeeder;
use Database\Seeders\IndustrySectorSeeder;
use Database\Seeders\OrganizationSeeder;
use Database\Seeders\OrganizationTypeSeeder;
use Database\Seeders\QualificationSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Override;
use Tests\TestCase;

class ProgrammeControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private UserProfile $userProfile;
    private Organization $organization;

    private const PROGRAMME_RETURN_COLUMNS = [
        'id',
        'faculty_id',
        'programme_name',
        'programme_code',
        'programme_level',
        'duration_years',
        'status',
        'organization_id',
        'field_of_study_id',
        'qualification_id',
        'created_at',
        'updated_at',
    ];

    private const EDUCATION_RETURN_COLUMNS = [
        'id',
        'user_profile_id',
        'programme_id',
        'description',
        'cgpa',
        'start_date',
        'end_date',
        'enrollment_status',
        'verification_status',
    ];

    private const SEMESTER_RETURN_COLUMNS = [
        'id',
        'education_id',
        'gpa',
        'session',
        'created_at',
        'updated_at',
    ];

    private const MEDIA_RETURN_COLUMNS = [
        'id',
        'uploaded_by_user_id',
        'source_name',
        'source_id',
        'media_type',
        'file_name',
        'title',
        'description',
        'created_at',
        'updated_at',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(IndustryCategorySeeder::class);
        $this->seed(IndustrySectorSeeder::class);
        $this->seed(OrganizationTypeSeeder::class);

        FieldOfStudy::factory()->count(5)->create();
        Qualification::factory()->count(5)->create();

        $this->user = User::factory()->create();
        $this->userProfile = UserProfile::factory()->create([
            'user_id' => $this->user->id
        ]);
        $this->organization = Organization::factory()->create();
        Faculty::factory()->create();

        $this->actingAs($this->user)
            ->withSession([
                'user_profile_id' => $this->userProfile->id
            ]);
    }

    public function test_user_can_get_programme_by_user_profile_id(): void
    {
        $programmes = Programme::factory()->count(3)->create();
        foreach ($programmes as $programme) {
            $education = Education::factory()->create([
                'user_profile_id' => $this->userProfile->id,
                'programme_id' => $programme->id
            ]);

            $semester = Semester::factory()->create([
                'session' => '1 - 2024/2025',
                'education_id' => $education->id
            ]);

            Media::factory()->create([
                'source_name' => 'semester',
                'source_id' => $semester->id
            ]);
        }
        $response = $this->getJson(route('programme.getProgrammesByUserProfileId', ['id' => $this->userProfile->id]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'Success.'
            ])
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        ...self::PROGRAMME_RETURN_COLUMNS,
                        'education' => [
                            '*' => [
                                ...self::EDUCATION_RETURN_COLUMNS,
                                'semesters' => [
                                    '*' => [
                                        ...self::SEMESTER_RETURN_COLUMNS,
                                        'media' => self::MEDIA_RETURN_COLUMNS
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]);

        $this->assertCount(3, $response->json('data'));
    }

    public function test_user_can_get_programme_by_user_profile_id_with_search_filter_by_programme_name(): void
    {
        $programmes = Programme::factory()->count(2)->create([
            'programme_name' => 'Engineering'
        ]);
        $programmeToSearch = Programme::factory()->create([
            'programme_name' => 'Computer Science'
        ]);

        $programmes = [...$programmes, $programmeToSearch];

        foreach ($programmes as $programme) {
            $education = Education::factory()->create([
                'user_profile_id' => $this->userProfile->id,
                'programme_id' => $programme->id
            ]);

            $semester = Semester::factory()->create([
                'session' => '1 - 2024/2025',
                'education_id' => $education->id
            ]);

            Media::factory()->create([
                'source_name' => 'semester',
                'source_id' => $semester->id
            ]);
        }

        $response = $this->getJson(route('programme.getProgrammesByUserProfileId', ['id' => $this->userProfile->id, 'search' => 'Comp']));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'Success.'
            ])
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        ...self::PROGRAMME_RETURN_COLUMNS,
                        'education' => [
                            '*' => [
                                ...self::EDUCATION_RETURN_COLUMNS,
                                'semesters' => [
                                    '*' => [
                                        ...self::SEMESTER_RETURN_COLUMNS,
                                        'media' => self::MEDIA_RETURN_COLUMNS
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ])
            ->assertJsonPath('data.0.id', $programmeToSearch->id)
            ->assertJsonPath('data.0.education.0.user_profile_id', $this->userProfile->id)
            ->assertJsonPath('data.0.programme_name', $programmeToSearch->programme_name);

        $this->assertCount(1, $response->json('data'));
    }

    public function test_user_can_get_programme_by_user_profile_id_with_search_filter_by_programme_code(): void
    {
        $programmes = Programme::factory()->count(2)->create([
            'programme_code' => 'DMK'
        ]);
        $programmeToSearch = Programme::factory()->create([
            'programme_code' => 'DCS'
        ]);

        $programmes = [...$programmes, $programmeToSearch];

        foreach ($programmes as $programme) {
            $education = Education::factory()->create([
                'user_profile_id' => $this->userProfile->id,
                'programme_id' => $programme->id
            ]);

            $semester = Semester::factory()->create([
                'session' => '1 - 2024/2025',
                'education_id' => $education->id
            ]);

            Media::factory()->create([
                'source_name' => 'semester',
                'source_id' => $semester->id
            ]);
        }

        $response = $this->getJson(route('programme.getProgrammesByUserProfileId', ['id' => $this->userProfile->id, 'search' => 'dcs']));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'Success.'
            ])
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        ...self::PROGRAMME_RETURN_COLUMNS,
                        'education' => [
                            '*' => [
                                ...self::EDUCATION_RETURN_COLUMNS,
                                'semesters' => [
                                    '*' => [
                                        ...self::SEMESTER_RETURN_COLUMNS,
                                        'media' => self::MEDIA_RETURN_COLUMNS
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ])
            ->assertJsonPath('data.0.id', $programmeToSearch->id)
            ->assertJsonPath('data.0.education.0.user_profile_id', $this->userProfile->id)
            ->assertJsonPath('data.0.programme_code', $programmeToSearch->programme_code);

        $this->assertCount(1, $response->json('data'));
    }

    public function test_user_can_get_programme_by_user_profile_id_with_search_filter_by_programme_level(): void
    {
        $programmes = Programme::factory()->count(2)->create([
            'programme_level' => AppConstants::PROGRAMME_LEVELS[1]
        ]);
        $programmeToSearch = Programme::factory()->create([
            'programme_level' => AppConstants::PROGRAMME_LEVELS[2]
        ]);

        $programmes = [...$programmes, $programmeToSearch];

        foreach ($programmes as $programme) {
            $education = Education::factory()->create([
                'user_profile_id' => $this->userProfile->id,
                'programme_id' => $programme->id
            ]);

            $semester = Semester::factory()->create([
                'session' => '1 - 2024/2025',
                'education_id' => $education->id
            ]);

            Media::factory()->create([
                'source_name' => 'semester',
                'source_id' => $semester->id
            ]);
        }

        $response = $this->getJson(route('programme.getProgrammesByUserProfileId', ['id' => $this->userProfile->id, 'search' => 'mast']));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'Success.'
            ])
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        ...self::PROGRAMME_RETURN_COLUMNS,
                        'education' => [
                            '*' => [
                                ...self::EDUCATION_RETURN_COLUMNS,
                                'semesters' => [
                                    '*' => [
                                        ...self::SEMESTER_RETURN_COLUMNS,
                                        'media' => self::MEDIA_RETURN_COLUMNS
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ])
            ->assertJsonPath('data.0.id', $programmeToSearch->id)
            ->assertJsonPath('data.0.education.0.user_profile_id', $this->userProfile->id)
            ->assertJsonPath('data.0.programme_level', $programmeToSearch->programme_level);

        $this->assertCount(1, $response->json('data'));
    }

    public function test_user_can_get_programme_by_user_profile_id_returns_empty_array_if_filters_not_match(): void
    {
        $programmes = Programme::factory()->count(2)->create([
            'programme_level' => AppConstants::PROGRAMME_LEVELS[1]
        ]);
        $programmeToSearch = Programme::factory()->create([
            'programme_level' => AppConstants::PROGRAMME_LEVELS[2]
        ]);

        $programmes = [...$programmes, $programmeToSearch];

        foreach ($programmes as $programme) {
            $education = Education::factory()->create([
                'user_profile_id' => $this->userProfile->id,
                'programme_id' => $programme->id
            ]);

            $semester = Semester::factory()->create([
                'session' => '1 - 2024/2025',
                'education_id' => $education->id
            ]);

            Media::factory()->create([
                'source_name' => 'semester',
                'source_id' => $semester->id
            ]);
        }

        $response = $this->getJson(route('programme.getProgrammesByUserProfileId', [
            'id' => $this->userProfile->id,
            'search' => 'PhD',
            'session' => '3 - 2024/2025'
        ]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'Success.'
            ])
            ->assertJsonPath('data', []);
    }

    public function test_user_can_get_programme_by_user_profile_id_with_session_filter(): void
    {
        $programmes = Programme::factory()->count(2)->create();
        $programmeToSearch = Programme::factory()->create();

        foreach ($programmes as $programme) {
            $education = Education::factory()->create([
                'user_profile_id' => $this->userProfile->id,
                'programme_id' => $programme->id
            ]);

            $semester = Semester::factory()->create([
                'session' => '1 - 2024/2025',
                'education_id' => $education->id
            ]);

            Media::factory()->create([
                'source_name' => 'semester',
                'source_id' => $semester->id
            ]);
        }

        // create separate data for programme to be searched
        $education = Education::factory()->create([
            'user_profile_id' => $this->userProfile->id,
            'programme_id' => $programmeToSearch->id
        ]);

        $semester = Semester::factory()->create([
            'session' => '2 - 2025/2026',
            'education_id' => $education->id
        ]);

        Media::factory()->create([
            'source_name' => 'semester',
            'source_id' => $semester->id
        ]);

        $response = $this->getJson(route('programme.getProgrammesByUserProfileId', ['id' => $this->userProfile->id, 'session' => '2 - 2025/2026']));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'Success.'
            ])
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        ...self::PROGRAMME_RETURN_COLUMNS,
                        'education' => [
                            '*' => [
                                ...self::EDUCATION_RETURN_COLUMNS,
                                'semesters' => [
                                    '*' => [
                                        ...self::SEMESTER_RETURN_COLUMNS,
                                        'media' => self::MEDIA_RETURN_COLUMNS
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ])
            ->assertJsonPath('data.0.id', $programmeToSearch->id)
            ->assertJsonPath('data.0.education.0.user_profile_id', $this->userProfile->id)
            ->assertJsonPath('data.0.education.0.semesters.0.session', $programmeToSearch->education->first()->semesters->first()->session);

        $this->assertCount(1, $response->json('data'));
    }

    public function test_user_can_get_programme_by_user_profile_id_with_search_and_session_filter(): void
    {
        $programmes = Programme::factory()->count(2)->create([
            'programme_name' => 'Engineering'
        ]);
        $programmeToSearch = Programme::factory()->create([
            'programme_name' => 'Computer Science'
        ]);

        foreach ($programmes as $programme) {
            $education = Education::factory()->create([
                'user_profile_id' => $this->userProfile->id,
                'programme_id' => $programme->id
            ]);

            $semester = Semester::factory()->create([
                'session' => '1 - 2024/2025',
                'education_id' => $education->id
            ]);

            Media::factory()->create([
                'source_name' => 'semester',
                'source_id' => $semester->id
            ]);
        }

        // create separate data for programme to be searched
        $education = Education::factory()->create([
            'user_profile_id' => $this->userProfile->id,
            'programme_id' => $programmeToSearch->id
        ]);

        $semester = Semester::factory()->create([
            'session' => '2 - 2025/2026',
            'education_id' => $education->id
        ]);

        Media::factory()->create([
            'source_name' => 'semester',
            'source_id' => $semester->id
        ]);

        $response = $this->getJson(route('programme.getProgrammesByUserProfileId', [
            'id' => $this->userProfile->id,
            'search' => 'comp',
            'session' => '2 - 2025/2026'
        ]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'Success.'
            ])
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        ...self::PROGRAMME_RETURN_COLUMNS,
                        'education' => [
                            '*' => [
                                ...self::EDUCATION_RETURN_COLUMNS,
                                'semesters' => [
                                    '*' => [
                                        ...self::SEMESTER_RETURN_COLUMNS,
                                        'media' => self::MEDIA_RETURN_COLUMNS
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ])
            ->assertJsonPath('data.0.id', $programmeToSearch->id)
            ->assertJsonPath('data.0.programme_name', $programmeToSearch->programme_name)
            ->assertJsonPath('data.0.education.0.user_profile_id', $this->userProfile->id)
            ->assertJsonPath('data.0.education.0.semesters.0.session', $programmeToSearch->education->first()->semesters->first()->session);

        $this->assertCount(1, $response->json('data'));
    }

    public function test_user_can_get_programme_by_org_id(): void
    {
        $programmes = Programme::factory()->count(3)->create([
            'organization_id' => $this->organization->id
        ]);

        foreach ($programmes as $programme) {
            $education = Education::factory()->create([
                'user_profile_id' => $this->userProfile->id,
                'programme_id' => $programme->id
            ]);

            $semester = Semester::factory()->create([
                'education_id' => $education->id
            ]);

            Media::factory()->create([
                'source_name' => 'semester',
                'source_id' => $semester->id
            ]);
        }

        $response = $this->getJson(route('programme.getProgrammesByOrgId', ['orgId' => $this->organization->id]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'Success.'
            ])
            ->assertJsonStructure([
                'data' => [
                    '*' => self::PROGRAMME_RETURN_COLUMNS
                ]
            ])
            ->assertJsonPath('data.0.organization_id', $this->organization->id)
            ->assertJsonPath('data.1.organization_id', $this->organization->id)
            ->assertJsonPath('data.2.organization_id', $this->organization->id);

        $this->assertCount(3, $response->json('data'));
    }

    public function test_get_programme_by_org_id_returns_empty_array_if_no_programme_exists(): void
    {
        $otherOrganization = Organization::factory()->create();
        $programmes = Programme::factory()->count(3)->create([
            'organization_id' => $otherOrganization->id
        ]);

        foreach ($programmes as $programme) {
            $education = Education::factory()->create([
                'user_profile_id' => $this->userProfile->id,
                'programme_id' => $programme->id
            ]);

            $semester = Semester::factory()->create([
                'education_id' => $education->id
            ]);

            Media::factory()->create([
                'source_name' => 'semester',
                'source_id' => $semester->id
            ]);
        }

        $response = $this->getJson(route('programme.getProgrammesByOrgId', ['orgId' => $this->organization->id]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'Success.'
            ])
            ->assertJsonPath('data', []);
    }

    public function test_get_programme_by_org_id_fails_with_non_existing_org_id(): void
    {
        $programmes = Programme::factory()->count(3)->create([
            'organization_id' => $this->organization->id
        ]);

        foreach ($programmes as $programme) {
            $education = Education::factory()->create([
                'user_profile_id' => $this->userProfile->id,
                'programme_id' => $programme->id
            ]);

            $semester = Semester::factory()->create([
                'education_id' => $education->id
            ]);

            Media::factory()->create([
                'source_name' => 'semester',
                'source_id' => $semester->id
            ]);
        }

        $response = $this->getJson(route('programme.getProgrammesByOrgId', ['orgId' => 0]));

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonFragment([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Organization not found with given ID.'
            ])
            ->assertJsonPath('data', null);
    }

    public function test_user_can_get_field_of_studies(): void
    {
        $response = $this->getJson(route('programme.getAllFieldOfStudies'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'Success.'
            ])
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name'
                    ]
                ]
            ]);

        $this->assertCount(5, $response->json('data'));
    }

    public function test_user_can_get_qualifications(): void
    {
        $response = $this->getJson(route('programme.getAllQualifications'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'Success.'
            ])
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name'
                    ]
                ]
            ]);

        $this->assertCount(5, $response->json('data'));
    }
}
