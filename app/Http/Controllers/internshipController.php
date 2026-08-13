<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class internshipController extends Controller
{
    public function index()
    {
        return view("internship.index");
    }

    // public function education()
    // {
    //     return view("internship.education");
    // }

    public function education()
    {
        $educations = [
            [
                'id' => 15,
                'institution_name' => 'Universiti Teknikal Malaysia Melaka (UTeM)',
                'programme_name' => 'Bachelor of Computer Science (Software Engineering)',
                'field_of_study' => 'Computer Science',
                'qualification' => 'degree',
                'cgpa' => '4.00',
                'description' => 'Currently pursuing a degree in software engineering',
                'start_date' => '2026-10-01',
                'end_date' => '2027-10-01',
                'skills' => [
                    [
                        'id' => 10,
                        'skill_id' => 1,
                        'skill_name' => 'PHP',
                        'proficiency' => 'Advanced',
                    ],
                    [
                        'id' => 11,
                        'skill_id' => 2,
                        'skill_name' => 'Laravel',
                        'proficiency' => 'Intermediate',
                    ],
                ],

                'media' => [
                    [
                        'id' => 1,
                        'title' => 'Project Screenshot',
                        'description' => 'Screenshot of my university project',
                        'file_url' => '/assets/internship-assets/images/1.jpg',
                    ],
                    [
                        'id' => 2,
                        'title' => 'System Design',
                        'description' => 'System interface design',
                        'file_url' => '/assets/internship-assets/images/2.jpg',
                    ],
                    [
                        'id' => 3,
                        'title' => 'Final Project',
                        'description' => 'Final project presentation',
                        'file_url' => '/assets/internship-assets/images/3.jpg',
                    ],
                ],
            ],
            [
                'id' => 16,
                'institution_name' => 'Universiti Teknikal Malaysia Melaka (UTeM)',
                'programme_name' => 'Diploma in Computer Science',
                'field_of_study' => 'Computer Science',
                'qualification' => 'diploma',
                'cgpa' => '3.60',
                'description' => 'Completed Diploma in Computer Science',
                'start_date' => '2023-10-01',
                'end_date' => '2026-08-01',
                'skills' => [
                    [
                        'id' => 10,
                        'skill_id' => 1,
                        'skill_name' => 'PHP',
                        'proficiency' => 'Advanced',
                    ],
                    [
                        'id' => 11,
                        'skill_id' => 2,
                        'skill_name' => 'Laravel',
                        'proficiency' => 'Intermediate',
                    ],
                ],

                'media' => [
                    [
                        'id' => 1,
                        'title' => 'Project Screenshot',
                        'description' => 'Screenshot of my university project',
                        'file_url' => '/assets/internship-assets/images/4.jpg',
                    ],
                    [
                        'id' => 2,
                        'title' => 'System Design',
                        'description' => 'System interface design',
                        'file_url' => '/assets/internship-assets/images/5.jpg',
                    ],
                    [
                        'id' => 3,
                        'title' => 'Final Project',
                        'description' => 'Final project presentation',
                        'file_url' => '/assets/internship-assets/images/3.jpg',
                    ],
                ],
            ],
        ];

        return view('internship.education', compact('educations'));
    }

    public function profile()
    {
        return view("internship.profile");
    }

    public function experience()
    {
        return view("internship.experience");
    }

    public function destroy(string $id) {}
}
