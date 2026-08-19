<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $languages = [
            [
                'language_name' => 'English',
                'language_code' => 'EN',
            ],
            [
                'language_name' => 'Malay',
                'language_code' => 'MS',
            ],
            [
                'language_name' => 'Chinese',
                'language_code' => 'ZH',
            ],
            [
                'language_name' => 'Tamil',
                'language_code' => 'TA',
            ]
        ];

        foreach ($languages as $language) {
            Language::create([
                'language_name' => $language['language_name'],
                'language_code' => $language['language_code']
            ]);
        }
    }
}
