<?php

namespace Database\Factories;

use App\Constants\AppConstants;
use App\Models\Faculty;
use App\Models\FieldOfStudy;
use App\Models\Organization;
use App\Models\Programme;
use App\Models\Qualification;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Programme>
 */
class ProgrammeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'faculty_id' => Faculty::inRandomOrder()->first()->id,
            'programme_name' => $this->faker->word(),
            'programme_code' => $this->faker->numerify("########"),
            'programme_level' => $this->faker->randomElement(AppConstants::PROGRAMME_LEVELS),
            'duration_years' => $this->faker->randomDigitNotZero(),
            'status' => $this->faker->boolean(),
            'organization_id' => Organization::inRandomOrder()->first()->id,
            'field_of_study_id' => FieldOfStudy::inRandomOrder()->first()->id,
            'qualification_id' => Qualification::inRandomOrder()->first()->id,
        ];
    }
}
