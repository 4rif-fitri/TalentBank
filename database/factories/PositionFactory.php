<?php

namespace Database\Factories;

use App\Constants\AppConstants;
use App\Models\Organization;
use App\Models\Position;
use App\Models\UserProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Position>
 */
class PositionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'organization_id' => Organization::inRandomOrder()->first()->id,
            'user_profile_id' => UserProfile::inRandomOrder()->first()->id,
            'position_title' => fake()->word(),
            'employment_type' => fake()->randomElement(AppConstants::EMPLOYMENT_TYPES),
            'department' => fake()->word(),
            'work_location' => fake()->address(),
            'vacancies' => fake()->randomDigit(),
            'description' => fake()->paragraph(),
        ];
    }
}
