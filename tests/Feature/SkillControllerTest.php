<?php

namespace Tests\Feature;

use App\Models\Skill;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\UserSkill;
use App\Services\SkillService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class SkillControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private UserProfile $userProfile;
    private UserProfile $otherProfile;
    private Skill $skill;
    private Skill $otherSkill;

    protected function setUp(): void
    {
        parent::setUp();

        Cache::forget('skills');

        $this->user = User::factory()->create();
        $this->userProfile = UserProfile::factory()->create([
            'user_id' => $this->user->id,
        ]);
        $this->otherProfile = UserProfile::factory()->create();
        $this->skill = Skill::factory()->create([
            'skill_name' => 'Laravel',
        ]);
        $this->otherSkill = Skill::factory()->create([
            'skill_name' => 'JavaScript',
        ]);

        $this->actingAs($this->user)
            ->withSession([
                'user_profile_id' => $this->userProfile->id,
            ]);
    }

    private function validUserSkillPayload(array $overrides = []): array
    {
        return array_merge([
            'source_type' => 'user_profile',
            'source_id' => $this->userProfile->id,
            'skill_id' => $this->skill->id,
        ], $overrides);
    }

    public function test_user_can_get_all_skills(): void
    {
        $response = $this->getJson(route('skills.getAllSkills'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'Success.',
            ])
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'skill_name',
                        'skill_category',
                        'icon_class_name',
                    ],
                ],
            ]);

        $this->assertCount(2, $response->json('data'));
    }

    public function test_user_can_create_user_skill(): void
    {
        $response = $this->postJson(route('skills.store'), $this->validUserSkillPayload());

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonFragment([
                'status' => Response::HTTP_CREATED,
                'message' => 'User skill added successfully.',
            ])
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'source_type',
                    'source_id',
                    'skill_id',
                ],
            ])
            ->assertJsonPath('data.source_type', 'user_profile')
            ->assertJsonPath('data.source_id', $this->userProfile->id)
            ->assertJsonPath('data.skill_id', $this->skill->id);

        $this->assertDatabaseCount('user_skills', 1)
            ->assertDatabaseHas('user_skills', $this->validUserSkillPayload());
    }

    public function test_create_user_skill_fails_without_required_fields(): void
    {
        $response = $this->postJson(route('skills.store'));

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'status' => Response::HTTP_BAD_REQUEST
            ])
            ->assertJsonPath('data', null);

        $this->assertDatabaseEmpty('user_skills');
    }

    public function test_create_user_skill_fails_with_invalid_source_type(): void
    {
        $response = $this->postJson(route('skills.store'), $this->validUserSkillPayload([
            'source_type' => 'invalid',
        ]));

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment(['status' => Response::HTTP_BAD_REQUEST])
            ->assertJsonPath('data', null);

        $this->assertStringContainsString('source type', $response->json('message'));
        $this->assertDatabaseEmpty('user_skills');
    }

    public function test_create_user_skill_fails_with_non_positive_source_id(): void
    {
        $response = $this->postJson(route('skills.store'), $this->validUserSkillPayload([
            'source_id' => 0,
        ]));

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment(['status' => Response::HTTP_BAD_REQUEST])
            ->assertJsonPath('data', null);

        $this->assertDatabaseEmpty('user_skills');
    }

    public function test_create_user_skill_fails_with_non_existing_source_id(): void
    {
        $response = $this->postJson(route('skills.store'), $this->validUserSkillPayload([
            'source_id' => 1000,
        ]));

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonFragment([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Source not found with given ID.'
            ])
            ->assertJsonPath('data', null);

        $this->assertDatabaseEmpty('user_skills');
    }

    public function test_create_user_skill_fails_with_non_existing_skill_id(): void
    {
        $response = $this->postJson(route('skills.store'), $this->validUserSkillPayload([
            'skill_id' => 0,
        ]));

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment(['status' => Response::HTTP_BAD_REQUEST])
            ->assertJsonPath('data', null);

        $this->assertStringContainsString('skill id', $response->json('message'));

        $this->assertDatabaseEmpty('user_skills');
    }

    public function test_create_user_skill_fails_when_skill_already_exists_for_source(): void
    {
        UserSkill::create($this->validUserSkillPayload());

        $response = $this->postJson(route('skills.store'), $this->validUserSkillPayload());

        $response->assertStatus(Response::HTTP_CONFLICT)
            ->assertJsonFragment([
                'status' => Response::HTTP_CONFLICT,
                'message' => 'User skill(s) already exist in profile.',
            ])
            ->assertJsonPath('data', null);

        $this->assertDatabaseCount('user_skills', 1)
            ->assertDatabaseHas('user_skills', $this->validUserSkillPayload());
    }

    public function test_user_can_update_user_skill(): void
    {
        $userSkill = UserSkill::create($this->validUserSkillPayload());

        $response = $this->putJson(route('skills.update', ['id' => $userSkill->id]), $this->validUserSkillPayload([
            'skill_id' => $this->otherSkill->id,
        ]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'User skill updated successfully.',
            ])
            ->assertJsonPath('data.id', $userSkill->id)
            ->assertJsonPath('data.skill_id', $this->otherSkill->id)
            ->assertJsonPath('data.skill.id', $this->otherSkill->id);

        $this->assertDatabaseHas('user_skills', $this->validUserSkillPayload([
            'skill_id' => $this->otherSkill->id,
        ]));
    }

    public function test_update_user_skill_fails_without_required_fields(): void
    {
        $userSkill = UserSkill::create($this->validUserSkillPayload());

        $response = $this->putJson(route('skills.update', ['id' => $userSkill->id]));

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment(['status' => Response::HTTP_BAD_REQUEST])
            ->assertJsonPath('data', null);

        $this->assertDatabaseHas('user_skills', $this->validUserSkillPayload());
    }

    public function test_update_user_skill_fails_with_invalid_source_id(): void
    {
        $userSkill = UserSkill::create($this->validUserSkillPayload());

        $response = $this->putJson(route('skills.update', ['id' => $userSkill->id]), [
            'source_type' => 'user_profile',
            'source_id' => 1000,
            'skill_id' => $this->otherSkill->id
        ]);

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonFragment([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'User skill not found with given ID.'
            ])
            ->assertJsonPath('data', null);

        $this->assertDatabaseHas('user_skills', $this->validUserSkillPayload());
    }

    public function test_update_user_skill_fails_with_non_existing_user_skill_id(): void
    {
        UserSkill::factory()->create($this->validUserSkillPayload());

        $response = $this->putJson(route('skills.update', ['id' => 0]), $this->validUserSkillPayload());

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonFragment([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'User skill not found with given ID.',
            ])
            ->assertJsonPath('data', null);

        $this->assertDatabaseCount('user_skills', 1)
            ->assertDatabaseHas('user_skills', $this->validUserSkillPayload());
    }

    public function test_update_user_skill_fails_when_source_does_not_match_existing_record(): void
    {
        $userSkill = UserSkill::create($this->validUserSkillPayload());

        $response = $this->putJson(route('skills.update', ['id' => $userSkill->id]), $this->validUserSkillPayload([
            'source_id' => $this->otherProfile->id,
        ]));

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonFragment([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'User skill not found with given ID.',
            ])
            ->assertJsonPath('data', null);

        $this->assertDatabaseHas('user_skills', $this->validUserSkillPayload());
    }

    public function test_update_user_skill_fails_when_user_does_not_own_source(): void
    {
        $userSkill = UserSkill::create($this->validUserSkillPayload([
            'source_id' => $this->otherProfile->id,
        ]));

        $response = $this->putJson(route('skills.update', ['id' => $userSkill->id]), $this->validUserSkillPayload([
            'source_id' => $this->otherProfile->id,
        ]));

        $response->assertStatus(Response::HTTP_FORBIDDEN)
            ->assertJsonFragment([
                'status' => Response::HTTP_FORBIDDEN,
                'message' => 'Unauthorized access to this skill.',
            ])
            ->assertJsonPath('data', null);

        $this->assertDatabaseHas('user_skills', [
            'id' => $userSkill->id,
            'source_id' => $this->otherProfile->id,
            'skill_id' => $this->skill->id,
        ]);
    }

    public function test_update_user_skill_fails_when_skill_already_exists_for_source(): void
    {
        $userSkill = UserSkill::create($this->validUserSkillPayload());
        $otherUserSkill = UserSkill::create($this->validUserSkillPayload([
            'skill_id' => $this->otherSkill->id,
        ]));

        $response = $this->putJson(route('skills.update', ['id' => $otherUserSkill->id]), $this->validUserSkillPayload([
            'skill_id' => $this->skill->id,
        ]));

        $response->assertStatus(Response::HTTP_CONFLICT)
            ->assertJsonFragment([
                'status' => Response::HTTP_CONFLICT,
                'message' => 'User skill(s) already exist in profile.',
            ])
            ->assertJsonPath('data', null);

        $this->assertDatabaseCount('user_skills', 2)
            ->assertDatabaseHas('user_skills', [
                'id' => $userSkill->id,
                'skill_id' => $this->skill->id,
            ])
            ->assertDatabaseHas('user_skills', [
                'id' => $otherUserSkill->id,
                'skill_id' => $this->otherSkill->id,
            ]);
    }

    public function test_user_can_delete_user_skill(): void
    {
        $userSkill = UserSkill::create($this->validUserSkillPayload());

        $response = $this->deleteJson(route('skills.delete', ['id' => $userSkill->id]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'User skill deleted successfully.',
            ])
            ->assertJsonPath('data', null);

        $this->assertDatabaseEmpty('user_skills');
    }

    public function test_delete_user_skill_fails_with_non_existing_user_skill_id(): void
    {
        UserSkill::factory()->create($this->validUserSkillPayload());

        $response = $this->deleteJson(route('skills.delete', ['id' => 0]));

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonFragment([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'User skill not found with given ID.',
            ])
            ->assertJsonPath('data', null);

        $this->assertDatabaseCount('user_skills', 1)
            ->assertDatabaseHas('user_skills', $this->validUserSkillPayload());
    }

    public function test_delete_user_skill_fails_when_user_does_not_own_source(): void
    {
        $userSkill = UserSkill::create($this->validUserSkillPayload([
            'source_id' => $this->otherProfile->id,
        ]));

        $response = $this->deleteJson(route('skills.delete', ['id' => $userSkill->id]));

        $response->assertStatus(Response::HTTP_FORBIDDEN)
            ->assertJsonFragment([
                'status' => Response::HTTP_FORBIDDEN,
                'message' => 'Unauthorized access to this skill.',
            ])
            ->assertJsonPath('data', null);

        $this->assertDatabaseHas('user_skills', [
            'id' => $userSkill->id,
            'source_id' => $this->otherProfile->id,
        ]);
    }
}
