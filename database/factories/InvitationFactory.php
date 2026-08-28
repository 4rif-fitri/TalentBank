<?php

namespace Database\Factories;

use App\Constants\AppConstants;
use App\Models\Invitation;
use App\Models\Position;
use App\Models\UserProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Invitation>
 */
class InvitationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sender_profile_id' => UserProfile::inRandomOrder()->first()->id,
            'receiver_profile_id' => UserProfile::inRandomOrder()->first()->id,
            'invitation_message' => fake()->paragraph(),
            'invitation_status' => fake()->randomElement(AppConstants::INVITATION_STATUS),
            'expires_at' => fake()->dateTimeBetween('+1 days', '+14 days'),
            'updated_at' => null,
            'position_id' => Position::inRandomOrder()->first()->id
        ];
    }
}
