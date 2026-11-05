<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BulkEnrollmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_enroll_multiple_students_in_multiple_courses(): void
    {
        $this->actingAs(User::factory()->create([
            'role' => 'admin',
            'must_change_password' => false,
        ]));

        $firstStudent = $this->student('Computer Science');
        $secondStudent = $this->student('Computer Science');
        $firstCourse = $this->course('CS601', 'Computer Science');
        $secondCourse = $this->course('CS602', 'Computer Science');

        $this->post(route('enrollments.store'), [
            'student_ids' => [$firstStudent->id, $secondStudent->id],
            'course_ids' => [$firstCourse->id, $secondCourse->id],
            'status' => 'active',
            'enrollment_date' => '2026-01-15',
        ])->assertRedirect(route('enrollments.index'))
            ->assertSessionHas('success', 'Created 4 enrollment(s).');

        $this->assertDatabaseCount('enrollments', 4);
        $this->assertDatabaseHas('enrollments', [
            'student_id' => $firstStudent->id,
            'course_id' => $secondCourse->id,
            'program' => 'Computer Science',
        ]);
    }

    public function test_bulk_enrollment_rejects_programme_mismatch_without_creating_any_records(): void
    {
        $this->actingAs(User::factory()->create([
            'role' => 'admin',
            'must_change_password' => false,
        ]));

        $student = $this->student('Computer Science');
        $course = $this->course('BUS601', 'Business Administration');

        $this->from(route('enrollments.create'))
            ->post(route('enrollments.store'), [
                'student_ids' => [$student->id],
                'course_ids' => [$course->id],
                'status' => 'active',
                'enrollment_date' => '2026-01-15',
            ])
            ->assertSessionHasErrors('course_ids');

        $this->assertDatabaseCount('enrollments', 0);
    }

    public function test_bulk_enrollment_skips_existing_student_course_pairs(): void
    {
        $this->actingAs(User::factory()->create([
            'role' => 'admin',
            'must_change_password' => false,
        ]));

        $student = $this->student('Computer Science');
        $course = $this->course('CS603', 'Computer Science');
        Enrollment::create([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'program' => $student->program,
            'enrollment_date' => '2026-01-01',
            'status' => 'active',
        ]);

        $this->post(route('enrollments.store'), [
            'student_ids' => [$student->id],
            'course_ids' => [$course->id],
            'status' => 'active',
            'enrollment_date' => '2026-01-15',
        ])->assertRedirect(route('enrollments.index'))
            ->assertSessionHas('success', 'Created 0 enrollment(s). Skipped 1 existing student-course pair(s).');

        $this->assertDatabaseCount('enrollments', 1);
    }

    private function student(string $program): Student
    {
        return Student::create([
            'student_id' => fake()->unique()->numerify('STU#####'),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'date_of_birth' => '2005-01-01',
            'gender' => 'other',
            'enrollment_date' => '2026-01-01',
            'program' => $program,
            'year' => 1,
            'semester' => 1,
            'status' => 'active',
        ]);
    }

    private function course(string $code, string $program): Course
    {
        return Course::create([
            'course_code' => $code,
            'course_name' => $code . ' Course',
            'credits' => 3,
            'department' => $program,
            'term' => 1,
            'status' => 'active',
        ]);
    }
}