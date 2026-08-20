<?php

namespace Database\Factories;

use App\Constants\AppConstants;
use App\Models\Skill;
use App\Models\UserProfile;
use App\Models\UserSkill;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<UserSkill>
 */
class UserSkillFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'source_type' => 'user_profile',
            'source_id' => UserProfile::inRandomOrder()->first()->id,
            'skill_id' => Skill::inRandomOrder()->first()->id,
        ];
    }
}
