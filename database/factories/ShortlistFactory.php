<?php

namespace Database\Factories;

use App\Models\Position;
use App\Models\Shortlist;
use App\Models\UserProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Shortlist>
 */
class ShortlistFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'position_id' => Position::inRandomOrder()->first()->id,
            'user_profile_id' => UserProfile::inRandomOrder()->first()->id,
        ];
    }
}
