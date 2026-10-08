<?php

namespace Tests\Feature;

use App\Constants\AppConstants;
use App\Models\Education;
use App\Models\Media;
use App\Models\Programme;
use App\Models\Skill;
use App\Models\User;
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

class EducationControllerTest extends TestCase
{
    use RefreshDatabase;

    private UserProfile $userProfile;
    private Programme $programme;
    private const ORGANIZATION_RETURN_COLUMNS = ['id', 'company_name', 'organization_logo',];

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

        $user = User::factory()->create();
        $this->userProfile = UserProfile::factory()->create();
        $this->programme = Programme::factory()->create();

        $this->actingAs($user)->withSession([
            'user_profile_id' => $this->userProfile->id,
            'roles' => ['Organization Admin', 'Recruiter']
        ]);
    }

    private function validEducationPayload(array $overrides = []): array
    {
        return array_merge([
            'programme_id' => $this->programme->id,
            'description' => 'Completed a full-time undergraduate programme with a focus on software engineering and database systems.',
            'cgpa' => 3.75,
            'start_date' => '2019-09-01',
            'end_date' => '2023-06-30',
            'enrollment_status' => AppConstants::ENROLLMENT_STATUS[1],
            'user_profile_id' => $this->userProfile->id
        ], $overrides);
    }

    public function test_user_can_get_education_by_user_profile_id(): void
    {
        Education::factory()->count(3)->create([
            'programme_id' => $this->programme->id
        ]);

        $response = $this->getJson(route('education.getEducationByUserProfileId', ['id' => $this->userProfile->id]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'Success.'
            ])
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'programme' => [
                            'organization',
                            'qualification',
                            'field_of_study',
                        ],
                        'media',
                        'skills'
                    ]
                ]
            ])
            ->assertJsonPath('data.0.programme.organization.id', $this->programme->organization->id)
            ->assertJsonPath('data.0.programme.qualification.id', $this->programme->qualification->id)
            ->assertJsonPath('data.0.programme.field_of_study.id', $this->programme->fieldOfStudy->id);

        $this->assertEquals(3, count($response->json('data')));
    }

    public function test_user_can_get_education_by_id(): void
    {
        $education = Education::factory()->create([
            'programme_id' => $this->programme->id
        ]);

        // unrelated education for testing
        Education::factory()->create([
            'programme_id' => $this->programme->id
        ]);

        $response = $this->getJson(route('education.getEducationById', ['id' => $education->id]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'Success.'
            ])
            ->assertJsonStructure([
                'data' => [
                    'programme' => [
                        'organization',
                        'qualification',
                        'field_of_study',
                    ],
                    'media',
                    'skills'
                ]
            ])
            ->assertJsonPath('data.id', $education->id)
            ->assertJsonPath('data.programme.id', $this->programme->id)
            ->assertJsonPath('data.programme.organization.id', $this->programme->organization->id)
            ->assertJsonPath('data.programme.qualification.id', $this->programme->qualification->id)
            ->assertJsonPath('data.programme.field_of_study.id', $this->programme->fieldOfStudy->id);
    }

    public function test_get_education_by_id_fails_with_invalid_education_id(): void
    {
        Education::factory()->create([
            'programme_id' => $this->programme->id
        ]);

        $response = $this->getJson(route('education.getEducationById', ['id' => 0]));

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonFragment([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Education record not found with given ID.'
            ]);
    }

    public function test_user_can_create_education(): void
    {
        Storage::fake('public');

        $laravelSkill = Skill::factory()->create([
            'skill_name' => 'Laravel'
        ]);
        $javaScriptSkill = Skill::factory()->create([
            'skill_name' => 'JavaScript'
        ]);

        $file1 = UploadedFile::fake()->image('education_1.jpg', 300, 300);
        $file2 = UploadedFile::fake()->create('education_2.pdf', 100, 'application/pdf');

        $response = $this->postJson(route('education.store'), $this->validEducationPayload([
            // media
            'media' => [
                [
                    'file' => $file1,
                    'title' => 'File 1',
                    'description' => 'Description for file 1.',
                ],
                [
                    'file' => $file2,
                    'title' => 'File 2',
                    'description' => 'Description for file 2.',
                ],
            ],

            // skills
            'new_skill_ids' => [$laravelSkill->id, $javaScriptSkill->id],
        ]));

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonFragment([
                'status' => Response::HTTP_CREATED,
                'message' => 'Education created successfully.'
            ])
            ->assertJsonStructure([
                'data' => [
                    'programme' => [
                        'organization' => self::ORGANIZATION_RETURN_COLUMNS,
                        'qualification',
                        'field_of_study',
                    ],
                    'media',
                    'skills',
                ]
            ])
            ->assertJsonFragment([
                'user_profile_id' => $this->userProfile->id,
                'programme_id' => $this->programme->id,
                'description' => 'Completed a full-time undergraduate programme with a focus on software engineering and database systems.',
                'cgpa' => 3.75,
                'start_date' => '2019-09-01',
                'end_date' => '2023-06-30',
                'enrollment_status' => AppConstants::ENROLLMENT_STATUS[1],
            ])
            ->assertJsonPath('data.skills.0.skill_name', 'Laravel')
            ->assertJsonPath('data.skills.1.skill_name', 'JavaScript')
            ->assertJsonPath('data.programme.organization.id', $this->programme->organization->id)
            ->assertJsonPath('data.programme.qualification.id', $this->programme->qualification->id)
            ->assertJsonPath('data.programme.field_of_study.id', $this->programme->fieldOfStudy->id);

        $this->assertDatabaseHas('education', [
            'user_profile_id' => $this->userProfile->id,
            'programme_id' => $this->programme->id,
            'description' => 'Completed a full-time undergraduate programme with a focus on software engineering and database systems.',
            'cgpa' => 3.75,
            'start_date' => '2019-09-01',
            'end_date' => '2023-06-30',
            'enrollment_status' => AppConstants::ENROLLMENT_STATUS[1],
        ])
            ->assertDatabaseHas('media', [
                'source_name' => 'education',
                'source_id' => $response->json('data')['id'],
                'media_type' => 'image'
            ])
            ->assertDatabaseHas('media', [
                'source_name' => 'education',
                'source_id' => $response->json('data')['id'],
                'media_type' => 'pdf'
            ]);

        $media = Media::where([
            'source_name' => 'education',
            'source_id' => $response->json('data')['id']
        ])->get();

        $this->assertCount(2, $media);

        foreach ($media as $m) {
            Storage::disk('public')->assertExists(config('services.uploads_file_path.education') . $m->file_name);
        }
    }

    public function test_user_can_create_education_without_nullable_fields(): void
    {
        $response = $this->postJson(route('education.store'), [
            'programme_id' => $this->programme->id,
            'enrollment_status' => AppConstants::ENROLLMENT_STATUS[1],
            'start_date' => '2019-09-01',
            'end_date' => '2023-06-30',
        ]);

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonFragment([
                'status' => Response::HTTP_CREATED,
                'message' => 'Education created successfully.'
            ])
            ->assertJsonStructure([
                'data' => [
                    'programme' => [
                        'organization' => [
                            'id',
                            'company_name',
                            'organization_logo',
                        ],
                        'qualification',
                        'field_of_study',
                    ],
                    'media',
                    'skills',
                ]
            ])
            ->assertJsonFragment([
                'programme_id' => $this->programme->id,
                'enrollment_status' => AppConstants::ENROLLMENT_STATUS[1],
                'start_date' => '2019-09-01',
                'end_date' => '2023-06-30',
            ])
            ->assertJsonPath('data.programme.organization.id', $this->programme->organization->id)
            ->assertJsonPath('data.programme.qualification.id', $this->programme->qualification->id)
            ->assertJsonPath('data.programme.field_of_study.id', $this->programme->fieldOfStudy->id);

        $this->assertDatabaseHas('education', [
            'programme_id' => $this->programme->id,
            'enrollment_status' => AppConstants::ENROLLMENT_STATUS[1],
            'start_date' => '2019-09-01',
            'end_date' => '2023-06-30',
        ]);
    }

    public function test_create_education_fails_without_required_fields(): void
    {
        $response = $this->postJson(route('education.store'));

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'status' => Response::HTTP_BAD_REQUEST,
            ]);

        $this->assertDatabaseEmpty('education');
    }

    public function test_create_education_fails_with_non_existing_skill_ids(): void
    {
        $response = $this->postJson(route('education.store'), $this->validEducationPayload([
            // skills
            'new_skill_ids' => [1, 2],
        ]));

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'status' => Response::HTTP_BAD_REQUEST,
            ]);

        $this->assertDatabaseEmpty('education')
            ->assertStringContainsString('new_skill_ids', $response->json('message'));
    }

    public function test_create_education_fails_with_invalid_enrollment_status(): void
    {
        $response = $this->postJson(route('education.store'), $this->validEducationPayload([
            'enrollment_status' => 'Invalid enrollment status',
        ]));

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'status' => Response::HTTP_BAD_REQUEST,
            ]);

        $this->assertDatabaseEmpty('education')
            ->assertStringContainsString('enrollment status', $response->json('message'));
    }

    public function test_create_education_fails_with_invalid_programme_id(): void
    {
        $response = $this->postJson(route('education.store'), $this->validEducationPayload([
            'programme_id' => 0,
        ]));

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'status' => Response::HTTP_BAD_REQUEST,
            ]);

        $this->assertDatabaseEmpty('education')
            ->assertStringContainsString('programme id', $response->json('message'));
    }

    public function test_create_education_fails_with_invalid_cgpa(): void
    {
        $response = $this->postJson(route('education.store'), $this->validEducationPayload([
            'cgpa' => 5.00,
        ]));

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'status' => Response::HTTP_BAD_REQUEST,
            ]);

        $this->assertDatabaseEmpty('education')
            ->assertStringContainsString('cgpa', $response->json('message'));

        $response = $this->postJson(route('education.store'), $this->validEducationPayload([
            'cgpa' => -1.00,
        ]));

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'status' => Response::HTTP_BAD_REQUEST,
            ]);

        $this->assertDatabaseEmpty('education')
            ->assertStringContainsString('cgpa', $response->json('message'));
    }

    public function test_create_education_fails_with_invalid_media_file_type(): void
    {
        Storage::fake('public');

        $invalidFile = UploadedFile::fake()->create('malicious.exe', 100);

        $response = $this->postJson(route('education.store'), $this->validEducationPayload([
            // media
            'media' => [
                [
                    'file' => $invalidFile,
                    'title' => 'Invalid File',
                    'description' => 'This file type should not be accepted.',
                ],
            ],
        ]));

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => 'Invalid file type. File must either be image/jpeg, image/png, image/jpg, application/pdf'
            ]);

        $this->assertStringContainsString('file', strtolower($response->json('message')));

        $this->assertDatabaseEmpty('education');
        $this->assertDatabaseEmpty('media');

        Storage::disk('public')->assertDirectoryEmpty(config('services.uploads_file_path.education'));
    }

    public function test_create_education_fails_with_start_date_later_than_end_date(): void
    {
        $response = $this->postJson(route('education.store'), $this->validEducationPayload([
            'start_date' => '2024-05-30',
            'end_date' => '2023-05-30',
        ]));

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'status' => Response::HTTP_BAD_REQUEST,
            ]);

        $this->assertDatabaseEmpty('education')
            ->assertStringContainsString('start date', $response->json('message'));
    }

    public function test_user_can_update_education(): void
    {
        Storage::fake('public');

        $newProgramme = Programme::factory()->create();

        $laravelSkill = Skill::factory()->create([
            'skill_name' => 'Laravel'
        ]);
        $javaScriptSkill = Skill::factory()->create([
            'skill_name' => 'JavaScript'
        ]);
        $pythonSkill = Skill::factory()->create([
            'skill_name' => 'Python'
        ]);
        $javaSkill = Skill::factory()->create([
            'skill_name' => 'Java'
        ]);

        $education = Education::factory()->create($this->validEducationPayload());

        $userSkillToDelete = UserSkill::factory()->create([
            'skill_id' => $laravelSkill->id,
            'source_type' => 'education',
            'source_id' => $education->id
        ]);

        $userSkillToUpdate = UserSkill::factory()->create([
            'skill_id' => $javaScriptSkill->id,
            'source_type' => 'education',
            'source_id' => $education->id
        ]);

        $file1 = UploadedFile::fake()->image('education_1.jpg', 300, 300);
        $file2 = UploadedFile::fake()->create('education_2.pdf', 100, 'application/pdf');
        $fileToUpload = UploadedFile::fake()->image('education_3.jpg', 300, 300);

        $initialFiles = [$file1, $file2];

        // add initial files for testing setup
        foreach ($initialFiles as $file) {
            Media::factory()->create([
                'source_name' => 'education',
                'source_id' => $education->id,
                'file_name' => $file->getClientOriginalName(),
                'uploaded_by_user_id' => $this->userProfile->id
            ]);

            $file->storeAs(config('services.uploads_file_path.education'), $file->getClientOriginalName(), 'public');
        }

        // get media to be deleted for testing purposes
        $mediaToDelete = Media::where('file_name', $file1->getClientOriginalName())->first();

        $response = $this->putJson(route('education.update', ['id' => $education->id]), [
            'programme_id' => $newProgramme->id,
            'description' => 'Completed a part-time postgraduate programme specializing in cloud infrastructure and distributed systems.',
            'cgpa' => 3.20,
            'start_date' => '2020-01-15',
            'end_date' => '2024-12-20',
            'enrollment_status' => AppConstants::ENROLLMENT_STATUS[3],

            // media
            'media' => [
                [
                    'file' => $fileToUpload,
                    'title' => 'File 3',
                    'description' => 'Description for file 3.',
                ],
            ],
            'deleted_media_ids' => [$mediaToDelete->id],

            // skills
            'new_skill_ids' => [$pythonSkill->id],
            'updated_user_skills' => [
                [
                    'id' => $userSkillToUpdate->id,
                    'skill_id' => $javaSkill->id
                ]
            ],
            'deleted_user_skill_ids' => [$userSkillToDelete->id],
        ]);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'Education updated successfully.'
            ])
            ->assertJsonStructure([
                'data' => [
                    'programme' => [
                        'organization' => self::ORGANIZATION_RETURN_COLUMNS,
                        'qualification',
                        'field_of_study',
                    ],
                    'media',
                    'skills',
                ]
            ])
            ->assertJsonFragment([
                'user_profile_id' => $this->userProfile->id,
                'programme_id' => $newProgramme->id,
                'description' => 'Completed a part-time postgraduate programme specializing in cloud infrastructure and distributed systems.',
                'cgpa' => 3.20,
                'start_date' => '2020-01-15',
                'end_date' => '2024-12-20',
                'enrollment_status' => AppConstants::ENROLLMENT_STATUS[3],
            ])
            ->assertJsonPath('data.programme.organization.id', $newProgramme->organization->id)
            ->assertJsonPath('data.programme.qualification.id', $newProgramme->qualification->id)
            ->assertJsonPath('data.programme.field_of_study.id', $newProgramme->fieldOfStudy->id);

        $payload = $response->json('data');

        $this->assertDatabaseHas('education', [
            'user_profile_id' => $this->userProfile->id,
            'programme_id' => $newProgramme->id,
            'description' => 'Completed a part-time postgraduate programme specializing in cloud infrastructure and distributed systems.',
            'cgpa' => 3.20,
            'start_date' => '2020-01-15',
            'end_date' => '2024-12-20',
            'enrollment_status' => AppConstants::ENROLLMENT_STATUS[3],
        ])
            ->assertDatabaseHas('media', [
                'source_name' => 'education',
                'source_id' => $payload['id'],
                'media_type' => 'image'
            ]);

        $this->assertEquals(2, count($payload['skills']));
        $this->assertEquals(2, count($payload['media']));

        $media = Media::where([
            'source_name' => 'education',
            'source_id' => $payload['id']
        ])->get();

        foreach ($media as $m) {
            Storage::disk('public')->assertExists(config('services.uploads_file_path.education') . $m->file_name);
        }
    }

    public function test_user_can_update_education_without_nullable_fields(): void
    {
        $newProgramme = Programme::factory()->create();

        $education = Education::factory()->create([
            'programme_id' => $this->programme->id,
            'description' => 'Completed a full-time undergraduate programme with a focus on software engineering and database systems.',
            'cgpa' => 3.75,
            'start_date' => '2019-09-01',
            'end_date' => '2023-06-30',
            'enrollment_status' => AppConstants::ENROLLMENT_STATUS[1],
        ]);

        $response = $this->putJson(route('education.update', ['id' => $education->id]), [
            'programme_id' => $newProgramme->id,
            'start_date' => '2020-01-15',
            'end_date' => '2024-12-20',
            'enrollment_status' => AppConstants::ENROLLMENT_STATUS[3],
        ]);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'Education updated successfully.'
            ])
            ->assertJsonFragment([
                'user_profile_id' => $this->userProfile->id,
                'programme_id' => $newProgramme->id,
                'description' => null,
                'cgpa' => null,
                'start_date' => '2020-01-15',
                'end_date' => '2024-12-20',
                'enrollment_status' => AppConstants::ENROLLMENT_STATUS[3],
            ])
            ->assertJsonStructure([
                'data' => [
                    'programme' => [
                        'organization' => self::ORGANIZATION_RETURN_COLUMNS,
                        'qualification',
                        'field_of_study',
                    ],
                    'media',
                    'skills',
                ]
            ])
            ->assertJsonPath('data.programme.organization.id', $newProgramme->organization->id)
            ->assertJsonPath('data.programme.qualification.id', $newProgramme->qualification->id)
            ->assertJsonPath('data.programme.field_of_study.id', $newProgramme->fieldOfStudy->id);

        $this->assertDatabaseHas('education', [
            'user_profile_id' => $this->userProfile->id,
            'programme_id' => $newProgramme->id,
            'description' => null,
            'cgpa' => null,
            'start_date' => '2020-01-15',
            'end_date' => '2024-12-20',
            'enrollment_status' => AppConstants::ENROLLMENT_STATUS[3],
        ]);
    }

    public function test_update_education_fails_without_required_fields(): void
    {
        $education = Education::factory()->create([
            'programme_id' => $this->programme->id,
            'description' => 'Completed a full-time undergraduate programme with a focus on software engineering and database systems.',
            'cgpa' => 3.75,
            'start_date' => '2019-09-01',
            'end_date' => '2023-06-30',
            'enrollment_status' => AppConstants::ENROLLMENT_STATUS[1],
        ]);

        $response = $this->putJson(route('education.update', ['id' => $education->id]));

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'status' => Response::HTTP_BAD_REQUEST,
            ]);

        $this->assertDatabaseHas('education', [
            'user_profile_id' => $this->userProfile->id,
            'programme_id' => $this->programme->id,
            'description' => 'Completed a full-time undergraduate programme with a focus on software engineering and database systems.',
            'cgpa' => 3.75,
            'start_date' => '2019-09-01',
            'end_date' => '2023-06-30',
            'enrollment_status' => AppConstants::ENROLLMENT_STATUS[1],
        ]);
    }

    public function test_update_education_fails_with_invalid_education_id(): void
    {
        Education::factory()->create($this->validEducationPayload());

        $newProgramme = Programme::factory()->create();

        $response = $this->putJson(route('education.update', ['id' => 0]), $this->validEducationPayload([
            'programme_id' => $newProgramme->id,
            'start_date' => '2020-01-15',
            'end_date' => '2024-12-20',
            'enrollment_status' => AppConstants::ENROLLMENT_STATUS[3],
        ]));

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonFragment([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Education data not found or access unauthorized.'
            ]);

        $this->assertDatabaseHas('education', [
            'user_profile_id' => $this->userProfile->id,
            'programme_id' => $this->programme->id,
            'description' => 'Completed a full-time undergraduate programme with a focus on software engineering and database systems.',
            'cgpa' => 3.75,
            'start_date' => '2019-09-01',
            'end_date' => '2023-06-30',
            'enrollment_status' => AppConstants::ENROLLMENT_STATUS[1],
        ]);
    }

    public function test_update_education_fails_with_invalid_enrollment_status(): void
    {
        $newProgramme = Programme::factory()->create();

        $education = Education::factory()->create($this->validEducationPayload());

        $response = $this->putJson(route('education.update', ['id' => $education->id]), [
            'programme_id' => $newProgramme->id,
            'start_date' => '2020-01-15',
            'end_date' => '2024-12-20',
            'enrollment_status' => 'Invalid enrollment status',
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'status' => Response::HTTP_BAD_REQUEST,
            ]);

        $this->assertDatabaseHas('education', $this->validEducationPayload([
            'user_profile_id' => $this->userProfile->id,
        ]))
            ->assertStringContainsString('enrollment status', $response->json('message'));
    }

    public function test_update_education_fails_with_invalid_cgpa(): void
    {
        $education = Education::factory()->create($this->validEducationPayload());

        $response = $this->putJson(route('education.update', ['id' => $education->id]), $this->validEducationPayload([
            'cgpa' => 5.00
        ]));

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'status' => Response::HTTP_BAD_REQUEST,
            ]);

        $this->assertDatabaseHas('education', $this->validEducationPayload([
            'user_profile_id' => $this->userProfile->id,
        ]))
            ->assertStringContainsString('cgpa', $response->json('message'));

        $response = $this->putJson(route('education.update', ['id' => $education->id]), $this->validEducationPayload([
            'cgpa' => -1.00
        ]));

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'status' => Response::HTTP_BAD_REQUEST,
            ]);

        $this->assertDatabaseHas('education', $this->validEducationPayload([
            'user_profile_id' => $this->userProfile->id,
        ]))
            ->assertStringContainsString('cgpa', $response->json('message'));
    }

    public function test_update_education_fails_with_start_date_later_than_end_date(): void
    {
        $education = Education::factory()->create($this->validEducationPayload());

        $response = $this->putJson(route('education.update', ['id' => $education->id]), $this->validEducationPayload([
            'start_date' => '2024-05-30',
            'end_date' => '2023-05-30'
        ]));

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'status' => Response::HTTP_BAD_REQUEST,
            ]);

        $this->assertDatabaseHas('education', $this->validEducationPayload([
            'user_profile_id' => $this->userProfile->id,
        ]))
            ->assertStringContainsString('start date', $response->json('message'));
    }

    public function test_update_education_fails_with_non_existing_deleted_media_ids(): void
    {
        $education = Education::factory()->create($this->validEducationPayload());

        $file1 = UploadedFile::fake()->image('education_1.jpg', 300, 300);
        $file2 = UploadedFile::fake()->create('education_2.pdf', 100, 'application/pdf');

        $initialFiles = [$file1, $file2];

        // add initial files for testing setup
        foreach ($initialFiles as $file) {
            Media::factory()->create([
                'source_name' => 'education',
                'source_id' => $education->id,
                'file_name' => $file->getClientOriginalName(),
                'uploaded_by_user_id' => $this->userProfile->id,
                'media_type' => $file->getClientOriginalExtension() === 'jpg' ? 'image' : 'pdf'
            ]);

            $file->storeAs(config('services.uploads_file_path.education'), $file->getClientOriginalName(), 'public');
        }

        $response = $this->putJson(route('education.update', ['id' => $education->id]), $this->validEducationPayload([
            'deleted_media_ids' => [-1, 0]
        ]));

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'status' => Response::HTTP_BAD_REQUEST,
            ]);

        $this->assertDatabaseHas('education', $this->validEducationPayload([
            'user_profile_id' => $this->userProfile->id,
        ]))
            ->assertDatabaseHas('media', [
                'source_name' => 'education',
                'source_id' => $education->id,
                'media_type' => 'image',
                'file_name' => $file1->getClientOriginalName(),
                'uploaded_by_user_id' => $this->userProfile->id
            ])
            ->assertDatabaseHas('media', [
                'source_name' => 'education',
                'source_id' => $education->id,
                'media_type' => 'pdf',
                'file_name' => $file2->getClientOriginalName(),
                'uploaded_by_user_id' => $this->userProfile->id
            ])
            ->assertStringContainsString('deleted_media_ids', $response->json('message'));

        Storage::disk('public')->assertExists(config('services.uploads_file_path.education') . $file1->getClientOriginalName());
        Storage::disk('public')->assertExists(config('services.uploads_file_path.education') . $file2->getClientOriginalName());
    }

    public function test_update_education_fails_with_non_existing_user_skill_ids_in_updated_user_skills_field(): void
    {
        $education = Education::factory()->create($this->validEducationPayload());

        $laravelSkill = Skill::factory()->create([
            'skill_name' => 'Laravel'
        ]);
        $javaScriptSkill = Skill::factory()->create([
            'skill_name' => 'JavaScript'
        ]);
        $pythonSkill = Skill::factory()->create([
            'skill_name' => 'Python'
        ]);
        $javaSkill = Skill::factory()->create([
            'skill_name' => 'Java'
        ]);

        $initialSkills = [$laravelSkill, $javaScriptSkill];

        foreach ($initialSkills as $skill) {
            UserSkill::factory()->create([
                'source_type' => 'education',
                'source_id' => $education->id,
                'skill_id' => $skill->id
            ]);
        }

        $response = $this->putJson(route('education.update', ['id' => $education->id]), $this->validEducationPayload([
            'updated_user_skills' => [
                [
                    'id' => -1,
                    'skill_id' => $pythonSkill->id
                ],
                [
                    'id' => 0,
                    'skill_id' => $javaSkill->id
                ],
            ]
        ]));

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonFragment([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'There are some user skill IDs that were not found.'
            ]);

        $this->assertDatabaseHas('education', $this->validEducationPayload([
            'user_profile_id' => $this->userProfile->id
        ]))
            ->assertDatabaseHas('user_skills', [
                'source_type' => 'education',
                'source_id' => $education->id,
                'skill_id' => $laravelSkill->id
            ])
            ->assertDatabaseHas('user_skills', [
                'source_type' => 'education',
                'source_id' => $education->id,
                'skill_id' => $javaScriptSkill->id
            ]);
    }

    public function test_update_education_fails_when_updating_other_users_skills(): void
    {
        // create another user profile to test unauthorized accessing other user skills
        $otherUserProfile = UserProfile::factory()->create();

        $ownEducation = Education::factory()->create($this->validEducationPayload());
        $otherEducation = Education::factory()->create($this->validEducationPayload([
            'user_profile_id' => $otherUserProfile->id
        ]));

        $laravelSkill = Skill::factory()->create([
            'skill_name' => 'Laravel'
        ]);
        $javaScriptSkill = Skill::factory()->create([
            'skill_name' => 'JavaScript'
        ]);
        $pythonSkill = Skill::factory()->create([
            'skill_name' => 'Python'
        ]);
        $javaSkill = Skill::factory()->create([
            'skill_name' => 'Java'
        ]);

        $laravelUserSkill = UserSkill::factory()->create([
            'source_type' => 'education',
            'source_id' => $otherEducation->id,
            'skill_id' => $laravelSkill->id
        ]);

        $javaScriptUserSkill = UserSkill::factory()->create([
            'source_type' => 'education',
            'source_id' => $otherEducation->id,
            'skill_id' => $javaScriptSkill->id
        ]);

        $response = $this->putJson(route('education.update', ['id' => $ownEducation->id]), $this->validEducationPayload([
            'updated_user_skills' => [
                [
                    'id' => $laravelUserSkill->id,
                    'skill_id' => $pythonSkill->id
                ],
                [
                    'id' => $javaScriptUserSkill->id,
                    'skill_id' => $javaSkill->id
                ],
            ]
        ]));

        $response->assertStatus(Response::HTTP_FORBIDDEN)
            ->assertJsonFragment([
                'status' => Response::HTTP_FORBIDDEN,
                'message' => 'Unauthorized access to this skill.'
            ]);

        $this->assertDatabaseHas('user_skills', [
            'source_type' => 'education',
            'source_id' => $otherEducation->id,
            'skill_id' => $laravelSkill->id
        ])
            ->assertDatabaseHas('user_skills', [
                'source_type' => 'education',
                'source_id' => $otherEducation->id,
                'skill_id' => $javaScriptSkill->id
            ]);
    }

    public function test_update_education_fails_with_updated_user_skills_duplicate_with_existing_user_skills(): void
    {
        $education = Education::factory()->create($this->validEducationPayload());

        $laravelSkill = Skill::factory()->create([
            'skill_name' => 'Laravel'
        ]);
        $javaScriptSkill = Skill::factory()->create([
            'skill_name' => 'JavaScript'
        ]);
        $pythonSkill = Skill::factory()->create([
            'skill_name' => 'Python'
        ]);
        $javaSkill = Skill::factory()->create([
            'skill_name' => 'Java'
        ]);

        $laravelUserSkill = UserSkill::factory()->create([
            'source_type' => 'education',
            'source_id' => $education->id,
            'skill_id' => $laravelSkill->id
        ]);

        $javaScriptUserSkill = UserSkill::factory()->create([
            'source_type' => 'education',
            'source_id' => $education->id,
            'skill_id' => $javaScriptSkill->id
        ]);

        // create other user skills for conflict testing
        $otherEducationSkills = [$pythonSkill, $javaSkill];

        foreach ($otherEducationSkills as $skill) {
            UserSkill::factory()->create([
                'source_type' => 'education',
                'source_id' => $education->id,
                'skill_id' => $skill->id
            ]);
        }

        $response = $this->putJson(route('education.update', ['id' => $education->id]), $this->validEducationPayload([
            'updated_user_skills' => [
                [
                    'id' => $laravelUserSkill->id,
                    'skill_id' => $pythonSkill->id
                ],
                [
                    'id' => $javaScriptUserSkill->id,
                    'skill_id' => $javaSkill->id
                ],
            ]
        ]));

        $response->assertStatus(Response::HTTP_CONFLICT)
            ->assertJsonFragment([
                'status' => Response::HTTP_CONFLICT,
                'message' => 'User skill(s) already exist in profile.'
            ]);

        $this->assertDatabaseHas('user_skills', [
            'source_type' => 'education',
            'source_id' => $education->id,
            'skill_id' => $laravelSkill->id
        ])
            ->assertDatabaseHas('user_skills', [
                'source_type' => 'education',
                'source_id' => $education->id,
                'skill_id' => $javaScriptSkill->id
            ]);
    }

    public function test_update_education_fails_with_non_existing_deleted_user_skill_ids(): void
    {
        $education = Education::factory()->create($this->validEducationPayload());

        $laravelSkill = Skill::factory()->create([
            'skill_name' => 'Laravel'
        ]);
        $javaScriptSkill = Skill::factory()->create([
            'skill_name' => 'JavaScript'
        ]);

        $initialSkills = [$laravelSkill, $javaScriptSkill];

        foreach ($initialSkills as $skill) {
            UserSkill::factory()->create([
                'source_type' => 'education',
                'source_id' => $education->id,
                'skill_id' => $skill->id
            ]);
        }

        $response = $this->putJson(route('education.update', ['id' => $education->id]), $this->validEducationPayload([
            'deleted_user_skill_ids' => [-1, 0]
        ]));

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'status' => Response::HTTP_BAD_REQUEST,
            ]);

        $this->assertDatabaseHas('user_skills', [
            'source_type' => 'education',
            'source_id' => $education->id,
            'skill_id' => $laravelSkill->id
        ])
            ->assertDatabaseHas('user_skills', [
                'source_type' => 'education',
                'source_id' => $education->id,
                'skill_id' => $javaScriptSkill->id
            ])
            ->assertStringContainsString('deleted_user_skill_ids', $response->json('message'));
    }

    public function test_user_can_delete_education(): void
    {
        Storage::fake('public');

        $education = Education::factory()->create($this->validEducationPayload());

        $laravelSkill = Skill::factory()->create([
            'skill_name' => 'Laravel'
        ]);
        $javaScriptSkill = Skill::factory()->create([
            'skill_name' => 'JavaScript'
        ]);

        $initialSkills = [$laravelSkill, $javaScriptSkill];

        foreach ($initialSkills as $skill) {
            UserSkill::factory()->create([
                'source_type' => 'education',
                'source_id' => $education->id,
                'skill_id' => $skill->id
            ]);
        }

        $file1 = UploadedFile::fake()->image('education_1.jpg', 300, 300);
        $file2 = UploadedFile::fake()->create('education_2.pdf', 100, 'application/pdf');

        $initialFiles = [$file1, $file2];

        // add initial files for testing setup
        foreach ($initialFiles as $file) {
            Media::factory()->create([
                'source_name' => 'education',
                'source_id' => $education->id,
                'file_name' => $file->getClientOriginalName(),
                'uploaded_by_user_id' => $this->userProfile->id
            ]);

            $file->storeAs(config('services.uploads_file_path.education'), $file->getClientOriginalName(), 'public');
        }

        $response = $this->deleteJson(route('education.delete', ['id' => $education->id]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'Education deleted successfully.'
            ]);

        $this->assertDatabaseEmpty('education')
            ->assertDatabaseEmpty('media');

        Storage::disk('public')->assertDirectoryEmpty(config('services.uploads_file_path.education'));
    }

    public function test_delete_education_fails_with_invalid_education_id(): void
    {
        Storage::fake('public');

        $education = Education::factory()->create($this->validEducationPayload());

        $laravelSkill = Skill::factory()->create([
            'skill_name' => 'Laravel'
        ]);
        $javaScriptSkill = Skill::factory()->create([
            'skill_name' => 'JavaScript'
        ]);

        $initialSkills = [$laravelSkill, $javaScriptSkill];

        foreach ($initialSkills as $skill) {
            UserSkill::factory()->create([
                'source_type' => 'education',
                'source_id' => $education->id,
                'skill_id' => $skill->id
            ]);
        }

        $file1 = UploadedFile::fake()->image('education_1.jpg', 300, 300);
        $file2 = UploadedFile::fake()->create('education_2.pdf', 100, 'application/pdf');

        $initialFiles = [$file1, $file2];

        // add initial files for testing setup
        foreach ($initialFiles as $file) {
            Media::factory()->create([
                'source_name' => 'education',
                'source_id' => $education->id,
                'file_name' => $file->getClientOriginalName(),
                'uploaded_by_user_id' => $this->userProfile->id,
                'media_type' => $file->getClientOriginalExtension() === 'jpg' ? 'image' : 'pdf'
            ]);

            $file->storeAs(config('services.uploads_file_path.education'), $file->getClientOriginalName(), 'public');
        }

        $response = $this->deleteJson(route('education.delete', ['id' => 0]));

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonFragment([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Education data not found or access unauthorized.'
            ]);

        $this->assertDatabaseHas('education', $this->validEducationPayload([
            'user_profile_id' => $this->userProfile->id
        ]))
            ->assertDatabaseHas('media', [
                'source_name' => 'education',
                'source_id' => $education->id,
                'media_type' => 'image',
                'file_name' => $file1->getClientOriginalName(),
                'uploaded_by_user_id' => $this->userProfile->id
            ])
            ->assertDatabaseHas('media', [
                'source_name' => 'education',
                'source_id' => $education->id,
                'media_type' => 'pdf',
                'file_name' => $file2->getClientOriginalName(),
                'uploaded_by_user_id' => $this->userProfile->id
            ]);
    }
}
