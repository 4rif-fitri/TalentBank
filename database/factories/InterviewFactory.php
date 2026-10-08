<?php

namespace Database\Factories;

use App\Constants\AppConstants;
use App\Models\Interview;
use App\Models\Position;
use App\Models\UserProfile;
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
            'title' => fake()->sentence(),
            'scheduled_at' => fake()->dateTimeBetween('now', '+1 month'),
            'interview_mode' => fake()->randomElement(AppConstants::INTERVIEW_MODES),
            'location' => null,
            'meeting_url' => null,
            'interview_status' => fake()->randomElement(AppConstants::INTERVIEW_STATUS),
            'interview_result' => fake()->randomElement(AppConstants::INTERVIEW_RESULTS),
            'recruiter_comment' => fake()->optional()->sentence(),
            'position_id' => Position::inRandomOrder()->first()->id,
            'interviewer_profile_id' => UserProfile::inRandomOrder()->first()->id,
            'interviewee_profile_id' => UserProfile::inRandomOrder()->first()->id,
        ];
    }
}
