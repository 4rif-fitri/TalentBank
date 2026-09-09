<?php

namespace Tests\Feature;

use App\Models\Education;
use App\Models\Media;
use App\Models\Semester;
use App\Models\User;
use App\Models\UserProfile;
use Database\Seeders\FacultySeeder;
use Database\Seeders\FieldOfStudySeeder;
use Database\Seeders\IndustryCategorySeeder;
use Database\Seeders\IndustrySectorSeeder;
use Database\Seeders\OrganizationSeeder;
use Database\Seeders\OrganizationTypeSeeder;
use Database\Seeders\ProgrammeSeeder;
use Database\Seeders\QualificationSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Override;
use Tests\TestCase;

class SemesterControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private UserProfile $userProfile;
    private Education $education;

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
        $this->seed(ProgrammeSeeder::class);

        $this->user = User::factory()->create();
        $this->userProfile = UserProfile::factory()->create([
            'user_id' => $this->user->id
        ]);
        $this->education = Education::factory()->create();

        $this->actingAs($this->user)
            ->withSession([
                'user_profile_id' => $this->userProfile->id
            ]);
    }

    private function validSemesterPayload(array $overrides = []): array
    {
        return array_merge([
            'education_id' => $this->education->id,
            'gpa' => 4.00,
            'session' => '1 - 2024/2025'
        ], $overrides);
    }

    public function test_user_can_upload_results(): void
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->create('results.pdf', 100, 'application/pdf');

        $semester = Semester::factory()->create([
            'education_id' => $this->education->id
        ]);

        $response = $this->postJson(route('semester.uploadResults', ['id' => $semester->id]), [
            'result_file' => $file,
            'title' => 'Result',
            'description' => 'This semester\'s result.',
        ]);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'Result file uploaded successfully.'
            ])
            ->assertJsonStructure([
                'data' => [
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
                ]
            ])
            ->assertJsonPath('data.uploaded_by_user_id', $this->userProfile->id)
            ->assertJsonPath('data.media_type', 'pdf')
            ->assertJsonPath('data.title', 'Result')
            ->assertJsonPath('data.description', 'This semester\'s result.')
            ->assertJsonPath('data.source_name', 'semester')
            ->assertJsonPath('data.source_id', $semester->id);

        $this->assertDatabaseHas('media', [
            'uploaded_by_user_id' => $this->userProfile->id,
            'media_type' => 'pdf',
            'title' => 'Result',
            'description' => 'This semester\'s result.',
            'source_name' => 'semester',
            'source_id' => $semester->id,
        ]);

        $media = Media::where([
            'source_name' => 'semester',
            'source_id' => $semester->id
        ])->first();

        $this->assertStringContainsString('results.pdf', $media->file_name);

        Storage::disk('public')->assertExists(config('services.uploads_file_path.semester_results') . $media->file_name);
    }

    public function test_user_can_upload_results_without_nullable_fields(): void
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->create('results.pdf', 100, 'application/pdf');

        $semester = Semester::factory()->create([
            'education_id' => $this->education->id
        ]);

        $response = $this->postJson(route('semester.uploadResults', ['id' => $semester->id]), [
            'result_file' => $file,
        ]);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'Result file uploaded successfully.'
            ])
            ->assertJsonStructure([
                'data' => [
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
                ]
            ])
            ->assertJsonPath('data.uploaded_by_user_id', $this->userProfile->id)
            ->assertJsonPath('data.media_type', 'pdf')
            ->assertJsonPath('data.title', null)
            ->assertJsonPath('data.description', null)
            ->assertJsonPath('data.source_name', 'semester')
            ->assertJsonPath('data.source_id', $semester->id);

        $this->assertDatabaseHas('media', [
            'uploaded_by_user_id' => $this->userProfile->id,
            'media_type' => 'pdf',
            'title' => null,
            'description' => null,
            'source_name' => 'semester',
            'source_id' => $semester->id,
        ]);

        $media = Media::where([
            'source_name' => 'semester',
            'source_id' => $semester->id
        ])->first();

        $this->assertStringContainsString('results.pdf', $media->file_name);

        Storage::disk('public')->assertExists(config('services.uploads_file_path.semester_results') . $media->file_name);
    }

    public function test_upload_results_fails_without_required_fields(): void
    {
        Storage::fake('public');

        $semester = Semester::factory()->create([
            'education_id' => $this->education->id
        ]);

        $response = $this->postJson(route('semester.uploadResults', ['id' => $semester->id]));

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'status' => Response::HTTP_BAD_REQUEST,
            ])
            ->assertJsonPath('data', null);

        $this->assertDatabaseEmpty('media', null);
        $this->assertStringContainsString('result file', $response->json('message'));

        Storage::disk('public')->assertDirectoryEmpty(config('services.uploads_file_path.semester_results'));
    }

    public function test_upload_results_fails_with_invalid_file_format(): void
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->image('image.jpg', 100, 100);

        $semester = Semester::factory()->create([
            'education_id' => $this->education->id
        ]);

        $response = $this->postJson(route('semester.uploadResults', ['id' => $semester->id]), [
            'result_file' => $file
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'status' => Response::HTTP_BAD_REQUEST,
            ])
            ->assertJsonPath('data', null);

        $this->assertDatabaseEmpty('media', null);
        $this->assertStringContainsString('result file', $response->json('message'));

        Storage::disk('public')->assertDirectoryEmpty(config('services.uploads_file_path.semester_results'));
    }

    public function test_upload_results_fails_without_file_path_env(): void
    {
        Config::set('services.uploads_file_path.semester_results', null);
        Storage::fake('public');
        $file = UploadedFile::fake()->create('results.pdf', 100, 'application/pdf');

        $semester = Semester::factory()->create([
            'education_id' => $this->education->id
        ]);

        $response = $this->postJson(route('semester.uploadResults', ['id' => $semester->id]), [
            'result_file' => $file
        ]);

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonFragment([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'No file path found for semester results uploads.'
            ])
            ->assertJsonPath('data', null);

        $this->assertDatabaseEmpty('media', null);

        Storage::disk('public')->assertDirectoryEmpty(config('services.uploads_file_path.semester_results'));
    }

    public function test_user_can_create_semester(): void
    {
        $response = $this->postJson(route('semester.store'), $this->validSemesterPayload());

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonFragment([
                'status' => Response::HTTP_CREATED,
                'message' => 'Semester created successfully.'
            ])
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'education_id',
                    'gpa',
                    'session',
                    'created_at',
                    'updated_at',
                ]
            ])
            ->assertJsonPath('data.education_id', $this->education->id)
            ->assertJsonPath('data.gpa', '4.00')
            ->assertJsonPath('data.session', '1 - 2024/2025');

        $this->assertDatabaseHas('semesters', [
            'education_id' => $this->education->id,
            'gpa' => 4.00,
            'session' => '1 - 2024/2025',
        ]);
    }

    public function test_create_semester_fails_without_required_fields(): void
    {
        $response = $this->postJson(route('semester.store'));

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'status' => Response::HTTP_BAD_REQUEST,
            ])
            ->assertJsonPath('data', null);

        $this->assertDatabaseEmpty('semesters');
    }

    public function test_create_semester_fails_with_existed_semester_record(): void
    {
        Semester::create($this->validSemesterPayload());

        $response = $this->postJson(route('semester.store'), $this->validSemesterPayload());

        $response->assertStatus(Response::HTTP_CONFLICT)
            ->assertJsonFragment([
                'status' => Response::HTTP_CONFLICT,
                'message' => 'Semester already exists for this session.'
            ])
            ->assertJsonPath('data', null);

        $this->assertDatabaseCount('semesters', 1);
    }

    public function test_create_semester_fails_with_non_existing_education_id(): void
    {
        $response = $this->postJson(route('semester.store'), $this->validSemesterPayload([
            'education_id' => 0
        ]));

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'status' => Response::HTTP_BAD_REQUEST,
            ])
            ->assertJsonPath('data', null);

        $this->assertStringContainsString('education id', $response->json('message'));
        $this->assertDatabaseEmpty('semesters');
    }

    public function test_create_semester_fails_with_invalid_gpa(): void
    {
        $response = $this->postJson(route('semester.store'), $this->validSemesterPayload([
            'gpa' => -1.00
        ]));

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'status' => Response::HTTP_BAD_REQUEST,
            ])
            ->assertJsonPath('data', null);

        $this->assertStringContainsString('gpa', $response->json('message'));
        $this->assertDatabaseEmpty('semesters');

        $response = $this->postJson(route('semester.store'), $this->validSemesterPayload([
            'gpa' => 5.00
        ]));

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'status' => Response::HTTP_BAD_REQUEST,
            ])
            ->assertJsonPath('data', null);

        $this->assertStringContainsString('gpa', $response->json('message'));
        $this->assertDatabaseEmpty('semesters');
    }

    public function test_user_can_update_semester(): void
    {
        $semester = Semester::create($this->validSemesterPayload([
            'gpa' => 3.00,
            'session' => '2 - 2024/2025'
        ]));

        $response = $this->putJson(route('semester.update', ['id' => $semester->id]), $this->validSemesterPayload());

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'Semester updated successfully.'
            ])
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'education_id',
                    'gpa',
                    'session',
                    'created_at',
                    'updated_at',
                ]
            ])
            ->assertJsonPath('data.education_id', $this->education->id)
            ->assertJsonPath('data.gpa', '4.00')
            ->assertJsonPath('data.session', '1 - 2024/2025');

        $this->assertDatabaseHas('semesters', [
            'education_id' => $this->education->id,
            'gpa' => 4.00,
            'session' => '1 - 2024/2025',
        ]);
    }

    public function test_update_semester_fails_without_required_fields(): void
    {
        $semester = Semester::create($this->validSemesterPayload([
            'gpa' => 3.00,
            'session' => '2 - 2024/2025'
        ]));

        $response = $this->putJson(route('semester.update', ['id' => $semester->id]));

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'status' => Response::HTTP_BAD_REQUEST,
            ])
            ->assertJsonPath('data', null);

        $this->assertDatabaseHas('semesters', [
            'education_id' => $this->education->id,
            'gpa' => 3.00,
            'session' => '2 - 2024/2025'
        ]);
    }

    public function test_update_semester_fails_with_non_existing_semester_id(): void
    {
        Semester::create($this->validSemesterPayload([
            'gpa' => 3.00,
            'session' => '2 - 2024/2025'
        ]));

        $response = $this->putJson(route('semester.update', ['id' => 0]), $this->validSemesterPayload());

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonFragment([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'No semester found or access unauthorized.'
            ])
            ->assertJsonPath('data', null);

        $this->assertDatabaseHas('semesters', [
            'education_id' => $this->education->id,
            'gpa' => 3.00,
            'session' => '2 - 2024/2025'
        ]);
    }

    public function test_update_semester_fails_when_updating_other_user_semester(): void
    {
        $otherUserProfile = UserProfile::factory()->create();
        $otherEducation = Education::factory()->create([
            'user_profile_id' => $otherUserProfile->id
        ]);
        $otherSemester = Semester::create([
            'education_id' => $otherEducation->id,
            'gpa' => 3.00,
            'session' => '2 - 2024/2025'
        ]);

        $response = $this->putJson(route('semester.update', ['id' => $otherSemester->id]), $this->validSemesterPayload());

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonFragment([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'No semester found or access unauthorized.'
            ])
            ->assertJsonPath('data', null);

        $this->assertDatabaseHas('semesters', [
            'education_id' => $otherEducation->id,
            'gpa' => 3.00,
            'session' => '2 - 2024/2025'
        ]);
    }

    public function test_update_semester_fails_with_existed_semester_record(): void
    {
        // create an initial existing semester record
        Semester::create($this->validSemesterPayload());

        $semester = Semester::create($this->validSemesterPayload([
            'gpa' => 3.00,
            'session' => '2 - 2024/2025'
        ]));

        $response = $this->putJson(route('semester.update', ['id' => $semester->id]), $this->validSemesterPayload());

        $response->assertStatus(Response::HTTP_CONFLICT)
            ->assertJsonFragment([
                'status' => Response::HTTP_CONFLICT,
                'message' => 'Semester already exists for this session.'
            ])
            ->assertJsonPath('data', null);

        $this->assertDatabaseCount('semesters', 2);

        $this->assertDatabaseHas('semesters', [
            'education_id' => $this->education->id,
            'gpa' => 3.00,
            'session' => '2 - 2024/2025'
        ])
            ->assertDatabaseHas('semesters', $this->validSemesterPayload());
    }

    public function test_update_semester_fails_with_invalid_gpa(): void
    {
        $semester = Semester::create($this->validSemesterPayload([
            'gpa' => 3.00,
            'session' => '2 - 2024/2025'
        ]));

        $response = $this->putJson(route('semester.update', ['id' => $semester->id]), $this->validSemesterPayload([
            'gpa' => -1.00
        ]));

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'status' => Response::HTTP_BAD_REQUEST,
            ])
            ->assertJsonPath('data', null);

        $this->assertStringContainsString('gpa', $response->json('message'));

        $this->assertDatabaseHas('semesters', [
            'education_id' => $this->education->id,
            'gpa' => 3.00,
            'session' => '2 - 2024/2025'
        ]);

        $response = $this->putJson(route('semester.update', ['id' => $semester->id]), $this->validSemesterPayload([
            'gpa' => 5.00
        ]));

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'status' => Response::HTTP_BAD_REQUEST,
            ])
            ->assertJsonPath('data', null);

        $this->assertStringContainsString('gpa', $response->json('message'));

        $this->assertDatabaseHas('semesters', [
            'education_id' => $this->education->id,
            'gpa' => 3.00,
            'session' => '2 - 2024/2025'
        ]);
    }
}
