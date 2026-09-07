<?php

namespace Tests\Feature;

use App\Constants\AppConstants;
use App\Models\JobOffer;
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

class JobOfferControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private UserProfile $senderProfile;
    private UserProfile $receiverProfile;
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
        $this->senderProfile = UserProfile::factory()->create([
            'user_id' => $this->user->id,
        ]);
        $this->receiverProfile = UserProfile::factory()->create();
        $this->organization = Organization::factory()->create();

        OrganizationUser::create([
            'organization_id' => $this->organization->id,
            'user_profile_id' => $this->senderProfile->id,
            'role_id' => Role::where('name', 'Recruiter')->first()->id,
            'status' => 1,
        ]);

        $this->position = Position::factory()->create([
            'organization_id' => $this->organization->id,
            'user_profile_id' => $this->senderProfile->id,
        ]);

        $this->actingAs($this->user)->withSession([
            'user_profile_id' => $this->senderProfile->id,
            'roles' => ['Recruiter'],
        ]);
    }

    private function validJobOfferPayload(array $overrides = []): array
    {
        return array_merge([
            'position_id' => $this->position->id,
            'receiver_profile_id' => $this->receiverProfile->id,
            'salary_amount' => 5500.00,
            'salary_period' => AppConstants::SALARY_PERIODS['MONTHLY'],
            'start_date' => now()->addDays(14)->toDateString(),
            'end_date' => now()->addYear()->toDateString(),
            'terms_and_conditions' => 'Standard terms and conditions apply.',
            'benefits' => 'Medical, dental and annual leave.',
            'expires_at' => now()->addDays(7)->toDateTimeString(),
        ], $overrides);
    }

    public function test_sender_can_get_job_offers_by_status_and_sender_id(): void
    {
        $pendingOffer = JobOffer::factory()->create([
            'position_id' => $this->position->id,
            'sender_profile_id' => $this->senderProfile->id,
            'receiver_profile_id' => $this->receiverProfile->id,
            'offer_status' => AppConstants::JOB_OFFER_STATUS['ACCEPTED'],
        ]);

        JobOffer::factory()->create([
            'position_id' => $this->position->id,
            'sender_profile_id' => $this->senderProfile->id,
            'receiver_profile_id' => $this->receiverProfile->id,
            'offer_status' => AppConstants::JOB_OFFER_STATUS['REJECTED'],
        ]);

        $response = $this->getJson(route('jobOffers.getJobOffersByStatusAndSenderId', [
            'status' => AppConstants::JOB_OFFER_STATUS['ACCEPTED'],
        ]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'Success.',
            ])
            ->assertJsonFragment([
                'id' => $pendingOffer->id,
                'position_id' => $this->position->id,
                'sender_profile_id' => $this->senderProfile->id,
                'receiver_profile_id' => $this->receiverProfile->id,
            ]);

        $payload = $response->json();

        $this->assertEquals(1, count($payload['data']));
    }

    public function test_receiver_can_get_job_offers_by_status_and_receiver_id(): void
    {
        $acceptedOffer = JobOffer::factory()->create([
            'position_id' => $this->position->id,
            'sender_profile_id' => $this->senderProfile->id,
            'receiver_profile_id' => $this->receiverProfile->id,
            'offer_status' => AppConstants::JOB_OFFER_STATUS['ACCEPTED'],
        ]);

        JobOffer::factory()->create([
            'position_id' => $this->position->id,
            'sender_profile_id' => $this->senderProfile->id,
            'receiver_profile_id' => $this->receiverProfile->id,
            'offer_status' => AppConstants::JOB_OFFER_STATUS['REJECTED'],
        ]);

        $this->withSession(['user_profile_id' => $this->receiverProfile->id]);

        $response = $this->getJson(route('jobOffers.getJobOffersByStatusAndReceiverId', [
            'status' => AppConstants::JOB_OFFER_STATUS['ACCEPTED'],
        ]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'Success.',
            ])
            ->assertJsonFragment([
                'id' => $acceptedOffer->id,
                'position_id' => $this->position->id,
                'sender_profile_id' => $this->senderProfile->id,
                'receiver_profile_id' => $this->receiverProfile->id,
            ]);

        $payload = $response->json();

        $this->assertEquals(1, count($payload['data']));
    }

    public function test_user_can_get_job_offer_by_id_when_they_are_the_sender(): void
    {
        $jobOffer = JobOffer::factory()->create([
            'position_id' => $this->position->id,
            'sender_profile_id' => $this->senderProfile->id,
            'receiver_profile_id' => $this->receiverProfile->id,
            'offer_status' => AppConstants::JOB_OFFER_STATUS['PENDING'],
        ]);

        $response = $this->getJson(route('jobOffers.getJobOfferById', ['id' => $jobOffer->id]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'Success.',
            ])
            ->assertJsonFragment([
                'id' => $jobOffer->id,
                'position_id' => $this->position->id,
                'sender_profile_id' => $this->senderProfile->id,
                'receiver_profile_id' => $this->receiverProfile->id,
                'user_role' => 'sender',
            ]);
    }

    public function test_user_can_get_job_offer_by_id_when_they_are_the_receiver(): void
    {
        $jobOffer = JobOffer::factory()->create([
            'position_id' => $this->position->id,
            'sender_profile_id' => $this->senderProfile->id,
            'receiver_profile_id' => $this->receiverProfile->id,
            'offer_status' => AppConstants::JOB_OFFER_STATUS['PENDING'],
        ]);

        $this->withSession(['user_profile_id' => $this->receiverProfile->id]);

        $response = $this->getJson(route('jobOffers.getJobOfferById', ['id' => $jobOffer->id]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'Success.',
            ])
            ->assertJsonFragment([
                'id' => $jobOffer->id,
                'position_id' => $this->position->id,
                'sender_profile_id' => $this->senderProfile->id,
                'receiver_profile_id' => $this->receiverProfile->id,
                'user_role' => 'receiver',
            ]);
    }

    public function test_get_job_offer_by_id_returns_not_found_when_not_part_of_offer(): void
    {
        $jobOffer = JobOffer::factory()->create([
            'position_id' => $this->position->id,
            'sender_profile_id' => $this->senderProfile->id,
            'receiver_profile_id' => $this->receiverProfile->id,
            'offer_status' => AppConstants::JOB_OFFER_STATUS['PENDING'],
        ]);

        $otherProfile = UserProfile::factory()->create();
        $this->withSession(['user_profile_id' => $otherProfile->id]);

        $response = $this->getJson(route('jobOffers.getJobOfferById', ['id' => $jobOffer->id]));

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonFragment([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Job offer not found or access unauthorized on job offer.',
            ]);
    }

    public function test_get_job_offer_by_id_returns_not_found_when_id_invalid(): void
    {
        JobOffer::factory()->create([
            'position_id' => $this->position->id,
            'sender_profile_id' => $this->senderProfile->id,
            'receiver_profile_id' => $this->receiverProfile->id,
            'offer_status' => AppConstants::JOB_OFFER_STATUS['PENDING'],
        ]);

        $response = $this->getJson(route('jobOffers.getJobOfferById', ['id' => 0]));

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonFragment([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Job offer not found or access unauthorized on job offer.',
            ]);
    }

    public function test_org_admin_can_create_job_offer(): void
    {
        $response = $this->postJson(route('jobOffers.store'), $this->validJobOfferPayload());

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonFragment([
                'status' => Response::HTTP_CREATED,
                'message' => 'Job offer created successfully.',
            ])
            ->assertJsonFragment([
                'position_id' => $this->position->id,
                'receiver_profile_id' => $this->receiverProfile->id,
                'salary_period' => AppConstants::SALARY_PERIODS['MONTHLY'],
            ]);

        $this->assertDatabaseHas('job_offers', [
            'position_id' => $this->position->id,
            'sender_profile_id' => $this->senderProfile->id,
            'receiver_profile_id' => $this->receiverProfile->id,
            'offer_status' => AppConstants::JOB_OFFER_STATUS['PENDING'],
            'salary_period' => AppConstants::SALARY_PERIODS['MONTHLY'],
        ]);
    }

    public function test_create_job_offer_fails_without_required_fields(): void
    {
        $response = $this->postJson(route('jobOffers.store'));

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'status' => Response::HTTP_BAD_REQUEST,
            ]);
    }

    public function test_create_job_offer_fails_with_expires_at_in_the_past(): void
    {
        $response = $this->postJson(route('jobOffers.store'), $this->validJobOfferPayload([
            'expires_at' => now()->subDay()->toDateTimeString(),
        ]));

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'status' => Response::HTTP_BAD_REQUEST,
            ]);
    }

    public function test_create_job_offer_fails_when_end_date_before_start_date(): void
    {
        $response = $this->postJson(route('jobOffers.store'), $this->validJobOfferPayload([
            'start_date' => now()->addDays(30)->toDateString(),
            'end_date' => now()->addDays(10)->toDateString(),
        ]));

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'status' => Response::HTTP_BAD_REQUEST,
            ]);
    }

    public function test_create_job_offer_fails_when_user_is_not_organization_admin(): void
    {
        $nonAdminUser = User::factory()->create();
        $nonAdminProfile = UserProfile::factory()->create([
            'user_id' => $nonAdminUser->id
        ]);

        $position = Position::factory()->create([
            'organization_id' => $this->organization->id,
            'user_profile_id' => $nonAdminProfile->id,
        ]);

        $this->actingAs($nonAdminUser)->withSession([
            'user_profile_id' => $nonAdminProfile->id,
            'roles' => ['Recruiter'],
        ]);

        $response = $this->postJson(route('jobOffers.store'), $this->validJobOfferPayload([
            'position_id' => $position->id,
        ]));

        $response->assertStatus(Response::HTTP_FORBIDDEN)
            ->assertJsonFragment([
                'status' => Response::HTTP_FORBIDDEN,
                'message' => 'Unauthorized access to create job offer.',
            ]);
    }

    public function test_org_admin_can_update_job_offer(): void
    {
        $jobOffer = JobOffer::factory()->create([
            'position_id' => $this->position->id,
            'sender_profile_id' => $this->senderProfile->id,
            'receiver_profile_id' => $this->receiverProfile->id,
            'salary_amount' => 4000.00,
            'salary_period' => 'monthly',
            'offer_status' => AppConstants::JOB_OFFER_STATUS['PENDING'],
        ]);

        $response = $this->putJson(route('jobOffers.update', ['id' => $jobOffer->id]), $this->validJobOfferPayload([
            'salary_amount' => 6000.00,
            'terms_and_conditions' => 'Updated terms.',
        ]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'Job offer updated successfully.',
            ])
            ->assertJsonFragment([
                'id' => $jobOffer->id,
                'salary_amount' => 6000,
                'terms_and_conditions' => 'Updated terms.',
            ]);

        $this->assertDatabaseHas('job_offers', [
            'id' => $jobOffer->id,
            'salary_amount' => 6000,
            'terms_and_conditions' => 'Updated terms.',
        ]);
    }

    public function test_update_job_offer_fails_when_offer_is_accepted(): void
    {
        $jobOffer = JobOffer::factory()->create([
            'position_id' => $this->position->id,
            'sender_profile_id' => $this->senderProfile->id,
            'receiver_profile_id' => $this->receiverProfile->id,
            'offer_status' => AppConstants::JOB_OFFER_STATUS['ACCEPTED'],
        ]);

        $response = $this->putJson(route('jobOffers.update', ['id' => $jobOffer->id]), $this->validJobOfferPayload());

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment([
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'message' => 'Job offer accepted, rejected or withdrawn cannot be updated anymore.',
            ]);
    }

    public function test_update_job_offer_fails_when_offer_is_rejected(): void
    {
        $jobOffer = JobOffer::factory()->create([
            'position_id' => $this->position->id,
            'sender_profile_id' => $this->senderProfile->id,
            'receiver_profile_id' => $this->receiverProfile->id,
            'offer_status' => AppConstants::JOB_OFFER_STATUS['REJECTED'],
        ]);

        $response = $this->putJson(route('jobOffers.update', ['id' => $jobOffer->id]), $this->validJobOfferPayload());

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment([
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'message' => 'Job offer accepted, rejected or withdrawn cannot be updated anymore.',
            ]);
    }

    public function test_update_job_offer_fails_when_offer_is_withdrawn(): void
    {
        $jobOffer = JobOffer::factory()->create([
            'position_id' => $this->position->id,
            'sender_profile_id' => $this->senderProfile->id,
            'receiver_profile_id' => $this->receiverProfile->id,
            'offer_status' => AppConstants::JOB_OFFER_STATUS['WITHDRAWN'],
        ]);

        $response = $this->putJson(route('jobOffers.update', ['id' => $jobOffer->id]), $this->validJobOfferPayload());

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment([
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'message' => 'Job offer accepted, rejected or withdrawn cannot be updated anymore.',
            ]);
    }

    public function test_update_job_offer_fails_with_invalid_job_offer_id(): void
    {
        JobOffer::factory()->create([
            'position_id' => $this->position->id,
            'sender_profile_id' => $this->senderProfile->id,
            'receiver_profile_id' => $this->receiverProfile->id,
            'offer_status' => AppConstants::JOB_OFFER_STATUS['PENDING'],
        ]);

        $response = $this->putJson(route('jobOffers.update', ['id' => 0]), $this->validJobOfferPayload());

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonFragment([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Job offer not found or access unauthorized.',
            ]);
    }

    public function test_update_job_offer_fails_with_invalid_salary_period(): void
    {
        $jobOffer = JobOffer::factory()->create([
            'position_id' => $this->position->id,
            'sender_profile_id' => $this->senderProfile->id,
            'receiver_profile_id' => $this->receiverProfile->id,
            'offer_status' => AppConstants::JOB_OFFER_STATUS['PENDING'],
        ]);

        $response = $this->putJson(route('jobOffers.update', ['id' => $jobOffer->id]), $this->validJobOfferPayload([
            'salary_period' => 'Invalid salary period'
        ]));

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'status' => Response::HTTP_BAD_REQUEST,
            ]);

        $this->assertStringContainsString('salary period', $response->json('message'));
    }

    public function test_receiver_can_accept_pending_job_offer(): void
    {
        $jobOffer = JobOffer::factory()->create([
            'position_id' => $this->position->id,
            'sender_profile_id' => $this->senderProfile->id,
            'receiver_profile_id' => $this->receiverProfile->id,
            'offer_status' => AppConstants::JOB_OFFER_STATUS['PENDING'],
        ]);

        $this->withSession(['user_profile_id' => $this->receiverProfile->id]);

        $response = $this->putJson(route('jobOffers.acceptJobOffer', ['id' => $jobOffer->id]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'Job offer accepted.',
            ])
            ->assertJsonFragment([
                'id' => $jobOffer->id,
                'offer_status' => AppConstants::JOB_OFFER_STATUS['ACCEPTED'],
            ]);

        $this->assertDatabaseHas('job_offers', [
            'id' => $jobOffer->id,
            'offer_status' => AppConstants::JOB_OFFER_STATUS['ACCEPTED'],
        ]);
    }

    public function test_receiver_can_reject_pending_job_offer(): void
    {
        $jobOffer = JobOffer::factory()->create([
            'position_id' => $this->position->id,
            'sender_profile_id' => $this->senderProfile->id,
            'receiver_profile_id' => $this->receiverProfile->id,
            'offer_status' => AppConstants::JOB_OFFER_STATUS['PENDING'],
        ]);

        $this->withSession(['user_profile_id' => $this->receiverProfile->id]);

        $response = $this->putJson(route('jobOffers.rejectJobOffer', ['id' => $jobOffer->id]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'Job offer rejected.',
            ])
            ->assertJsonFragment([
                'id' => $jobOffer->id,
                'offer_status' => AppConstants::JOB_OFFER_STATUS['REJECTED'],
            ]);

        $this->assertDatabaseHas('job_offers', [
            'id' => $jobOffer->id,
            'offer_status' => AppConstants::JOB_OFFER_STATUS['REJECTED'],
        ]);
    }

    public function test_sender_can_withdraw_pending_job_offer(): void
    {
        $jobOffer = JobOffer::factory()->create([
            'position_id' => $this->position->id,
            'sender_profile_id' => $this->senderProfile->id,
            'receiver_profile_id' => $this->receiverProfile->id,
            'offer_status' => AppConstants::JOB_OFFER_STATUS['PENDING'],
        ]);

        $response = $this->putJson(route('jobOffers.withdrawJobOffer', ['id' => $jobOffer->id]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'Job offer withdrawn.',
            ])
            ->assertJsonFragment([
                'id' => $jobOffer->id,
                'offer_status' => AppConstants::JOB_OFFER_STATUS['WITHDRAWN'],
            ]);

        $this->assertDatabaseHas('job_offers', [
            'id' => $jobOffer->id,
            'offer_status' => AppConstants::JOB_OFFER_STATUS['WITHDRAWN'],
        ]);
    }

    public function test_accept_job_offer_fails_when_status_is_not_pending(): void
    {
        $jobOffer = JobOffer::factory()->create([
            'position_id' => $this->position->id,
            'sender_profile_id' => $this->senderProfile->id,
            'receiver_profile_id' => $this->receiverProfile->id,
            'offer_status' => AppConstants::JOB_OFFER_STATUS['WITHDRAWN'],
        ]);

        $this->withSession(['user_profile_id' => $this->receiverProfile->id]);

        $response = $this->putJson(route('jobOffers.acceptJobOffer', ['id' => $jobOffer->id]));

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment([
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'message' => 'Job offer accepted, rejected or withdrawn cannot be updated anymore.',
            ]);
    }

    public function test_reject_job_offer_fails_when_status_is_not_pending(): void
    {
        $jobOffer = JobOffer::factory()->create([
            'position_id' => $this->position->id,
            'sender_profile_id' => $this->senderProfile->id,
            'receiver_profile_id' => $this->receiverProfile->id,
            'offer_status' => AppConstants::JOB_OFFER_STATUS['ACCEPTED'],
        ]);

        $this->withSession(['user_profile_id' => $this->receiverProfile->id]);

        $response = $this->putJson(route('jobOffers.rejectJobOffer', ['id' => $jobOffer->id]));

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment([
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'message' => 'Job offer accepted, rejected or withdrawn cannot be updated anymore.',
            ]);
    }

    public function test_withdraw_job_offer_fails_when_status_is_not_pending(): void
    {
        $jobOffer = JobOffer::factory()->create([
            'position_id' => $this->position->id,
            'sender_profile_id' => $this->senderProfile->id,
            'receiver_profile_id' => $this->receiverProfile->id,
            'offer_status' => AppConstants::JOB_OFFER_STATUS['REJECTED'],
        ]);

        $response = $this->putJson(route('jobOffers.withdrawJobOffer', ['id' => $jobOffer->id]));

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment([
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'message' => 'Job offer accepted, rejected or withdrawn cannot be updated anymore.',
            ]);
    }

    public function test_accept_job_offer_fails_with_invalid_job_offer_id(): void
    {
        JobOffer::factory()->create([
            'position_id' => $this->position->id,
            'sender_profile_id' => $this->senderProfile->id,
            'receiver_profile_id' => $this->receiverProfile->id,
            'offer_status' => AppConstants::JOB_OFFER_STATUS['PENDING'],
        ]);

        $this->withSession(['user_profile_id' => $this->receiverProfile->id]);

        $response = $this->putJson(route('jobOffers.acceptJobOffer', ['id' => 0]));

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonFragment([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Job offer not found or access unauthorized.',
            ]);
    }

    public function test_reject_job_offer_fails_with_invalid_job_offer_id(): void
    {
        JobOffer::factory()->create([
            'position_id' => $this->position->id,
            'sender_profile_id' => $this->senderProfile->id,
            'receiver_profile_id' => $this->receiverProfile->id,
            'offer_status' => AppConstants::JOB_OFFER_STATUS['PENDING'],
        ]);

        $this->withSession(['user_profile_id' => $this->receiverProfile->id]);

        $response = $this->putJson(route('jobOffers.rejectJobOffer', ['id' => 0]));

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonFragment([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Job offer not found or access unauthorized.',
            ]);
    }

    public function test_withdraw_job_offer_fails_with_invalid_job_offer_id(): void
    {
        JobOffer::factory()->create([
            'position_id' => $this->position->id,
            'sender_profile_id' => $this->senderProfile->id,
            'receiver_profile_id' => $this->receiverProfile->id,
            'offer_status' => AppConstants::JOB_OFFER_STATUS['PENDING'],
        ]);

        $response = $this->putJson(route('jobOffers.withdrawJobOffer', ['id' => 0]));

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonFragment([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Job offer not found or access unauthorized.',
            ]);
    }
}