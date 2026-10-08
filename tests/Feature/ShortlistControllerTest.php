<?php

namespace Tests\Feature;

use App\Models\Organization;
use App\Models\OrganizationUser;
use App\Models\Position;
use App\Models\Role;
use App\Models\Shortlist;
use App\Models\User;
use App\Models\UserProfile;
use Database\Seeders\IndustryCategorySeeder;
use Database\Seeders\IndustrySectorSeeder;
use Database\Seeders\OrganizationTypeSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class ShortlistControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private UserProfile $adminProfile;
    private UserProfile $nonAdminProfile;
    private Organization $organization;
    private Position $position;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(OrganizationTypeSeeder::class);
        $this->seed(IndustryCategorySeeder::class);
        $this->seed(IndustrySectorSeeder::class);
        $this->seed(RoleSeeder::class);

        $this->user = User::factory()->create();
        $this->adminProfile = UserProfile::factory()->create([
            'user_id' => $this->user->id,
            'email' => 'sender@example.com',
        ]);
        $this->nonAdminProfile = UserProfile::factory()->create([
            'user_id' => $this->user->id,
            'email' => 'receiver@example.com',
        ]);
        $this->organization = Organization::factory()->create();
        $this->position = Position::factory()->create([
            'organization_id' => $this->organization->id,
        ]);

        OrganizationUser::factory()->create([
            'user_profile_id' => $this->adminProfile->id,
            'organization_id' => $this->organization->id,
            'role_id' => Role::where('name', 'Recruiter')->first()->id,
        ]);

        OrganizationUser::factory()->create([
            'user_profile_id' => $this->nonAdminProfile->id,
            'organization_id' => $this->organization->id,
            'role_id' => Role::where('name', 'Student')->first()->id,
        ]);

        $this->actingAs($this->user)
            ->withSession([
                'user_profile_id' => $this->adminProfile->id,
                'roles' => ['Recruiter']
            ]);
    }

    private function validShortlistPayload(array $overrides = []): array
    {
        return array_merge([
            'user_profile_id' => $this->nonAdminProfile->id,
            'position_id' => $this->position->id
        ], $overrides);
    }

    public function test_org_admin_can_get_shortlisted_position_ids(): void
    {
        $otherPosition = Position::factory()->create([
            'organization_id' => $this->organization->id
        ]);
        $otherProfile = UserProfile::factory()->create();

        Shortlist::create([
            'position_id' => $this->position->id,
            'user_profile_id' => $this->nonAdminProfile->id
        ]);

        Shortlist::create([
            'position_id' => $otherPosition->id,
            'user_profile_id' => $this->nonAdminProfile->id
        ]);

        Shortlist::create([
            'position_id' => $otherPosition->id,
            'user_profile_id' => $otherProfile->id
        ]);

        $response = $this->getJson(route('shortlists.getShortlistedPositionIds', ['profileId' => $this->nonAdminProfile->id, 'orgId' => $this->organization->id]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'Success.'
            ])
            ->assertJsonStructure(['data']);

        $this->assertIsArray($response->json('data'));
        $this->assertCount(2, $response->json('data'));
        $this->assertEqualsCanonicalizing([$this->position->id, $otherPosition->id], $response->json('data'));
    }

    public function test_get_shortlisted_position_ids_fails_when_user_is_not_any_org_admin(): void
    {
        $this->withSession([
            'user_profile_id' => $this->nonAdminProfile->id,
            'roles' => ['Student']
        ]);

        $otherPosition = Position::factory()->create([
            'organization_id' => $this->organization->id
        ]);
        $otherProfile = UserProfile::factory()->create();

        Shortlist::create([
            'position_id' => $this->position->id,
            'user_profile_id' => $this->nonAdminProfile->id
        ]);

        Shortlist::create([
            'position_id' => $otherPosition->id,
            'user_profile_id' => $this->nonAdminProfile->id
        ]);

        Shortlist::create([
            'position_id' => $otherPosition->id,
            'user_profile_id' => $otherProfile->id
        ]);

        $response = $this->getJson(route('shortlists.getShortlistedPositionIds', ['profileId' => $otherProfile->id, 'orgId' => $this->organization->id]));

        $response->assertStatus(Response::HTTP_FORBIDDEN)
            ->assertJsonFragment([
                'status' => Response::HTTP_FORBIDDEN,
                'message' => 'Unauthorized access.'
            ])
            ->assertJsonPath('data', null);
    }

    public function test_get_shortlisted_position_ids_fails_when_user_is_not_current_org_admin(): void
    {
        $otherPosition = Position::factory()->create([
            'organization_id' => $this->organization->id
        ]);
        $otherProfile = UserProfile::factory()->create();
        $otherOrganization = Organization::factory()->create();

        $this->withSession([
            'user_profile_id' => $otherProfile->id,
            'roles' => ['Organization Admin']
        ]);

        OrganizationUser::factory()->create([
            'role_id' => Role::where('name', 'Alumni')->first()->id,
            'organization_id' => $otherOrganization->id,
            'user_profile_id' => $otherProfile->id
        ]);

        Shortlist::create([
            'position_id' => $this->position->id,
            'user_profile_id' => $this->nonAdminProfile->id
        ]);

        Shortlist::create([
            'position_id' => $otherPosition->id,
            'user_profile_id' => $this->nonAdminProfile->id
        ]);

        $response = $this->getJson(route('shortlists.getShortlistedPositionIds', ['profileId' => $this->nonAdminProfile->id, 'orgId' => $this->organization->id]));

        $response->assertStatus(Response::HTTP_FORBIDDEN)
            ->assertJsonFragment([
                'status' => Response::HTTP_FORBIDDEN,
                'message' => 'Unauthorized access to view shortlisted positions.'
            ])
            ->assertJsonPath('data', null);
    }

    public function test_org_admin_can_create_shortlist(): void
    {
        $this->withSession([
            'user_profile_id' => $this->adminProfile->id,
            'roles' => ['Recruiter']
        ]);

        $response = $this->postJson(route('shortlists.store'), $this->validShortlistPayload());

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonFragment([
                'status' => Response::HTTP_CREATED,
                'message' => 'User profile shortlisted successfully.'
            ])
            ->assertJsonStructure([
                'data' => [
                    'position_id',
                    'user_profile_id',
                ]
            ])
            ->assertJsonPath('data.position_id', $this->position->id)
            ->assertJsonPath('data.user_profile_id', $this->nonAdminProfile->id);

        $this->assertDatabaseHas('shortlists', $this->validShortlistPayload());
    }

    public function test_create_shortlist_fails_when_user_shortlist_themselves(): void
    {
        $response = $this->postJson(route('shortlists.store'), $this->validShortlistPayload([
            'user_profile_id' => $this->adminProfile->id
        ]));

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => 'Users cannot shortlist themselves.'
            ])
            ->assertJsonPath('data', null);

        $this->assertDatabaseEmpty('shortlists');
    }

    public function test_create_shortlist_fails_when_user_is_not_admin_in_any_org(): void
    {
        $this->withSession([
            'user_profile_id' => $this->nonAdminProfile->id,
            'roles' => ['Student']
        ]);

        $otherProfile = UserProfile::factory()->create();

        $response = $this->postJson(route('shortlists.store'), $this->validShortlistPayload([
            'user_profile_id' => $otherProfile->id
        ]));

        $response->assertStatus(Response::HTTP_FORBIDDEN)
            ->assertJsonFragment([
                'status' => Response::HTTP_FORBIDDEN,
                'message' => 'Unauthorized access.'
            ])
            ->assertJsonPath('data', null);

        $this->assertDatabaseEmpty('shortlists');
    }

    public function test_create_shortlist_fails_when_user_is_not_admin_in_current_org(): void
    {
        $this->withSession([
            'user_profile_id' => $this->nonAdminProfile->id,
            'roles' => ['Organization Admin']
        ]);

        $otherProfile = UserProfile::factory()->create();

        $response = $this->postJson(route('shortlists.store'), $this->validShortlistPayload([
            'user_profile_id' => $otherProfile->id
        ]));

        $response->assertStatus(Response::HTTP_FORBIDDEN)
            ->assertJsonFragment([
                'status' => Response::HTTP_FORBIDDEN,
                'message' => 'Unauthorized access to shortlist user for this position.'
            ])
            ->assertJsonPath('data', null);

        $this->assertDatabaseEmpty('shortlists');
    }

    public function test_create_shortlist_fails_when_user_is_already_shortlisted(): void
    {
        Shortlist::factory()->create($this->validShortlistPayload());
        $response = $this->postJson(route('shortlists.store'), $this->validShortlistPayload());

        $response->assertStatus(Response::HTTP_CONFLICT)
            ->assertJsonFragment([
                'status' => Response::HTTP_CONFLICT,
                'message' => 'User is already shortlisted for this position.'
            ])
            ->assertJsonPath('data', null);

        $this->assertDatabaseCount('shortlists', 1);
    }

    public function test_create_shortlist_fails_with_non_existing_position_id(): void
    {
        $response = $this->postJson(route('shortlists.store'), $this->validShortlistPayload([
            'position_id' => 0
        ]));

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'status' => Response::HTTP_BAD_REQUEST,
            ])
            ->assertJsonPath('data', null);

        $this->assertStringContainsString('position id', $response->json('message'));
        $this->assertDatabaseEmpty('shortlists');
    }

    public function test_org_admin_can_delete_shortlist(): void
    {
        $otherProfile = UserProfile::factory()->create();
        Shortlist::factory()->create([
            'user_profile_id' => $otherProfile->id
        ]);

        $shortlist = Shortlist::factory()->create($this->validShortlistPayload());
        $response = $this->deleteJson(route('shortlists.delete', ['id' => $shortlist->id]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'User profile removed successfully from shortlist.',
            ])
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'position_id',
                    'user_profile_id',
                ]
            ])
            ->assertJsonPath('data.id', $shortlist->id)
            ->assertJsonPath('data.position_id', $shortlist->position_id)
            ->assertJsonPath('data.user_profile_id', $shortlist->user_profile_id);

        $this->assertDatabaseCount('shortlists', 1);
    }

    public function test_delete_shortlist_fails_with_non_existing_shortlist_id(): void
    {
        Shortlist::factory()->create($this->validShortlistPayload());

        $response = $this->deleteJson(route('shortlists.delete', ['id' => 0]));

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonFragment([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Shortlist entry not found.',
            ])
            ->assertJsonPath('data', null);

        $this->assertDatabaseCount('shortlists', 1);
        $this->assertDatabaseHas('shortlists', $this->validShortlistPayload());
    }

    public function test_delete_shortlist_fails_when_user_not_admin_in_any_org(): void
    {
        $this->withSession([
            'user_profile_id' => $this->nonAdminProfile->id,
            'roles' => ['Alumni']
        ]);

        $shortlist = Shortlist::factory()->create($this->validShortlistPayload());

        $response = $this->deleteJson(route('shortlists.delete', ['id' => $shortlist]));

        $response->assertStatus(Response::HTTP_FORBIDDEN)
            ->assertJsonFragment([
                'status' => Response::HTTP_FORBIDDEN,
                'message' => 'Unauthorized access.',
            ])
            ->assertJsonPath('data', null);

        $this->assertDatabaseCount('shortlists', 1);
        $this->assertDatabaseHas('shortlists', $this->validShortlistPayload());
    }

    public function test_delete_shortlist_fails_when_user_not_admin_in_current_org(): void
    {
        $otherProfile = UserProfile::factory()->create();

        $this->withSession([
            'user_profile_id' => $otherProfile->id,
            'roles' => ['Organization Admin']
        ]);

        $shortlist = Shortlist::factory()->create($this->validShortlistPayload());

        $response = $this->deleteJson(route('shortlists.delete', ['id' => $shortlist]));

        $response->assertStatus(Response::HTTP_FORBIDDEN)
            ->assertJsonFragment([
                'status' => Response::HTTP_FORBIDDEN,
                'message' => 'Unauthorized access to delete this shortlist entry.',
            ])
            ->assertJsonPath('data', null);

        $this->assertDatabaseCount('shortlists', 1);
        $this->assertDatabaseHas('shortlists', $this->validShortlistPayload());
    }
}
