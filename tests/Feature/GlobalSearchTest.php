<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Program;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GlobalSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_search_returns_matching_students_courses_and_programmes(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'must_change_password' => false,
        ]);
        $student = Student::create([
            'student_id' => 'PHX2026001',
            'first_name' => 'Phoenix',
            'last_name' => 'Learner',
            'email' => 'phoenix.learner@example.com',
            'date_of_birth' => '2005-01-01',
            'gender' => 'other',
            'enrollment_date' => '2026-01-01',
            'program' => 'Phoenix Computing',
            'year' => 1,
            'semester' => 1,
            'status' => 'active',
        ]);
        Program::create([
            'name' => 'Phoenix Computing',
            'department' => 'Phoenix Faculty',
            'duration_years' => 4,
            'status' => 'active',
        ]);
        Course::create([
            'course_code' => 'PHX101',
            'course_name' => 'Phoenix Systems',
            'credits' => 3,
            'department' => 'Phoenix Computing',
            'term' => 1,
            'status' => 'active',
        ]);

        $this->actingAs($admin)
            ->getJson(route('global.search', ['q' => 'Phoenix']))
            ->assertOk()
            ->assertJsonCount(3, 'results')
            ->assertJsonFragment(['title' => 'Phoenix Learner', 'type' => 'Student'])
            ->assertJsonFragment(['title' => 'Phoenix Systems', 'type' => 'Course'])
            ->assertJsonFragment(['title' => 'Phoenix Computing', 'type' => 'Programme']);
    }

    public function test_student_search_only_returns_their_own_record_and_programme_courses(): void
    {
        $user = User::factory()->create([
            'role' => 'student',
            'must_change_password' => false,
        ]);
        Student::create([
            'user_id' => $user->id,
            'student_id' => 'PHX2026002',
            'first_name' => 'Phoenix',
            'last_name' => 'Student',
            'email' => $user->email,
            'date_of_birth' => '2005-01-01',
            'gender' => 'other',
            'enrollment_date' => '2026-01-01',
            'program' => 'Phoenix Computing',
            'year' => 1,
            'semester' => 1,
            'status' => 'active',
        ]);
        Student::create([
            'student_id' => 'PHX2026999',
            'first_name' => 'Private',
            'last_name' => 'Record',
            'email' => 'private.record@example.com',
            'date_of_birth' => '2005-01-01',
            'gender' => 'other',
            'enrollment_date' => '2026-01-01',
            'program' => 'Other Programme',
            'year' => 1,
            'semester' => 1,
            'status' => 'active',
        ]);
        Course::create([
            'course_code' => 'PHX201',
            'course_name' => 'Phoenix Networks',
            'credits' => 3,
            'department' => 'Phoenix Computing',
            'term' => 1,
            'status' => 'active',
        ]);
        Course::create([
            'course_code' => 'PHX202',
            'course_name' => 'Phoenix Medicine',
            'credits' => 3,
            'department' => 'Other Programme',
            'term' => 1,
            'status' => 'active',
        ]);

        $this->actingAs($user)
            ->getJson(route('global.search', ['q' => 'Phoenix']))
            ->assertOk()
            ->assertJsonFragment(['title' => 'Phoenix Student', 'type' => 'Student record'])
            ->assertJsonFragment(['title' => 'Phoenix Networks', 'type' => 'Course'])
            ->assertJsonMissing(['title' => 'Private Record'])
            ->assertJsonMissing(['title' => 'Phoenix Medicine']);
    }

    public function test_search_requires_at_least_two_characters_to_return_results(): void
    {
        $this->actingAs(User::factory()->create([
            'role' => 'admin',
            'must_change_password' => false,
        ]))
            ->getJson(route('global.search', ['q' => 'a']))
            ->assertOk()
            ->assertExactJson(['results' => []]);
    }

    public function test_authenticated_dashboard_renders_shared_global_header(): void
    {
        $user = User::factory()->create([
            'name' => 'Header Review User',
            'role' => 'super_admin',
            'must_change_password' => false,
        ]);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Phoenix Open University')
            ->assertSee('Management System')
            ->assertSee('Search students, courses, programmes...')
            ->assertSee('Ctrl')
            ->assertSee('aria-label="Notifications"', false)
            ->assertSee('aria-label="Help"', false)
            ->assertSee('class="wrapper phoenix-app-shell"', false)
            ->assertSee('class="content phoenix-app-content"', false)
            ->assertSee('Header Review User')
            ->assertSee('Super Admin');
    }

    public function test_super_admin_sidebar_preserves_groups_and_quick_actions(): void
    {
        $this->actingAs(User::factory()->create([
            'role' => 'super_admin',
            'must_change_password' => false,
        ]))
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Academic Management')
            ->assertSee('Academic Records')
            ->assertSee('Administration')
            ->assertSee('User Management')
            ->assertSee('Quick Actions')
            ->assertSee('Add Student')
            ->assertSee('data-sidebar-collapse', false)
            ->assertSee('data-sidebar-group="academic"', false);
    }

    public function test_student_sidebar_has_student_actions_without_administration_links(): void
    {
        $studentUser = User::factory()->create([
            'role' => 'student',
            'must_change_password' => false,
        ]);
        Student::create([
            'user_id' => $studentUser->id,
            'student_id' => 'SIDEBAR001',
            'first_name' => 'Navigation',
            'last_name' => 'Student',
            'email' => $studentUser->email,
            'date_of_birth' => '2005-01-01',
            'gender' => 'other',
            'enrollment_date' => '2026-01-01',
            'program' => 'Computer Science',
            'year' => 1,
            'semester' => 1,
            'status' => 'active',
        ]);

        $this->actingAs($studentUser)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('My Courses')
            ->assertSee('My Grades')
            ->assertSee('Quick Actions')
            ->assertSee('Browse Courses')
            ->assertDontSee('User Management')
            ->assertDontSee('Add Student');
    }
}