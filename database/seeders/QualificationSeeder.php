<?php

namespace Database\Seeders;

use App\Models\Qualification;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QualificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $qualifications = [
            // Pre-University & Secondary
            'Certificate',
            'Foundation',
            'Matriculation',

            // Higher Education
            'Diploma',
            'Advanced Diploma',
            'Associate Degree',

            // Undergraduate
            "Bachelor's Degree",
            "Bachelor's Degree (Honours)",

            // Postgraduate & Advanced
            'Postgraduate Certificate',
            'Postgraduate Diploma',
            "Master's Degree",
            'Doctorate (Ph.D.)',
            'Doctor of Business Administration (DBA)',

            // Professional
            'Professional Certification',
        ];

        foreach ($qualifications as $qualification) {
            Qualification::create(['name' => $qualification]);
        }
    }
}
