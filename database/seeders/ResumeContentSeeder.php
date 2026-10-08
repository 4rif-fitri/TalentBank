<?php

namespace Database\Seeders;

use App\Models\ResumeContent;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ResumeContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ResumeContent::factory()->count(10)->create();
    }
}
