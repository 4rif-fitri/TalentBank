<?php

namespace Tests\Feature;

use App\Models\IndustryCategory;
use App\Models\IndustrySector;
use App\Models\Organization;
use App\Models\OrganizationType;
use App\Models\OrganizationUser;
use App\Models\Role;
use App\Models\User;
use App\Models\UserProfile;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class OrganizationControllerTest extends TestCase
{
    use RefreshDatabase;

    private const ORGANIZATION_RETURN_COLUMNS = [
        'id',
        'company_name',
        'ssm_number',
        'industry_category_id',
        'address',
        'postcode',
        'city',
        'state',
        'website',
        'description',
        'company_email',
        'company_phone',
        'organization_logo',
        'industry_sector_id',
        'organization_type_id',
        'created_at',
        'updated_at',
    ];

    private User $user;
    private UserProfile $userProfile;
    private IndustryCategory $industryCategory;
    private IndustrySector $industrySector;
    private OrganizationType $organizationType;
    private int $adminRoleId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleSeeder::class);

        OrganizationType::factory()->count(3)->create();
        IndustryCategory::factory()->count(3)->create();
        IndustrySector::factory()->count(3)->create();

        $this->user = User::factory()->create();
        $this->userProfile = UserProfile::factory()->create([
            'user_id' => $this->user->id,
        ]);
        $this->industryCategory = IndustryCategory::first();
        $this->industrySector = IndustrySector::first();
        $this->organizationType = OrganizationType::first();
        $this->adminRoleId = Role::where('name', 'Organization Admin')->first()->id;

        $this->actingAs($this->user)->withSession([
            'user_profile_id' => $this->userProfile->id,
            'roles' => ['Organization Admin'],
        ]);
    }

    private function validOrganizationPayload(array $overrides = []): array
    {
        return array_merge([
            'company_name' => 'Talent Bank Sdn. Bhd.',
            'ssm_number' => '202601234567',
            'industry_category_id' => $this->industryCategory->id,
            'address' => '1 Talent Bank Street',
            'postcode' => '50000',
            'city' => 'Kuala Lumpur',
            'state' => 'Kuala Lumpur',
            'website' => 'https://talentbank.example.com',
            'description' => 'A talent management organization.',
            'company_email' => 'company@talentbank.example.com',
            'company_phone' => '+60312345678',
            'organization_logo' => 'logo.png',
            'industry_sector_id' => $this->industrySector->id,
            'organization_type_id' => $this->organizationType->id,
        ], $overrides);
    }

    private function createOrganizationAdmin(Organization $organization): void
    {
        OrganizationUser::create([
            'organization_id' => $organization->id,
            'user_profile_id' => $this->userProfile->id,
            'role_id' => Role::where('name', 'Organization Admin')->first()->id,
            'status' => 1,
        ]);
    }

    public function test_can_get_all_organizations(): void
    {
        Organization::factory()->count(3)->create();

        $response = $this->getJson(route('organization.getAllOrganizations'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'Success.',
            ])
            ->assertJsonStructure([
                'data' => [
                    '*' => self::ORGANIZATION_RETURN_COLUMNS,
                ],
            ]);

        $this->assertCount(3, $response->json('data'));
    }

    public function test_can_get_organization_types(): void
    {
        $response = $this->getJson(route('organization.getAllOrganizationTypes'));

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

        $this->assertCount(3, $response->json('data'));
    }

    public function test_can_get_industry_categories(): void
    {
        $response = $this->getJson(route('organization.getAllIndustryCategories'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment(['name' => $this->industryCategory->name])
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name'
                    ]
                ]
            ]);

        $this->assertCount(3, $response->json('data'));
    }

    public function test_can_get_industry_sectors(): void
    {
        $response = $this->getJson(route('organization.getAllIndustrySectors'));

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

        $this->assertCount(3, $response->json('data'));
    }

    public function test_can_create_organization(): void
    {
        $response = $this->postJson(route('organization.store'), $this->validOrganizationPayload());

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonFragment([
                'status' => Response::HTTP_CREATED,
                'message' => 'Organization created successfully.',
            ])
            ->assertJsonStructure([
                'data' => self::ORGANIZATION_RETURN_COLUMNS,
            ])
            ->assertJsonPath('data.company_name', 'Talent Bank Sdn. Bhd.')
            ->assertJsonPath('data.ssm_number', '202601234567');

        $organizationId = $response->json('data.id');
        $this->assertDatabaseHas('organizations', [
            'id' => $organizationId,
            'company_name' => 'Talent Bank Sdn. Bhd.',
            'ssm_number' => '202601234567',
        ]);

        $this->assertDatabaseHas('organization_users', [
            'organization_id' => $organizationId,
            'user_profile_id' => $this->userProfile->id,
            'role_id' => $this->adminRoleId,
            'status' => 1,
        ]);
    }

    public function test_can_create_organization_without_nullable_fields(): void
    {
        $response = $this->postJson(route('organization.store'), [
            'company_name' => 'Talent Bank Sdn. Bhd.',
            'ssm_number' => '202601234567',
            'industry_category_id' => $this->industryCategory->id,
            'address' => '1 Talent Bank Street',
            'postcode' => '50000',
            'city' => 'Kuala Lumpur',
            'state' => 'Kuala Lumpur',
            'company_email' => 'company@talentbank.example.com',
            'company_phone' => '+60312345678',
            'industry_sector_id' => $this->industrySector->id,
            'organization_type_id' => $this->organizationType->id,
        ]);

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonFragment([
                'status' => Response::HTTP_CREATED,
                'message' => 'Organization created successfully.',
            ])
            ->assertJsonStructure([
                'data' => self::ORGANIZATION_RETURN_COLUMNS,
            ])
            ->assertJsonPath('data.company_name', 'Talent Bank Sdn. Bhd.')
            ->assertJsonPath('data.ssm_number', '202601234567')
            ->assertJsonPath('data.website', null)
            ->assertJsonPath('data.description', null)
            ->assertJsonPath('data.organization_logo', null);

        $this->assertDatabaseHas('organizations', [
            'id' => $response->json('data.id'),
            'website' => null,
            'description' => null,
            'organization_logo' => null,
        ]);

        $this->assertDatabaseHas('organization_users', [
            'organization_id' => $response->json('data.id'),
            'user_profile_id' => $this->userProfile->id,
            'role_id' => $this->adminRoleId,
            'status' => 1,
        ]);
    }

    public function test_create_organization_fails_without_required_fields(): void
    {
        $response = $this->postJson(route('organization.store'));

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'status' => Response::HTTP_BAD_REQUEST
            ])
            ->assertJsonPath('data.id', null);

        $this->assertDatabaseEmpty('organizations');
        $this->assertDatabaseEmpty('organization_users');
    }

    public function test_create_organization_fails_with_invalid_non_existing_industry_category_id(): void
    {
        $response = $this->postJson(route('organization.store'), $this->validOrganizationPayload([
            'industry_category_id' => 0,
        ]));

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'status' => Response::HTTP_BAD_REQUEST
            ])
            ->assertJsonPath('data.id', null);

        $this->assertStringContainsString('industry category id', $response->json('message'));
        $this->assertDatabaseEmpty('organizations');
        $this->assertDatabaseEmpty('organization_users');
    }

    public function test_create_organization_fails_with_invalid_non_existing_industry_sector_id(): void
    {
        $response = $this->postJson(route('organization.store'), $this->validOrganizationPayload([
            'industry_sector_id' => 0,
        ]));

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'status' => Response::HTTP_BAD_REQUEST
            ])
            ->assertJsonPath('data.id', null);

        $this->assertStringContainsString('industry sector id', $response->json('message'));
        $this->assertDatabaseEmpty('organizations');
        $this->assertDatabaseEmpty('organization_users');
    }

    public function test_create_organization_fails_with_invalid_non_existing_organization_type_id(): void
    {
        $response = $this->postJson(route('organization.store'), $this->validOrganizationPayload([
            'organization_type_id' => 0,
        ]));

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'status' => Response::HTTP_BAD_REQUEST
            ])
            ->assertJsonPath('data.id', null);

        $this->assertStringContainsString('organization type id', $response->json('message'));
        $this->assertDatabaseEmpty('organizations');
        $this->assertDatabaseEmpty('organization_users');
    }

    public function test_create_organization_fails_with_invalid_email(): void
    {
        $response = $this->postJson(route('organization.store'), $this->validOrganizationPayload([
            'company_email' => 'Invalid email',
        ]));

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'status' => Response::HTTP_BAD_REQUEST
            ])
            ->assertJsonPath('data.id', null);

        $this->assertStringContainsString('company email', $response->json('message'));
        $this->assertDatabaseEmpty('organizations');
        $this->assertDatabaseEmpty('organization_users');
    }

    public function test_create_organization_fails_with_invalid_website(): void
    {
        $response = $this->postJson(route('organization.store'), $this->validOrganizationPayload([
            'website' => 'Invalid url',
        ]));

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'status' => Response::HTTP_BAD_REQUEST
            ]);

        $this->assertStringContainsString('website', $response->json('message'));
        $this->assertDatabaseEmpty('organizations');
        $this->assertDatabaseEmpty('organization_users');
    }

    public function test_create_organization_fails_when_ssm_number_is_taken(): void
    {
        $organization = Organization::factory()->create(['ssm_number' => $this->validOrganizationPayload()['ssm_number']]);

        $response = $this->postJson(route('organization.store'), $this->validOrganizationPayload());

        $response->assertStatus(Response::HTTP_CONFLICT)
            ->assertJsonFragment([
                'status' => Response::HTTP_CONFLICT,
                'message' => 'SSM number already taken.',
            ])
            ->assertJsonPath('data.id', null);

        $this->assertDatabaseHas('organizations', [
            'id' => $organization->id,
            'ssm_number' => $organization->ssm_number,
        ]);
        $this->assertDatabaseEmpty('organization_users');
    }

    public function test_create_organization_fails_when_organization_admin_role_is_missing(): void
    {
        Role::where('name', 'Organization Admin')->delete();

        $response = $this->postJson(route('organization.store'), $this->validOrganizationPayload());

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonFragment([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Role not found with given ID.',
            ])
            ->assertJsonPath('data.id', null);

        $this->assertDatabaseEmpty('organizations');
        $this->assertDatabaseEmpty('organization_users');
    }

    public function test_org_admin_can_update_organization(): void
    {
        $organization = Organization::factory()->create();
        $this->createOrganizationAdmin($organization);

        $response = $this->putJson(route('organization.update', ['orgId' => $organization->id]), $this->validOrganizationPayload([
            'company_name' => 'Updated Talent Bank',
            'ssm_number' => '202609876543',
        ]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'Organization updated successfully.',
            ])
            ->assertJsonStructure([
                'data' => self::ORGANIZATION_RETURN_COLUMNS
            ])
            ->assertJsonPath('data.id', $organization->id)
            ->assertJsonPath('data.company_name', 'Updated Talent Bank');

        $this->assertDatabaseHas('organizations', [
            'id' => $organization->id,
            'company_name' => 'Updated Talent Bank',
            'ssm_number' => '202609876543',
        ]);

        $this->assertDatabaseCount('organization_users', 1);
    }

    public function test_org_admin_can_update_organization_without_nullable_fields(): void
    {
        $organization = Organization::factory()->create([
            'website' => 'https://old.example.com',
            'description' => 'Old description',
            'organization_logo' => 'old.png',
        ]);
        $this->createOrganizationAdmin($organization);

        $response = $this->putJson(route('organization.update', ['orgId' => $organization->id]), [
            'company_name' => 'Talent Bank Sdn. Bhd.',
            'ssm_number' => '2026012343453',
            'industry_category_id' => $this->industryCategory->id,
            'address' => '1 Talent Bank Street',
            'postcode' => '50000',
            'city' => 'Kuala Lumpur',
            'state' => 'Kuala Lumpur',
            'company_email' => 'company@talentbank.example.com',
            'company_phone' => '+60312345678',
            'industry_sector_id' => $this->industrySector->id,
            'organization_type_id' => $this->organizationType->id,
        ]);

        $response->assertStatus(Response::HTTP_OK)->assertJsonFragment([
            'status' => Response::HTTP_OK,
            'message' => 'Organization updated successfully.',
        ])
            ->assertJsonStructure([
                'data' => self::ORGANIZATION_RETURN_COLUMNS
            ])
            ->assertJsonPath('data.id', $organization->id)
            ->assertJsonPath('data.company_name', 'Talent Bank Sdn. Bhd.')
            ->assertJsonPath('data.website', null)
            ->assertJsonPath('data.description', null)
            ->assertJsonPath('data.organization_logo', null);

        $this->assertDatabaseHas('organizations', [
            'id' => $organization->id,
            'website' => null,
            'description' => null,
            'organization_logo' => null,
        ]);
    }

    public function test_update_organization_fails_when_user_is_not_an_admin_of_the_organization(): void
    {
        $organization = Organization::factory()->create();

        $response = $this->putJson(route('organization.update', ['orgId' => $organization->id]), $this->validOrganizationPayload());

        $response->assertStatus(Response::HTTP_FORBIDDEN)
            ->assertJsonFragment([
                'status' => Response::HTTP_FORBIDDEN,
                'message' => 'Unauthorized access to update organization.',
            ])
            ->assertJsonPath('data.id', null);

        $this->assertDatabaseHas('organizations', [
            'id' => $organization->id,
            'company_name' => $organization->company_name,
            'ssm_number' => $organization->ssm_number
        ]);
    }

    public function test_update_organization_fails_when_organization_does_not_exist(): void
    {
        $organization = Organization::factory()->create();
        $response = $this->putJson(route('organization.update', ['orgId' => 0]), $this->validOrganizationPayload());

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonFragment([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Organization not found with given ID.'
            ]);

        $this->assertDatabaseHas('organizations', [
            'id' => $organization->id,
            'company_name' => $organization->company_name,
            'ssm_number' => $organization->ssm_number
        ]);
    }

    public function test_update_organization_fails_when_ssm_number_is_taken_by_another_organization(): void
    {
        $organization = Organization::factory()->create(['ssm_number' => '202601234567']);
        $this->createOrganizationAdmin($organization);
        Organization::factory()->create(['ssm_number' => '202609876543']);

        $response = $this->putJson(route('organization.update', ['orgId' => $organization->id]), $this->validOrganizationPayload([
            'ssm_number' => '202609876543',
        ]));

        $response->assertStatus(Response::HTTP_CONFLICT)
            ->assertJsonFragment([
                'status' => Response::HTTP_CONFLICT,
                'message' => 'SSM number already taken.',
            ])
            ->assertJsonPath('data.id', null);

        $this->assertDatabaseHas('organizations', [
            'id' => $organization->id,
            'company_name' => $organization->company_name,
            'ssm_number' => '202601234567'
        ]);
    }

    public function test_org_admin_can_upload_organization_logo(): void
    {
        Storage::fake('public');
        $organization = Organization::factory()->create(['organization_logo' => null]);
        $this->createOrganizationAdmin($organization);
        $file = UploadedFile::fake()->image('new-logo.jpg');

        $response = $this->postJson(route('organization.uploadOrganizationLogo', ['orgId' => $organization->id]), [
            'organization_logo' => $file,
        ]);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'Organization logo uploaded successfully.',
            ])
            ->assertJsonStructure([
                'data' => self::ORGANIZATION_RETURN_COLUMNS
            ])
            ->assertJsonPath('data.id', $organization->id);

        $filename = $response->json('data.organization_logo');
        $this->assertStringContainsString('new-logo.jpg', $filename);

        $this->assertNotSame('new-logo.jpg', $filename);
        $this->assertDatabaseHas('organizations', [
            'id' => $organization->id,
            'organization_logo' => $filename,
        ]);
        Storage::disk('public')->assertExists(config('services.uploads_file_path.organization_logos') . $filename);
    }

    public function test_upload_logo_deletes_the_previous_logo(): void
    {
        Storage::fake('public');
        $oldLogo = 'old-logo.jpg';
        $logoPath = config('services.uploads_file_path.organization_logos');
        Storage::disk('public')->put($logoPath . $oldLogo, 'old logo');
        $organization = Organization::factory()->create(['organization_logo' => $oldLogo]);
        $this->createOrganizationAdmin($organization);

        $response = $this->postJson(route('organization.uploadOrganizationLogo', ['orgId' => $organization->id]), [
            'organization_logo' => UploadedFile::fake()->image('new-logo.jpg'),
        ]);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'Organization logo uploaded successfully.',
            ])
            ->assertJsonStructure([
                'data' => self::ORGANIZATION_RETURN_COLUMNS
            ])
            ->assertJsonPath('data.id', $organization->id);

        $filename = $response->json('data.organization_logo');
        $this->assertStringContainsString('new-logo.jpg', $filename);

        $this->assertDatabaseHas('organizations', [
            'id' => $organization->id,
            'organization_logo' => $filename
        ]);

        Storage::disk('public')->assertExists(config('services.uploads_file_path.organization_logos') . $filename);
        Storage::disk('public')->assertCount(config('services.uploads_file_path.organization_logos'), 1);
    }

    public function test_upload_logo_fails_without_a_file(): void
    {
        Storage::fake('public');
        $organization = Organization::factory()->create([
            'organization_logo' => null
        ]);
        $this->createOrganizationAdmin($organization);

        $response = $this->postJson(route('organization.uploadOrganizationLogo', ['orgId' => $organization->id]));

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'status' => Response::HTTP_BAD_REQUEST
            ])
            ->assertJsonPath('data.id', null);

        $this->assertDatabaseHas('organizations', [
            'id' => $organization->id,
            'organization_logo' => null
        ]);

        $this->assertStringContainsString('organization logo', $response->json('message'));
        Storage::disk('public')->assertDirectoryEmpty(config('services.uploads_file_path.organization_logos'));
    }

    public function test_upload_logo_fails_with_invalid_file_format(): void
    {
        Storage::fake('public');
        $organization = Organization::factory()->create([
            'organization_logo' => null
        ]);
        $this->createOrganizationAdmin($organization);

        $response = $this->postJson(route('organization.uploadOrganizationLogo', ['orgId' => $organization->id]), [
            'organization_logo' => UploadedFile::fake()->create('document.pdf', 100, 'application/pdf'),
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'status' => Response::HTTP_BAD_REQUEST
            ])
            ->assertJsonPath('data.id', null);

        $this->assertDatabaseHas('organizations', [
            'id' => $organization->id,
            'organization_logo' => null
        ]);

        $this->assertStringContainsString('organization logo', $response->json('message'));
        Storage::disk('public')->assertDirectoryEmpty(config('services.uploads_file_path.organization_logos'));
    }

    public function test_upload_logo_fails_with_non_existing_org_id(): void
    {
        Storage::fake('public');
        $organization = Organization::factory()->create([
            'organization_logo' => null
        ]);
        $this->createOrganizationAdmin($organization);
        $response = $this->postJson(route('organization.uploadOrganizationLogo', ['orgId' => 0]), [
            'organization_logo' => UploadedFile::fake()->image('logo.jpg'),
        ]);

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonFragment([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Organization not found with given ID.'
            ])
            ->assertJsonPath('data.id', null);

        $this->assertDatabaseHas('organizations', [
            'id' => $organization->id,
            'organization_logo' => null
        ]);

        Storage::disk('public')->assertDirectoryEmpty(config('services.uploads_file_path.organization_logos'));
    }

    public function test_upload_logo_fails_when_user_is_not_an_admin_of_the_organization(): void
    {
        Storage::fake('public');
        $organization = Organization::factory()->create([
            'organization_logo' => null
        ]);

        $response = $this->postJson(route('organization.uploadOrganizationLogo', ['orgId' => $organization->id]), [
            'organization_logo' => UploadedFile::fake()->image('logo.jpg'),
        ]);

        $response->assertStatus(Response::HTTP_FORBIDDEN)
            ->assertJsonFragment([
                'status' => Response::HTTP_FORBIDDEN,
                'message' => 'Unauthorized access to upload organization logo for this organization.'
            ])
            ->assertJsonPath('data.id', null);

        $this->assertDatabaseHas('organizations', [
            'id' => $organization->id,
            'organization_logo' => null
        ]);

        Storage::disk('public')->assertDirectoryEmpty(config('services.uploads_file_path.organization_logos'));
    }
}
