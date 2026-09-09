<?php

namespace Tests\Feature;

use App\Constants\AppConstants;
use App\Models\Organization;
use App\Models\OrganizationUser;
use App\Models\Position;
use App\Models\Role;
use App\Models\User;
use App\Models\UserProfile;
use Database\Seeders\FacultySeeder;
use Database\Seeders\FieldOfStudySeeder;
use Database\Seeders\IndustryCategorySeeder;
use Database\Seeders\IndustrySectorSeeder;
use Database\Seeders\OrganizationSeeder;
use Database\Seeders\OrganizationTypeSeeder;
use Database\Seeders\QualificationSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class PositionControllerTest extends TestCase
{
    use RefreshDatabase;

    private const POSITION_RETURN_COLUMNS = [
        'id',
        'organization_id',
        'user_profile_id',
        'position_title',
        'employment_type',
        'department',
        'work_location',
        'vacancies',
        'description',
    ];

    private User $user;
    private UserProfile $adminProfile;
    private Organization $organization;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(IndustryCategorySeeder::class);
        $this->seed(IndustrySectorSeeder::class);
        $this->seed(OrganizationTypeSeeder::class);
        $this->seed(RoleSeeder::class);
        $this->seed(OrganizationSeeder::class);
        $this->seed(FacultySeeder::class);
        $this->seed(QualificationSeeder::class);
        $this->seed(FieldOfStudySeeder::class);

        $this->user = User::factory()->create();
        $this->adminProfile = UserProfile::factory()->create([
            'user_id' => $this->user->id,
        ]);
        $this->organization = Organization::factory()->create();

        OrganizationUser::create([
            'organization_id' => $this->organization->id,
            'user_profile_id' => $this->adminProfile->id,
            'role_id' => Role::where('name', 'Organization Admin')->first()->id,
            'status' => 1,
        ]);

        $this->actingAs($this->user)->withSession([
            'user_profile_id' => $this->adminProfile->id,
            'roles' => ['Organization Admin'],
        ]);
    }

    private function validPositionPayload(array $overrides = []): array
    {
        return array_merge([
            'organization_id' => $this->organization->id,
            'position_title' => 'Backend Engineer',
            'employment_type' => AppConstants::EMPLOYMENT_TYPES[0],
            'department' => 'Engineering',
            'work_location' => 'Remote',
            'vacancies' => 2,
            'description' => 'Build and maintain backend services.',
        ], $overrides);
    }

    private function createNonOrgAdminProfile(): UserProfile
    {
        $nonAdminUser = User::factory()->create();
        $nonAdminProfile = UserProfile::factory()->create([
            'user_id' => $nonAdminUser->id,
        ]);

        $this->actingAs($nonAdminUser)->withSession([
            'user_profile_id' => $nonAdminProfile->id,
            'roles' => ['Organization Admin'],
        ]);

        return $nonAdminProfile;
    }

    public function test_can_get_all_positions(): void
    {
        Position::factory()->count(3)->create([
            'organization_id' => $this->organization->id,
            'user_profile_id' => $this->adminProfile->id,
        ]);

        $response = $this->getJson(route('positions.getAllPositions'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'Success.',
            ])
            ->assertJsonStructure([
                'data' => [
                    '*' => self::POSITION_RETURN_COLUMNS,
                ],
            ])
            ->assertJsonPath('data.0.organization_id', $this->organization->id)
            ->assertJsonPath('data.0.user_profile_id', $this->adminProfile->id);

        $this->assertEquals(3, count($response->json()['data']));
    }

    public function test_org_admin_can_get_positions_by_org_id(): void
    {
        Position::factory()->count(2)->create([
            'organization_id' => $this->organization->id,
            'user_profile_id' => $this->adminProfile->id,
        ]);

        $response = $this->getJson(route('positions.getPositionsByOrgId', ['id' => $this->organization->id]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'Success.',
            ])
            ->assertJsonStructure([
                'data' => [
                    '*' => self::POSITION_RETURN_COLUMNS
                ],
            ])
            ->assertJsonPath('data.0.organization_id', $this->organization->id)
            ->assertJsonPath('data.0.user_profile_id', $this->adminProfile->id);

        $this->assertEquals(2, count($response->json()['data']));
    }

    public function test_get_positions_by_org_id_fails_when_user_is_not_org_admin(): void
    {
        $this->createNonOrgAdminProfile();

        $response = $this->getJson(route('positions.getPositionsByOrgId', ['id' => $this->organization->id]));

        $response->assertStatus(Response::HTTP_FORBIDDEN)
            ->assertJsonFragment([
                'status' => Response::HTTP_FORBIDDEN,
                'message' => 'Unauthorized access to view positions for this organization.',
            ]);
    }

    public function test_org_admin_can_get_position_by_id(): void
    {
        $position = Position::factory()->create([
            'organization_id' => $this->organization->id,
            'user_profile_id' => $this->adminProfile->id,
        ]);

        $response = $this->getJson(route('positions.getPositionById', ['id' => $position->id]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'Success.',
            ])
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'shortlist_users' => [
                        '*' => [
                            'id',
                            'name',
                            'location',
                            'profile_image',
                            'headline',
                            'receivedInvitations',
                            'receivedInterviews',
                            'receivedJobOffers',
                        ]
                    ],
                ]
            ])
            ->assertJsonFragment([
                'id' => $position->id,
                'organization_id' => $this->organization->id,
                'user_profile_id' => $this->adminProfile->id,
            ])
            ->assertJsonPath('data.id', $position->id)
            ->assertJsonPath('data.organization_id', $this->organization->id)
            ->assertJsonPath('data.user_profile_id', $this->adminProfile->id)
            ->assertJsonPath('data.shortlist_users', []);
    }

    public function test_get_position_by_id_fails_when_user_is_not_org_admin_of_position(): void
    {
        $position = Position::factory()->create([
            'organization_id' => $this->organization->id,
            'user_profile_id' => $this->adminProfile->id,
        ]);

        $this->createNonOrgAdminProfile();

        $response = $this->getJson(route('positions.getPositionById', ['id' => $position->id]));

        $response->assertStatus(Response::HTTP_FORBIDDEN)
            ->assertJsonFragment([
                'status' => Response::HTTP_FORBIDDEN,
                'message' => 'Unauthorized access to view this position.',
            ]);
    }

    public function test_get_position_by_id_returns_not_found_when_id_invalid(): void
    {
        Position::factory()->create([
            'organization_id' => $this->organization->id,
            'user_profile_id' => $this->adminProfile->id,
        ]);

        $response = $this->getJson(route('positions.getPositionById', ['id' => 0]));

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonFragment([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Position not found with given ID.',
            ]);
    }

    public function test_org_admin_can_create_position(): void
    {
        $response = $this->postJson(route('positions.store'), $this->validPositionPayload());

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonFragment([
                'status' => Response::HTTP_CREATED,
                'message' => 'Position created successfully.',
            ])
            ->assertJsonFragment([
                'organization_id' => $this->organization->id,
                'position_title' => 'Backend Engineer',
                'department' => 'Engineering',
            ])
            ->assertJsonStructure([
                'data' => self::POSITION_RETURN_COLUMNS,
            ])
            ->assertJsonPath('data.organization_id', $this->organization->id)
            ->assertJsonPath('data.user_profile_id', $this->adminProfile->id)
            ->assertJsonPath('data.position_title', 'Backend Engineer')
            ->assertJsonPath('data.department', 'Engineering');

        $this->assertDatabaseHas('positions', [
            'organization_id' => $this->organization->id,
            'user_profile_id' => $this->adminProfile->id,
            'position_title' => 'Backend Engineer',
            'department' => 'Engineering',
            'work_location' => 'Remote',
            'vacancies' => 2,
        ]);
    }

    public function test_org_admin_can_create_position_without_nullable_fields(): void
    {
        $response = $this->postJson(route('positions.store'), [
            'organization_id' => $this->organization->id,
            'position_title' => 'Backend Engineer',
            'employment_type' => AppConstants::EMPLOYMENT_TYPES[0],
            'department' => 'Engineering',
            'work_location' => 'Remote',
            'vacancies' => 2,
        ]);

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonFragment([
                'status' => Response::HTTP_CREATED,
                'message' => 'Position created successfully.',
            ])
            ->assertJsonFragment([
                'organization_id' => $this->organization->id,
                'position_title' => 'Backend Engineer',
                'department' => 'Engineering',
            ])
            ->assertJsonStructure([
                'data' => self::POSITION_RETURN_COLUMNS,
            ])
            ->assertJsonPath('data.organization_id', $this->organization->id)
            ->assertJsonPath('data.user_profile_id', $this->adminProfile->id)
            ->assertJsonPath('data.position_title', 'Backend Engineer')
            ->assertJsonPath('data.department', 'Engineering');

        $this->assertDatabaseHas('positions', [
            'organization_id' => $this->organization->id,
            'user_profile_id' => $this->adminProfile->id,
            'position_title' => 'Backend Engineer',
            'department' => 'Engineering',
            'work_location' => 'Remote',
            'vacancies' => 2,
        ]);
    }

    public function test_create_position_fails_without_required_fields(): void
    {
        $response = $this->postJson(route('positions.store'));

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'status' => Response::HTTP_BAD_REQUEST,
            ]);

        $this->assertDatabaseEmpty('positions');
    }

    public function test_create_position_fails_with_invalid_employment_type(): void
    {
        $response = $this->postJson(route('positions.store'), $this->validPositionPayload([
            'employment_type' => 'not_a_real_type',
        ]));

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'status' => Response::HTTP_BAD_REQUEST,
            ]);

        $this->assertDatabaseEmpty('positions');
    }

    public function test_create_position_fails_when_organization_does_not_exist(): void
    {
        $response = $this->postJson(route('positions.store'), $this->validPositionPayload([
            'organization_id' => 999999,
        ]));

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'status' => Response::HTTP_BAD_REQUEST,
            ]);

        $this->assertDatabaseEmpty('positions');
    }

    public function test_create_position_fails_when_user_is_not_organization_admin(): void
    {
        $this->createNonOrgAdminProfile();

        $response = $this->postJson(route('positions.store'), $this->validPositionPayload());

        $response->assertStatus(Response::HTTP_FORBIDDEN)
            ->assertJsonFragment([
                'status' => Response::HTTP_FORBIDDEN,
                'message' => 'Unauthorized access to create position.',
            ]);

        $this->assertDatabaseEmpty('positions');
    }

    public function test_org_admin_can_update_position(): void
    {
        $position = Position::factory()->create([
            'organization_id' => $this->organization->id,
            'user_profile_id' => $this->adminProfile->id,
            'position_title' => 'Junior Engineer',
            'vacancies' => 1,
            'description' => 'Description'
        ]);

        $response = $this->putJson(route('positions.update', ['id' => $position->id]), $this->validPositionPayload([
            'position_title' => 'Senior Engineer',
            'vacancies' => 3,
            'description' => 'Updated description.'
        ]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'Position updated successfully.',
            ])
            ->assertJsonFragment([
                'id' => $position->id,
                'position_title' => 'Senior Engineer',
                'vacancies' => 3,
                'description' => 'Updated description.'
            ])
            ->assertJsonStructure([
                'data' => self::POSITION_RETURN_COLUMNS,
            ])
            ->assertJsonPath('data.id', $position->id)
            ->assertJsonPath('data.organization_id', $this->organization->id)
            ->assertJsonPath('data.user_profile_id', $this->adminProfile->id)
            ->assertJsonPath('data.position_title', 'Senior Engineer')
            ->assertJsonPath('data.vacancies', 3)
            ->assertJsonPath('data.description', 'Updated description.');

        $this->assertDatabaseHas('positions', [
            'id' => $position->id,
            'position_title' => 'Senior Engineer',
            'vacancies' => 3,
            'description' => 'Updated description.'
        ]);
    }

    public function test_org_admin_can_update_position_without_nullable_fields(): void
    {
        $position = Position::factory()->create([
            'organization_id' => $this->organization->id,
            'user_profile_id' => $this->adminProfile->id,
            'position_title' => 'Junior Engineer',
            'vacancies' => 1,
        ]);

        $response = $this->putJson(route('positions.update', ['id' => $position->id]), [
            'organization_id' => $this->organization->id,
            'position_title' => 'Senior Engineer',
            'employment_type' => AppConstants::EMPLOYMENT_TYPES[0],
            'department' => 'Engineering',
            'work_location' => 'Remote',
            'vacancies' => 3,
        ]);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'Position updated successfully.',
            ])
            ->assertJsonFragment([
                'id' => $position->id,
                'position_title' => 'Senior Engineer',
                'vacancies' => 3,
            ])
            ->assertJsonStructure([
                'data' => self::POSITION_RETURN_COLUMNS,
            ])
            ->assertJsonPath('data.id', $position->id)
            ->assertJsonPath('data.organization_id', $this->organization->id)
            ->assertJsonPath('data.user_profile_id', $this->adminProfile->id)
            ->assertJsonPath('data.position_title', 'Senior Engineer')
            ->assertJsonPath('data.vacancies', 3);

        $this->assertDatabaseHas('positions', [
            'id' => $position->id,
            'position_title' => 'Senior Engineer',
            'vacancies' => 3,
        ]);
    }

    public function test_update_position_fails_without_required_fields(): void
    {
        $position = Position::factory()->create([
            'organization_id' => $this->organization->id,
            'user_profile_id' => $this->adminProfile->id,
        ]);

        $response = $this->putJson(route('positions.update', ['id' => $position->id]));

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'status' => Response::HTTP_BAD_REQUEST,
            ]);

        $this->assertDatabaseHas('positions', [
            'id' => $position->id,
            'organization_id' => $this->organization->id,
            'user_profile_id' => $this->adminProfile->id
        ]);
    }

    public function test_update_position_fails_when_user_is_not_organization_admin(): void
    {
        $position = Position::factory()->create([
            'organization_id' => $this->organization->id,
            'user_profile_id' => $this->adminProfile->id,
        ]);

        $this->createNonOrgAdminProfile();

        $response = $this->putJson(route('positions.update', ['id' => $position->id]), $this->validPositionPayload());

        $response->assertStatus(Response::HTTP_FORBIDDEN)
            ->assertJsonFragment([
                'status' => Response::HTTP_FORBIDDEN,
                'message' => 'Unauthorized access to update position.',
            ]);

        $this->assertDatabaseHas('positions', [
            'id' => $position->id,
            'organization_id' => $this->organization->id,
            'user_profile_id' => $this->adminProfile->id
        ]);
    }

    public function test_update_position_fails_with_invalid_position_id(): void
    {
        $position = Position::factory()->create([
            'organization_id' => $this->organization->id,
            'user_profile_id' => $this->adminProfile->id,
        ]);

        $response = $this->putJson(route('positions.update', ['id' => 0]), $this->validPositionPayload());

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonFragment([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Position not found.',
            ]);

        $this->assertDatabaseHas('positions', [
            'id' => $position->id,
            'organization_id' => $this->organization->id,
            'user_profile_id' => $this->adminProfile->id
        ]);
    }
}