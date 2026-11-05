<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Lecturer;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseTermTest extends TestCase
{
    use RefreshDatabase;

    public function test_course_can_be_created_for_one_of_three_terms(): void
    {
        $this->actingAs(User::factory()->create([
            'role' => 'admin',
            'must_change_password' => false,
        ]));

        $this->post(route('courses.store'), [
            'course_code' => 'CS201',
            'course_name' => 'Data Structures',
            'credits' => 3,
            'department' => 'Computer Science',
            'term' => 3,
            'status' => 'active',
        ])->assertRedirect(route('courses.index'));

        $this->assertDatabaseHas('courses', [
            'course_code' => 'CS201',
            'term' => 3,
        ]);
    }

    public function test_course_term_must_be_between_one_and_three(): void
    {
        $this->actingAs(User::factory()->create([
            'role' => 'admin',
            'must_change_password' => false,
        ]));

        $this->from(route('courses.create'))
            ->post(route('courses.store'), [
                'course_code' => 'CS202',
                'course_name' => 'Algorithms',
                'credits' => 3,
                'department' => 'Computer Science',
                'term' => 4,
                'status' => 'active',
            ])
            ->assertSessionHasErrors('term');

        $this->assertDatabaseMissing('courses', ['course_code' => 'CS202']);
    }

    public function test_course_list_can_be_filtered_by_term(): void
    {
        $this->actingAs(User::factory()->create([
            'role' => 'admin',
            'must_change_password' => false,
        ]));

        Course::create([
            'course_code' => 'CS301',
            'course_name' => 'Term One Course',
            'credits' => 3,
            'department' => 'Computer Science',
            'term' => 1,
            'status' => 'active',
        ]);
        Course::create([
            'course_code' => 'CS302',
            'course_name' => 'Term Two Course',
            'credits' => 3,
            'department' => 'Computer Science',
            'term' => 2,
            'status' => 'active',
        ]);

        $this->get(route('courses.index', ['term' => 2]))
            ->assertOk()
            ->assertSee('Term Two Course')
            ->assertDontSee('Term One Course');
    }

    public function test_student_available_courses_can_be_filtered_by_term(): void
    {
        $user = User::factory()->create([
            'role' => 'student',
            'must_change_password' => false,
        ]);
        $student = Student::create([
            'user_id' => $user->id,
            'student_id' => 'STU00002',
            'first_name' => 'Test',
            'last_name' => 'Learner',
            'email' => 'learner@example.com',
            'date_of_birth' => '2005-01-01',
            'gender' => 'other',
            'enrollment_date' => '2025-01-01',
            'program' => 'Computer Science',
            'year' => 1,
            'semester' => 2,
            'status' => 'active',
        ]);
        Course::create([
            'course_code' => 'CS401',
            'course_name' => 'Term One Course',
            'credits' => 3,
            'department' => 'Computer Science',
            'term' => 1,
            'status' => 'active',
        ]);
        Course::create([
            'course_code' => 'CS402',
            'course_name' => 'Term Two Course',
            'credits' => 3,
            'department' => 'Computer Science',
            'term' => 2,
            'status' => 'active',
        ]);

        $this->actingAs($user)
            ->get(route('enrollments.available', ['term' => 2]))
            ->assertOk()
            ->assertSee('Term Two Course')
            ->assertDontSee('Term One Course');

        $this->assertSame(2, $student->fresh()->semester);
    }

    public function test_course_instructor_dropdown_uses_active_lecturers(): void
    {
        $this->actingAs(User::factory()->create([
            'role' => 'admin',
            'must_change_password' => false,
        ]));

        $lecturerUser = User::factory()->create([
            'role' => 'admin',
            'must_change_password' => false,
        ]);
        $lecturer = Lecturer::create([
            'user_id' => $lecturerUser->id,
            'lecturer_code' => 'LEC001',
            'first_name' => 'Ada',
            'last_name' => 'Lecturer',
            'email' => 'ada.lecturer@example.com',
            'status' => 'active',
        ]);
        $course = Course::create([
            'course_code' => 'CS501',
            'course_name' => 'Systems Design',
            'credits' => 3,
            'department' => 'Computer Science',
            'term' => 1,
            'status' => 'active',
        ]);

        $this->get(route('courses.edit', $course))
            ->assertOk()
            ->assertSee('name="instructor"', false)
            ->assertSee('value="' . e($lecturer->full_name) . '"', false)
            ->assertSee('Ada Lecturer (LEC001)');
    }

    public function test_course_instructor_must_be_an_active_lecturer(): void
    {
        $this->actingAs(User::factory()->create([
            'role' => 'admin',
            'must_change_password' => false,
        ]));

        $lecturerUser = User::factory()->create([
            'role' => 'admin',
            'must_change_password' => false,
        ]);
        Lecturer::create([
            'user_id' => $lecturerUser->id,
            'lecturer_code' => 'LEC002',
            'first_name' => 'Grace',
            'last_name' => 'Hopper',
            'email' => 'grace.hopper@example.com',
            'status' => 'active',
        ]);

        $this->from(route('courses.index'))
            ->post(route('courses.store'), [
                'course_code' => 'CS502',
                'course_name' => 'Compiler Design',
                'credits' => 3,
                'department' => 'Computer Science',
                'term' => 2,
                'instructor' => 'Someone Not On Staff',
                'status' => 'active',
            ])
            ->assertSessionHasErrors('instructor');

        $this->assertDatabaseMissing('courses', ['course_code' => 'CS502']);
    }
}