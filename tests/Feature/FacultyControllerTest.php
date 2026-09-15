<?php

namespace Tests\Feature;

use App\Models\Faculty;
use App\Models\Organization;
use App\Models\OrganizationUser;
use App\Models\Role;
use App\Models\User;
use App\Models\UserProfile;
use Database\Seeders\IndustryCategorySeeder;
use Database\Seeders\IndustrySectorSeeder;
use Database\Seeders\OrganizationTypeSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class FacultyControllerTest extends TestCase
{
    use RefreshDatabase;

    private const FACULTY_RETURN_COLUMNS = [
        'id',
        'organization_id',
        'faculty_name',
        'faculty_code',
        'created_at',
        'updated_at',
    ];

    private User $user;
    private UserProfile $userProfile;
    private Organization $organization;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleSeeder::class);
        $this->seed(IndustryCategorySeeder::class);
        $this->seed(IndustrySectorSeeder::class);
        $this->seed(OrganizationTypeSeeder::class);

        $this->user = User::factory()->create();
        $this->userProfile = UserProfile::factory()->create([
            'user_id' => $this->user->id,
        ]);
        $this->organization = Organization::factory()->create();

        $adminRoleId = Role::where('name', 'Organization Admin')->first()->id;

        OrganizationUser::factory()->create([
            'user_profile_id' => $this->userProfile->id,
            'organization_id' => $this->organization->id,
            'role_id' => $adminRoleId
        ]);

        $this->actingAs($this->user)->withSession([
            'user_profile_id' => $this->userProfile->id,
            'roles' => ['Organization Admin'],
        ]);
    }

    private function validFacultyPayload(array $overrides = []): array
    {
        return array_merge([
            'organization_id' => $this->organization->id,
            'faculty_name' => 'Faculty of Computing',
            'faculty_code' => 'FOC',
        ], $overrides);
    }

    public function test_can_get_faculties_by_organization_id(): void
    {
        Faculty::factory()->count(3)->create([
            'organization_id' => $this->organization->id,
        ]);
        $otherOrganization = Organization::factory()->create();
        Faculty::factory()->create(['organization_id' => $otherOrganization->id]);

        $response = $this->getJson(route('faculty.getFacultiesByOrgId', [
            'id' => $this->organization->id,
        ]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'Success.',
            ])
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        ...self::FACULTY_RETURN_COLUMNS,
                        'programmes'
                    ],
                ],
            ])
            ->assertJsonPath('data.0.organization_id', $this->organization->id);

        $this->assertCount(3, $response->json('data'));
    }

    public function test_get_faculties_by_organization_id_returns_empty_data_when_no_faculties_exist(): void
    {
        $response = $this->getJson(route('faculty.getFacultiesByOrgId', [
            'id' => 0,
        ]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'Success.',
            ])
            ->assertJsonPath('data', []);
    }

    public function test_can_get_faculty_by_id(): void
    {
        $faculty = Faculty::factory()->create([
            'organization_id' => $this->organization->id,
            'faculty_name' => 'Faculty of Engineering',
            'faculty_code' => 'FOE',
        ]);

        $response = $this->getJson(route('faculty.getFacultyById', ['id' => $faculty->id]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'Success.',
            ])
            ->assertJsonStructure([
                'data' => [
                    ...self::FACULTY_RETURN_COLUMNS,
                    'programmes'
                ],
            ])
            ->assertJsonPath('data.organization_id', $this->organization->id)
            ->assertJsonPath('data.faculty_name', 'Faculty of Engineering')
            ->assertJsonPath('data.faculty_code', 'FOE')
            ->assertJsonPath('data.programmes', []);
    }

    public function test_get_faculty_by_id_fails_with_non_existing_faculty_id(): void
    {
        Faculty::factory()->create([
            'organization_id' => $this->organization->id,
            'faculty_name' => 'Faculty of Engineering',
            'faculty_code' => 'FOE',
        ]);

        $response = $this->getJson(route('faculty.getFacultyById', ['id' => 0]));

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonFragment([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Faculty not found with given ID.',
            ])
            ->assertJsonPath('data', null);
    }

    public function test_org_admin_can_create_faculty(): void
    {
        $response = $this->postJson(route('faculty.store'), $this->validFacultyPayload());

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonFragment([
                'status' => Response::HTTP_CREATED,
                'message' => 'Faculty created successfully.',
            ])
            ->assertJsonStructure([
                'data' => self::FACULTY_RETURN_COLUMNS,
            ]);

        $this->assertDatabaseHas('faculties', [
            'organization_id' => $this->organization->id,
            'faculty_name' => 'Faculty of Computing',
            'faculty_code' => 'FOC',
        ]);
    }

    public function test_create_faculty_fails_without_required_fields(): void
    {
        $response = $this->postJson(route('faculty.store'));

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'status' => Response::HTTP_BAD_REQUEST
            ])
            ->assertJsonPath('data', null);

        $this->assertDatabaseEmpty('faculties');
    }

    public function test_create_faculty_fails_with_invalid_organization_id(): void
    {
        $response = $this->postJson(route('faculty.store'), $this->validFacultyPayload([
            'organization_id' => 0,
        ]));

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'status' => Response::HTTP_BAD_REQUEST,
            ])
            ->assertJsonPath('data', null);

        $this->assertStringContainsString('organization id', $response->json('message'));
        $this->assertDatabaseEmpty('faculties');
    }

    public function test_create_faculty_fails_with_invalid_field_types(): void
    {
        $response = $this->postJson(route('faculty.store'), $this->validFacultyPayload([
            'faculty_name' => 123,
            'faculty_code' => ['FOC'],
        ]));

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'status' => Response::HTTP_BAD_REQUEST
            ])
            ->assertJsonPath('data', null);

        $this->assertDatabaseEmpty('faculties');
    }

    public function test_create_faculty_fails_when_user_is_not_any_org_admin(): void
    {
        $this->actingAs($this->user)->withSession([
            'user_profile_id' => $this->userProfile->id,
            'roles' => ['Student'],
        ]);

        $response = $this->postJson(route('faculty.store'), $this->validFacultyPayload());

        $response->assertStatus(Response::HTTP_FORBIDDEN)
            ->assertJsonFragment([
                'status' => Response::HTTP_FORBIDDEN,
                'message' => 'Unauthorized access.',
            ])
            ->assertJsonPath('data', null);

        $this->assertDatabaseEmpty('faculties');
    }

    public function test_create_faculty_fails_when_user_is_not_current_org_admin(): void
    {
        $otherOrg = Organization::factory()->create();
        $response = $this->postJson(route('faculty.store'), $this->validFacultyPayload([
            'organization_id' => $otherOrg->id
        ]));

        $response->assertStatus(Response::HTTP_FORBIDDEN)
            ->assertJsonFragment([
                'status' => Response::HTTP_FORBIDDEN,
                'message' => 'Unauthorized access to create faculty in this organization.',
            ])
            ->assertJsonPath('data', null);

        $this->assertDatabaseEmpty('faculties');
    }

    public function test_org_admin_can_update_faculty(): void
    {
        $faculty = Faculty::factory()->create([
            'organization_id' => $this->organization->id,
            'faculty_name' => 'Old Faculty Name',
            'faculty_code' => 'OLD',
        ]);

        $response = $this->putJson(route('faculty.update', ['id' => $faculty->id]), [
            'faculty_name' => 'Updated Faculty Name',
            'faculty_code' => 'UFN',
        ]);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'Faculty updated successfully.',
            ])
            ->assertJsonStructure([
                'data' => self::FACULTY_RETURN_COLUMNS
            ]);

        $this->assertDatabaseHas('faculties', [
            'id' => $faculty->id,
            'faculty_name' => 'Updated Faculty Name',
            'faculty_code' => 'UFN',
        ]);
    }

    public function test_update_faculty_fails_without_required_fields(): void
    {
        $faculty = Faculty::factory()->create([
            'organization_id' => $this->organization->id,
        ]);

        $response = $this->putJson(route('faculty.update', ['id' => $faculty->id]));

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'status' => Response::HTTP_BAD_REQUEST
            ])
            ->assertJsonPath('data', null);

        $this->assertDatabaseHas('faculties', [
            'id' => $faculty->id,
            'organization_id' => $this->organization->id,
            'faculty_name' => $faculty->faculty_name,
            'faculty_code' => $faculty->faculty_code,
        ]);
    }

    public function test_update_faculty_fails_with_invalid_field_types(): void
    {
        $faculty = Faculty::factory()->create([
            'organization_id' => $this->organization->id,
        ]);

        $response = $this->putJson(route('faculty.update', ['id' => $faculty->id]), [
            'faculty_name' => ['Faculty'],
            'faculty_code' => 123,
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'status' => Response::HTTP_BAD_REQUEST
            ])
            ->assertJsonPath('data', null);

        $this->assertDatabaseHas('faculties', [
            'id' => $faculty->id,
            'faculty_name' => $faculty->faculty_name,
            'faculty_code' => $faculty->faculty_code,
        ]);
    }

    public function test_update_faculty_fails_when_faculty_does_not_exist(): void
    {
        $faculty = Faculty::factory()->create([
            'organization_id' => $this->organization->id,
        ]);

        $response = $this->putJson(route('faculty.update', ['id' => 0]), [
            'faculty_name' => 'Updated Faculty',
            'faculty_code' => 'UF',
        ]);

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonFragment([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Faculty not found with given ID.',
            ])
            ->assertJsonPath('data', null);

        $this->assertDatabaseHas('faculties', [
            'id' => $faculty->id,
            'faculty_name' => $faculty->faculty_name,
            'faculty_code' => $faculty->faculty_code,
        ]);
    }

    public function test_update_faculty_fails_when_user_is_not_any_org_admin(): void
    {
        $faculty = Faculty::factory()->create([
            'organization_id' => $this->organization->id,
        ]);
        $this->actingAs($this->user)->withSession([
            'user_profile_id' => $this->userProfile->id,
            'roles' => ['Student'],
        ]);

        $response = $this->putJson(route('faculty.update', ['id' => $faculty->id]), [
            'faculty_name' => 'Updated Faculty',
            'faculty_code' => 'UF',
        ]);

        $response->assertStatus(Response::HTTP_FORBIDDEN)
            ->assertJsonFragment([
                'status' => Response::HTTP_FORBIDDEN,
                'message' => 'Unauthorized access.',
            ])
            ->assertJsonPath('data', null);

        $this->assertDatabaseHas('faculties', [
            'id' => $faculty->id,
            'faculty_name' => $faculty->faculty_name,
            'faculty_code' => $faculty->faculty_code,
        ]);
    }

    public function test_update_faculty_fails_when_user_is_not_current_org_admin(): void
    {
        $otherOrg = Organization::factory()->create();
        $faculty = Faculty::factory()->create([
            'organization_id' => $otherOrg->id,
        ]);

        $response = $this->putJson(route('faculty.update', ['id' => $faculty->id]), [
            'faculty_name' => 'Updated Faculty',
            'faculty_code' => 'UF',
        ]);

        $response->assertStatus(Response::HTTP_FORBIDDEN)
            ->assertJsonFragment([
                'status' => Response::HTTP_FORBIDDEN,
                'message' => 'Unauthorized access to update faculty in this organization.',
            ])
            ->assertJsonPath('data', null);

        $this->assertDatabaseHas('faculties', [
            'id' => $faculty->id,
            'faculty_name' => $faculty->faculty_name,
            'faculty_code' => $faculty->faculty_code,
        ]);
    }
}
