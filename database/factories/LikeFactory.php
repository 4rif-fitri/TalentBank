<?php

namespace Database\Factories;

use App\Models\Like;
use App\Models\UserProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Like>
 */
class LikeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'liker_user_profile_id' => UserProfile::inRandomOrder()->first()->id,
            'liked_user_profile_id' => UserProfile::inRandomOrder()->first()->id,
        ];
    }
}
