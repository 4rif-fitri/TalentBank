<?php

namespace Database\Seeders;

use App\Models\SocialMedia;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SocialMediaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $socialMedias = [
            [
                'name' => 'Instagram',
                'icon_class_name' => 'fa-brands fa-instagram',
            ],
            [
                'name' => 'Facebook',
                'icon_class_name' => 'fa-brands fa-facebook',
            ],
            [
                'name' => 'LinkedIn',
                'icon_class_name' => 'fa-brands fa-linkedin',
            ],
            [
                'name' => 'GitHub',
                'icon_class_name' => 'fa-brands fa-github',
            ],
            [
                'name' => 'X/Twitter',
                'icon_class_name' => 'fa-brands fa-x-twitter',
            ],
            [
                'name' => 'Discord',
                'icon_class_name' => 'fa-brands fa-discord',
            ],
            [
                'name' => 'TikTok',
                'icon_class_name' => 'fa-brands fa-tiktok',
            ],
            [
                'name' => 'YouTube',
                'icon_class_name' => 'fa-brands fa-youtube',
            ],
        ];

        foreach ($socialMedias as $socialMedia) {
            SocialMedia::create([
                'name' => $socialMedia['name'],
                'icon_class_name' => $socialMedia['icon_class_name'],
            ]);
        }
    }
}
