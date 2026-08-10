<?php

namespace Database\Factories;

use App\Models\Faculty;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Faculty>
 */
class FacultyFactory extends Factory
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
            'faculty_name' => $this->faker->company(),
            'faculty_code' => $this->faker->word()
        ];
    }
}
