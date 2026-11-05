<?php

namespace Tests\Feature;

use App\Models\Program;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ShortCourseStudentTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    public function test_short_course_schedule_has_one_month_theory_and_two_months_practical(): void
    {
        $student = $this->student('short_course', '2026-01-31');

        $this->assertSame('2026-02-28', $student->shortCourseTheoryEndsOn()->format('Y-m-d'));
        $this->assertSame('2026-04-30', $student->shortCourseCompletesOn()->format('Y-m-d'));
        $this->assertSame('Theory', $student->shortCoursePhase(Carbon::parse('2026-02-27')));
        $this->assertSame('Practical', $student->shortCoursePhase(Carbon::parse('2026-02-28')));
        $this->assertSame('Completed', $student->shortCoursePhase(Carbon::parse('2026-04-30')));
    }

    public function test_login_is_rejected_after_short_course_completion(): void
    {
        $student = $this->student('short_course', '2026-01-01');
        Carbon::setTestNow('2026-04-01 09:00:00');

        $this->post(route('login'), [
            'identifier' => $student->student_id,
            'password' => 'welcome123',
        ])->assertSessionHasErrors('identifier');

        $this->assertGuest();
    }

    public function test_expired_short_course_cannot_log_in_using_email_either(): void
    {
        $student = $this->student('short_course', '2026-01-01');
        Carbon::setTestNow('2026-04-01 09:00:00');

        $this->post(route('login'), [
            'identifier' => $student->email,
            'password' => 'welcome123',
        ])->assertSessionHasErrors('identifier');

        $this->assertGuest();
    }

    public function test_existing_short_course_session_is_ended_at_completion(): void
    {
        $student = $this->student('short_course', '2026-01-01');
        Carbon::setTestNow('2026-04-01 09:00:00');

        $this->actingAs($student->user)
            ->get('/')
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }

    public function test_regular_student_login_does_not_expire_after_three_months(): void
    {
        $student = $this->student('regular', '2025-01-01');
        Carbon::setTestNow('2026-01-01 09:00:00');

        $this->post(route('login'), [
            'identifier' => $student->student_id,
            'password' => 'welcome123',
        ])->assertRedirect('/');

        $this->assertAuthenticatedAs($student->user);
    }

    public function test_admin_can_create_a_short_course_student_without_regular_year_and_semester(): void
    {
        Mail::fake();
        Program::create([
            'name' => 'Computer Science',
            'duration_years' => 4,
            'status' => 'active',
        ]);
        $admin = User::factory()->create([
            'role' => 'admin',
            'must_change_password' => false,
        ]);

        $this->actingAs($admin)
            ->post(route('students.store'), [
                'first_name' => 'Short',
                'last_name' => 'Learner',
                'email' => 'short.learner@example.com',
                'school_code' => '01',
                'nrc' => '123456/78/9',
                'date_of_birth' => '2005-04-15',
                'gender' => 'other',
                'enrollment_date' => '2026-01-01',
                'program' => 'Computer Science',
                'student_type' => 'short_course',
                'tuition_fee' => 500,
                'status' => 'active',
            ])->assertRedirect();

        $this->assertDatabaseHas('students', [
            'email' => 'short.learner@example.com',
            'student_type' => 'short_course',
            'year' => 1,
            'semester' => 1,
        ]);
    }

    private function student(string $type, string $enrollmentDate): Student
    {
        $user = User::factory()->create([
            'role' => 'student',
            'must_change_password' => false,
        ]);

        return Student::create([
            'user_id' => $user->id,
            'student_id' => fake()->unique()->numerify('STU#####'),
            'first_name' => 'Test',
            'last_name' => 'Student',
            'email' => $user->email,
            'date_of_birth' => '2005-01-01',
            'gender' => 'other',
            'enrollment_date' => $enrollmentDate,
            'program' => 'Computer Science',
            'student_type' => $type,
            'year' => 1,
            'semester' => 1,
            'status' => 'active',
        ]);
    }
}