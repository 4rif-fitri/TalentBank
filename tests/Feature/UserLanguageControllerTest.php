<?php

namespace Tests\Feature;

use App\Constants\AppConstants;
use App\Models\Language;
use App\Models\User;
use App\Models\UserLanguage;
use App\Models\UserProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Override;
use Tests\TestCase;

class UserLanguageControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private UserProfile $userProfile;
    private Language $language;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->userProfile = UserProfile::factory()->create();

        $this->language = Language::create([
            'language_name' => 'English',
            'language_code' => 'EN',
        ]);

        Language::create([
            'language_name' => 'Malay',
            'language_code' => 'MS',
        ]);

        $this->actingAs($this->user)
            ->withSession([
                'user_profile_id' => $this->userProfile->id,
            ]);
    }

    private function validUserLanguagePayload(array $overrides = []): array
    {
        return array_merge([
            'language_id' => $this->language->id,
            'proficiency_level' => AppConstants::PROFICIENCY_LEVELS[0],
        ], $overrides);
    }

    public function test_user_can_get_all_languages(): void
    {
        $response = $this->getJson(route('languages.getAllLanguages'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'Success.'
            ])
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'language_name',
                        'language_code',
                    ]
                ]
            ]);

        $this->assertCount(2, $response->json('data'));
    }

    public function test_user_can_create_user_language(): void
    {
        $response = $this->postJson(route('languages.store'), $this->validUserLanguagePayload());

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonFragment([
                'status' => Response::HTTP_CREATED,
                'message' => 'Language added to user profile successfully.'
            ])
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'user_profile_id',
                    'language_id',
                    'proficiency_level',
                ]
            ])
            ->assertJsonPath('data.user_profile_id', $this->userProfile->id)
            ->assertJsonPath('data.language_id', $this->language->id)
            ->assertJsonPath('data.proficiency_level', AppConstants::PROFICIENCY_LEVELS[0]);

        $this->assertDatabaseHas('user_languages', [
            'user_profile_id' => $this->userProfile->id,
            'language_id' => $this->language->id,
            'proficiency_level' => AppConstants::PROFICIENCY_LEVELS[0],
        ]);
    }

    public function test_create_user_language_fails_without_required_fields(): void
    {
        $response = $this->postJson(route('languages.store'));

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'status' => Response::HTTP_BAD_REQUEST,
            ])
            ->assertJsonPath('data', null);

        $this->assertDatabaseEmpty('user_languages');
    }

    public function test_create_user_language_fails_with_invalid_proficiency_level(): void
    {
        $response = $this->postJson(route('languages.store'), $this->validUserLanguagePayload([
            'proficiency_level' => 'Invalid proficiency level'
        ]));

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'status' => Response::HTTP_BAD_REQUEST,
            ])
            ->assertJsonPath('data', null);

        $this->assertStringContainsString('proficiency level', $response->json('message'));

        $this->assertDatabaseEmpty('user_languages');
    }

    public function test_create_user_language_fails_with_non_existing_language_id(): void
    {
        $response = $this->postJson(route('languages.store'), $this->validUserLanguagePayload([
            'language_id' => 0
        ]));

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'status' => Response::HTTP_BAD_REQUEST,
            ])
            ->assertJsonPath('data', null);

        $this->assertStringContainsString('language id', $response->json('message'));

        $this->assertDatabaseEmpty('user_languages');
    }

    public function test_create_user_language_fails_when_creating_language_that_already_existed(): void
    {
        UserLanguage::create($this->validUserLanguagePayload($this->validUserLanguagePayload([
            'user_profile_id' => $this->userProfile->id
        ])));

        $response = $this->postJson(route('languages.store'), $this->validUserLanguagePayload());

        $response->assertStatus(Response::HTTP_CONFLICT)
            ->assertJsonFragment([
                'status' => Response::HTTP_CONFLICT,
                'message' => 'Language already exists in profile.'
            ])
            ->assertJsonPath('data', null);

        $this->assertDatabaseCount('user_languages', 1)
            ->assertDatabaseHas('user_languages', $this->validUserLanguagePayload([
                'user_profile_id' => $this->userProfile->id
            ]));
    }

    public function test_user_can_update_user_language(): void
    {
        $otherLanguage = Language::factory()->create();
        $userLanguage = UserLanguage::create([
            'language_id' => $otherLanguage->id,
            'proficiency_level' => AppConstants::PROFICIENCY_LEVELS[1],
            'user_profile_id' => $this->userProfile->id
        ]);

        $response = $this->putJson(route('languages.update', ['id' => $userLanguage->id]), $this->validUserLanguagePayload());

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'Language updated successfully.'
            ])
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'user_profile_id',
                    'language_id',
                    'proficiency_level',
                    'language' => [
                        'id',
                        'language_name',
                        'language_code',
                    ]
                ]
            ])
            ->assertJsonPath('data.user_profile_id', $this->userProfile->id)
            ->assertJsonPath('data.language_id', $this->language->id)
            ->assertJsonPath('data.proficiency_level', AppConstants::PROFICIENCY_LEVELS[0]);

        $this->assertDatabaseHas('user_languages', [
            'user_profile_id' => $this->userProfile->id,
            'language_id' => $this->language->id,
            'proficiency_level' => AppConstants::PROFICIENCY_LEVELS[0],
        ]);
    }

    public function test_update_user_language_fails_without_required_fields(): void
    {
        $otherLanguage = Language::factory()->create();
        $userLanguage = UserLanguage::create([
            'language_id' => $otherLanguage->id,
            'proficiency_level' => AppConstants::PROFICIENCY_LEVELS[1],
            'user_profile_id' => $this->userProfile->id
        ]);

        $response = $this->putJson(route('languages.update', ['id' => $userLanguage->id]));

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'status' => Response::HTTP_BAD_REQUEST,
            ])
            ->assertJsonPath('data', null);

        $this->assertDatabaseHas('user_languages', [
            'language_id' => $otherLanguage->id,
            'proficiency_level' => AppConstants::PROFICIENCY_LEVELS[1],
            'user_profile_id' => $this->userProfile->id
        ]);
    }

    public function test_update_user_language_fails_with_non_existing_user_language_id(): void
    {
        $otherLanguage = Language::factory()->create();
        UserLanguage::create([
            'language_id' => $otherLanguage->id,
            'proficiency_level' => AppConstants::PROFICIENCY_LEVELS[1],
            'user_profile_id' => $this->userProfile->id
        ]);

        $response = $this->putJson(route('languages.update', ['id' => 0]), $this->validUserLanguagePayload());

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonFragment([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Language not found or access unauthorized.'
            ])
            ->assertJsonPath('data', null);

        $this->assertDatabaseHas('user_languages', [
            'language_id' => $otherLanguage->id,
            'proficiency_level' => AppConstants::PROFICIENCY_LEVELS[1],
            'user_profile_id' => $this->userProfile->id
        ]);
    }

    public function test_update_user_language_fails_when_trying_to_update_other_users_language(): void
    {
        $otherLanguage = Language::factory()->create();
        $otherProfile = UserProfile::factory()->create();
        $userLanguage = UserLanguage::create([
            'language_id' => $otherLanguage->id,
            'proficiency_level' => AppConstants::PROFICIENCY_LEVELS[1],
            'user_profile_id' => $otherProfile->id
        ]);

        $response = $this->putJson(route('languages.update', ['id' => $userLanguage->id]), $this->validUserLanguagePayload());

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonFragment([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Language not found or access unauthorized.'
            ])
            ->assertJsonPath('data', null);

        $this->assertDatabaseHas('user_languages', [
            'language_id' => $otherLanguage->id,
            'proficiency_level' => AppConstants::PROFICIENCY_LEVELS[1],
            'user_profile_id' => $otherProfile->id
        ]);
    }

    public function test_update_user_language_fails_when_updating_to_already_existed_language(): void
    {
        $otherLanguage = Language::factory()->create();
        UserLanguage::create($this->validUserLanguagePayload([
            'user_profile_id' => $this->userProfile->id
        ]));
        $otherUserLanguage = UserLanguage::create([
            'language_id' => $otherLanguage->id,
            'proficiency_level' => AppConstants::PROFICIENCY_LEVELS[3],
            'user_profile_id' => $this->userProfile->id
        ]);

        $response = $this->putJson(route('languages.update', ['id' => $otherUserLanguage->id]), $this->validUserLanguagePayload());

        $response->assertStatus(Response::HTTP_CONFLICT)
            ->assertJsonFragment([
                'status' => Response::HTTP_CONFLICT,
                'message' => 'Language already exists in profile.'
            ])
            ->assertJsonPath('data', null);

        $this->assertDatabaseHas('user_languages', [
            'language_id' => $otherLanguage->id,
            'proficiency_level' => AppConstants::PROFICIENCY_LEVELS[3],
            'user_profile_id' => $this->userProfile->id
        ]);
    }

    public function test_update_user_language_fails_with_invalid_proficiency_level(): void
    {
        $otherLanguage = Language::factory()->create();
        $userLanguage = UserLanguage::create([
            'language_id' => $otherLanguage->id,
            'proficiency_level' => AppConstants::PROFICIENCY_LEVELS[3],
            'user_profile_id' => $this->userProfile->id
        ]);

        $response = $this->putJson(route('languages.update', ['id' => $userLanguage->id]), $this->validUserLanguagePayload([
            'proficiency_level' => 'Invalid proficiency level'
        ]));

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'status' => Response::HTTP_BAD_REQUEST,
            ])
            ->assertJsonPath('data', null);

        $this->assertStringContainsString('proficiency level', $response->json('message'));

        $this->assertDatabaseHas('user_languages', [
            'language_id' => $otherLanguage->id,
            'proficiency_level' => AppConstants::PROFICIENCY_LEVELS[3],
            'user_profile_id' => $this->userProfile->id
        ]);
    }

    public function test_user_can_delete_user_language(): void
    {
        $userLanguage = UserLanguage::create($this->validUserLanguagePayload([
            'user_profile_id' => $this->userProfile->id
        ]));

        $response = $this->deleteJson(route('languages.delete', ['id' => $userLanguage->id]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'Language deleted successfully.'
            ])
            ->assertJsonPath('data', null);

        $this->assertDatabaseEmpty('user_languages');
    }

    public function test_delete_user_language_fails_with_non_existing_user_language_id(): void
    {
        $userLanguage = UserLanguage::create($this->validUserLanguagePayload([
            'user_profile_id' => $this->userProfile->id
        ]));

        $response = $this->deleteJson(route('languages.delete', ['id' => 0]));

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonFragment([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Language not found or access unauthorized.'
            ])
            ->assertJsonPath('data', null);

        $this->assertDatabaseCount('user_languages', 1)
            ->assertDatabaseHas('user_languages', [
                'id' => $userLanguage->id,
                'user_profile_id' => $this->userProfile->id,
                'language_id' => $this->language->id,
            ]);
    }

    public function test_delete_user_language_fails_when_deleting_other_users_language(): void
    {
        $otherProfile = UserProfile::factory()->create();
        $userLanguage = UserLanguage::create($this->validUserLanguagePayload([
            'user_profile_id' => $otherProfile->id
        ]));

        $response = $this->deleteJson(route('languages.delete', ['id' => $userLanguage->id]));

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonFragment([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Language not found or access unauthorized.'
            ])
            ->assertJsonPath('data', null);

        $this->assertDatabaseCount('user_languages', 1)
            ->assertDatabaseHas('user_languages', [
                'id' => $userLanguage->id,
                'user_profile_id' => $otherProfile->id,
                'language_id' => $this->language->id,
            ]);
    }
}
