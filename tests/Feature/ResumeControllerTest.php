<?php

namespace Tests\Feature;

use App\Constants\AppConstants;
use App\Models\Education;
use App\Models\Programme;
use App\Models\Resume;
use App\Models\ResumeContent;
use App\Models\ResumeTemplate;
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

class ResumeControllerTest extends TestCase
{
    use RefreshDatabase;

    private const RESUME_RETURN_COLUMNS = ['id', 'user_profile_id', 'created_at', 'updated_at'];
    private const RESUME_CONTENT_RELATIONS_RETURN_COLUMNS = [
        'education' => [
            '*' => [
                'programme' => [
                    'organization'
                ]
            ]
        ],
        'user_profile',
        'user_languages' => [
            '*' => [
                'language'
            ]
        ],
        'social_media_links' => [
            '*' => [
                'social_media'
            ]
        ],
        'user_skills' => [
            '*' => [
                'skill'
            ]
        ],
    ];

    private User $user;
    private UserProfile $userProfile;
    private Education $education;
    private Education $otherEducation;
    private ResumeTemplate $resumeTemplate;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(IndustryCategorySeeder::class);
        $this->seed(IndustrySectorSeeder::class);
        $this->seed(OrganizationTypeSeeder::class);
        $this->seed(RoleSeeder::class);
        $this->seed(QualificationSeeder::class);
        $this->seed(FieldOfStudySeeder::class);
        $this->seed(OrganizationSeeder::class);
        $this->seed(FacultySeeder::class);

        $this->user = User::factory()->create();
        $this->userProfile = UserProfile::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $programme = Programme::factory()->create();
        $this->education = Education::factory()->create([
            'user_profile_id' => $this->userProfile->id,
            'programme_id' => $programme->id,
        ]);
        $this->otherEducation = Education::factory()->create([
            'user_profile_id' => $this->userProfile->id,
            'programme_id' => $programme->id,
        ]);
        $this->resumeTemplate = ResumeTemplate::factory()->create();

        $this->actingAs($this->user)->withSession([
            'user_profile_id' => $this->userProfile->id,
        ]);
    }

    private function validResumePayload(array $overrides = []): array
    {
        return array_merge([
            'content_to_add' => [
                [
                    'source_type' => 'education',
                    'source_id' => $this->education->id,
                ],
            ],
        ], $overrides);
    }

    public function test_user_can_get_resumes_by_user_profile_id(): void
    {
        $resume = Resume::factory()->create(['user_profile_id' => $this->userProfile->id]);
        ResumeContent::create([
            'source_type' => 'education',
            'source_id' => $this->education->id,
            'resume_id' => $resume->id,
        ]);

        $response = $this->getJson(route('resumes.getResumesByUserProfileId', ['id' => $this->userProfile->id]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'Success.',
            ])
            ->assertJsonStructure([
                'data' => [
                    '*' => self::RESUME_RETURN_COLUMNS,
                ],
            ])
            ->assertJsonPath('data.0.id', $resume->id)
            ->assertJsonPath('data.0.user_profile_id', $this->userProfile->id);
    }

    public function test_user_can_get_resume_by_id_and_is_marked_as_owner(): void
    {
        $resume = Resume::factory()->create(['user_profile_id' => $this->userProfile->id]);

        $response = $this->getJson(route('resumes.getResumeById', ['id' => $resume->id]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'Success.',
            ])
            ->assertJsonStructure([
                'data' => [
                    ...self::RESUME_RETURN_COLUMNS,
                    'user_role',
                    ...self::RESUME_CONTENT_RELATIONS_RETURN_COLUMNS,
                ],
            ])
            ->assertJsonPath('data.id', $resume->id)
            ->assertJsonPath('data.user_role', 'owner');
    }

    public function test_user_can_get_resume_by_id_and_is_marked_as_viewer(): void
    {
        $otherProfile = UserProfile::factory()->create();
        $resume = Resume::factory()->create(['user_profile_id' => $otherProfile->id]);

        $response = $this->getJson(route('resumes.getResumeById', ['id' => $resume->id]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'Success.',
            ])
            ->assertJsonStructure([
                'data' => [
                    ...self::RESUME_RETURN_COLUMNS,
                    'user_role',
                    ...self::RESUME_CONTENT_RELATIONS_RETURN_COLUMNS,
                ],
            ])
            ->assertJsonPath('data.id', $resume->id)
            ->assertJsonPath('data.user_role', 'viewer');
    }

    public function test_get_resume_by_id_fails_with_non_existing_resume_id(): void
    {
        $response = $this->getJson(route('resumes.getResumeById', ['id' => 0]));

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonFragment([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Resume not found with given ID.',
            ])
            ->assertJsonPath('data', null);
    }

    public function test_user_can_create_resume(): void
    {
        $response = $this->postJson(route('resumes.store'), $this->validResumePayload([
            'resume_template_id' => $this->resumeTemplate->id
        ]));

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonFragment([
                'status' => Response::HTTP_CREATED,
                'message' => 'Resume created successfully.',
            ])
            ->assertJsonStructure([
                'data' => self::RESUME_RETURN_COLUMNS,
            ])
            ->assertJsonPath('data.user_profile_id', $this->userProfile->id)
            ->assertJsonPath('data.resume_template_id', $this->resumeTemplate->id);

        $this->assertDatabaseHas('resumes', [
            'id' => $response->json('data.id'),
            'user_profile_id' => $this->userProfile->id,
            'resume_template_id' => $this->resumeTemplate->id,
        ])
            ->assertDatabaseHas('resume_contents', [
                'resume_id' => $response->json('data.id'),
                'source_type' => 'education',
                'source_id' => $this->education->id,
            ]);
    }

    public function test_create_resume_fails_without_required_content(): void
    {
        $response = $this->postJson(route('resumes.store'));

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment(['status' => Response::HTTP_BAD_REQUEST])
            ->assertJsonPath('data', null);

        $this->assertDatabaseEmpty('resumes');
    }

    public function test_create_resume_fails_with_non_existing_source_id(): void
    {
        $response = $this->postJson(route('resumes.store'), $this->validResumePayload([
            'resume_template_id' => $this->resumeTemplate->id,
            'content_to_add' => [
                [
                    'source_type' => 'education',
                    'source_id' => 0
                ]
            ],
        ]));

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonFragment([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Source not found for education with given ID.',
            ]);

        $this->assertDatabaseEmpty('resumes');
    }

    public function test_create_resume_fails_with_non_existing_resume_template_id(): void
    {
        $response = $this->postJson(route('resumes.store'), $this->validResumePayload([
            'resume_template_id' => 0,
            'content_to_add' => [
                [
                    'source_type' => 'education',
                    'source_id' => $this->education->id
                ]
            ],
        ]));

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'status' => Response::HTTP_BAD_REQUEST,
            ]);

        $this->assertStringContainsString('resume template id', $response->json('message'));
        $this->assertDatabaseEmpty('resumes');
    }

    public function test_create_resume_fails_with_invalid_source_type(): void
    {
        $response = $this->postJson(route('resumes.store'), $this->validResumePayload([
            'resume_template_id' => $this->resumeTemplate->id,
            'content_to_add' => [
                [
                    'source_type' => 'Invalid type',
                    'source_id' => $this->education->id
                ]
            ],
        ]));

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'status' => Response::HTTP_BAD_REQUEST,
            ]);

        $this->assertStringContainsString('source_type', $response->json('message'));
        $this->assertDatabaseEmpty('resumes');
    }


    public function test_user_can_update_resume_content(): void
    {
        $resume = Resume::factory()->create(['user_profile_id' => $this->userProfile->id]);
        $content = ResumeContent::create([
            'source_type' => 'education',
            'source_id' => $this->education->id,
            'resume_id' => $resume->id,
        ]);

        $response = $this->putJson(route('resumes.update', ['id' => $resume->id]), [
            'content_to_add' => [['source_type' => 'education', 'source_id' => $this->otherEducation->id]],
            'content_ids_to_delete' => [$content->id],
        ]);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'Resume updated successfully.',
            ])
            ->assertJsonStructure([
                'data' => self::RESUME_RETURN_COLUMNS,
            ]);

        $this->assertDatabaseMissing('resume_contents', ['id' => $content->id])
            ->assertDatabaseHas('resume_contents', [
                'resume_id' => $resume->id,
                'source_type' => 'education',
                'source_id' => $this->otherEducation->id,
            ]);
    }

    public function test_user_can_update_resume_content_without_content_to_add_field(): void
    {
        $resume = Resume::factory()->create(['user_profile_id' => $this->userProfile->id]);
        $content = ResumeContent::create([
            'source_type' => 'education',
            'source_id' => $this->education->id,
            'resume_id' => $resume->id,
        ]);

        $response = $this->putJson(route('resumes.update', ['id' => $resume->id]), [
            'content_ids_to_delete' => [$content->id],
        ]);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'Resume updated successfully.',
            ])
            ->assertJsonStructure([
                'data' => self::RESUME_RETURN_COLUMNS,
            ])
            ->assertJsonPath('data.id', $resume->id);

        $this->assertDatabaseMissing('resume_contents', [
            'id' => $content->id,
        ])
            ->assertDatabaseCount('resume_contents', 0);
    }

    public function test_user_can_update_resume_content_without_content_ids_to_delete_field(): void
    {
        $resume = Resume::factory()->create(['user_profile_id' => $this->userProfile->id]);
        $content = ResumeContent::create([
            'source_type' => 'education',
            'source_id' => $this->education->id,
            'resume_id' => $resume->id,
        ]);

        $response = $this->putJson(route('resumes.update', ['id' => $resume->id]), [
            'content_to_add' => [['source_type' => 'education', 'source_id' => $this->otherEducation->id]],
        ]);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'Resume updated successfully.',
            ])
            ->assertJsonStructure([
                'data' => self::RESUME_RETURN_COLUMNS,
            ])
            ->assertJsonPath('data.id', $resume->id);

        $this->assertDatabaseHas('resume_contents', [
            'id' => $content->id,
            'resume_id' => $resume->id,
            'source_type' => 'education',
            'source_id' => $this->education->id,
        ])
            ->assertDatabaseHas('resume_contents', [
                'resume_id' => $resume->id,
                'source_type' => 'education',
                'source_id' => $this->otherEducation->id,
            ])
            ->assertDatabaseCount('resume_contents', 2);
    }

    public function test_update_resume_content_fails_without_any_fields(): void
    {
        $resume = Resume::factory()->create(['user_profile_id' => $this->userProfile->id]);
        $content = ResumeContent::create([
            'source_type' => 'education',
            'source_id' => $this->education->id,
            'resume_id' => $resume->id,
        ]);

        $response = $this->putJson(route('resumes.update', ['id' => $resume->id]));

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'status' => Response::HTTP_BAD_REQUEST,
            ])
            ->assertJsonPath('data', null);

        $this->assertDatabaseHas('resume_contents', [
            'id' => $content->id,
            'resume_id' => $resume->id,
            'source_type' => 'education',
            'source_id' => $this->education->id,
        ])
            ->assertDatabaseCount('resume_contents', 1);
    }

    public function test_update_resume_fails_when_content_already_exists(): void
    {
        $resume = Resume::factory()->create(['user_profile_id' => $this->userProfile->id]);
        ResumeContent::create([
            'source_type' => 'education',
            'source_id' => $this->education->id,
            'resume_id' => $resume->id,
        ]);

        $response = $this->putJson(route('resumes.update', ['id' => $resume->id]), [
            'content_to_add' => [['source_type' => 'education', 'source_id' => $this->education->id]],
            'content_ids_to_delete' => [],
        ]);

        $response->assertStatus(Response::HTTP_CONFLICT)
            ->assertJsonFragment([
                'status' => Response::HTTP_CONFLICT,
                'message' => 'Content already exists in resume.',
            ]);
    }

    public function test_update_resume_fails_with_invalid_source_type(): void
    {
        $resume = Resume::factory()->create(['user_profile_id' => $this->userProfile->id]);
        ResumeContent::create([
            'source_type' => 'education',
            'source_id' => $this->education->id,
            'resume_id' => $resume->id,
        ]);

        $response = $this->putJson(route('resumes.update', ['id' => $resume->id]), [
            'content_to_add' => [
                [
                    'source_type' => 'Invalid type',
                    'source_id' => $this->education->id
                ]
            ],
            'content_ids_to_delete' => [],
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'status' => Response::HTTP_BAD_REQUEST,
            ])
            ->assertJsonPath('data', null);

        $this->assertStringContainsString('source_type', $response->json('message'));
        $this->assertDatabaseCount('resume_contents', 1);
    }

    public function test_user_cannot_update_another_users_resume(): void
    {
        $otherProfile = UserProfile::factory()->create();
        $resume = Resume::factory()->create(['user_profile_id' => $otherProfile->id]);

        $response = $this->putJson(route('resumes.update', ['id' => 0]), [
            'content_to_add' => [['source_type' => 'education', 'source_id' => $this->education->id]],
            'content_ids_to_delete' => [],
        ]);

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonFragment([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Resume not found or access unauthorized.',
            ])
            ->assertJsonPath('data', null);
    }

    public function test_user_can_delete_resume_and_its_content(): void
    {
        $resume = Resume::factory()->create(['user_profile_id' => $this->userProfile->id]);
        ResumeContent::create([
            'source_type' => 'education',
            'source_id' => $this->education->id,
            'resume_id' => $resume->id,
        ]);

        $response = $this->deleteJson(route('resumes.delete', ['id' => $resume->id]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'Resume deleted successfully.',
            ])
            ->assertJsonStructure([
                'data' => self::RESUME_RETURN_COLUMNS,
            ])
            ->assertJsonPath('data.id', $resume->id);

        $this->assertDatabaseMissing('resumes', ['id' => $resume->id])
            ->assertDatabaseMissing('resume_contents', ['resume_id' => $resume->id]);
    }

    public function test_delete_resume_and_its_content_fails_with_non_existing_resume_id(): void
    {
        $resume = Resume::factory()->create(['user_profile_id' => $this->userProfile->id]);
        ResumeContent::create([
            'source_type' => 'education',
            'source_id' => $this->education->id,
            'resume_id' => $resume->id,
        ]);

        $response = $this->deleteJson(route('resumes.delete', ['id' => 0]));

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonFragment([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Resume not found or access unauthorized.',
            ])
            ->assertJsonPath('data', null);

        $this->assertDatabaseHas('resumes', ['id' => $resume->id])
            ->assertDatabaseHas('resume_contents', ['resume_id' => $resume->id]);
    }

    public function test_delete_resume_and_its_content_fails_when_deleting_other_user_resume(): void
    {
        $otherProfile = UserProfile::factory()->create();
        $resume = Resume::factory()->create(['user_profile_id' => $otherProfile->id]);
        ResumeContent::create([
            'source_type' => 'education',
            'source_id' => $this->education->id,
            'resume_id' => $resume->id,
        ]);

        $response = $this->deleteJson(route('resumes.delete', ['id' => 0]));

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonFragment([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Resume not found or access unauthorized.',
            ])
            ->assertJsonPath('data', null);

        $this->assertDatabaseHas('resumes', ['id' => $resume->id])
            ->assertDatabaseHas('resume_contents', ['resume_id' => $resume->id]);
    }
}
