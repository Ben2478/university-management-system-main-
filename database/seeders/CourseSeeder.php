<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = [
            [
                'course_code' => 'MATH101',
                'course_name' => 'Mathematics',
                'description' => 'Basic Mathematics',
                'credits' => 3,
                'department' => 'Science',
                'semester' => 1,
                'tuition_fee' => 1500.00,
                'status' => 'active',
            ],
            [
                'course_code' => 'ENG101',
                'course_name' => 'English Language',
                'description' => 'Fundamentals of English',
                'credits' => 3,
                'department' => 'Arts',
                'semester' => 1,
                'tuition_fee' => 1400.00,
                'status' => 'active',
            ],
            [
                'course_code' => 'PHY101',
                'course_name' => 'Physics',
                'description' => 'Introduction to Physics',
                'credits' => 4,
                'department' => 'Science',
                'semester' => 2,
                'tuition_fee' => 1600.00,
                'status' => 'active',
            ],
            [
                'course_code' => 'CHEM101',
                'course_name' => 'Chemistry',
                'description' => 'Basic Chemistry',
                'credits' => 4,
                'department' => 'Science',
                'semester' => 2,
                'tuition_fee' => 1600.00,
                'status' => 'active',
            ],
            [
                'course_code' => 'BIO101',
                'course_name' => 'Biology',
                'description' => 'General Biology',
                'credits' => 3,
                'department' => 'Science',
                'semester' => 1,
                'tuition_fee' => 1500.00,
                'status' => 'active',
            ],
            [
                'course_code' => 'HIST101',
                'course_name' => 'History',
                'description' => 'World History',
                'credits' => 2,
                'department' => 'Social Studies',
                'semester' => 1,
                'tuition_fee' => 1200.00,
                'status' => 'active',
            ],
            [
                'course_code' => 'GEO101',
                'course_name' => 'Geography',
                'description' => 'Physical Geography',
                'credits' => 2,
                'department' => 'Social Studies',
                'semester' => 2,
                'tuition_fee' => 1200.00,
                'status' => 'active',
            ],
            [
                'course_code' => 'CS101',
                'course_name' => 'Computer Science',
                'description' => 'Intro to Computer Science',
                'credits' => 3,
                'department' => 'Technology',
                'semester' => 1,
                'tuition_fee' => 1700.00,
                'status' => 'active',
            ],
            [
                'course_code' => 'ECON101',
                'course_name' => 'Economics',
                'description' => 'Principles of Economics',
                'credits' => 3,
                'department' => 'Business',
                'semester' => 2,
                'tuition_fee' => 1500.00,
                'status' => 'active',
            ],
            [
                'course_code' => 'CIV101',
                'course_name' => 'Civic Education',
                'description' => 'Civic Responsibilities',
                'credits' => 2,
                'department' => 'Social Studies',
                'semester' => 1,
                'tuition_fee' => 1200.00,
                'status' => 'active',
            ],
        ];

        foreach ($courses as $course) {
            Course::create($course);
        }
    }
}
