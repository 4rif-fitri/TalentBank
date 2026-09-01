<?php

namespace Database\Factories;

use App\Constants\AppConstants;
use App\Models\Invitation;
use App\Models\JobOffer;
use App\Models\UserProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<JobOffer>
 */
class JobOfferFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'salary_amount' => $this->faker->randomFloat(2, 0, 100000),
            'salary_period' => $this->faker->randomElement(),
            'start_date' => $this->faker->date(),
            'end_date' => $this->faker->date(),
            'terms_and_conditions' => $this->faker->text(),
            'benefits' => $this->faker->text(),
            'offer_status' => $this->faker->randomElement(AppConstants::JOB_OFFER_STATUS),
            'expires_at' => $this->faker->dateTime(),
            'invitation_id' => Invitation::inRandomOrder()->first()->id,
        ];
    }
}
