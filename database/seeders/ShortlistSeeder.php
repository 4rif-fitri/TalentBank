<?php

namespace Database\Seeders;

use App\Models\Shortlist;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShortlistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Shortlist::factory()->count(10)->create();
    }
}
