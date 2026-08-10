<?php

namespace Database\Seeders;

use App\Models\OrganizationUser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrganizationUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        OrganizationUser::factory()->count(10)->create();
    }
}
