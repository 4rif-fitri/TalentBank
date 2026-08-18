<?php

namespace Database\Factories;

use App\Models\SocialMedia;
use App\Models\SocialMediaLink;
use App\Models\UserProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SocialMediaLink>
 */
class SocialMediaLinkFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_profile_id' => UserProfile::inRandomOrder()->first()->id,
            'social_media_id' => SocialMedia::inRandomOrder()->first()->id,
            'link' => fake()->url()
        ];
    }
}
