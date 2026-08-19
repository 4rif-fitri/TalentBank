<?php

namespace Database\Seeders;

use App\Models\UserLanguage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserLanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserLanguage::factory()->count(5)->create();
    }
}
