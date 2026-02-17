<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Program;

class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $programs = [
            [
                'name' => 'Computer Science',
                'description' => 'Study of computation, algorithms, programming languages, and computer systems.',
                'department' => 'School of Computing',
                'duration_years' => 4,
                'status' => 'active',
            ],
            [
                'name' => 'Business Administration',
                'description' => 'Comprehensive business education covering management, finance, marketing, and operations.',
                'department' => 'School of Business',
                'duration_years' => 4,
                'status' => 'active',
            ],
            [
                'name' => 'Engineering',
                'description' => 'Applied science and mathematics to design, build, and maintain systems and structures.',
                'department' => 'School of Engineering',
                'duration_years' => 4,
                'status' => 'active',
            ],
            [
                'name' => 'Medicine',
                'description' => 'Medical science and practice for diagnosis, treatment, and prevention of disease.',
                'department' => 'School of Medicine',
                'duration_years' => 6,
                'status' => 'active',
            ],
            [
                'name' => 'Education',
                'description' => 'Teacher training and educational theory for primary and secondary education.',
                'department' => 'School of Education',
                'duration_years' => 4,
                'status' => 'active',
            ],
            [
                'name' => 'Law',
                'description' => 'Legal studies covering constitutional, criminal, civil, and international law.',
                'department' => 'School of Law',
                'duration_years' => 4,
                'status' => 'active',
            ],
            [
                'name' => 'Arts & Humanities',
                'description' => 'Study of human culture, history, languages, literature, and philosophy.',
                'department' => 'School of Arts',
                'duration_years' => 3,
                'status' => 'active',
            ],
            [
                'name' => 'Natural Sciences',
                'description' => 'Study of natural phenomena including biology, chemistry, and physics.',
                'department' => 'School of Sciences',
                'duration_years' => 4,
                'status' => 'active',
            ],
        ];

        foreach ($programs as $program) {
            Program::create($program);
        }
    }
}
