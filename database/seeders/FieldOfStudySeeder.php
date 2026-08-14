<?php

namespace Database\Seeders;

use App\Models\FieldOfStudy;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FieldOfStudySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fieldOfStudies = [
            // Information Technology & Computing
            'Computer Science',
            'Software Engineering',
            'Data Science & Analytics',
            'Cybersecurity',
            'Information Technology',
            'Artificial Intelligence & Machine Learning',
            'Game Development',

            // Engineering & Architecture
            'Mechanical Engineering',
            'Electrical & Electronic Engineering',
            'Civil Engineering',
            'Chemical Engineering',
            'Architecture & Built Environment',

            // Business, Finance & Management
            'Business Administration',
            'Accounting',
            'Finance & Investment',
            'Digital Marketing',
            'Human Resource Management',
            'International Business',
            'Supply Chain Management',

            // Healthcare & Medical Sciences
            'Nursing',
            'Pharmacy',
            'Public Health',
            'Biomedical Science',
            'Nutrition & Dietetics',

            // Arts, Humanities & Social Sciences
            'Graphic Design & Digital Media',
            'Mass Communication & Journalism',
            'Psychology',
            'English Language & Literature',
            'International Relations',

            // Pure & Applied Sciences
            'Biotechnology',
            'Environmental Science',
            'Applied Mathematics',
            'Chemistry',
        ];

        foreach ($fieldOfStudies as $field) {
            FieldOfStudy::create(['name' => $field]);
        }
    }
}
