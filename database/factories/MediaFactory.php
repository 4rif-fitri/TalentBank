<?php

namespace Database\Factories;

use App\Models\Media;
use App\Models\Semester;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Media>
 */
class MediaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uploaded_by_user_id' => UserProfile::inRandomOrder()->first()->id,
            'source_name' => $this->faker->randomElement([
                'semester',
                'education',
                'experience',
                'project',
                'honors_award',
                'certification',
            ]),
            'source_id' => Semester::inRandomOrder()->first()->id,
            'media_type' => 'image',
            'file_name' => $this->faker->imageUrl,
            'title' => $this->faker->word(),
            'description' => $this->faker->paragraph(),
        ];
    }
}
