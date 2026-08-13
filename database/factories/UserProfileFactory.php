<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<UserProfile>
 */
class UserProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => function (array $attributes) {
                return User::find($attributes['user_id'])->name;
            },
            'email' => function (array $attributes) {
                return User::find($attributes['user_id'])->email;
            },
            'about' => fake()->paragraph(3),
            'headline' => fake()->jobTitle(),
            'location' => fake()->city() . ', ' . fake()->country(),
            'phone_no' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'profile_image' => 'default.png',
            'cover_image' => 'default.png',
            'profile_visibility' => fake()->randomElement(['Public', 'Recruiter', 'Private']),
            'employment_status' => fake()->randomElement(['Open to Work', 'Open to Internship', 'Employed', 'Not Looking']),
        ];
    }
}
