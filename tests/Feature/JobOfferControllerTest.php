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

    private const ORGANIZATION_RETURN_COLUMNS = ['id', 'company_name', 'organization_logo'];
    private const PROFILE_RETURN_COLUMNS = ['id', 'name', 'profile_image', 'location', 'headline'];
    private const POSITION_RETURN_COLUMNS = ['id', 'position_title', 'organization_id'];

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
            ])
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'position_id',
                        'sender_profile_id',
                        'receiver_profile_id',
                        'salary_amount',
                        'salary_period',
                        'start_date',
                        'end_date',
                        'terms_and_conditions',
                        'benefits',
                        'offer_status',
                        'created_at',
                        'updated_at',
                        'expires_at',
                        'position' => [
                            ...self::POSITION_RETURN_COLUMNS,
                            'organization' => self::ORGANIZATION_RETURN_COLUMNS,
                        ],
                        'receiver' => self::PROFILE_RETURN_COLUMNS,
                    ]
                ],
            ])
            ->assertJsonPath('data.0.id', $pendingOffer->id)
            ->assertJsonPath('data.0.position_id', $this->position->id)
            ->assertJsonPath('data.0.sender_profile_id', $this->senderProfile->id)
            ->assertJsonPath('data.0.receiver_profile_id', $this->receiverProfile->id)
            ->assertJsonPath('data.0.offer_status', AppConstants::JOB_OFFER_STATUS['ACCEPTED'])
            ->assertJsonPath('data.0.position.id', $this->position->id)
            ->assertJsonPath('data.0.position.organization.id', $this->organization->id)
            ->assertJsonPath('data.0.receiver.id', $this->receiverProfile->id);

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
            ])
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'position_id',
                        'sender_profile_id',
                        'receiver_profile_id',
                        'salary_amount',
                        'salary_period',
                        'start_date',
                        'end_date',
                        'terms_and_conditions',
                        'benefits',
                        'offer_status',
                        'created_at',
                        'updated_at',
                        'expires_at',
                        'position' => [
                            ...self::POSITION_RETURN_COLUMNS,
                            'organization' => self::ORGANIZATION_RETURN_COLUMNS,
                        ],
                        'sender' => self::PROFILE_RETURN_COLUMNS,
                    ]
                ],
            ])
            ->assertJsonPath('data.0.id', $acceptedOffer->id)
            ->assertJsonPath('data.0.position_id', $this->position->id)
            ->assertJsonPath('data.0.sender_profile_id', $this->senderProfile->id)
            ->assertJsonPath('data.0.receiver_profile_id', $this->receiverProfile->id)
            ->assertJsonPath('data.0.offer_status', AppConstants::JOB_OFFER_STATUS['ACCEPTED'])
            ->assertJsonPath('data.0.position.id', $this->position->id)
            ->assertJsonPath('data.0.position.organization.id', $this->organization->id)
            ->assertJsonPath('data.0.sender.id', $this->senderProfile->id);

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
            ])
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'position_id',
                    'sender_profile_id',
                    'receiver_profile_id',
                    'salary_amount',
                    'salary_period',
                    'start_date',
                    'end_date',
                    'terms_and_conditions',
                    'benefits',
                    'offer_status',
                    'created_at',
                    'updated_at',
                    'expires_at',
                    'user_role',
                    'position' => [
                        'id',
                        'position_title',
                        'organization_id',
                        'organization' => self::ORGANIZATION_RETURN_COLUMNS,
                    ],
                    'sender' => self::PROFILE_RETURN_COLUMNS,
                    'receiver' => self::PROFILE_RETURN_COLUMNS,
                ],
            ])
            ->assertJsonPath('data.id', $jobOffer->id)
            ->assertJsonPath('data.sender_profile_id', $this->senderProfile->id)
            ->assertJsonPath('data.receiver_profile_id', $this->receiverProfile->id)
            ->assertJsonPath('data.user_role', 'sender')
            ->assertJsonPath('data.position.id', $this->position->id)
            ->assertJsonPath('data.position.organization.id', $this->organization->id)
            ->assertJsonPath('data.sender.id', $this->senderProfile->id)
            ->assertJsonPath('data.receiver.id', $this->receiverProfile->id);
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
            ])
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'position_id',
                    'sender_profile_id',
                    'receiver_profile_id',
                    'salary_amount',
                    'salary_period',
                    'start_date',
                    'end_date',
                    'terms_and_conditions',
                    'benefits',
                    'offer_status',
                    'created_at',
                    'updated_at',
                    'expires_at',
                    'user_role',
                    'position' => [
                        'id',
                        'position_title',
                        'organization_id',
                        'organization' => self::ORGANIZATION_RETURN_COLUMNS,
                    ],
                    'sender' => self::PROFILE_RETURN_COLUMNS,
                    'receiver' => self::PROFILE_RETURN_COLUMNS,
                ],
            ])
            ->assertJsonPath('data.id', $jobOffer->id)
            ->assertJsonPath('data.sender_profile_id', $this->senderProfile->id)
            ->assertJsonPath('data.receiver_profile_id', $this->receiverProfile->id)
            ->assertJsonPath('data.user_role', 'receiver')
            ->assertJsonPath('data.position.id', $this->position->id)
            ->assertJsonPath('data.position.organization.id', $this->organization->id)
            ->assertJsonPath('data.sender.id', $this->senderProfile->id)
            ->assertJsonPath('data.receiver.id', $this->receiverProfile->id);
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
            ])
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'position_id',
                    'sender_profile_id',
                    'receiver_profile_id',
                    'salary_amount',
                    'salary_period',
                    'start_date',
                    'end_date',
                    'terms_and_conditions',
                    'benefits',
                    'offer_status',
                    'created_at',
                    'updated_at',
                    'expires_at',
                    'position' => [
                        ...self::POSITION_RETURN_COLUMNS,
                    ],
                    'receiver' => self::PROFILE_RETURN_COLUMNS,
                ],
            ])
            ->assertJsonPath('data.position_id', $this->position->id)
            ->assertJsonPath('data.sender_profile_id', $this->senderProfile->id)
            ->assertJsonPath('data.receiver_profile_id', $this->receiverProfile->id)
            ->assertJsonPath('data.salary_period', AppConstants::SALARY_PERIODS['MONTHLY'])
            ->assertJsonPath('data.offer_status', AppConstants::JOB_OFFER_STATUS['PENDING'])
            ->assertJsonPath('data.position.id', $this->position->id)
            ->assertJsonPath('data.receiver.id', $this->receiverProfile->id);

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

        $this->assertDatabaseEmpty('job_offers');
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

        $this->assertDatabaseEmpty('job_offers');
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

        $this->assertDatabaseEmpty('job_offers');
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

        $this->assertDatabaseEmpty('job_offers');
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
            ])
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'position_id',
                    'sender_profile_id',
                    'receiver_profile_id',
                    'salary_amount',
                    'salary_period',
                    'start_date',
                    'end_date',
                    'terms_and_conditions',
                    'benefits',
                    'offer_status',
                    'created_at',
                    'updated_at',
                    'expires_at',
                ],
            ])
            ->assertJsonPath('data.id', $jobOffer->id)
            ->assertJsonPath('data.position_id', $this->position->id)
            ->assertJsonPath('data.sender_profile_id', $this->senderProfile->id)
            ->assertJsonPath('data.receiver_profile_id', $this->receiverProfile->id)
            ->assertJsonPath('data.salary_amount', 6000)
            ->assertJsonPath('data.terms_and_conditions', 'Updated terms.');

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
            ])
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'position_id',
                    'sender_profile_id',
                    'receiver_profile_id',
                    'salary_amount',
                    'salary_period',
                    'start_date',
                    'end_date',
                    'terms_and_conditions',
                    'benefits',
                    'offer_status',
                    'created_at',
                    'updated_at',
                    'expires_at',
                ],
            ])
            ->assertJsonPath('data.id', $jobOffer->id)
            ->assertJsonPath('data.receiver_profile_id', $this->receiverProfile->id)
            ->assertJsonPath('data.offer_status', AppConstants::JOB_OFFER_STATUS['ACCEPTED']);

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
            ])
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'position_id',
                    'sender_profile_id',
                    'receiver_profile_id',
                    'salary_amount',
                    'salary_period',
                    'start_date',
                    'end_date',
                    'terms_and_conditions',
                    'benefits',
                    'offer_status',
                    'created_at',
                    'updated_at',
                    'expires_at',
                ],
            ])
            ->assertJsonPath('data.id', $jobOffer->id)
            ->assertJsonPath('data.receiver_profile_id', $this->receiverProfile->id)
            ->assertJsonPath('data.offer_status', AppConstants::JOB_OFFER_STATUS['REJECTED']);

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
            ])
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'position_id',
                    'sender_profile_id',
                    'receiver_profile_id',
                    'salary_amount',
                    'salary_period',
                    'start_date',
                    'end_date',
                    'terms_and_conditions',
                    'benefits',
                    'offer_status',
                    'created_at',
                    'updated_at',
                    'expires_at',
                ],
            ])
            ->assertJsonPath('data.id', $jobOffer->id)
            ->assertJsonPath('data.sender_profile_id', $this->senderProfile->id)
            ->assertJsonPath('data.offer_status', AppConstants::JOB_OFFER_STATUS['WITHDRAWN']);

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

        $this->assertDatabaseHas('job_offers', [
            'id' => $jobOffer->id,
            'offer_status' => AppConstants::JOB_OFFER_STATUS['WITHDRAWN'],
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

        $this->assertDatabaseHas('job_offers', [
            'id' => $jobOffer->id,
            'offer_status' => AppConstants::JOB_OFFER_STATUS['ACCEPTED'],
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

        $this->assertDatabaseHas('job_offers', [
            'id' => $jobOffer->id,
            'offer_status' => AppConstants::JOB_OFFER_STATUS['REJECTED'],
        ]);
    }

    public function test_accept_job_offer_fails_with_invalid_job_offer_id(): void
    {
        $jobOffer = JobOffer::factory()->create([
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

        $this->assertDatabaseHas('job_offers', [
            'id' => $jobOffer->id,
            'offer_status' => AppConstants::JOB_OFFER_STATUS['PENDING'],
        ]);
    }

    public function test_reject_job_offer_fails_with_invalid_job_offer_id(): void
    {
        $jobOffer = JobOffer::factory()->create([
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

        $this->assertDatabaseHas('job_offers', [
            'id' => $jobOffer->id,
            'offer_status' => AppConstants::JOB_OFFER_STATUS['PENDING'],
        ]);
    }

    public function test_withdraw_job_offer_fails_with_invalid_job_offer_id(): void
    {
        $jobOffer = JobOffer::factory()->create([
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

        $this->assertDatabaseHas('job_offers', [
            'id' => $jobOffer->id,
            'offer_status' => AppConstants::JOB_OFFER_STATUS['PENDING'],
        ]);
    }
}