<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Enrollment;
use App\Models\Student;
use App\Models\Course;

class EnrollmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = Student::all();
        $courses = Course::all();
        foreach ($students as $student) {
            // Enroll each student in 3 random courses
            $randomCourses = $courses->random(min(3, $courses->count()));
            foreach ($randomCourses as $course) {
                Enrollment::create([
                    'student_id' => $student->id,
                    'course_id' => $course->id,
                    'enrollment_date' => now()->subDays(rand(1, 365)),
                    'status' => 'active',
                ]);
            }
        }
    }
}
