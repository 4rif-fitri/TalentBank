<?php

namespace Database\Factories;

use App\Models\Enrollment;
use App\Models\Programme;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Enrollment>
 */
class EnrollmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'programme_id' => Programme::inRandomOrder()->first()->id,
            'user_profile_id' => UserProfile::inRandomOrder()->first()->id,
            'student_email' => $this->faker->email(),
            // 'matric_number' => $this->faker->unique()->word(),
            'matric_number' => fake()->unique()->numerify('B########'),
            'intake_year' => $this->faker->year(),
            'graduation_year' => $this->faker->year(),
            'cgpa' => $this->faker->randomFloat(2, 0, 4.0),
            'enrollment_status' => $this->faker->randomElement(['Active', 'Graduated', 'Deferred', 'Withdrawn']),
            'verification_status' => $this->faker->randomElement(['Pending', 'Verified', 'Rejected']),
        ];
    }
}
