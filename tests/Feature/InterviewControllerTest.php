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
            ->assertJsonFragment([
                'id' => $scheduledInterview->id,
                'position_id' => $this->position->id,
                'interviewer_profile_id' => $this->interviewerProfile->id,
                'interviewee_profile_id' => $this->intervieweeProfile->id,
            ]);
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
            ->assertJsonFragment([
                'id' => $completedInterview->id,
                'position_id' => $this->position->id,
                'interviewer_profile_id' => $this->interviewerProfile->id,
                'interviewee_profile_id' => $this->intervieweeProfile->id,
            ]);
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
            ->assertJsonFragment([
                'id' => $interview->id,
                'position_id' => $this->position->id,
                'interviewer_profile_id' => $this->interviewerProfile->id,
                'interviewee_profile_id' => $this->intervieweeProfile->id,
                'user_role' => 'interviewer'
            ]);
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

    public function test_org_admin_can_create_interview(): void
    {
        $response = $this->postJson(route('interviews.store'), [
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
            ->assertJsonFragment([
                'position_id' => $this->position->id,
                'interviewer_profile_id' => $this->interviewerProfile->id,
                'interviewee_profile_id' => $this->intervieweeProfile->id,
                'interview_status' => AppConstants::INTERVIEW_STATUS['SCHEDULED'],
                'interview_result' => AppConstants::INTERVIEW_RESULTS['PENDING'],
            ]);

        $this->assertDatabaseHas('interviews', [
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
            ->assertJsonFragment([
                'position_id' => $this->position->id,
                'scheduled_at' => now()->addDays(2)->toDateTimeString(),
                'interview_mode' => AppConstants::INTERVIEW_MODES[0],
                'interviewee_profile_id' => $this->intervieweeProfile->id,
            ]);

        $this->assertDatabaseHas('interviews', [
            'position_id' => $this->position->id,
            'scheduled_at' => now()->addDays(2)->toDateTimeString(),
            'interview_mode' => AppConstants::INTERVIEW_MODES[0],
            'interviewee_profile_id' => $this->intervieweeProfile->id,
        ]);
    }

    public function test_org_admin_can_create_on_site_interview_without_nullable_fields_except_location(): void
    {
        $response = $this->postJson(route('interviews.store'), [
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
            ->assertJsonFragment([
                'position_id' => $this->position->id,
                'scheduled_at' => now()->addDays(2)->toDateTimeString(),
                'interview_mode' => AppConstants::INTERVIEW_MODES[1],
                'interviewee_profile_id' => $this->intervieweeProfile->id,
                'location' => 'Block A'
            ]);

        $this->assertDatabaseHas('interviews', [
            'position_id' => $this->position->id,
            'scheduled_at' => now()->addDays(2)->toDateTimeString(),
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
            ->assertJsonFragment([
                'id' => $interview->id,
                'interview_mode' => AppConstants::INTERVIEW_MODES[1],
                'location' => 'HQ Room 3',
                'meeting_url' => null,
                'interview_result' => AppConstants::INTERVIEW_RESULTS['PASSED'],
                'recruiter_comment' => 'Updated interview note.',
            ]);

        $this->assertDatabaseHas('interviews', [
            'id' => $interview->id,
            'interview_mode' => AppConstants::INTERVIEW_MODES[1],
            'location' => 'HQ Room 3',
            'meeting_url' => null,
            'interview_result' => AppConstants::INTERVIEW_RESULTS['PASSED'],
            'recruiter_comment' => 'Updated interview note.',
        ]);
    }

    public function test_update_interview_fails_when_interview_is_completed(): void
    {
        $interview = Interview::factory()->create([
            'position_id' => $this->position->id,
            'interviewer_profile_id' => $this->interviewerProfile->id,
            'interviewee_profile_id' => $this->intervieweeProfile->id,
            'interview_status' => AppConstants::INTERVIEW_STATUS['COMPLETED'],
        ]);

        $response = $this->putJson(route('interviews.update', ['id' => $interview->id]), [
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
    }

    public function test_update_interview_fails_when_interview_is_cancelled(): void
    {
        $interview = Interview::factory()->create([
            'position_id' => $this->position->id,
            'interviewer_profile_id' => $this->interviewerProfile->id,
            'interviewee_profile_id' => $this->intervieweeProfile->id,
            'interview_status' => AppConstants::INTERVIEW_STATUS['CANCELLED'],
        ]);

        $response = $this->putJson(route('interviews.update', ['id' => $interview->id]), [
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
    }

    public function test_update_interview_fails_with_invalid_interview_id(): void
    {
        $interview = Interview::factory()->create([
            'position_id' => $this->position->id,
            'interviewer_profile_id' => $this->interviewerProfile->id,
            'interviewee_profile_id' => $this->intervieweeProfile->id,
            'interview_status' => AppConstants::INTERVIEW_STATUS['SCHEDULED'],
        ]);

        $response = $this->putJson(route('interviews.update', ['id' => 0]), [
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
    }

    public function test_org_admin_can_complete_scheduled_interview(): void
    {
        $interview = Interview::factory()->create([
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
            ->assertJsonFragment([
                'position_id' => $this->position->id,
                'interviewer_profile_id' => $this->interviewerProfile->id,
                'interviewee_profile_id' => $this->intervieweeProfile->id,
                'interview_status' => AppConstants::INTERVIEW_STATUS['COMPLETED'],
            ]);
    }

    public function test_org_admin_can_cancel_scheduled_interview(): void
    {
        $interview = Interview::factory()->create([
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
            ->assertJsonFragment([
                'position_id' => $this->position->id,
                'interviewer_profile_id' => $this->interviewerProfile->id,
                'interviewee_profile_id' => $this->intervieweeProfile->id,
                'interview_status' => AppConstants::INTERVIEW_STATUS['CANCELLED'],
            ]);
    }

    public function test_complete_interview_fails_when_interview_status_is_not_scheduled(): void
    {
        $interview = Interview::factory()->create([
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
    }

    public function test_cancel_interview_fails_when_interview_status_is_not_scheduled(): void
    {
        $interview = Interview::factory()->create([
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
    }

    public function test_complete_interview_fails_with_invalid_interview_id(): void
    {
        $interview = Interview::factory()->create([
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
    }

    public function test_cancel_interview_fails_with_invalid_interview_id(): void
    {
        $interview = Interview::factory()->create([
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
    }
}
