<?php

namespace Tests\Feature;

use App\Constants\AppConstants;
use App\Models\Invitation;
use App\Models\Organization;
use App\Models\OrganizationUser;
use App\Models\Position;
use App\Models\Role;
use App\Models\User;
use App\Models\UserProfile;
use Database\Seeders\IndustryCategorySeeder;
use Database\Seeders\IndustrySectorSeeder;
use Database\Seeders\OrganizationSeeder;
use Database\Seeders\OrganizationTypeSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\ResponseSequence;
use Illuminate\Http\Response;
use Override;
use Tests\TestCase;

class InvitationControllerTest extends TestCase
{
    use RefreshDatabase;

    private const INVITATION_RETURN_COLUMNS = [
        'id',
        'sender_profile_id',
        'receiver_profile_id',
        'invitation_message',
        'invitation_status',
        'created_at',
        'updated_at',
        'expires_at',
        'position_id',
    ];
    private const PROFILE_RETURN_COLUMNS = ['id', 'name', 'profile_image', 'location', 'headline'];
    private const ORGANIZATION_RETURN_COLUMNS = ['id', 'company_name', 'organization_logo'];
    private const POSITION_RETURN_COLUMNS = ['id', 'position_title', 'organization_id'];

    private User $user;
    private UserProfile $senderProfile;
    private UserProfile $receiverProfile;
    private Position $position;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(OrganizationTypeSeeder::class);
        $this->seed(IndustryCategorySeeder::class);
        $this->seed(IndustrySectorSeeder::class);
        $this->seed(OrganizationSeeder::class);
        $this->seed(RoleSeeder::class);

        $this->user = User::factory()->create();
        $this->senderProfile = UserProfile::factory()->create([
            'user_id' => $this->user->id,
            'email' => 'sender@example.com',
        ]);
        $this->receiverProfile = UserProfile::factory()->create([
            'user_id' => $this->user->id,
            'email' => 'receiver@example.com',
        ]);
        $this->position = Position::factory()->create();

        OrganizationUser::factory()->create([
            'user_profile_id' => $this->senderProfile->id,
            'organization_id' => $this->position->organization_id,
            'role_id' => Role::where('name', 'Recruiter')->first()->id
        ]);

        OrganizationUser::factory()->create([
            'user_profile_id' => $this->receiverProfile->id,
            'organization_id' => $this->position->organization_id,
            'role_id' => Role::where('name', 'Student')->first()->id
        ]);

        $this->actingAs($this->user)
            ->withSession([
                'user_profile_id' => $this->senderProfile->id,
                'roles' => ['Recruiter']
            ]);
    }

    private function validInvitationPayload(array $overrides = []): array
    {
        return array_merge([
            'sender_profile_id' => $this->senderProfile->id,
            'receiver_profile_id' => $this->receiverProfile->id,
            'invitation_message' => 'We would love to have you join our organization as part of our growing engineering team.',
            'invitation_status' => AppConstants::INVITATION_STATUS['PENDING'],
            'expires_at' => now()->addDays(7)->toDateTimeString(),
            'position_id' => $this->position->id,
        ], $overrides);
    }

    public function test_receiver_can_get_invitation_by_id(): void
    {
        $this->withSession([
            'user_profile_id' => $this->receiverProfile->id
        ]);

        $invitation = Invitation::create($this->validInvitationPayload());

        Invitation::create($this->validInvitationPayload([
            'invitation_message' => 'Test message.',
        ]));

        $response = $this->getJson(route('invitations.getInvitationById', ['id' => $invitation->id]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'Success.'
            ])
            ->assertJsonStructure([
                'data' => [
                    ...self::INVITATION_RETURN_COLUMNS,
                    'user_role',
                    'position' => [
                        'id',
                        'organization_id',
                        'user_profile_id',
                        'position_title',
                        'employment_type',
                        'department',
                        'work_location',
                        'vacancies',
                        'description',
                        'created_at',
                        'updated_at',
                        'organization' => self::ORGANIZATION_RETURN_COLUMNS
                    ],
                    'receiver' => self::PROFILE_RETURN_COLUMNS,
                    'sender' => self::PROFILE_RETURN_COLUMNS,
                ]
            ])
            ->assertJsonPath('data.id', $invitation->id)
            ->assertJsonPath('data.sender.id', $this->senderProfile->id)
            ->assertJsonPath('data.receiver.id', $this->receiverProfile->id)
            ->assertJsonPath('data.position.id', $this->position->id)
            ->assertJsonPath('data.user_role', 'receiver');
    }

    public function test_sender_can_get_invitation_by_id(): void
    {
        $invitation = Invitation::create($this->validInvitationPayload());

        Invitation::create($this->validInvitationPayload([
            'invitation_message' => 'Test message.',
        ]));

        $response = $this->getJson(route('invitations.getInvitationById', ['id' => $invitation->id]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'Success.'
            ])
            ->assertJsonStructure([
                'data' => [
                    ...self::INVITATION_RETURN_COLUMNS,
                    'user_role',
                    'position' => [
                        'id',
                        'organization_id',
                        'user_profile_id',
                        'position_title',
                        'employment_type',
                        'department',
                        'work_location',
                        'vacancies',
                        'description',
                        'created_at',
                        'updated_at',
                        'organization' => self::ORGANIZATION_RETURN_COLUMNS
                    ],
                    'receiver' => self::PROFILE_RETURN_COLUMNS,
                    'sender' => self::PROFILE_RETURN_COLUMNS,
                ]
            ])
            ->assertJsonPath('data.id', $invitation->id)
            ->assertJsonPath('data.sender.id', $this->senderProfile->id)
            ->assertJsonPath('data.receiver.id', $this->receiverProfile->id)
            ->assertJsonPath('data.position.id', $this->position->id)
            ->assertJsonPath('data.user_role', 'sender');
    }

    public function test_get_invitation_by_id_fails_with_non_existing_invitation_id(): void
    {
        Invitation::create($this->validInvitationPayload());

        Invitation::create($this->validInvitationPayload([
            'invitation_message' => 'Test message.',
        ]));

        $response = $this->getJson(route('invitations.getInvitationById', ['id' => 0]));

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonFragment([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Invitation not found or access unauthorized.'
            ])
            ->assertJsonPath('data', null);
    }

    public function test_sender_get_invitation_by_id_fails_when_user_not_part_of_invitation(): void
    {
        $otherSenderProfile = UserProfile::factory()->create();
        $otherReceiverProfile = UserProfile::factory()->create();

        $invitation = Invitation::create($this->validInvitationPayload([
            'sender_profile_id' => $otherSenderProfile->id,
            'receiver_profile_id' => $otherReceiverProfile->id,
        ]));

        $response = $this->getJson(route('invitations.getInvitationById', ['id' => $invitation->id]));

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonFragment([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Invitation not found or access unauthorized.'
            ])
            ->assertJsonPath('data', null);
    }

    public function test_receiver_get_invitation_by_id_fails_when_user_not_part_of_invitation(): void
    {
        $this->withSession([
            'user_profile_id' => $this->receiverProfile->id
        ]);

        $otherSenderProfile = UserProfile::factory()->create();
        $otherReceiverProfile = UserProfile::factory()->create();

        $invitation = Invitation::create($this->validInvitationPayload([
            'sender_profile_id' => $otherSenderProfile->id,
            'receiver_profile_id' => $otherReceiverProfile->id,
        ]));

        $response = $this->getJson(route('invitations.getInvitationById', ['id' => $invitation->id]));

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonFragment([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Invitation not found or access unauthorized.'
            ])
            ->assertJsonPath('data', null);
    }

    public function test_receiver_can_get_invitations_by_status(): void
    {
        $this->withSession([
            'user_profile_id' => $this->receiverProfile->id
        ]);

        $invitation = Invitation::create($this->validInvitationPayload([
            'invitation_status' => AppConstants::INVITATION_STATUS['PENDING']
        ]));

        Invitation::factory()->count(2)->create($this->validInvitationPayload([
            'invitation_status' => AppConstants::INVITATION_STATUS['WITHDRAWN']
        ]));

        $response = $this->getJson(route('invitations.getInvitationsByStatusAndReceiverId', ['status' => AppConstants::INVITATION_STATUS['PENDING']]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'Success.'
            ])
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        ...self::INVITATION_RETURN_COLUMNS,
                        'position' => [
                            ...self::POSITION_RETURN_COLUMNS,
                            'organization' => self::ORGANIZATION_RETURN_COLUMNS
                        ],
                        'sender' => self::PROFILE_RETURN_COLUMNS,
                    ]
                ]
            ])
            ->assertJsonPath('data.0.id', $invitation->id)
            ->assertJsonPath('data.0.receiver_profile_id', $this->receiverProfile->id)
            ->assertJsonPath('data.0.sender.id', $this->senderProfile->id)
            ->assertJsonPath('data.0.position.id', $this->position->id)
            ->assertJsonPath('data.0.invitation_status', AppConstants::INVITATION_STATUS['PENDING']);
    }

    public function test_sender_can_get_invitations_by_status(): void
    {
        $invitation = Invitation::create($this->validInvitationPayload([
            'invitation_status' => AppConstants::INVITATION_STATUS['ACCEPTED']
        ]));

        Invitation::factory()->count(2)->create($this->validInvitationPayload([
            'invitation_status' => AppConstants::INVITATION_STATUS['PENDING']
        ]));

        $response = $this->getJson(route('invitations.getInvitationsByStatusAndSenderId', ['status' => AppConstants::INVITATION_STATUS['ACCEPTED']]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'Success.'
            ])
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        ...self::INVITATION_RETURN_COLUMNS,
                        'position' => [
                            ...self::POSITION_RETURN_COLUMNS,
                            'organization' => self::ORGANIZATION_RETURN_COLUMNS
                        ],
                        'receiver' => self::PROFILE_RETURN_COLUMNS,
                    ]
                ]
            ])
            ->assertJsonPath('data.0.id', $invitation->id)
            ->assertJsonPath('data.0.sender_profile_id', $this->senderProfile->id)
            ->assertJsonPath('data.0.receiver.id', $this->receiverProfile->id)
            ->assertJsonPath('data.0.position.id', $this->position->id)
            ->assertJsonPath('data.0.invitation_status', AppConstants::INVITATION_STATUS['ACCEPTED']);
    }

    public function test_receiver_get_invitations_by_status_fails_with_invalid_invitation_status(): void
    {
        $this->withSession([
            'user_profile_id' => $this->receiverProfile->id
        ]);

        Invitation::create($this->validInvitationPayload([
            'invitation_status' => AppConstants::INVITATION_STATUS['PENDING']
        ]));

        Invitation::factory()->count(2)->create($this->validInvitationPayload([
            'invitation_status' => AppConstants::INVITATION_STATUS['WITHDRAWN']
        ]));

        $response = $this->getJson(route('invitations.getInvitationsByStatusAndReceiverId', ['status' => 'Invalid status']));

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => 'Invalid invitation status provided.',
            ])
            ->assertJsonPath('data', null);
    }

    public function test_sender_get_invitations_by_status_fails_with_invalid_invitation_status(): void
    {
        Invitation::create($this->validInvitationPayload([
            'invitation_status' => AppConstants::INVITATION_STATUS['ACCEPTED']
        ]));

        Invitation::factory()->count(2)->create($this->validInvitationPayload([
            'invitation_status' => AppConstants::INVITATION_STATUS['PENDING']
        ]));

        $response = $this->getJson(route('invitations.getInvitationsByStatusAndSenderId', ['status' => 'Invalid status']));

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => 'Invalid invitation status provided.',
            ])
            ->assertJsonPath('data', null);
    }

    public function test_org_admin_can_create_invitation(): void
    {
        $response = $this->postJson(route('invitations.store'), $this->validInvitationPayload());

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonFragment([
                'status' => Response::HTTP_CREATED,
                'message' => 'Invitation created successfully.'
            ])
            ->assertJsonStructure([
                'data' => [
                    ...self::INVITATION_RETURN_COLUMNS,
                    'position' => self::POSITION_RETURN_COLUMNS,
                    'receiver' => self::PROFILE_RETURN_COLUMNS
                ]
            ])
            ->assertJsonPath('data.position.id', $this->position->id)
            ->assertJsonPath('data.sender_profile_id', $this->senderProfile->id)
            ->assertJsonPath('data.receiver_profile_id', $this->receiverProfile->id)
            ->assertJsonPath('data.receiver.id', $this->receiverProfile->id);
    }

    public function test_create_invitation_fails_without_required_fields(): void
    {
        $response = $this->postJson(route('invitations.store'));

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'status' => Response::HTTP_BAD_REQUEST,
            ])
            ->assertJsonPath('data', null);
    }

    public function test_create_invitation_fails_when_user_not_admin_of_current_org(): void
    {
        $this->withSession([
            'user_profile_id' => $this->receiverProfile->id,
            'roles' => ['Recruiter']
        ]);

        $otherUserProfile = UserProfile::factory()->create();

        $response = $this->postJson(route('invitations.store'), $this->validInvitationPayload([
            'receiver_profile_id' => $otherUserProfile->id
        ]));

        $response->assertStatus(Response::HTTP_FORBIDDEN)
            ->assertJsonFragment([
                'status' => Response::HTTP_FORBIDDEN,
                'message' => 'Unauthorized access to create invitation for this position.',
            ])
            ->assertJsonPath('data', null);
    }

    public function test_create_invitation_fails_when_user_sends_invitation_to_themselves(): void
    {
        $response = $this->postJson(route('invitations.store'), $this->validInvitationPayload([
            'receiver_profile_id' => $this->senderProfile->id
        ]));

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment([
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'message' => 'Unable to send invitations to themselves.',
            ])
            ->assertJsonPath('data', null);
    }

    public function test_create_invitation_fails_with_expires_at_before_current_time(): void
    {
        $response = $this->postJson(route('invitations.store'), $this->validInvitationPayload([
            'expires_at' => now()->subDay()->toDateTimeString()
        ]));

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'status' => Response::HTTP_BAD_REQUEST,
            ])
            ->assertJsonPath('data', null);

        $this->assertStringContainsString('expires at', $response->json('message'));
    }

    public function test_create_invitation_fails_with_non_existing_position_id(): void
    {
        $response = $this->postJson(route('invitations.store'), $this->validInvitationPayload([
            'position_id' => 0
        ]));

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'status' => Response::HTTP_BAD_REQUEST,
            ])
            ->assertJsonPath('data', null);

        $this->assertStringContainsString('position id', $response->json('message'));
    }

    public function test_create_invitation_fails_with_position_id_of_other_org(): void
    {
        $otherOrganization = Organization::factory()->create();
        $otherOrgPosition = Position::factory()->create([
            'organization_id' => $otherOrganization->id
        ]);

        $response = $this->postJson(route('invitations.store'), $this->validInvitationPayload([
            'position_id' => $otherOrgPosition->id
        ]));

        $response->assertStatus(Response::HTTP_FORBIDDEN)
            ->assertJsonFragment([
                'status' => Response::HTTP_FORBIDDEN,
                'message' => 'Unauthorized access to create invitation for this position.',
            ])
            ->assertJsonPath('data', null);
    }

    public function test_org_admin_can_update_invitation(): void
    {
        $invitation = Invitation::factory()->create($this->validInvitationPayload());

        $response = $this->putJson(route('invitations.update', ['id' => $invitation->id]), [
            'invitation_message' => 'Updated message.',
            'expires_at' => now()->addDays(3)->toDateTimeString(),
        ]);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'Invitation updated successfully.'
            ])
            ->assertJsonStructure([
                'data' => self::INVITATION_RETURN_COLUMNS
            ])
            ->assertJsonPath('data.id', $invitation->id)
            ->assertJsonPath('data.sender_profile_id', $this->senderProfile->id)
            ->assertJsonPath('data.receiver_profile_id', $this->receiverProfile->id)
            ->assertJsonPath('data.invitation_message', 'Updated message.');
    }

    public function test_org_admin_can_update_expired_invitation(): void
    {
        $invitation = Invitation::factory()->create($this->validInvitationPayload([
            'expires_at' => now()->subDay()->toDateString(),
            'invitation_status' => AppConstants::INVITATION_STATUS['EXPIRED']
        ]));

        $response = $this->putJson(route('invitations.update', ['id' => $invitation->id]), [
            'invitation_message' => 'Updated message.',
            'expires_at' => now()->addDays(3)->toDateTimeString(),
        ]);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'Invitation updated successfully.'
            ])
            ->assertJsonStructure([
                'data' => self::INVITATION_RETURN_COLUMNS
            ])
            ->assertJsonPath('data.id', $invitation->id)
            ->assertJsonPath('data.sender_profile_id', $this->senderProfile->id)
            ->assertJsonPath('data.receiver_profile_id', $this->receiverProfile->id)
            ->assertJsonPath('data.invitation_message', 'Updated message.')
            ->assertJsonPath('data.invitation_status', AppConstants::INVITATION_STATUS['PENDING']);
    }

    public function test_update_invitation_fails_without_required_fields(): void
    {
        $invitation = Invitation::factory()->create($this->validInvitationPayload());

        $response = $this->putJson(route('invitations.update', ['id' => $invitation->id]));

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'status' => Response::HTTP_BAD_REQUEST,
            ])
            ->assertJsonPath('data', null);
    }

    public function test_update_invitation_fails_with_non_existing_invitation_id(): void
    {
        Invitation::factory()->create($this->validInvitationPayload());

        $response = $this->putJson(route('invitations.update', ['id' => 0]), [
            'invitation_message' => 'Updated message.',
            'expires_at' => now()->addDays(3)->toDateTimeString(),
        ]);

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonFragment([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Invitation not found or access unauthorized.'
            ])
            ->assertJsonPath('data', null);
    }

    public function test_update_invitation_fails_when_existing_invitation_status_is_not_pending_or_expired(): void
    {
        $invitation = Invitation::factory()->create($this->validInvitationPayload([
            'invitation_status' => AppConstants::INVITATION_STATUS['ACCEPTED']
        ]));

        $response = $this->putJson(route('invitations.update', ['id' => $invitation->id]), [
            'invitation_message' => 'Updated message.',
            'expires_at' => now()->addDays(3)->toDateTimeString(),
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment([
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'message' => 'Invitation accepted, rejected or withdrawn cannot be updated anymore.'
            ])
            ->assertJsonPath('data', null);
    }

    public function test_update_invitation_fails_when_updating_other_sender_invitation(): void
    {
        $otherSenderProfile = UserProfile::factory()->create();

        $invitation = Invitation::factory()->create($this->validInvitationPayload([
            'sender_profile_id' => $otherSenderProfile->id
        ]));

        $response = $this->putJson(route('invitations.update', ['id' => $invitation->id]), [
            'invitation_message' => 'Updated message.',
            'expires_at' => now()->addDays(3)->toDateTimeString(),
        ]);

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonFragment([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Invitation not found or access unauthorized.'
            ])
            ->assertJsonPath('data', null);
    }

    public function test_update_invitation_fails_with_expires_at_before_current_time(): void
    {
        $invitation = Invitation::factory()->create($this->validInvitationPayload());

        $response = $this->putJson(route('invitations.update', ['id' => $invitation->id]), [
            'invitation_message' => 'Updated message.',
            'expires_at' => now()->subDay()->toDateTimeString(),
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'status' => Response::HTTP_BAD_REQUEST,
            ])
            ->assertJsonPath('data', null);

        $this->assertStringContainsString('expires at', $response->json('message'));
    }

    public function test_receiver_can_accept_invitation(): void
    {
        $this->withSession([
            'user_profile_id' => $this->receiverProfile->id
        ]);

        $invitation = Invitation::factory()->create($this->validInvitationPayload());

        $response = $this->putJson(route('invitations.acceptInvitation', ['id' => $invitation->id]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'Invitation accepted.'
            ])
            ->assertJsonStructure([
                'data' => self::INVITATION_RETURN_COLUMNS
            ])
            ->assertJsonPath('data.id', $invitation->id)
            ->assertJsonPath('data.invitation_status', AppConstants::INVITATION_STATUS['ACCEPTED']);
    }

    public function test_receiver_accept_invitation_fails_with_non_existing_invitation_id(): void
    {
        $this->withSession([
            'user_profile_id' => $this->receiverProfile->id
        ]);

        Invitation::factory()->create($this->validInvitationPayload());

        $response = $this->putJson(route('invitations.acceptInvitation', ['id' => 0]));

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonFragment([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Invitation not found or access unauthorized.'
            ])
            ->assertJsonPath('data', null);
    }

    public function test_receiver_accept_invitation_fails_when_existing_invitation_status_is_not_pending(): void
    {
        $this->withSession([
            'user_profile_id' => $this->receiverProfile->id
        ]);

        $invitation = Invitation::factory()->create($this->validInvitationPayload([
            'invitation_status' => AppConstants::INVITATION_STATUS['WITHDRAWN']
        ]));

        $response = $this->putJson(route('invitations.acceptInvitation', ['id' => $invitation->id]));

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment([
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'message' => 'Invitation accepted, rejected or withdrawn cannot be updated anymore.'
            ])
            ->assertJsonPath('data', null);
    }

    public function test_accept_invitation_fails_when_user_is_not_receiver(): void
    {
        $invitation = Invitation::factory()->create($this->validInvitationPayload());

        $response = $this->putJson(route('invitations.acceptInvitation', ['id' => $invitation->id]));

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonFragment([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Invitation not found or access unauthorized.'
            ])
            ->assertJsonPath('data', null);
    }

    public function test_receiver_can_reject_invitation(): void
    {
        $this->withSession([
            'user_profile_id' => $this->receiverProfile->id
        ]);

        $invitation = Invitation::factory()->create($this->validInvitationPayload());

        $response = $this->putJson(route('invitations.rejectInvitation', ['id' => $invitation->id]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'Invitation rejected.'
            ])
            ->assertJsonStructure([
                'data' => self::INVITATION_RETURN_COLUMNS
            ])
            ->assertJsonPath('data.id', $invitation->id)
            ->assertJsonPath('data.invitation_status', AppConstants::INVITATION_STATUS['REJECTED']);
    }

    public function test_receiver_reject_invitation_fails_with_non_existing_invitation_id(): void
    {
        $this->withSession([
            'user_profile_id' => $this->receiverProfile->id
        ]);

        Invitation::factory()->create($this->validInvitationPayload());

        $response = $this->putJson(route('invitations.rejectInvitation', ['id' => 0]));

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonFragment([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Invitation not found or access unauthorized.'
            ])
            ->assertJsonPath('data', null);
    }

    public function test_receiver_reject_invitation_fails_when_existing_invitation_status_is_not_pending(): void
    {
        $this->withSession([
            'user_profile_id' => $this->receiverProfile->id
        ]);

        $invitation = Invitation::factory()->create($this->validInvitationPayload([
            'invitation_status' => AppConstants::INVITATION_STATUS['ACCEPTED']
        ]));

        $response = $this->putJson(route('invitations.rejectInvitation', ['id' => $invitation->id]));

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment([
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'message' => 'Invitation accepted, rejected or withdrawn cannot be updated anymore.'
            ])
            ->assertJsonPath('data', null);
    }

    public function test_reject_invitation_fails_when_user_is_not_receiver(): void
    {
        $invitation = Invitation::factory()->create($this->validInvitationPayload());

        $response = $this->putJson(route('invitations.rejectInvitation', ['id' => $invitation->id]));

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonFragment([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Invitation not found or access unauthorized.'
            ])
            ->assertJsonPath('data', null);
    }

    public function test_sender_can_withdraw_invitation(): void
    {
        $invitation = Invitation::factory()->create($this->validInvitationPayload());

        $response = $this->putJson(route('invitations.withdrawInvitation', ['id' => $invitation->id]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'Invitation withdrawn.'
            ])
            ->assertJsonStructure([
                'data' => self::INVITATION_RETURN_COLUMNS
            ])
            ->assertJsonPath('data.id', $invitation->id)
            ->assertJsonPath('data.invitation_status', AppConstants::INVITATION_STATUS['WITHDRAWN']);
    }

    public function test_sender_withdraw_invitation_fails_with_non_existing_invitation_id(): void
    {
        Invitation::factory()->create($this->validInvitationPayload());

        $response = $this->putJson(route('invitations.withdrawInvitation', ['id' => 0]));

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonFragment([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Invitation not found or access unauthorized.'
            ])
            ->assertJsonPath('data', null);
    }

    public function test_sender_withdraw_invitation_fails_when_existing_invitation_status_is_not_pending(): void
    {
        $invitation = Invitation::factory()->create($this->validInvitationPayload([
            'invitation_status' => AppConstants::INVITATION_STATUS['ACCEPTED']
        ]));

        $response = $this->putJson(route('invitations.withdrawInvitation', ['id' => $invitation->id]));

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment([
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'message' => 'Invitation accepted, rejected or withdrawn cannot be updated anymore.'
            ])
            ->assertJsonPath('data', null);
    }

    public function test_withdraw_invitation_fails_when_user_is_not_sender(): void
    {
        $this->withSession([
            'user_profile_id' => $this->receiverProfile->id
        ]);

        $invitation = Invitation::factory()->create($this->validInvitationPayload());

        $response = $this->putJson(route('invitations.withdrawInvitation', ['id' => $invitation->id]));

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonFragment([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Invitation not found or access unauthorized.'
            ])
            ->assertJsonPath('data', null);
    }
}
