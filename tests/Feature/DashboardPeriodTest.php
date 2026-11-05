<?php

namespace Tests\Feature;

use App\Models\AcademicCalendar;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DashboardPeriodTest extends TestCase
{
    use RefreshDatabase;

    public function test_semester_revenue_uses_calendar_start_and_excludes_future_and_pending_payments(): void
    {
        $this->travelTo(now()->setDate(2026, 11, 4)->setTime(12, 0));
        AcademicCalendar::create([
            'academic_year' => '2026', 'semester' => 2,
            'semester_start_date' => '2026-10-01', 'semester_end_date' => '2026-12-31',
            'term_1_start_date' => '2026-10-01', 'term_1_end_date' => '2026-10-31',
            'term_2_start_date' => '2026-11-01', 'term_2_end_date' => '2026-11-30',
            'term_3_start_date' => '2026-12-01', 'term_3_end_date' => '2026-12-31',
        ]);
        $student = Student::create([
            'student_id' => 'PERIOD001', 'first_name' => 'Period', 'last_name' => 'Student',
            'email' => 'period@example.test', 'date_of_birth' => '2000-01-01',
            'gender' => 'other', 'enrollment_date' => '2026-01-01',
        ]);
        foreach ([
            ['2026-09-30 23:59:59', 'completed', 10],
            ['2026-10-01 00:00:00', 'completed', 20],
            ['2026-11-04 12:00:00', 'completed', 30],
            ['2026-11-05 00:00:00', 'completed', 40],
            ['2026-11-01 00:00:00', 'pending', 50],
        ] as [$createdAt, $status, $amount]) {
            DB::table('payments')->insert([
                'student_id' => $student->id, 'amount' => $amount, 'status' => $status,
                'created_at' => $createdAt, 'updated_at' => $createdAt,
            ]);
        }

        $this->actingAs($this->admin())->get(route('dashboard', ['period' => 'semester']))
            ->assertOk()->assertViewHas('stats', fn ($stats) => (float) $stats['total_revenue'] === 50.0
                && $stats['completed_payments'] === 2)
            ->assertViewHas('recent_payments', fn ($payments) => $payments->count() === 3)
            ->assertSee('2026 / Semester 2');
    }

    public function test_missing_semester_returns_empty_filtered_lists_with_explanation(): void
    {
        $this->actingAs($this->admin())->get(route('dashboard', ['period' => 'semester']))
            ->assertOk()
            ->assertViewHas('recent_payments', fn ($payments) => $payments->isEmpty())
            ->assertViewHas('recent_receipts', fn ($receipts) => $receipts->isEmpty())
            ->assertSee('No current semester is configured.');
    }

    public function test_invalid_period_is_rejected(): void
    {
        $this->actingAs($this->admin())->getJson(route('dashboard', ['period' => 'invalid']))
            ->assertUnprocessable()->assertJsonValidationErrors('period');
    }

    private function admin(): User
    {
        return User::factory()->create(['role' => 'super_admin', 'must_change_password' => false]);
    }
}