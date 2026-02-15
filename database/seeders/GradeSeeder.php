<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Grade;
use App\Models\Student;
use App\Models\Course;

class GradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = Student::all();
        $courses = Course::all();
        $grades = ['A', 'B', 'C', 'D', 'E', 'F'];

        foreach ($students as $student) {
            // Assign 3 random courses and grades to each student
            $randomCourses = $courses->random(min(3, $courses->count()));
            foreach ($randomCourses as $course) {
                Grade::create([
                    'student_id' => $student->id,
                    'course_id' => $course->id,
                    'grade' => $grades[array_rand($grades)],
                    'remarks' => 'Test data',
                ]);
            }
        }
    }
}
