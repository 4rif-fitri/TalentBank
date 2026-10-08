<?php

namespace Tests\Feature;

use App\Models\SocialMedia;
use App\Models\SocialMediaLink;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class SocialMediaLinkControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private UserProfile $userProfile;
    private SocialMedia $socialMediaGitHub;
    private SocialMedia $socialMediaLinkedIn;

    protected function setUp(): void
    {
        parent::setUp();

        SocialMedia::factory()->count(3)->create();

        $this->user = User::factory()->create();
        $this->userProfile = UserProfile::factory()->create([
            'user_id' => $this->user->id,
            'email' => 'sender@example.com',
        ]);

        $this->socialMediaGitHub = SocialMedia::factory()->create([
            'name' => 'GitHub'
        ]);
        $this->socialMediaLinkedIn = SocialMedia::factory()->create([
            'name' => 'LinkedIn'
        ]);

        $this->actingAs($this->user)
            ->session([
                'user_profile_id' => $this->userProfile->id
            ]);
    }

    public function test_user_can_get_all_social_media(): void
    {
        $response = $this->getJson(route('social-media.getAllSocialMedia'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'Success.'
            ])
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'icon_class_name'
                    ]
                ]
            ]);

        $this->assertCount(5, $response->json('data'));
    }

    public function test_user_can_create_social_media_link(): void
    {
        $response = $this->postJson(route('social-media.store'), [
            'social_media_id' => $this->socialMediaGitHub->id,
            'link' => 'https://new_link.com'
        ]);

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonFragment([
                'status' => Response::HTTP_CREATED,
                'message' => 'Social media link created successfully.'
            ])
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'user_profile_id',
                    'social_media_id',
                    'link'
                ]
            ])
            ->assertJsonPath('data.social_media_id', $this->socialMediaGitHub->id)
            ->assertJsonPath('data.link', 'https://new_link.com');

        $this->assertDatabaseHas('social_media_links', [
            'social_media_id' => $this->socialMediaGitHub->id,
            'link' => 'https://new_link.com',
            'user_profile_id' => $this->userProfile->id
        ]);
    }

    public function test_create_social_media_fails_without_required_fields(): void
    {
        $response = $this->postJson(route('social-media.store'));

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'status' => Response::HTTP_BAD_REQUEST,
            ])
            ->assertJsonPath('data', null);

        $this->assertDatabaseEmpty('social_media_links');
    }

    public function test_create_social_media_fails_with_non_existing_social_media_id(): void
    {
        $response = $this->postJson(route('social-media.store'), [
            'social_media_id' => 0,
            'link' => 'https://new_link.com'
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'status' => Response::HTTP_BAD_REQUEST,
            ])
            ->assertJsonPath('data', null);

        $this->assertStringContainsString('social media id', $response->json('message'));
        $this->assertDatabaseEmpty('social_media_links');
    }

    public function test_create_social_media_fails_with_invalid_link(): void
    {
        $response = $this->postJson(route('social-media.store'), [
            'social_media_id' => $this->socialMediaGitHub->id,
            'link' => 'Invalid link.'
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'status' => Response::HTTP_BAD_REQUEST,
            ])
            ->assertJsonPath('data', null);

        $this->assertStringContainsString('link', $response->json('message'));
        $this->assertDatabaseEmpty('social_media_links');
    }

    public function test_create_social_media_fails_with_already_existing_social_media_link(): void
    {
        SocialMediaLink::create([
            'user_profile_id' => $this->userProfile->id,
            'social_media_id' => $this->socialMediaGitHub->id,
            'link' => 'https://new_link.com'
        ]);

        $response = $this->postJson(route('social-media.store'), [
            'social_media_id' => $this->socialMediaGitHub->id,
            'link' => 'https://new_link.com'
        ]);

        $response->assertStatus(Response::HTTP_CONFLICT)
            ->assertJsonFragment([
                'status' => Response::HTTP_CONFLICT,
                'message' => 'Social media link already exists.'
            ])
            ->assertJsonPath('data', null);

        $this->assertDatabaseCount('social_media_links', 1);
    }

    public function test_user_can_update_social_media_link(): void
    {
        $socialMediaLink = SocialMediaLink::create([
            'user_profile_id' => $this->userProfile->id,
            'social_media_id' => $this->socialMediaGitHub->id,
            'link' => 'https://old-link.com'
        ]);

        $response = $this->putJson(route('social-media.update', $socialMediaLink->id), [
            'social_media_id' => $this->socialMediaLinkedIn->id,
            'link' => 'https://new-link.com'
        ]);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'Social media link updated successfully.'
            ])
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'user_profile_id',
                    'social_media_id',
                    'link',
                    'social_media' => [
                        'id',
                        'name',
                        'icon_class_name',
                    ]
                ]
            ])
            ->assertJsonPath('data.social_media_id', $this->socialMediaLinkedIn->id)
            ->assertJsonPath('data.social_media.id', $this->socialMediaLinkedIn->id)
            ->assertJsonPath('data.link', 'https://new-link.com');

        $this->assertDatabaseHas('social_media_links', [
            'id' => $socialMediaLink->id,
            'social_media_id' => $this->socialMediaLinkedIn->id,
            'link' => 'https://new-link.com',
            'user_profile_id' => $this->userProfile->id
        ]);
    }

    public function test_update_social_media_link_fails_without_required_fields(): void
    {
        $socialMediaLink = SocialMediaLink::create([
            'user_profile_id' => $this->userProfile->id,
            'social_media_id' => $this->socialMediaGitHub->id,
            'link' => 'https://old-link.com'
        ]);

        $response = $this->putJson(route('social-media.update', $socialMediaLink->id));

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'status' => Response::HTTP_BAD_REQUEST,
            ])
            ->assertJsonPath('data', null);

        $this->assertDatabaseHas('social_media_links', [
            'id' => $socialMediaLink->id,
            'social_media_id' => $this->socialMediaGitHub->id,
            'link' => 'https://old-link.com'
        ]);
    }

    public function test_update_social_media_link_fails_with_already_existing_social_media_link(): void
    {
        $socialMediaLink = SocialMediaLink::create([
            'user_profile_id' => $this->userProfile->id,
            'social_media_id' => $this->socialMediaGitHub->id,
            'link' => 'https://github.com/user'
        ]);

        SocialMediaLink::create([
            'user_profile_id' => $this->userProfile->id,
            'social_media_id' => $this->socialMediaLinkedIn->id,
            'link' => 'https://linkedin.com/in/user'
        ]);

        $response = $this->putJson(route('social-media.update', $socialMediaLink->id), [
            'social_media_id' => $this->socialMediaLinkedIn->id,
            'link' => 'https://linkedin.com/in/updated-user'
        ]);

        $response->assertStatus(Response::HTTP_CONFLICT)
            ->assertJsonFragment([
                'status' => Response::HTTP_CONFLICT,
                'message' => 'Social media link already exists.'
            ])
            ->assertJsonPath('data', null);

        $this->assertDatabaseHas('social_media_links', [
            'id' => $socialMediaLink->id,
            'social_media_id' => $this->socialMediaGitHub->id,
            'link' => 'https://github.com/user'
        ]);
    }

    public function test_update_social_media_link_fails_when_link_does_not_exist(): void
    {
        $socialMediaLink = SocialMediaLink::create([
            'user_profile_id' => $this->userProfile->id,
            'social_media_id' => $this->socialMediaGitHub->id,
            'link' => 'https://github.com/user'
        ]);

        $response = $this->putJson(route('social-media.update', 0), [
            'social_media_id' => $this->socialMediaGitHub->id,
            'link' => 'https://new-link.com'
        ]);

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonFragment([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Social media link not found or access unauthorized.'
            ])
            ->assertJsonPath('data', null);

        $this->assertDatabaseHas('social_media_links', [
            'id' => $socialMediaLink->id,
            'user_profile_id' => $this->userProfile->id,
            'social_media_id' => $this->socialMediaGitHub->id,
            'link' => 'https://github.com/user'
        ]);
    }

    public function test_update_social_media_link_fails_with_non_existing_social_media_id(): void
    {
        $socialMediaLink = SocialMediaLink::create([
            'user_profile_id' => $this->userProfile->id,
            'social_media_id' => $this->socialMediaGitHub->id,
            'link' => 'https://github.com/user'
        ]);

        $response = $this->putJson(route('social-media.update', $socialMediaLink->id), [
            'social_media_id' => 0,
            'link' => 'https://new-link.com'
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'status' => Response::HTTP_BAD_REQUEST,
            ])
            ->assertJsonPath('data', null);

        $this->assertStringContainsString('social media id', $response->json('message'));
        $this->assertDatabaseHas('social_media_links', [
            'id' => $socialMediaLink->id,
            'user_profile_id' => $this->userProfile->id,
            'social_media_id' => $this->socialMediaGitHub->id,
            'link' => 'https://github.com/user'
        ]);
    }

    public function test_update_social_media_link_fails_with_invalid_link(): void
    {
        $socialMediaLink = SocialMediaLink::create([
            'user_profile_id' => $this->userProfile->id,
            'social_media_id' => $this->socialMediaGitHub->id,
            'link' => 'https://github.com/user'
        ]);

        $response = $this->putJson(route('social-media.update', $socialMediaLink->id), [
            'social_media_id' => $this->socialMediaLinkedIn->id,
            'link' => 'Invalid link.'
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'status' => Response::HTTP_BAD_REQUEST,
            ])
            ->assertJsonPath('data', null);

        $this->assertStringContainsString('link', $response->json('message'));
        $this->assertDatabaseHas('social_media_links', [
            'id' => $socialMediaLink->id,
            'user_profile_id' => $this->userProfile->id,
            'social_media_id' => $this->socialMediaGitHub->id,
            'link' => 'https://github.com/user'
        ]);
    }

    public function test_update_other_users_social_media_link_fails(): void
    {
        $otherProfile = UserProfile::factory()->create();

        $socialMediaLink = SocialMediaLink::create([
            'user_profile_id' => $otherProfile->id,
            'social_media_id' => $this->socialMediaGitHub->id,
            'link' => 'https://github.com/user'
        ]);

        $response = $this->putJson(route('social-media.update', $socialMediaLink->id), [
            'social_media_id' => $this->socialMediaLinkedIn->id,
            'link' => 'https://new-link.com'
        ]);

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonFragment([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Social media link not found or access unauthorized.'
            ])
            ->assertJsonPath('data', null);

        $this->assertDatabaseHas('social_media_links', [
            'id' => $socialMediaLink->id,
            'user_profile_id' => $otherProfile->id,
            'social_media_id' => $this->socialMediaGitHub->id,
            'link' => 'https://github.com/user'
        ]);
    }

    public function test_user_can_delete_social_media_link(): void
    {
        $socialMediaLink = SocialMediaLink::create([
            'user_profile_id' => $this->userProfile->id,
            'social_media_id' => $this->socialMediaGitHub->id,
            'link' => 'https://github.com/user'
        ]);

        $response = $this->deleteJson(route('social-media.delete', $socialMediaLink->id));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'Social media link deleted successfully.'
            ])
            ->assertJsonPath('data', null);

        $this->assertDatabaseMissing('social_media_links', [
            'id' => $socialMediaLink->id
        ]);
    }

    public function test_delete_social_media_link_fails_with_non_existing_social_media_link(): void
    {
        $socialMediaLink = SocialMediaLink::create([
            'user_profile_id' => $this->userProfile->id,
            'social_media_id' => $this->socialMediaGitHub->id,
            'link' => 'https://github.com/user'
        ]);

        $response = $this->deleteJson(route('social-media.delete', 0));

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonFragment([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Social media link not found or access unauthorized.'
            ])
            ->assertJsonPath('data', null);

        $this->assertDatabaseHas('social_media_links', [
            'id' => $socialMediaLink->id,
            'user_profile_id' => $this->userProfile->id,
            'social_media_id' => $this->socialMediaGitHub->id,
            'link' => 'https://github.com/user'
        ]);
    }

    public function test_delete_social_media_link_fails_when_deleting_other_user_social_media_link(): void
    {
        $otherProfile = UserProfile::factory()->create();

        $socialMediaLink = SocialMediaLink::create([
            'user_profile_id' => $otherProfile->id,
            'social_media_id' => $this->socialMediaGitHub->id,
            'link' => 'https://github.com/user'
        ]);

        $response = $this->deleteJson(route('social-media.delete', $socialMediaLink->id));

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonFragment([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Social media link not found or access unauthorized.'
            ])
            ->assertJsonPath('data', null);

        $this->assertDatabaseHas('social_media_links', [
            'id' => $socialMediaLink->id,
            'user_profile_id' => $otherProfile->id,
            'social_media_id' => $this->socialMediaGitHub->id,
            'link' => 'https://github.com/user'
        ]);
    }
}
