<?php

namespace Database\Factories;

use App\Models\Education;
use App\Models\Semester;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Semester>
 */
class SemesterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'education_id' => Education::inRandomOrder()->first()->id,
            'gpa' => $this->faker->randomFloat(2, 0, 4.0),
            'session' => '1 - 2025/2026',
        ];
    }
}
