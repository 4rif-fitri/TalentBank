<?php

namespace Database\Factories;

use App\Models\Education;
use App\Models\Resume;
use App\Models\ResumeContent;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ResumeContent>
 */
class ResumeContentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'source_type' => 'education',
            'source_id' => Education::inRandomOrder()->first()->id,
            'resume_id' => Resume::inRandomOrder()->first()->id
        ];
    }
}
