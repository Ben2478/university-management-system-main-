<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseDeletionTest extends TestCase
{
    use RefreshDatabase;

    public function test_course_with_enrollments_cannot_be_deleted(): void
    {
        $this->actingAs(User::factory()->create([
            'role' => 'admin',
            'must_change_password' => false,
        ]));

        $course = Course::create([
            'course_code' => 'CS101',
            'course_name' => 'Introduction to Computing',
            'credits' => 3,
            'department' => 'Computer Science',
            'term' => 1,
            'status' => 'active',
        ]);
        $student = Student::create([
            'student_id' => 'STU00001',
            'first_name' => 'Test',
            'last_name' => 'Student',
            'email' => 'student@example.com',
            'date_of_birth' => '2005-01-01',
            'gender' => 'other',
            'enrollment_date' => '2025-01-01',
        ]);
        $enrollment = Enrollment::create([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'enrollment_date' => '2025-01-01',
            'status' => 'active',
        ]);

        $this->followingRedirects()
            ->delete(route('courses.destroy', $course))
            ->assertOk()
            ->assertSee('Courses with enrollment records cannot be deleted.');

        $this->assertDatabaseHas('courses', ['id' => $course->id]);
        $this->assertDatabaseHas('enrollments', ['id' => $enrollment->id]);
    }

    public function test_course_without_enrollments_can_be_deleted(): void
    {
        $this->actingAs(User::factory()->create([
            'role' => 'admin',
            'must_change_password' => false,
        ]));

        $course = Course::create([
            'course_code' => 'CS102',
            'course_name' => 'Data Structures',
            'credits' => 3,
            'department' => 'Computer Science',
            'term' => 2,
            'status' => 'active',
        ]);

        $this->delete(route('courses.destroy', $course))
            ->assertRedirect(route('courses.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('courses', ['id' => $course->id]);
    }
}