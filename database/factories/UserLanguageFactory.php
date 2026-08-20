<?php

namespace Database\Factories;

use App\Constants\AppConstants;
use App\Models\Language;
use App\Models\UserLanguage;
use App\Models\UserProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<UserLanguage>
 */
class UserLanguageFactory extends Factory
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
            'language_id' => Language::inRandomOrder()->first()->id,
            'proficiency_level' => fake()->randomElement(AppConstants::PROFICIENCY_LEVEL)
        ];
    }
}
