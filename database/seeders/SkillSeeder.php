<?php

namespace Database\Seeders;

use App\Models\Skill;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $skills = [
            // Frontend Development
            [
                'skill_name' => 'HTML5',
                'skill_category' => 'Frontend Development',
                'icon_class_name' => 'fa-brands fa-html5',
            ],
            [
                'skill_name' => 'CSS3',
                'skill_category' => 'Frontend Development',
                'icon_class_name' => 'fa-brands fa-css3-alt',
            ],
            [
                'skill_name' => 'JavaScript',
                'skill_category' => 'Frontend Development',
                'icon_class_name' => 'fa-brands fa-js',
            ],
            [
                'skill_name' => 'Vue.js',
                'skill_category' => 'Frontend Development',
                'icon_class_name' => 'fa-brands fa-vuejs',
            ],
            [
                'skill_name' => 'React',
                'skill_category' => 'Frontend Development',
                'icon_class_name' => 'fa-brands fa-react',
            ],
            [
                'skill_name' => 'Tailwind CSS',
                'skill_category' => 'Frontend Development',
                'icon_class_name' => 'fa-solid fa-palette',
            ],
            [
                'skill_name' => 'Bootstrap',
                'skill_category' => 'Frontend Development',
                'icon_class_name' => 'fa-brands fa-bootstrap',
            ],

            // Backend Development
            [
                'skill_name' => 'PHP',
                'skill_category' => 'Backend Development',
                'icon_class_name' => 'fa-brands fa-php',
            ],
            [
                'skill_name' => 'Laravel',
                'skill_category' => 'Backend Development',
                'icon_class_name' => 'fa-brands fa-laravel',
            ],
            [
                'skill_name' => 'Node.js',
                'skill_category' => 'Backend Development',
                'icon_class_name' => 'fa-brands fa-node-js',
            ],
            [
                'skill_name' => 'Python',
                'skill_category' => 'Backend Development',
                'icon_class_name' => 'fa-brands fa-python',
            ],
            [
                'skill_name' => 'Java',
                'skill_category' => 'Backend Development',
                'icon_class_name' => 'fa-brands fa-java',
            ],

            // Database & Storage
            [
                'skill_name' => 'MySQL',
                'skill_category' => 'Database',
                'icon_class_name' => 'fa-solid fa-database',
            ],
            [
                'skill_name' => 'PostgreSQL',
                'skill_category' => 'Database',
                'icon_class_name' => 'fa-solid fa-database',
            ],
            [
                'skill_name' => 'Redis',
                'skill_category' => 'Database',
                'icon_class_name' => 'fa-solid fa-server',
            ],

            // DevOps & Tools
            [
                'skill_name' => 'Git',
                'skill_category' => 'Tools & Version Control',
                'icon_class_name' => 'fa-brands fa-git-alt',
            ],
            [
                'skill_name' => 'Docker',
                'skill_category' => 'DevOps',
                'icon_class_name' => 'fa-brands fa-docker',
            ],
            [
                'skill_name' => 'AWS',
                'skill_category' => 'DevOps',
                'icon_class_name' => 'fa-brands fa-aws',
            ],
            [
                'skill_name' => 'Linux',
                'skill_category' => 'DevOps',
                'icon_class_name' => 'fa-brands fa-linux',
            ],
        ];

        foreach ($skills as $skill) {
            Skill::create([
                'skill_name' => $skill['skill_name'],
                'skill_category' => $skill['skill_category'],
                'icon_class_name' => $skill['icon_class_name'],
            ]);
        }
    }
}
