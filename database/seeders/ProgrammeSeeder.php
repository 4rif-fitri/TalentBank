<?php

namespace Database\Seeders;

use App\Models\Programme;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProgrammeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Programme::factory()->count(10)->create();
    }
}
