<?php

namespace Tests\Feature;

use App\Constants\AppConstants;
use App\Models\Interview;
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

class InterviewControllerTest extends TestCase
{
    use RefreshDatabase;

    private const ORGANIZATION_RETURN_COLUMNS = ['id', 'company_name', 'organization_logo'];
    private const PROFILE_RETURN_COLUMNS = ['id', 'name', 'profile_image', 'location', 'headline'];
    private const POSITION_RETURN_COLUMNS = ['id', 'position_title', 'organization_id', 'department', 'employment_type'];

    private User $user;
    private UserProfile $interviewerProfile;
    private UserProfile $intervieweeProfile;
    private Organization $organization;
    private Position $position;

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
        $this->interviewerProfile = UserProfile::factory()->create([
            'user_id' => $this->user->id,
        ]);
        $this->intervieweeProfile = UserProfile::factory()->create();
        $this->organization = Organization::factory()->create();

        OrganizationUser::create([
            'organization_id' => $this->organization->id,
            'user_profile_id' => $this->interviewerProfile->id,
            'role_id' => Role::where('name', 'Recruiter')->first()->id,
            'status' => 1,
        ]);

        $this->position = Position::factory()->create([
            'organization_id' => $this->organization->id,
            'user_profile_id' => $this->interviewerProfile->id,
        ]);

        $this->actingAs($this->user)->withSession([
            'user_profile_id' => $this->interviewerProfile->id,
            'roles' => ['Recruiter']
        ]);
    }

    public function test_interviewer_can_get_interviews_by_status(): void
    {
        $scheduledInterview = Interview::factory()->create([
            'position_id' => $this->position->id,
            'interviewer_profile_id' => $this->interviewerProfile->id,
            'interviewee_profile_id' => $this->intervieweeProfile->id,
            'interview_status' => AppConstants::INTERVIEW_STATUS['SCHEDULED'],
        ]);

        Interview::factory()->create([
            'position_id' => $this->position->id,
            'interviewer_profile_id' => $this->interviewerProfile->id,
            'interviewee_profile_id' => $this->intervieweeProfile->id,
            'interview_status' => AppConstants::INTERVIEW_STATUS['COMPLETED'],
        ]);

        $response = $this->getJson(route('interviews.getInterviewsByStatusAndInterviewerId', [
            'status' => AppConstants::INTERVIEW_STATUS['SCHEDULED'],
        ]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'Success.'
            ])
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'position_id',
                        'interviewer_profile_id',
                        'interviewee_profile_id',
                        'scheduled_at',
                        'interview_mode',
                        'location',
                        'meeting_url',
                        'interview_status',
                        'interview_result',
                        'recruiter_comment',
                        'created_at',
                        'updated_at',
                        'position' => [
                            'id',
                            'position_title',
                            'organization_id',
                            'department',
                            'employment_type',
                            'organization' => self::ORGANIZATION_RETURN_COLUMNS
                        ],
                        'interviewee' => self::PROFILE_RETURN_COLUMNS,
                    ]
                ]
            ])
            ->assertJsonPath('data.0.interviewer_profile_id', $this->interviewerProfile->id)
            ->assertJsonPath('data.0.interviewee.id', $this->intervieweeProfile->id)
            ->assertJsonPath('data.0.interview_status', AppConstants::INTERVIEW_STATUS['SCHEDULED'])
            ->assertJsonPath('data.0.position.id', $this->position->id);
    }

    public function test_interviewee_can_get_interviews_by_status(): void
    {
        $completedInterview = Interview::factory()->create([
            'position_id' => $this->position->id,
            'interviewer_profile_id' => $this->interviewerProfile->id,
            'interviewee_profile_id' => $this->intervieweeProfile->id,
            'interview_status' => AppConstants::INTERVIEW_STATUS['COMPLETED'],
        ]);

        Interview::factory()->create([
            'position_id' => $this->position->id,
            'interviewer_profile_id' => $this->interviewerProfile->id,
            'interviewee_profile_id' => $this->intervieweeProfile->id,
            'interview_status' => AppConstants::INTERVIEW_STATUS['CANCELLED'],
        ]);

        $this->withSession(['user_profile_id' => $this->intervieweeProfile->id]);

        $response = $this->getJson(route('interviews.getInterviewsByStatusAndIntervieweeId', [
            'status' => AppConstants::INTERVIEW_STATUS['COMPLETED'],
        ]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'Success.'
            ])
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'position_id',
                        'interviewer_profile_id',
                        'interviewee_profile_id',
                        'scheduled_at',
                        'interview_mode',
                        'location',
                        'meeting_url',
                        'interview_status',
                        'interview_result',
                        'recruiter_comment',
                        'created_at',
                        'updated_at',
                        'position' => [
                            'id',
                            'position_title',
                            'organization_id',
                            'department',
                            'employment_type',
                            'organization' => self::ORGANIZATION_RETURN_COLUMNS
                        ],
                        'interviewer' => self::PROFILE_RETURN_COLUMNS,
                    ]
                ]
            ])
            ->assertJsonPath('data.0.interviewee_profile_id', $this->intervieweeProfile->id)
            ->assertJsonPath('data.0.interviewer.id', $this->interviewerProfile->id)
            ->assertJsonPath('data.0.interview_status', AppConstants::INTERVIEW_STATUS['COMPLETED'])
            ->assertJsonPath('data.0.position.id', $this->position->id);
    }

    public function test_user_can_get_interview_by_id_when_they_are_the_interviewer(): void
    {
        $interview = Interview::factory()->create([
            'position_id' => $this->position->id,
            'interviewer_profile_id' => $this->interviewerProfile->id,
            'interviewee_profile_id' => $this->intervieweeProfile->id,
            'interview_status' => AppConstants::INTERVIEW_STATUS['SCHEDULED'],
        ]);

        $response = $this->getJson(route('interviews.getInterviewById', ['id' => $interview->id]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'Success.'
            ])
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'position_id',
                    'interviewer_profile_id',
                    'interviewee_profile_id',
                    'scheduled_at',
                    'interview_mode',
                    'location',
                    'meeting_url',
                    'interview_status',
                    'interview_result',
                    'recruiter_comment',
                    'created_at',
                    'updated_at',
                    'user_role',
                    'position' => [
                        'id',
                        'position_title',
                        'organization_id',
                        'department',
                        'employment_type',
                        'organization' => self::ORGANIZATION_RETURN_COLUMNS
                    ],
                    'interviewer' => self::PROFILE_RETURN_COLUMNS,
                    'interviewee' => self::PROFILE_RETURN_COLUMNS,
                ]
            ])
            ->assertJsonPath('data.id', $interview->id)
            ->assertJsonPath('data.interviewer.id', $this->interviewerProfile->id)
            ->assertJsonPath('data.interviewee.id', $this->intervieweeProfile->id)
            ->assertJsonPath('data.user_role', 'interviewer')
            ->assertJsonPath('data.position.id', $this->position->id)
            ->assertJsonPath('data.interviewee.id', $this->intervieweeProfile->id);
    }

    public function test_user_get_interview_by_id_returns_not_found_when_not_part_of_interview(): void
    {
        $interview = Interview::factory()->create([
            'position_id' => $this->position->id,
            'interviewer_profile_id' => $this->interviewerProfile->id,
            'interviewee_profile_id' => $this->intervieweeProfile->id,
            'interview_status' => AppConstants::INTERVIEW_STATUS['SCHEDULED'],
        ]);

        $otherProfile = UserProfile::factory()->create();
        $this->withSession(['user_profile_id' => $otherProfile->id]);

        $response = $this->getJson(route('interviews.getInterviewById', ['id' => $interview->id]));

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonFragment([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Interview not found or access unauthorized on interview.'
            ]);
    }

    public function test_user_get_interview_by_id_returns_not_found_when_id_invalid(): void
    {
        $interview = Interview::factory()->create([
            'position_id' => $this->position->id,
            'interviewer_profile_id' => $this->interviewerProfile->id,
            'interviewee_profile_id' => $this->intervieweeProfile->id,
            'interview_status' => AppConstants::INTERVIEW_STATUS['SCHEDULED'],
        ]);

        $response = $this->getJson(route('interviews.getInterviewById', ['id' => 0]));

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonFragment([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Interview not found or access unauthorized on interview.'
            ]);
    }

    public function test_interviewer_can_get_interviews_by_position_id_and_receiver_id(): void
    {
        $interview = Interview::factory()->create([
            'position_id' => $this->position->id,
            'interviewer_profile_id' => $this->interviewerProfile->id,
            'interviewee_profile_id' => $this->intervieweeProfile->id,
        ]);

        Interview::factory()->create([
            'position_id' => $this->position->id,
            'interviewer_profile_id' => $this->interviewerProfile->id,
            'interviewee_profile_id' => $this->intervieweeProfile->id,
        ]);

        $response = $this->getJson(route('interviews.getInterviewsByPositionIdAndIntervieweeId', [
            'intervieweeId' => $this->intervieweeProfile->id,
            'positionId' => $this->position->id,
        ]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'Success.'
            ])
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'position_id',
                        'title'
                    ]
                ]
            ])
            ->assertJsonPath('data.0.id', $interview->id)
            ->assertJsonPath('data.0.position_id', $interview->position_id)
            ->assertJsonPath('data.0.title', $interview->title);

        $this->assertCount(2, $response->json('data'));
    }

    public function test_get_interviews_by_position_id_and_receiver_id_fails_with_non_existing_position_id(): void
    {
        Interview::factory()->create([
            'position_id' => $this->position->id,
            'interviewer_profile_id' => $this->interviewerProfile->id,
            'interviewee_profile_id' => $this->intervieweeProfile->id,
        ]);

        $response = $this->getJson(route('interviews.getInterviewsByPositionIdAndIntervieweeId', [
            'intervieweeId' => $this->intervieweeProfile->id,
            'positionId' => 0,
        ]));

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonFragment([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Position not found with given ID.'
            ])
            ->assertJsonPath('data', null);
    }

    public function test_get_interviews_by_position_id_and_receiver_id_fails_when_user_is_not_admin_of_current_org(): void
    {
        $this->withSession([
            'user_profile_id' => $this->intervieweeProfile->id,
            'roles' => ['Organization Admin']
        ]);

        Interview::factory()->create([
            'position_id' => $this->position->id,
            'interviewer_profile_id' => $this->interviewerProfile->id,
            'interviewee_profile_id' => $this->intervieweeProfile->id,
        ]);

        $response = $this->getJson(route('interviews.getInterviewsByPositionIdAndIntervieweeId', [
            'intervieweeId' => $this->intervieweeProfile->id,
            'positionId' => $this->position->id,
        ]));

        $response->assertStatus(Response::HTTP_FORBIDDEN)
            ->assertJsonFragment([
                'status' => Response::HTTP_FORBIDDEN,
                'message' => 'Unauthorized access to get interviews.'
            ])
            ->assertJsonPath('data', null);
    }

    public function test_get_interviews_by_position_id_and_receiver_id_fails_with_non_existing_receiver_id(): void
    {
        Interview::factory()->create([
            'position_id' => $this->position->id,
            'interviewer_profile_id' => $this->interviewerProfile->id,
            'interviewee_profile_id' => $this->intervieweeProfile->id,
        ]);

        $response = $this->getJson(route('interviews.getInterviewsByPositionIdAndIntervieweeId', [
            'intervieweeId' => 0,
            'positionId' => $this->position->id,
        ]));

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonFragment([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'User profile not found with given ID.'
            ])
            ->assertJsonPath('data', null);
    }

    public function test_org_admin_can_create_interview(): void
    {
        $response = $this->postJson(route('interviews.store'), [
            'title' => 'Interview for position of Software Engineering role',
            'position_id' => $this->position->id,
            'scheduled_at' => now()->addDays(2)->toDateTimeString(),
            'interview_mode' => AppConstants::INTERVIEW_MODES[0],
            'meeting_url' => 'https://meet.example.com/interview',
            'recruiter_comment' => 'Please review the candidate portfolio.',
            'interviewee_profile_id' => $this->intervieweeProfile->id,
        ]);

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonFragment([
                'status' => Response::HTTP_CREATED,
                'message' => 'Interview created successfully.',
            ])
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'title',
                    'position_id',
                    'interviewer_profile_id',
                    'interviewee_profile_id',
                    'scheduled_at',
                    'interview_mode',
                    'location',
                    'meeting_url',
                    'interview_status',
                    'interview_result',
                    'recruiter_comment',
                    'created_at',
                    'updated_at',
                    'position' => [
                        'id',
                        'position_title',
                        'organization_id',
                        'department',
                        'employment_type',
                    ],
                    'interviewee' => self::PROFILE_RETURN_COLUMNS,
                ]
            ])
            ->assertJsonPath('data.position_id', $this->position->id)
            ->assertJsonPath('data.title', 'Interview for position of Software Engineering role')
            ->assertJsonPath('data.interviewee.id', $this->intervieweeProfile->id)
            ->assertJsonPath('data.interview_status', AppConstants::INTERVIEW_STATUS['SCHEDULED'])
            ->assertJsonPath('data.interview_result', AppConstants::INTERVIEW_RESULTS['PENDING'])
            ->assertJsonPath('data.position.id', $this->position->id)
            ->assertJsonPath('data.interviewee.id', $this->intervieweeProfile->id);

        $this->assertDatabaseHas('interviews', [
            'title' => 'Interview for position of Software Engineering role',
            'position_id' => $this->position->id,
            'interviewer_profile_id' => $this->interviewerProfile->id,
            'interviewee_profile_id' => $this->intervieweeProfile->id,
            'interview_status' => AppConstants::INTERVIEW_STATUS['SCHEDULED'],
            'interview_result' => AppConstants::INTERVIEW_RESULTS['PENDING'],
        ]);
    }

    public function test_org_admin_can_create_online_interview_without_nullable_fields_except_meeting_url(): void
    {
        $response = $this->postJson(route('interviews.store'), [
            'title' => 'Interview for position of Software Engineering role',
            'position_id' => $this->position->id,
            'scheduled_at' => now()->addDays(2)->toDateTimeString(),
            'interview_mode' => AppConstants::INTERVIEW_MODES[0],
            'interviewee_profile_id' => $this->intervieweeProfile->id,
            'meeting_url' => 'https://meet.example.com/interview',
        ]);

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonFragment([
                'status' => Response::HTTP_CREATED,
                'message' => 'Interview created successfully.',
            ])
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'title',
                    'position_id',
                    'interviewer_profile_id',
                    'interviewee_profile_id',
                    'scheduled_at',
                    'interview_mode',
                    'location',
                    'meeting_url',
                    'interview_status',
                    'interview_result',
                    'recruiter_comment',
                    'created_at',
                    'updated_at',
                    'position' => [
                        'id',
                        'position_title',
                        'organization_id',
                        'department',
                        'employment_type',
                    ],
                    'interviewee' => self::PROFILE_RETURN_COLUMNS,
                ]
            ])
            ->assertJsonPath('data.position_id', $this->position->id)
            ->assertJsonPath('data.interviewee.id', $this->intervieweeProfile->id)
            ->assertJsonPath('data.interview_mode', AppConstants::INTERVIEW_MODES[0])
            ->assertJsonPath('data.position.id', $this->position->id);

        $this->assertDatabaseHas('interviews', [
            'title' => 'Interview for position of Software Engineering role',
            'position_id' => $this->position->id,
            'interview_mode' => AppConstants::INTERVIEW_MODES[0],
            'interviewee_profile_id' => $this->intervieweeProfile->id,
        ]);
    }

    public function test_org_admin_can_create_on_site_interview_without_nullable_fields_except_location(): void
    {
        $response = $this->postJson(route('interviews.store'), [
            'title' => 'Interview for position of Software Engineering role',
            'position_id' => $this->position->id,
            'scheduled_at' => now()->addDays(2)->toDateTimeString(),
            'interview_mode' => AppConstants::INTERVIEW_MODES[1],
            'interviewee_profile_id' => $this->intervieweeProfile->id,
            'location' => 'Block A'
        ]);

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonFragment([
                'status' => Response::HTTP_CREATED,
                'message' => 'Interview created successfully.',
            ])
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'title',
                    'position_id',
                    'interviewer_profile_id',
                    'interviewee_profile_id',
                    'scheduled_at',
                    'interview_mode',
                    'location',
                    'meeting_url',
                    'interview_status',
                    'interview_result',
                    'recruiter_comment',
                    'created_at',
                    'updated_at',
                    'position' => [
                        'id',
                        'position_title',
                        'organization_id',
                        'department',
                        'employment_type',
                    ],
                    'interviewee' => self::PROFILE_RETURN_COLUMNS,
                ]
            ])
            ->assertJsonPath('data.position_id', $this->position->id)
            ->assertJsonPath('data.interviewee.id', $this->intervieweeProfile->id)
            ->assertJsonPath('data.interview_mode', AppConstants::INTERVIEW_MODES[1])
            ->assertJsonPath('data.location', 'Block A')
            ->assertJsonPath('data.position.id', $this->position->id);

        $this->assertDatabaseHas('interviews', [
            'title' => 'Interview for position of Software Engineering role',
            'position_id' => $this->position->id,
            'interview_mode' => AppConstants::INTERVIEW_MODES[1],
            'interviewee_profile_id' => $this->intervieweeProfile->id,
            'location' => 'Block A'
        ]);
    }

    public function test_create_interview_fails_without_required_fields(): void
    {
        $response = $this->postJson(route('interviews.store'));

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'status' => Response::HTTP_BAD_REQUEST,
            ]);
    }

    public function test_create_interview_fails_with_invalid_interview_mode(): void
    {
        $response = $this->postJson(route('interviews.store'), [
            'title' => 'Interview for position of Software Engineering role',
            'position_id' => $this->position->id,
            'scheduled_at' => now()->addDays(2)->toDateTimeString(),
            'interview_mode' => 'Physical',
            'meeting_url' => 'https://meet.example.com/interview',
            'recruiter_comment' => 'Please review the candidate portfolio.',
            'interviewee_profile_id' => $this->intervieweeProfile->id,
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'status' => Response::HTTP_BAD_REQUEST,
            ]);
    }

    public function test_org_admin_can_update_interview(): void
    {
        $interview = Interview::factory()->create([
            'title' => 'Interview for position of Software Engineering role',
            'position_id' => $this->position->id,
            'interviewer_profile_id' => $this->interviewerProfile->id,
            'interviewee_profile_id' => $this->intervieweeProfile->id,
            'scheduled_at' => now()->addDays(5)->toDateTimeString(),
            'interview_mode' => AppConstants::INTERVIEW_MODES[0],
            'location' => null,
            'meeting_url' => 'https://old.example.com/meet',
            'interview_status' => AppConstants::INTERVIEW_STATUS['SCHEDULED'],
            'interview_result' => AppConstants::INTERVIEW_RESULTS['PENDING'],
            'recruiter_comment' => 'Old note',
        ]);

        $response = $this->putJson(route('interviews.update', ['id' => $interview->id]), [
            'title' => 'Updated title',
            'scheduled_at' => now()->addDays(7)->toDateTimeString(),
            'interview_mode' => AppConstants::INTERVIEW_MODES[1],
            'location' => 'HQ Room 3',
            'meeting_url' => null,
            'interview_result' => AppConstants::INTERVIEW_RESULTS['PASSED'],
            'recruiter_comment' => 'Updated interview note.',
        ]);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'Interview updated successfully.',
            ])
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'title',
                    'position_id',
                    'interviewer_profile_id',
                    'interviewee_profile_id',
                    'scheduled_at',
                    'interview_mode',
                    'location',
                    'meeting_url',
                    'interview_status',
                    'interview_result',
                    'recruiter_comment',
                    'created_at',
                    'updated_at',
                ]
            ])
            ->assertJsonPath('data.id', $interview->id)
            ->assertJsonPath('data.title', 'Updated title')
            ->assertJsonPath('data.position_id', $this->position->id)
            ->assertJsonPath('data.interviewer_profile_id', $this->interviewerProfile->id)
            ->assertJsonPath('data.interviewee_profile_id', $this->intervieweeProfile->id)
            ->assertJsonPath('data.interview_mode', AppConstants::INTERVIEW_MODES[1])
            ->assertJsonPath('data.location', 'HQ Room 3')
            ->assertJsonPath('data.interview_result', AppConstants::INTERVIEW_RESULTS['PASSED'])
            ->assertJsonPath('data.recruiter_comment', 'Updated interview note.');

        $this->assertDatabaseHas('interviews', [
            'id' => $interview->id,
            'title' => 'Updated title',
            'interview_mode' => AppConstants::INTERVIEW_MODES[1],
            'location' => 'HQ Room 3',
            'meeting_url' => null,
            'interview_result' => AppConstants::INTERVIEW_RESULTS['PASSED'],
            'recruiter_comment' => 'Updated interview note.',
        ]);
    }

    public function test_org_admin_can_update_to_online_interview_without_nullable_fields_except_location(): void
    {
        $interview = Interview::factory()->create([
            'title' => 'Interview for position of Software Engineering role',
            'position_id' => $this->position->id,
            'interviewer_profile_id' => $this->interviewerProfile->id,
            'interviewee_profile_id' => $this->intervieweeProfile->id,
            'scheduled_at' => now()->addDays(5)->toDateTimeString(),
            'interview_mode' => AppConstants::INTERVIEW_MODES[0],
            'location' => null,
            'meeting_url' => 'https://old.example.com/meet',
            'interview_status' => AppConstants::INTERVIEW_STATUS['SCHEDULED'],
        ]);

        $response = $this->putJson(route('interviews.update', ['id' => $interview->id]), [
            'title' => 'Updated title',
            'interview_mode' => AppConstants::INTERVIEW_MODES[1],
            'location' => 'HQ Room 3',
            'meeting_url' => null,
            'scheduled_at' => now()->addDays(7)->toDateTimeString(),
        ]);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'Interview updated successfully.',
            ])
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'position_id',
                    'interviewer_profile_id',
                    'interviewee_profile_id',
                    'scheduled_at',
                    'interview_mode',
                    'location',
                    'meeting_url',
                    'interview_status',
                    'interview_result',
                    'recruiter_comment',
                    'created_at',
                    'updated_at',
                ]
            ])
            ->assertJsonPath('data.id', $interview->id)
            ->assertJsonPath('data.title', 'Updated title')
            ->assertJsonPath('data.position_id', $this->position->id)
            ->assertJsonPath('data.interviewer_profile_id', $this->interviewerProfile->id)
            ->assertJsonPath('data.interviewee_profile_id', $this->intervieweeProfile->id)
            ->assertJsonPath('data.interview_mode', AppConstants::INTERVIEW_MODES[1])
            ->assertJsonPath('data.location', 'HQ Room 3')
            ->assertJsonPath('data.meeting_url', null);

        $this->assertDatabaseHas('interviews', [
            'id' => $interview->id,
            'title' => 'Updated title',
            'interview_mode' => AppConstants::INTERVIEW_MODES[1],
            'location' => 'HQ Room 3',
            'meeting_url' => null,
        ]);
    }

    public function test_org_admin_can_update_to_on_site_interview_without_nullable_fields_except_meeting_url(): void
    {
        $interview = Interview::factory()->create([
            'title' => 'Interview for position of Software Engineering role',
            'position_id' => $this->position->id,
            'interviewer_profile_id' => $this->interviewerProfile->id,
            'interviewee_profile_id' => $this->intervieweeProfile->id,
            'scheduled_at' => now()->addDays(5)->toDateTimeString(),
            'interview_mode' => AppConstants::INTERVIEW_MODES[1],
            'location' => 'HQ Room 3',
            'meeting_url' => null,
            'interview_status' => AppConstants::INTERVIEW_STATUS['SCHEDULED'],
        ]);

        $response = $this->putJson(route('interviews.update', ['id' => $interview->id]), [
            'title' => 'Updated title',
            'interview_mode' => AppConstants::INTERVIEW_MODES[0],
            'location' => null,
            'meeting_url' => 'https://new.example.com/meet',
            'scheduled_at' => now()->addDays(7)->toDateTimeString(),
        ]);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'Interview updated successfully.',
            ])
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'position_id',
                    'interviewer_profile_id',
                    'interviewee_profile_id',
                    'scheduled_at',
                    'interview_mode',
                    'location',
                    'meeting_url',
                    'interview_status',
                    'interview_result',
                    'recruiter_comment',
                    'created_at',
                    'updated_at',
                ]
            ])
            ->assertJsonPath('data.id', $interview->id)
            ->assertJsonPath('data.title', 'Updated title')
            ->assertJsonPath('data.position_id', $this->position->id)
            ->assertJsonPath('data.interviewer_profile_id', $this->interviewerProfile->id)
            ->assertJsonPath('data.interviewee_profile_id', $this->intervieweeProfile->id)
            ->assertJsonPath('data.interview_mode', AppConstants::INTERVIEW_MODES[0])
            ->assertJsonPath('data.meeting_url', 'https://new.example.com/meet')
            ->assertJsonPath('data.location', null);

        $this->assertDatabaseHas('interviews', [
            'id' => $interview->id,
            'title' => 'Updated title',
            'interview_mode' => AppConstants::INTERVIEW_MODES[0],
            'location' => null,
            'meeting_url' => 'https://new.example.com/meet',
        ]);
    }

    public function test_update_to_on_site_interview_fails_without_location(): void
    {
        $interview = Interview::factory()->create([
            'title' => 'Interview for position of Software Engineering role',
            'position_id' => $this->position->id,
            'interviewer_profile_id' => $this->interviewerProfile->id,
            'interviewee_profile_id' => $this->intervieweeProfile->id,
            'scheduled_at' => now()->addDays(5)->toDateTimeString(),
            'interview_mode' => AppConstants::INTERVIEW_MODES[0],
            'location' => null,
            'meeting_url' => 'https://old.example.com/meet',
            'interview_status' => AppConstants::INTERVIEW_STATUS['SCHEDULED'],
        ]);

        $response = $this->putJson(route('interviews.update', ['id' => $interview->id]), [
            'title' => 'Updated interview',
            'interview_mode' => AppConstants::INTERVIEW_MODES[1],
            'scheduled_at' => now()->addDays(7)->toDateTimeString(),
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'status' => Response::HTTP_BAD_REQUEST,
            ])
            ->assertJsonPath('data', null);

        $this->assertStringContainsString('location', $response->json('message'));

        $this->assertDatabaseHas('interviews', [
            'id' => $interview->id,
            'title' => 'Interview for position of Software Engineering role',
            'interview_mode' => AppConstants::INTERVIEW_MODES[0],
            'location' => null,
            'meeting_url' => 'https://old.example.com/meet',
        ]);
    }

    public function test_update_to_online_interview_fails_without_meeting_url(): void
    {
        $interview = Interview::factory()->create([
            'title' => 'Interview for position of Software Engineering role',
            'position_id' => $this->position->id,
            'interviewer_profile_id' => $this->interviewerProfile->id,
            'interviewee_profile_id' => $this->intervieweeProfile->id,
            'scheduled_at' => now()->addDays(5)->toDateTimeString(),
            'interview_mode' => AppConstants::INTERVIEW_MODES[1],
            'location' => 'HQ Room 3',
            'meeting_url' => null,
            'interview_status' => AppConstants::INTERVIEW_STATUS['SCHEDULED'],
        ]);

        $response = $this->putJson(route('interviews.update', ['id' => $interview->id]), [
            'title' => 'Interview for position of Software Engineering role',
            'interview_mode' => AppConstants::INTERVIEW_MODES[0],
            'scheduled_at' => now()->addDays(7)->toDateTimeString(),
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'status' => Response::HTTP_BAD_REQUEST,
            ])
            ->assertJsonPath('data', null);

        $this->assertStringContainsString('meeting url', $response->json('message'));

        $this->assertDatabaseHas('interviews', [
            'id' => $interview->id,
            'title' => 'Interview for position of Software Engineering role',
            'interview_mode' => AppConstants::INTERVIEW_MODES[1],
            'location' => 'HQ Room 3',
            'meeting_url' => null,
        ]);
    }

    public function test_update_interview_fails_without_required_fields(): void
    {
        $interview = Interview::factory()->create([
            'title' => 'Interview for position of Software Engineering role',
            'position_id' => $this->position->id,
            'interviewer_profile_id' => $this->interviewerProfile->id,
            'interviewee_profile_id' => $this->intervieweeProfile->id,
            'scheduled_at' => now()->addDays(5)->toDateTimeString(),
            'interview_mode' => AppConstants::INTERVIEW_MODES[0],
            'location' => null,
            'meeting_url' => 'https://old.example.com/meet',
            'interview_status' => AppConstants::INTERVIEW_STATUS['SCHEDULED'],
            'interview_result' => AppConstants::INTERVIEW_RESULTS['PENDING'],
            'recruiter_comment' => 'Old note',
        ]);

        $response = $this->putJson(route('interviews.update', ['id' => $interview->id]));

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'status' => Response::HTTP_BAD_REQUEST
            ])
            ->assertJsonPath('data', null);

        $this->assertDatabaseHas('interviews', [
            'id' => $interview->id,
            'title' => 'Interview for position of Software Engineering role',
            'position_id' => $this->position->id,
            'interviewer_profile_id' => $this->interviewerProfile->id,
            'interviewee_profile_id' => $this->intervieweeProfile->id,
            'scheduled_at' => now()->addDays(5)->toDateTimeString(),
            'interview_mode' => AppConstants::INTERVIEW_MODES[0],
            'location' => null,
            'meeting_url' => 'https://old.example.com/meet',
            'interview_status' => AppConstants::INTERVIEW_STATUS['SCHEDULED'],
            'interview_result' => AppConstants::INTERVIEW_RESULTS['PENDING'],
            'recruiter_comment' => 'Old note',
        ]);
    }

    public function test_update_interview_fails_when_interview_is_completed(): void
    {
        $interview = Interview::factory()->create([
            'title' => 'Interview for position of Software Engineering role',
            'position_id' => $this->position->id,
            'interviewer_profile_id' => $this->interviewerProfile->id,
            'interviewee_profile_id' => $this->intervieweeProfile->id,
            'interview_status' => AppConstants::INTERVIEW_STATUS['COMPLETED'],
        ]);

        $response = $this->putJson(route('interviews.update', ['id' => $interview->id]), [
            'title' => 'Interview for position of Software Engineering role',
            'scheduled_at' => now()->addDays(7)->toDateTimeString(),
            'interview_mode' => AppConstants::INTERVIEW_MODES[0],
            'meeting_url' => 'https://meet.example.com/update',
            'interview_result' => AppConstants::INTERVIEW_RESULTS['PASSED'],
            'recruiter_comment' => 'Recruiter comment.',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment([
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'message' => 'Interview completed or cancelled cannot be updated anymore.',
            ]);

        $this->assertDatabaseHas('interviews', [
            'title' => 'Interview for position of Software Engineering role',
            'position_id' => $this->position->id,
            'interviewer_profile_id' => $this->interviewerProfile->id,
            'interviewee_profile_id' => $this->intervieweeProfile->id,
            'interview_status' => AppConstants::INTERVIEW_STATUS['COMPLETED'],
        ]);
    }

    public function test_update_interview_fails_when_interview_is_cancelled(): void
    {
        $interview = Interview::factory()->create([
            'title' => 'Interview for position of Software Engineering role',
            'position_id' => $this->position->id,
            'interviewer_profile_id' => $this->interviewerProfile->id,
            'interviewee_profile_id' => $this->intervieweeProfile->id,
            'interview_status' => AppConstants::INTERVIEW_STATUS['CANCELLED'],
        ]);

        $response = $this->putJson(route('interviews.update', ['id' => $interview->id]), [
            'title' => 'Interview for position of Software Engineering role',
            'scheduled_at' => now()->addDays(7)->toDateTimeString(),
            'interview_mode' => AppConstants::INTERVIEW_MODES[0],
            'meeting_url' => 'https://meet.example.com/update',
            'interview_result' => AppConstants::INTERVIEW_RESULTS['PASSED'],
            'recruiter_comment' => 'Recruiter comment.',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment([
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'message' => 'Interview completed or cancelled cannot be updated anymore.',
            ]);

        $this->assertDatabaseHas('interviews', [
            'title' => 'Interview for position of Software Engineering role',
            'position_id' => $this->position->id,
            'interviewer_profile_id' => $this->interviewerProfile->id,
            'interviewee_profile_id' => $this->intervieweeProfile->id,
            'interview_status' => AppConstants::INTERVIEW_STATUS['CANCELLED'],
        ]);
    }

    public function test_update_interview_fails_with_invalid_interview_id(): void
    {
        Interview::factory()->create([
            'title' => 'Interview for position of Software Engineering role',
            'position_id' => $this->position->id,
            'interviewer_profile_id' => $this->interviewerProfile->id,
            'interviewee_profile_id' => $this->intervieweeProfile->id,
            'interview_status' => AppConstants::INTERVIEW_STATUS['SCHEDULED'],
        ]);

        $response = $this->putJson(route('interviews.update', ['id' => 0]), [
            'title' => 'Interview for position of Software Engineering role',
            'scheduled_at' => now()->addDays(7)->toDateTimeString(),
            'interview_mode' => AppConstants::INTERVIEW_MODES[0],
            'meeting_url' => 'https://meet.example.com/update',
            'interview_result' => AppConstants::INTERVIEW_RESULTS['PASSED'],
            'recruiter_comment' => 'Recruiter comment.',
        ]);

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonFragment([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Interview not found or access unauthorized.',
            ]);

        $this->assertDatabaseHas('interviews', [
            'title' => 'Interview for position of Software Engineering role',
            'position_id' => $this->position->id,
            'interviewer_profile_id' => $this->interviewerProfile->id,
            'interviewee_profile_id' => $this->intervieweeProfile->id,
            'interview_status' => AppConstants::INTERVIEW_STATUS['SCHEDULED'],
        ]);
    }

    public function test_org_admin_can_complete_scheduled_interview(): void
    {
        $interview = Interview::factory()->create([
            'title' => 'Interview for position of Software Engineering role',
            'position_id' => $this->position->id,
            'interviewer_profile_id' => $this->interviewerProfile->id,
            'interviewee_profile_id' => $this->intervieweeProfile->id,
            'interview_status' => AppConstants::INTERVIEW_STATUS['SCHEDULED'],
        ]);

        $response = $this->putJson(route('interviews.completeInterview', ['id' => $interview->id]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'Interview marked as completed.',
            ])
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'title',
                    'position_id',
                    'interviewer_profile_id',
                    'interviewee_profile_id',
                    'scheduled_at',
                    'interview_mode',
                    'location',
                    'meeting_url',
                    'interview_status',
                    'interview_result',
                    'recruiter_comment',
                    'created_at',
                    'updated_at',
                ]
            ])
            ->assertJsonPath('data.id', $interview->id)
            ->assertJsonPath('data.position_id', $this->position->id)
            ->assertJsonPath('data.interviewer_profile_id', $this->interviewerProfile->id)
            ->assertJsonPath('data.interviewee_profile_id', $this->intervieweeProfile->id)
            ->assertJsonPath('data.interview_status', AppConstants::INTERVIEW_STATUS['COMPLETED']);

        $this->assertDatabaseHas('interviews', [
            'id' => $interview->id,
            'position_id' => $this->position->id,
            'interviewer_profile_id' => $this->interviewerProfile->id,
            'interviewee_profile_id' => $this->intervieweeProfile->id,
            'interview_status' => AppConstants::INTERVIEW_STATUS['COMPLETED'],
        ]);
    }

    public function test_org_admin_can_cancel_scheduled_interview(): void
    {
        $interview = Interview::factory()->create([
            'title' => 'Interview for position of Software Engineering role',
            'position_id' => $this->position->id,
            'interviewer_profile_id' => $this->interviewerProfile->id,
            'interviewee_profile_id' => $this->intervieweeProfile->id,
            'interview_status' => AppConstants::INTERVIEW_STATUS['SCHEDULED'],
        ]);

        $response = $this->putJson(route('interviews.cancelInterview', ['id' => $interview->id]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'Interview cancelled.',
            ])
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'title',
                    'position_id',
                    'interviewer_profile_id',
                    'interviewee_profile_id',
                    'scheduled_at',
                    'interview_mode',
                    'location',
                    'meeting_url',
                    'interview_status',
                    'interview_result',
                    'recruiter_comment',
                    'created_at',
                    'updated_at',
                ]
            ])

            ->assertJsonPath('data.id', $interview->id)
            ->assertJsonPath('data.position_id', $this->position->id)
            ->assertJsonPath('data.interviewer_profile_id', $this->interviewerProfile->id)
            ->assertJsonPath('data.interviewee_profile_id', $this->intervieweeProfile->id)
            ->assertJsonPath('data.interview_status', AppConstants::INTERVIEW_STATUS['CANCELLED']);

        $this->assertDatabaseHas('interviews', [
            'id' => $interview->id,
            'position_id' => $this->position->id,
            'interviewer_profile_id' => $this->interviewerProfile->id,
            'interviewee_profile_id' => $this->intervieweeProfile->id,
            'interview_status' => AppConstants::INTERVIEW_STATUS['CANCELLED'],
        ]);
    }

    public function test_complete_interview_fails_when_interview_status_is_not_scheduled(): void
    {
        $interview = Interview::factory()->create([
            'title' => 'Interview for position of Software Engineering role',
            'position_id' => $this->position->id,
            'interviewer_profile_id' => $this->interviewerProfile->id,
            'interviewee_profile_id' => $this->intervieweeProfile->id,
            'interview_status' => AppConstants::INTERVIEW_STATUS['CANCELLED'],
        ]);

        $response = $this->putJson(route('interviews.completeInterview', ['id' => $interview->id]));

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment([
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'message' => 'Interview completed or cancelled cannot be updated anymore.',
            ]);

        $this->assertDatabaseHas('interviews', [
            'id' => $interview->id,
            'position_id' => $this->position->id,
            'interviewer_profile_id' => $this->interviewerProfile->id,
            'interviewee_profile_id' => $this->intervieweeProfile->id,
            'interview_status' => AppConstants::INTERVIEW_STATUS['CANCELLED'],
        ]);
    }

    public function test_cancel_interview_fails_when_interview_status_is_not_scheduled(): void
    {
        $interview = Interview::factory()->create([
            'title' => 'Interview for position of Software Engineering role',
            'position_id' => $this->position->id,
            'interviewer_profile_id' => $this->interviewerProfile->id,
            'interviewee_profile_id' => $this->intervieweeProfile->id,
            'interview_status' => AppConstants::INTERVIEW_STATUS['COMPLETED'],
        ]);

        $response = $this->putJson(route('interviews.cancelInterview', ['id' => $interview->id]));

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment([
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'message' => 'Interview completed or cancelled cannot be updated anymore.',
            ]);

        $this->assertDatabaseHas('interviews', [
            'id' => $interview->id,
            'position_id' => $this->position->id,
            'interviewer_profile_id' => $this->interviewerProfile->id,
            'interviewee_profile_id' => $this->intervieweeProfile->id,
            'interview_status' => AppConstants::INTERVIEW_STATUS['COMPLETED'],
        ]);
    }

    public function test_complete_interview_fails_with_invalid_interview_id(): void
    {
        $interview = Interview::factory()->create([
            'title' => 'Interview for position of Software Engineering role',
            'position_id' => $this->position->id,
            'interviewer_profile_id' => $this->interviewerProfile->id,
            'interviewee_profile_id' => $this->intervieweeProfile->id,
            'interview_status' => AppConstants::INTERVIEW_STATUS['SCHEDULED'],
        ]);

        $response = $this->putJson(route('interviews.completeInterview', ['id' => 0]));

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonFragment([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Interview not found or access unauthorized.',
            ]);

        $this->assertDatabaseHas('interviews', [
            'id' => $interview->id,
            'position_id' => $this->position->id,
            'interviewer_profile_id' => $this->interviewerProfile->id,
            'interviewee_profile_id' => $this->intervieweeProfile->id,
            'interview_status' => AppConstants::INTERVIEW_STATUS['SCHEDULED'],
        ]);
    }

    public function test_cancel_interview_fails_with_invalid_interview_id(): void
    {
        $interview = Interview::factory()->create([
            'title' => 'Interview for position of Software Engineering role',
            'position_id' => $this->position->id,
            'interviewer_profile_id' => $this->interviewerProfile->id,
            'interviewee_profile_id' => $this->intervieweeProfile->id,
            'interview_status' => AppConstants::INTERVIEW_STATUS['SCHEDULED'],
        ]);

        $response = $this->putJson(route('interviews.cancelInterview', ['id' => 0]));

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonFragment([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Interview not found or access unauthorized.',
            ]);

        $this->assertDatabaseHas('interviews', [
            'id' => $interview->id,
            'position_id' => $this->position->id,
            'interviewer_profile_id' => $this->interviewerProfile->id,
            'interviewee_profile_id' => $this->intervieweeProfile->id,
            'interview_status' => AppConstants::INTERVIEW_STATUS['SCHEDULED'],
        ]);
    }
}
