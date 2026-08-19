<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            RoleSeeder::class,
            IndustrySectorSeeder::class,
            IndustryCategorySeeder::class,
            OrganizationTypeSeeder::class,
            OrganizationSeeder::class,
            UserProfileSeeder::class,
            OrganizationUserSeeder::class,
            FacultySeeder::class,
            ProgrammeSeeder::class,
            FieldOfStudySeeder::class,
            QualificationSeeder::class,
            EducationSeeder::class,
            SemesterSeeder::class,
            MediaSeeder::class,
            SocialMediaSeeder::class,
            SocialMediaLinkSeeder::class,
            LanguageSeeder::class,
            UserLanguageSeeder::class,
        ]);
    }
}
