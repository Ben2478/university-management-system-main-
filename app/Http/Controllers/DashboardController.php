<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Payment;
use App\Models\Receipt;
use App\Models\Enrollment;
use App\Models\Grade;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isSuperAdmin()) {
            return $this->superAdminDashboard();
        } elseif ($user->isAdmin()) {
            return $this->adminDashboard();
        } elseif ($user->isStudent()) {
            return $this->studentDashboard();
        } elseif ($user->isParent()) {
            return $this->parentDashboard();
        } elseif (method_exists($user, 'isLecturer') && $user->isLecturer()) {
            return $this->lecturerDashboard();
        }

        return redirect('login');
    }

    private function lecturerDashboard()
    {
        $lecturer = auth()->user()->lecturer;
        if (!$lecturer) {
            return redirect('login');
        }

        $courses = $lecturer->courses()
            ->withCount(['enrollments' => function ($q) {
                $q->where('status', 'active');
            }])
            ->orderBy('course_name')
            ->get();

        $stats = [
            'courses_count' => $courses->count(),
            'active_enrollments' => $courses->sum('enrollments_count'),
        ];

        return view('dashboards.lecturer', compact('lecturer', 'courses', 'stats'));
    }

    private function superAdminDashboard()
    {
        // Get time period filter (default: all time)
        $period = request('period', 'all');
        $dateFrom = $this->getDateFromPeriod($period);

        // Enhanced stats with time filtering
        $stats = [
            'total_students' => Student::count(),
            'active_students' => Student::whereHas('enrollments', function($q) {
                $q->where('status', 'active');
            })->count(),
            'total_users' => User::count(),
            'active_users' => User::whereNull('deleted_at')->count(),
            'recent_registrations' => User::where('created_at', '>=', now()->subDays(30))->count(),
            
            'total_revenue' => Payment::when($dateFrom, function($q) use ($dateFrom) {
                $q->where('created_at', '>=', $dateFrom);
            })->sum('amount'),
            'completed_payments' => Payment::where('status', 'completed')
                ->when($dateFrom, function($q) use ($dateFrom) {
                    $q->where('created_at', '>=', $dateFrom);
                })->count(),
            'pending_payments' => Payment::where('status', 'pending')->count(),
            
            'pending_receipts' => Receipt::where('status', 'pending')->count(),
            'pending_receipts_amount' => Receipt::where('status', 'pending')->sum('amount'),
            'approved_receipts' => Receipt::where('status', 'approved')
                ->when($dateFrom, function($q) use ($dateFrom) {
                    $q->where('created_at', '>=', $dateFrom);
                })->count(),
            'rejected_receipts' => Receipt::where('status', 'rejected')->count(),
            
            'total_enrollments' => Enrollment::count(),
            'active_enrollments' => Enrollment::where('status', 'active')->count(),
        ];

        // Calculate approval rate
        $totalReceipts = Receipt::count();
        $stats['approval_rate'] = $totalReceipts > 0 
            ? round((Receipt::where('status', 'approved')->count() / $totalReceipts) * 100, 1) 
            : 0;

        // Recent payments with filtering
        $recent_payments = Payment::with('student')
            ->when($dateFrom, function($q) use ($dateFrom) {
                $q->where('created_at', '>=', $dateFrom);
            })
            ->latest()
            ->limit(10)
            ->get();

        // Recent receipts with filtering
        $recent_receipts = Receipt::with('student')
            ->when($dateFrom, function($q) use ($dateFrom) {
                $q->where('created_at', '>=', $dateFrom);
            })
            ->latest()
            ->limit(10)
            ->get();

        // Payment trend data (last 12 months)
        $payment_trend = $this->getPaymentTrend();

        // Enrollment by program
        $enrollment_by_program = Student::selectRaw('program, COUNT(*) as count')
            ->groupBy('program')
            ->pluck('count', 'program')
            ->toArray();

        // System alerts
        $alerts = $this->getSuperAdminAlerts();

        // Recent activity feed
        $recent_activity = $this->getRecentActivity();

        return view('dashboards.super-admin', compact(
            'stats', 
            'recent_payments', 
            'recent_receipts', 
            'payment_trend',
            'enrollment_by_program',
            'alerts',
            'recent_activity',
            'period'
        ));
    }

    // Helper method to get date from period
    private function getDateFromPeriod($period)
    {
        return match($period) {
            'today' => now()->startOfDay(),
            'week' => now()->startOfWeek(),
            'month' => now()->startOfMonth(),
            'semester' => now()->subMonths(6),
            'year' => now()->startOfYear(),
            default => null, // all time
        };
    }

    // Get payment trend for last 12 months
    private function getPaymentTrend()
    {
        $months = [];
        $data = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M');
            
            $amount = Payment::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('amount');
            
            $data[] = round($amount, 2);
        }

        return [
            'labels' => $months,
            'data' => $data,
        ];
    }

    // Get system alerts for super admin
    private function getSuperAdminAlerts()
    {
        $alerts = [];

        // Pending receipts alert
        $pendingCount = Receipt::where('status', 'pending')->count();
        if ($pendingCount > 0) {
            $alerts[] = [
                'type' => 'warning',
                'icon' => '⚠️',
                'message' => "$pendingCount receipt(s) pending review",
                'link' => route('receipts.index', ['status' => 'pending']),
            ];
        }

        // Old pending receipts (over 3 days)
        $oldPending = Receipt::where('status', 'pending')
            ->where('created_at', '<', now()->subDays(3))
            ->count();
        if ($oldPending > 0) {
            $alerts[] = [
                'type' => 'danger',
                'icon' => '🚨',
                'message' => "$oldPending receipt(s) pending over 3 days",
                'link' => route('receipts.index', ['status' => 'pending']),
            ];
        }

        // Inactive users
        $inactiveUsers = User::whereNull('deleted_at')
            ->where('updated_at', '<', now()->subDays(30))
            ->count();
        if ($inactiveUsers > 5) {
            $alerts[] = [
                'type' => 'info',
                'icon' => 'ℹ️',
                'message' => "$inactiveUsers users inactive for 30+ days",
                'link' => route('users.index'),
            ];
        }

        return $alerts;
    }

    // Get recent activity across all modules
    private function getRecentActivity()
    {
        $activities = [];

        // Recent payments
        $recentPayments = Payment::with('student')->latest()->limit(3)->get();
        foreach ($recentPayments as $payment) {
            $activities[] = [
                'type' => 'payment',
                'icon' => '💰',
                'description' => "Payment of K" . number_format($payment->amount, 2) . " received from " . ($payment->student->first_name ?? 'Unknown'),
                'time' => $payment->created_at,
            ];
        }

        // Recent receipts
        $recentReceipts = Receipt::with('student')->latest()->limit(3)->get();
        foreach ($recentReceipts as $receipt) {
            $activities[] = [
                'type' => 'receipt',
                'icon' => '📄',
                'description' => "Receipt uploaded by " . ($receipt->student->first_name ?? 'Unknown') . " - " . ucfirst($receipt->status),
                'time' => $receipt->created_at,
            ];
        }

        // Recent enrollments
        $recentEnrollments = Enrollment::with('student', 'course')->latest()->limit(2)->get();
        foreach ($recentEnrollments as $enrollment) {
            $activities[] = [
                'type' => 'enrollment',
                'icon' => '📚',
                'description' => ($enrollment->student->first_name ?? 'Student') . " enrolled in " . ($enrollment->course->course_name ?? 'course'),
                'time' => $enrollment->created_at,
            ];
        }

        // Recent users
        $recentUsers = User::latest()->limit(2)->get();
        foreach ($recentUsers as $user) {
            $activities[] = [
                'type' => 'user',
                'icon' => '👤',
                'description' => "New user registered: " . $user->name,
                'time' => $user->created_at,
            ];
        }

        // Sort by time and take top 10
        return collect($activities)
            ->sortByDesc('time')
            ->take(10)
            ->values()
            ->all();
    }

    private function adminDashboard()
    {
        // Admin (Accountant) dashboard - comprehensive financial overview
        $stats = [
            // Receipt stats
            'pending_receipts' => Receipt::where('status', 'pending')->count(),
            'pending_amount' => Receipt::where('status', 'pending')->sum('amount'),
            'approved_today' => Receipt::where('status', 'approved')->whereDate('reviewed_at', today())->count(),
            'approval_rate' => Receipt::count() > 0 
                ? round((Receipt::where('status', 'approved')->count() / Receipt::count()) * 100, 1)
                : 0,
            
            // Payment stats
            'total_collected' => Payment::where('status', 'completed')->sum('amount'),
            'today_revenue' => Payment::where('status', 'completed')->whereDate('created_at', today())->sum('amount'),
            'this_month_revenue' => Payment::where('status', 'completed')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('amount'),
            'pending_payments' => Payment::where('status', 'pending')->count(),
            
            // Student stats
            'total_students' => Student::count(),
            'students_with_balance' => Student::whereRaw("tuition_fee > (SELECT COALESCE(SUM(amount), 0) FROM payments WHERE student_id = students.id AND status = 'completed')")
                ->count(),
            'fully_paid' => Student::whereRaw("tuition_fee <= (SELECT COALESCE(SUM(amount), 0) FROM payments WHERE student_id = students.id AND status = 'completed')")
                ->count(),
        ];

        // Calculate outstanding balance
        $stats['outstanding_balance'] = Student::get()->sum(function($student) {
            $paid = Payment::where('student_id', $student->id)->where('status', 'completed')->sum('amount');
            return max(0, $student->tuition_fee - $paid);
        });

        // Average processing time for receipts
        $avgProcessingTime = Receipt::whereNotNull('reviewed_at')
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, uploaded_at, reviewed_at)) as avg_hours')
            ->value('avg_hours');
        $stats['avg_processing_hours'] = $avgProcessingTime ? round($avgProcessingTime, 1) : 0;

        // Pending receipts for review
        $pending_receipts = Receipt::where('status', 'pending')
            ->with('student')
            ->latest()
            ->limit(8)
            ->get();

        // Recent approved receipts
        $recent_approved = Receipt::where('status', 'approved')
            ->with('student', 'reviewer')
            ->latest('reviewed_at')
            ->limit(5)
            ->get();

        // Daily revenue trend (last 7 days)
        $daily_revenue = [];
        $daily_labels = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $daily_labels[] = $date->format('D');
            $daily_revenue[] = Payment::where('status', 'completed')
                ->whereDate('created_at', $date)
                ->sum('amount');
        }

        // Revenue by payment method
        $revenue_by_method = [
            'Bank Transfer' => Payment::where('status', 'completed')->where('payment_method', 'bank_transfer')->sum('amount'),
            'Mobile Money' => Payment::where('status', 'completed')->where('payment_method', 'mobile_money')->sum('amount'),
            'Cash' => Payment::where('status', 'completed')->where('payment_method', 'cash')->sum('amount'),
            'Card' => Payment::where('status', 'completed')->where('payment_method', 'card')->sum('amount'),
            'Zananco' => Payment::where('status', 'completed')->where('payment_method', 'zananco_billmaster')->sum('amount'),
        ];

        // Receipt status breakdown
        $receipt_status = [
            'Pending' => Receipt::where('status', 'pending')->count(),
            'Approved' => Receipt::where('status', 'approved')->count(),
            'Rejected' => Receipt::where('status', 'rejected')->count(),
        ];

        // Students owing by program
        $owing_by_program = Student::with('payments')
            ->get()
            ->filter(function($student) {
                $paid = $student->payments->where('status', 'completed')->sum('amount');
                return $student->tuition_fee > $paid;
            })
            ->groupBy('program')
            ->map(function($students) {
                return $students->count();
            })
            ->sortDesc()
            ->take(5);

        // Quick alerts
        $alerts = [];
        
        // Old pending receipts
        $oldPending = Receipt::where('status', 'pending')
            ->where('uploaded_at', '<', now()->subDays(3))
            ->count();
        if ($oldPending > 0) {
            $alerts[] = [
                'type' => 'danger',
                'icon' => '🚨',
                'message' => "$oldPending receipt(s) pending over 3 days",
                'link' => route('receipts.pending'),
            ];
        }

        // Large pending amount
        if ($stats['pending_amount'] > 50000) {
            $alerts[] = [
                'type' => 'warning',
                'icon' => '⚠️',
                'message' => 'ZMW ' . number_format($stats['pending_amount'], 2) . ' in pending receipts',
                'link' => route('receipts.pending'),
            ];
        }

        return view('dashboards.admin', compact(
            'stats',
            'pending_receipts',
            'recent_approved',
            'daily_revenue',
            'daily_labels',
            'revenue_by_method',
            'receipt_status',
            'owing_by_program',
            'alerts'
        ));
    }

    private function studentDashboard()
    {
        $student = Student::where('user_id', auth()->id())->first();
        
        // If student record doesn't exist yet, show profile setup page
        if (!$student) {
            return view('dashboards.student-setup', [
                'message' => 'Please complete your student profile to access the dashboard.'
            ]);
        }

        // Calculate GPA
        $grades = $student->grades()->with('course')->get();
        $gpa = 0;
        $totalCredits = 0;
        
        foreach ($grades as $grade) {
            $gradePoints = match($grade->grade) {
                'A' => 4.0,
                'B' => 3.0,
                'C' => 2.0,
                'D' => 1.0,
                default => 0.0
            };
            $credits = $grade->course->credits ?? 3;
            $gpa += $gradePoints * $credits;
            $totalCredits += $credits;
        }
        
        $gpa = $totalCredits > 0 ? round($gpa / $totalCredits, 2) : 0;

        $stats = [
            'enrolled_courses' => $student->enrollments()->where('status', 'active')->count(),
            'total_enrollments' => $student->enrollments()->count(),
            'balance_due' => $student->getBalance(),
            'amount_paid' => $student->getPaidAmount(),
            'gpa' => $gpa,
            'total_grades' => $grades->count(),
            'pending_receipts' => $student->receipts()->where('status', 'pending')->count(),
            'average_grade' => $grades->avg('overall_score') ?? 0,
            'total_credits' => $student->enrollments()->with('course')->get()->sum(function($enrollment) {
                return $enrollment->course->credits ?? 3;
            }),
        ];

        $enrollments = $student->enrollments()
            ->with('course')
            ->where('status', 'active')
            ->latest()
            ->limit(6)
            ->get();

        $recentGrades = Grade::where('student_id', $student->id)
            ->with('course')
            ->latest('updated_at')
            ->limit(5)
            ->get();

        $receipts = $student->receipts()
            ->latest()
            ->limit(3)
            ->get();

        $payments = $student->payments()
            ->where('status', 'completed')
            ->latest('paid_at')
            ->limit(3)
            ->get();

        // Get upcoming/important info
        $alerts = [];
        
        if ($stats['balance_due'] > 0) {
            $alerts[] = [
                'type' => 'warning',
                'icon' => '💰',
                'message' => 'You have an outstanding balance of K' . number_format($stats['balance_due'], 2),
                'action' => route('receipts.create', $student),
                'action_text' => 'Upload Receipt'
            ];
        }
        
        if ($stats['pending_receipts'] > 0) {
            $alerts[] = [
                'type' => 'info',
                'icon' => '⏳',
                'message' => 'You have ' . $stats['pending_receipts'] . ' receipt(s) awaiting verification',
                'action' => route('receipts.student-history'),
                'action_text' => 'View Status'
            ];
        }

        if ($stats['enrolled_courses'] === 0) {
            $alerts[] = [
                'type' => 'warning',
                'icon' => '📚',
                'message' => 'You are not enrolled in any courses this semester',
                'action' => route('enrollments.available'),
                'action_text' => 'Browse Courses'
            ];
        }

        return view('dashboards.student', compact('student', 'stats', 'enrollments', 'recentGrades', 'receipts', 'payments', 'alerts'));
    }

    private function parentDashboard()
    {
        $user = auth()->user();
        
        // Parent can see their child's information
        $students = Student::where('parent_email', $user->email)
            ->orWhere('parent_id', $user->id)
            ->with('user')
            ->get();

        // If no students linked, show setup page
        if ($students->isEmpty()) {
            return view('dashboards.parent-setup', [
                'message' => 'No students are currently linked to your parent account.'
            ]);
        }

        $stats = [
            'children_count' => $students->count(),
            'total_balance_due' => $students->sum(function ($student) {
                return $student->getBalance();
            }),
            'total_paid' => $students->sum(function ($student) {
                return $student->getPaidAmount();
            }),
        ];

        $children_summary = $students->map(function ($student) {
            return [
                'name' => $student->user->name ?? 'Unknown',
                'student_id' => $student->id,
                'balance' => $student->getBalance(),
                'enrollments' => $student->enrollments()->count(),
            ];
        });

        return view('dashboards.parent', compact('stats', 'children_summary', 'students'));
    }
}
