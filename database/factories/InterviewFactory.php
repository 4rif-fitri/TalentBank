<?php

namespace Database\Factories;

use App\Constants\AppConstants;
use App\Models\Interview;
use App\Models\Invitation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Interview>
 */
class InterviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'invitation_id' => Invitation::inRandomOrder()->first()->id,
            'scheduled_at' => fake()->dateTimeBetween('now', '+1 month'),
            'interview_mode' => fake()->randomElement(AppConstants::INTERVIEW_MODES),
            'location' => null,
            'meeting_url' => null,
            'interview_status' => fake()->randomElement(AppConstants::INTERVIEW_STATUS),
            'interview_result' => fake()->randomElement(AppConstants::INTERVIEW_RESULTS),
            'recruiter_comment' => fake()->optional()->sentence(),
        ];
    }
}
