<?php

namespace Database\Factories;

use App\Models\IndustryCategory;
use App\Models\IndustrySector;
use App\Models\Organization;
use App\Models\OrganizationType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Organization>
 */
class OrganizationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company_name' => $this->faker->company(),
            'ssm_number' => $this->faker->unique()->numerify('###########'),
            'industry_category_id' => IndustryCategory::inRandomOrder()->first()->id,
            'address' => $this->faker->address(),
            'postcode' => $this->faker->postcode(),
            'city' => $this->faker->city(),
            'state' => $this->faker->state(),
            'website' => $this->faker->url(),
            'description' => $this->faker->paragraph(),
            'company_email' => $this->faker->unique()->companyEmail(),
            'company_phone' => $this->faker->phoneNumber(),
            'industry_sector_id' => IndustrySector::inRandomOrder()->first()->id,
            'organization_type_id' => OrganizationType::inRandomOrder()->first()->id,
        ];
    }
}
