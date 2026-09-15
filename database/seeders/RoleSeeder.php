<?php

namespace Database\Seeders;

use App\Constants\AppConstants;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (AppConstants::USER_ROLES as $role) {
            Role::create(['name' => $role]);
        }
    }
}
