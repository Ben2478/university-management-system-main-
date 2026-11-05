<?php

namespace Tests\Feature;

use App\Models\AcademicCalendar;
use App\Models\Course;
use App\Models\User;
use App\Services\DashboardInsights;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardInsightsTest extends TestCase
{
    use RefreshDatabase;

    public function test_calendar_uses_inclusive_term_end_dates_and_preserves_breaks(): void
    {
        $calendar = $this->calendar();
        $user = User::factory()->create(['role' => 'admin']);
        $this->travelTo(now()->setDate(2026, 2, 28)->startOfDay());

        $insights = app(DashboardInsights::class)->forUser($user);
        $this->assertSame($calendar->id, $insights['calendar']->id);
        $this->assertSame(1, $insights['term']);

        $this->travelTo(now()->setDate(2026, 3, 5)->startOfDay());
        $insights = app(DashboardInsights::class)->forUser($user);
        $this->assertNotNull($insights['calendar']);
        $this->assertNull($insights['term']);
    }

    public function test_upcoming_calendar_is_not_reported_as_current(): void
    {
        $calendar = $this->calendar();
        $this->travelTo(now()->setDate(2025, 12, 31)->startOfDay());

        $insights = app(DashboardInsights::class)->forUser(User::factory()->make(['role' => 'student']));
        $this->assertNull($insights['calendar']);
        $this->assertSame($calendar->id, $insights['nextCalendar']->id);
    }

    public function test_student_does_not_receive_staff_actions(): void
    {
        Course::create([
            'course_code' => 'UNASSIGNED', 'course_name' => 'Unassigned course',
            'credits' => 3, 'department' => 'Computing', 'term' => 1, 'status' => 'active',
        ]);
        $staff = app(DashboardInsights::class)->forUser(User::factory()->create(['role' => 'super_admin']));
        $this->assertContains('Courses without a lecturer', array_column($staff['actions'], 'label'));

        $student = app(DashboardInsights::class)->forUser(User::factory()->create(['role' => 'student']));
        $this->assertSame([], $student['actions']);
        $this->assertFalse($student['hasCalendar']);
    }

    public function test_unconfigured_calendar_renders_clear_message_on_dashboard(): void
    {
        $this->actingAs(User::factory()->create([
            'role' => 'super_admin', 'must_change_password' => false,
        ]))->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Action Required')
            ->assertSee('Current Academic Period')
            ->assertSee('Calendar not configured.');
    }

    private function calendar(): AcademicCalendar
    {
        return AcademicCalendar::create([
            'academic_year' => '2026', 'semester' => 1,
            'semester_start_date' => '2026-01-01', 'semester_end_date' => '2026-06-30',
            'term_1_start_date' => '2026-01-01', 'term_1_end_date' => '2026-02-28',
            'term_2_start_date' => '2026-03-10', 'term_2_end_date' => '2026-04-30',
            'term_3_start_date' => '2026-05-01', 'term_3_end_date' => '2026-06-30',
        ]);
    }
}