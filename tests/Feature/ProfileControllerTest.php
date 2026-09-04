<?php

namespace Tests\Feature;

use App\Models\Education;
use App\Models\Language;
use App\Models\Like;
use App\Models\Organization;
use App\Models\OrganizationUser;
use App\Models\Programme;
use App\Models\Role;
use App\Models\Skill;
use App\Models\User;
use App\Models\UserLanguage;
use App\Models\UserProfile;
use App\Models\UserSkill;
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
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private UserProfile $userProfile;

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
        $this->userProfile = UserProfile::factory()->create([
            'user_id' => $this->user->id,
        ]);
        $this->actingAs($this->user)->withSession(['user_profile_id' => $this->userProfile->id]);
    }

    private function createStudentInOrg(?array $attributes = null): UserProfile
    {
        $organization = Organization::factory()->create();
        $studentRoleId = Role::where('name', 'Student')->first()->id;

        $userProfile = UserProfile::factory()->create($attributes);

        OrganizationUser::factory()->create([
            'user_profile_id' => $userProfile->id,
            'role_id' => $studentRoleId,
            'organization_id' => $organization->id,
        ]);

        return $userProfile;
    }

    public function test_user_can_get_profile_data_by_profile_id(): void
    {
        $response = $this->getJson(route('profile.getProfileDataByProfileId', ['id' => $this->userProfile->id]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'Success.',
            ])
            ->assertJsonFragment([
                'id' => $this->userProfile->id,
                'name' => $this->userProfile->name,
                'email' => $this->userProfile->email,
            ]);
    }

    public function test_user_can_get_all_student_user_profiles(): void
    {
        $organization = Organization::factory()->create();
        $studentRoleId = Role::where('name', 'Student')->first()?->id;
        $alumniRoleId = Role::where('name', 'Alumni')->first()?->id;

        $this->assertNotNull($studentRoleId, 'Student role not seeded.');
        $this->assertNotNull($alumniRoleId, 'Alumni role not seeded.');

        UserProfile::factory()
            ->count(2)
            ->create()
            ->each(function (UserProfile $profile) use ($organization, $studentRoleId) {
                OrganizationUser::factory()->create([
                    'user_profile_id' => $profile->id,
                    'role_id' => $studentRoleId,
                    'organization_id' => $organization->id,
                ]);
            });

        UserProfile::factory()
            ->count(2)
            ->create()
            ->each(function (UserProfile $profile) use ($alumniRoleId, $organization) {
                OrganizationUser::factory()->create([
                    'user_profile_id' => $profile->id,
                    'role_id' => $alumniRoleId,
                    'organization_id' => $organization->id,
                ]);
            });

        $response = $this->getJson(route('profile.getAllStudentUserProfiles'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'Success.'
            ])
            ->assertJsonFragment([
                'total' => 4,
            ]);

        $payload = $response->json();
        $this->assertEquals(6, count($payload['data']));
        $this->assertEquals(4, count($payload['data']['data']));
    }

    public function test_user_can_search_student_profiles_by_name(): void
    {
        $userA = $this->createStudentInOrg(['name' => 'Jane Doe']);
        $userB = $this->createStudentInOrg(['name' => 'John Smith']);

        $response = $this->getJson(route('profile.getAllStudentUserProfiles', ['name' => 'Jane']));

        $response->assertStatus(Response::HTTP_OK);

        $returnedIds = collect($response->json('data.data'))->pluck('id');

        $this->assertTrue($returnedIds->contains($userA->id));
        $this->assertFalse($returnedIds->contains($userB->id));
    }

    public function test_user_can_filter_student_profiles_by_organization(): void
    {
        $orgA = Organization::factory()->create();
        $orgB = Organization::factory()->create();
        $studentRoleId = Role::where('name', 'Student')->first()->id;

        $userInOrgA = UserProfile::factory()->create();
        $userInOrgB = UserProfile::factory()->create();

        OrganizationUser::factory()->create([
            'user_profile_id' => $userInOrgA->id,
            'role_id' => $studentRoleId,
            'organization_id' => $orgA->id,
        ]);
        OrganizationUser::factory()->create([
            'user_profile_id' => $userInOrgB->id,
            'role_id' => $studentRoleId,
            'organization_id' => $orgB->id,
        ]);

        $response = $this->getJson(route('profile.getAllStudentUserProfiles', ['organizations' => [$orgA->id]]));

        $response->assertStatus(Response::HTTP_OK);
        $returnedIds = collect($response->json('data.data'))->pluck('id');

        $this->assertTrue($returnedIds->contains($userInOrgA->id));
        $this->assertFalse($returnedIds->contains($userInOrgB->id));
    }

    public function test_user_can_filter_student_profiles_by_skill(): void
    {
        $skill = Skill::factory()->create();

        $userWithSkill = $this->createStudentInOrg();
        $userWithoutSkill = $this->createStudentInOrg();

        UserSkill::create([
            'skill_id' => $skill->id,
            'source_type' => 'user_profile',
            'source_id' => $userWithSkill->id
        ]);

        $response = $this->getJson(route('profile.getAllStudentUserProfiles', ['skills' => [$skill->id]]));

        $response->assertStatus(Response::HTTP_OK);
        $returnedIds = collect($response->json('data.data'))->pluck('id');

        $this->assertTrue($returnedIds->contains($userWithSkill->id));
        $this->assertFalse($returnedIds->contains($userWithoutSkill->id));
    }

    public function test_user_can_filter_student_profiles_by_language(): void
    {
        $language = Language::factory()->create();

        $userWithLanguage = $this->createStudentInOrg();
        $userWithoutLanguage = $this->createStudentInOrg();

        UserLanguage::factory()->create([
            'language_id' => $language->id,
            'user_profile_id' => $userWithLanguage->id
        ]);

        $response = $this->getJson(route('profile.getAllStudentUserProfiles', ['languages' => [$language->id]]));

        $response->assertStatus(Response::HTTP_OK);
        $returnedIds = collect($response->json('data.data'))->pluck('id');

        $this->assertTrue($returnedIds->contains($userWithLanguage->id));
        $this->assertFalse($returnedIds->contains($userWithoutLanguage->id));
    }

    public function test_user_can_filter_student_profiles_by_programme(): void
    {
        $programme = Programme::factory()->create();

        $userWithProgramme = $this->createStudentInOrg();
        $userWithoutProgramme = $this->createStudentInOrg();

        Education::factory()->create([
            'user_profile_id' => $userWithProgramme->id,
            'programme_id' => $programme->id
        ]);

        $response = $this->getJson(route('profile.getAllStudentUserProfiles', ['programmes' => [$programme->id]]));

        $response->assertStatus(Response::HTTP_OK);
        $returnedIds = collect($response->json('data.data'))->pluck('id');

        $this->assertTrue($returnedIds->contains($userWithProgramme->id));
        $this->assertFalse($returnedIds->contains($userWithoutProgramme->id));
    }

    public function test_excludes_current_user_from_get_all_student_profiles_results(): void
    {
        $organization = Organization::factory()->create();
        $studentRoleId = Role::where('name', 'Student')->first()->id;

        OrganizationUser::factory()->create([
            'user_profile_id' => $this->userProfile->id,
            'role_id' => $studentRoleId,
            'organization_id' => $organization->id,
        ]);

        $response = $this->getJson(route('profile.getAllStudentUserProfiles'));

        $response->assertStatus(Response::HTTP_OK);
        $returnedIds = collect($response->json('data.data'))->pluck('id');

        $this->assertFalse($returnedIds->contains($this->userProfile->id));
    }

    public function test_user_can_get_liked_user_profiles(): void
    {
        collect(range(1, 3))->each(function () {
            $profile = $this->createStudentInOrg();

            Like::create([
                'liked_user_profile_id' => $profile->id,
                'liker_user_profile_id' => $this->userProfile->id
            ]);
        });

        $response = $this->getJson(route('profile.getAllStudentUserProfiles', ['return_liked' => 'true']));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'Success.',
            ]);

        $payload = $response->json();
        $this->assertEquals(3, count($payload['data']['data']));
    }

    public function test_user_can_update_profile_info(): void
    {
        $response = $this->putJson(route('profile.update'), [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'phone_no' => '1234567890',
            'location' => 'Updated Location',
            'headline' => 'Updated Headline',
        ]);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'name' => 'Updated Name',
                'email' => 'updated@example.com',
                'phone_no' => '1234567890',
                'location' => 'Updated Location',
                'headline' => 'Updated Headline',
            ]);

        $this->assertDatabaseHas('user_profiles', [
            'id' => $this->userProfile->id,
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'phone_no' => '1234567890',
            'location' => 'Updated Location',
            'headline' => 'Updated Headline',
        ])
            ->assertDatabaseHas('users', [
                'id' => $this->user->id,
                'name' => 'Updated Name',
                'email' => 'updated@example.com',
            ]);
    }

    public function test_update_profile_info_fails_when_email_taken(): void
    {
        UserProfile::factory()->create([
            'name' => 'Updated Name',
            'email' => 'existing@example.com',
            'phone_no' => '1234567890'
        ]);

        $response = $this->putJson(route('profile.update'), [
            'name' => 'Updated Name',
            'email' => 'existing@example.com',
            'phone_no' => '0123456789'
        ]);

        $response->assertStatus(Response::HTTP_CONFLICT)
            ->assertJsonFragment([
                'status' => Response::HTTP_CONFLICT,
                'message' => 'Email or phone already exists.'
            ]);
    }

    public function test_update_profile_info_fails_when_phone_taken(): void
    {
        UserProfile::factory()->create([
            'name' => 'Updated Name',
            'email' => 'existing@example.com',
            'phone_no' => '1234567890'
        ]);

        $response = $this->putJson(route('profile.update'), [
            'name' => 'Updated Name',
            'email' => 'test@example.com',
            'phone_no' => '1234567890'
        ]);

        $response->assertStatus(Response::HTTP_CONFLICT)
            ->assertJsonFragment([
                'status' => Response::HTTP_CONFLICT,
                'message' => 'Email or phone already exists.'
            ]);
    }

    public function test_update_profile_info_fails_when_email_and_phone_taken(): void
    {
        UserProfile::factory()->create([
            'name' => 'Updated Name',
            'email' => 'existing@example.com',
            'phone_no' => '1234567890'
        ]);

        $response = $this->putJson(route('profile.update'), [
            'name' => 'Updated Name',
            'email' => 'existing@example.com',
            'phone_no' => '1234567890'
        ]);

        $response->assertStatus(Response::HTTP_CONFLICT)
            ->assertJsonFragment([
                'status' => Response::HTTP_CONFLICT,
                'message' => 'Email or phone already exists.'
            ]);
    }

    public function test_update_profile_info_fails_without_required_fields(): void
    {
        $response = $this->putJson(route('profile.update'), [
            'phone_no' => '1234567890',
            'location' => 'Updated Location',
            'headline' => 'Updated Headline',
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'status' => Response::HTTP_BAD_REQUEST,
            ]);
    }

    public function test_update_profile_info_succeeds_without_optional_fields(): void
    {
        $response = $this->putJson(route('profile.update'), [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ]);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'Profile updated successfully.',
            ])
            ->assertJsonFragment([
                'name' => 'Updated Name',
                'email' => 'updated@example.com',
                'phone_no' => null,
                'location' => null,
                'headline' => null,
            ]);

        $this->assertDatabaseHas('user_profiles', [
            'id' => $this->userProfile->id,
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ])
            ->assertDatabaseHas('users', [
                'id' => $this->user->id,
                'name' => 'Updated Name',
                'email' => 'updated@example.com',
            ]);
    }

    public function test_user_can_update_about_field(): void
    {
        $response = $this->putJson(route('profile.updateAboutField'), [
            'about' => 'Updated About'
        ]);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'About saved successfully.',
            ])
            ->assertJsonFragment([
                'about' => 'Updated About'
            ]);

        $this->assertDatabaseHas('user_profiles', [
            'about' => 'Updated About'
        ]);
    }

    public function test_user_can_update_about_field_with_null_value(): void
    {
        $response = $this->putJson(route('profile.updateAboutField'), [
            'about' => null
        ]);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'About saved successfully.',
            ])
            ->assertJsonFragment([
                'about' => null
            ]);

        $this->assertDatabaseHas('user_profiles', [
            'about' => null
        ]);
    }

    public function test_user_can_upload_profile_image(): void
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->image('avatar.jpg', 300, 300);

        $response = $this->postJson(route('profile.uploadProfileImage'), [
            'profile_image' => $file
        ]);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'Profile image uploaded successfully.'
            ]);

        $this->userProfile->refresh();
        $this->assertStringContainsString('avatar.jpg', $this->userProfile->profile_image);
    }

    public function test_upload_profile_image_fails_without_required_field(): void
    {
        Storage::fake('public');
        $response = $this->postJson(route('profile.uploadProfileImage'), [
            'profile_image' => null
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => 'The profile image field is required.'
            ]);
    }

    public function test_upload_profile_image_fails_with_invalid_file_format(): void
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->create('document.pdf', 100);
        $response = $this->postJson(route('profile.uploadProfileImage'), [
            'profile_image' => $file
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => 'The profile image field must be an image.'
            ]);

        Storage::disk('public')->assertDirectoryEmpty(config('services.uploads_file_path.profile_image'));
    }

    public function test_user_can_upload_cover_image(): void
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->image('cover.jpg', 500, 300);

        $response = $this->postJson(route('profile.uploadCoverImage'), [
            'cover_image' => $file
        ]);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'Cover image uploaded successfully.'
            ]);

        $this->userProfile->refresh();
        $this->assertStringContainsString('cover.jpg', $this->userProfile->cover_image);
    }

    public function test_upload_cover_image_fails_without_required_field(): void
    {
        Storage::fake('public');
        $response = $this->postJson(route('profile.uploadCoverImage'), [
            'cover_image' => null
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => 'The cover image field is required.'
            ]);
    }

    public function test_upload_cover_image_fails_with_invalid_file_format(): void
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->create('document.pdf', 100);
        $response = $this->postJson(route('profile.uploadCoverImage'), [
            'cover_image' => $file
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => 'The cover image field must be an image.'
            ]);

        Storage::disk('public')->assertDirectoryEmpty(config('services.uploads_file_path.cover_image'));
    }

    public function test_user_can_like_profile(): void
    {
        $newUser = UserProfile::factory()->create();

        $response = $this->postJson(route('profile.toggleLike'), [
            'liked_user_profile_id' => $newUser->id
        ]);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'Profile liked successfully.',
                'data' => [
                    'is_liked' => true
                ]
            ]);

        $this->assertDatabaseHas('likes', [
            'liked_user_profile_id' => $newUser->id,
            'liker_user_profile_id' => $this->userProfile->id
        ]);
    }

    public function test_user_can_unlike_profile(): void
    {
        $newUser = UserProfile::factory()->create();
        Like::create([
            'liked_user_profile_id' => $newUser->id,
            'liker_user_profile_id' => $this->userProfile->id
        ]);

        $response = $this->postJson(route('profile.toggleLike'), [
            'liked_user_profile_id' => $newUser->id
        ]);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'Profile unliked successfully.',
                'data' => [
                    'is_liked' => false
                ]
            ]);

        $this->assertDatabaseEmpty('likes');
    }

    public function test_like_profile_fails_when_user_liked_own_profile(): void
    {
        $response = $this->postJson(route('profile.toggleLike'), [
            'liked_user_profile_id' => $this->userProfile->id
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => 'You cannot like your own profile.'
            ]);

        $this->assertDatabaseEmpty('likes');
    }

    public function test_toggle_like_fails_without_required_field(): void
    {
        $response = $this->postJson(route('profile.toggleLike'), [
            'liked_user_profile_id' => null
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => 'The liked user profile id field is required.'
            ]);

        $this->assertDatabaseEmpty('likes');
    }
}
