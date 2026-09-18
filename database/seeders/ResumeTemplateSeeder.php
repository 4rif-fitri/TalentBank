<?php

namespace Database\Seeders;

use App\Models\ResumeTemplate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ResumeTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ResumeTemplate::factory()->count(5)->create();
    }
}
