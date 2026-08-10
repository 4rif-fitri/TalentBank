<?php

namespace Database\Seeders;

use App\Models\IndustryCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IndustryCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Government',
            'Statutory Body',
            'Private (Multinational)',
            'Private (Local)',
            'Own Firm / Business',
            'Government-Linked Company (GLC)',
            'Non-Governmental Organization (NGO)',
            'Others'
        ];

        foreach ($categories as $category) {
            IndustryCategory::create(['name' => $category]);
        }
    }
}
