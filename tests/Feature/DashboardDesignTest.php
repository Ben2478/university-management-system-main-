<?php

namespace Tests\Feature;

use App\Models\Lecturer;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class DashboardDesignTest extends TestCase
{
    use RefreshDatabase;

    #[DataProvider('dashboards')]
    public function test_each_dashboard_uses_the_new_design(string $view, string $role, string $marker): void
    {
        $user = User::factory()->make(['name' => 'Dashboard Reviewer', 'role' => $role]);
        $this->actingAs($user);

        $stats = array_fill_keys([
            'total_students', 'active_students', 'total_users', 'recent_registrations',
            'total_revenue', 'completed_payments', 'pending_receipts', 'pending_receipts_amount',
            'total_enrollments', 'active_enrollments', 'approval_rate', 'approved_receipts',
            'pending_amount', 'today_revenue', 'approved_today', 'total_collected',
            'outstanding_balance', 'students_with_balance', 'this_month_revenue', 'fully_paid',
            'enrolled_courses', 'total_credits', 'gpa', 'total_grades', 'amount_paid',
            'balance_due', 'average_grade', 'courses_count', 'children_count',
            'total_balance_due', 'total_paid',
        ], 0);

        $student = new Student([
            'first_name' => 'Dashboard', 'last_name' => 'Reviewer',
            'student_id' => 'REVIEW001', 'program' => 'Computing',
            'year' => 1, 'semester' => 1, 'tuition_fee' => 0,
        ]);

        $data = compact('stats', 'student') + [
            'errors' => new \Illuminate\Support\ViewErrorBag(),
            'period' => 'all', 'alerts' => [], 'recent_activity' => [],
            'recent_payments' => collect(), 'recent_receipts' => collect(),
            'payment_trend' => ['labels' => [], 'data' => []],
            'enrollment_by_program' => [], 'daily_revenue' => [], 'daily_labels' => [],
            'revenue_by_method' => [], 'receipt_status' => [], 'owing_by_program' => collect(),
            'pending_receipts' => collect(), 'recent_approved' => collect(),
            'enrollments' => collect(), 'recentGrades' => collect(),
            'receipts' => collect(), 'payments' => collect(),
            'lecturer' => new Lecturer(['first_name' => 'Dashboard', 'last_name' => 'Reviewer']),
            'courses' => collect(), 'children_summary' => collect(),
        ];

        $response = $this->view('dashboards.' . $view, $data);
        $response->assertSee('data-dashboard="' . $marker . '"', false);
        $response->assertSee('dashboard-heading');
        $response->assertSee('css/dashboards.css');
        $response->assertSee('Phoenix Open University');

        if (!str_ends_with($view, '-setup')) {
            $response->assertSee('aria-label="Dashboard metrics"', false);
        }

        if ($view === 'super-admin') {
            $html = (string) $response;
            $this->assertSame(4, substr_count($html, '<article class="dashboard-metric"'));
            $response->assertSee('Total Students');
            $response->assertSee('Total Users');
            $response->assertSee('Total Revenue');
            $response->assertSee('Pending Receipts');
            $response->assertDontSee('>Approval Rate</h2>', false);
            $response->assertDontSee('>Enrollments</h2>', false);
        }
    }

    public static function dashboards(): array
    {
        return [
            ['super-admin', 'super_admin', 'super-admin'],
            ['admin', 'admin', 'accountant'],
            ['student', 'student', 'student'],
            ['lecturer', 'lecturer', 'lecturer'],
            ['parent', 'parent', 'parent'],
            ['student-setup', 'student', 'student-setup'],
            ['parent-setup', 'parent', 'parent-setup'],
        ];
    }
}