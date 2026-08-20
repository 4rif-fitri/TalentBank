<?php

namespace Database\Factories;

use App\Constants\AppConstants;
use App\Models\Education;
use App\Models\FieldOfStudy;
use App\Models\Programme;
use App\Models\Qualification;
use App\Models\UserProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Education>
 */
class EducationFactory extends Factory
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
            'programme_id' => Programme::inRandomOrder()->first()->id,
            'description' => fake()->paragraph(),
            'field_of_study_id' => FieldOfStudy::inRandomOrder()->first()->id,
            'qualification_id' => Qualification::inRandomOrder()->first()->id,
            'cgpa' => fake()->randomFloat(2, 2.00, 4.00),
            'start_date' => now()->subMonth()->format('Y-m-d'),
            'end_date' => now()->format('Y-m-d'),
            'enrollment_status' => fake()->randomElement(AppConstants::ENROLLMENT_STATUS),
            'verification_status' => fake()->randomElement(VERIFICATION_STATUS),
        ];
    }
}
