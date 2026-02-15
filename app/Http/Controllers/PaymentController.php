<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Student;
use App\Services\Payment\ZnancoGateway;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    private ZnancoGateway $gateway;

    public function __construct(ZnancoGateway $gateway)
    {
        $this->gateway = $gateway;
    }

    /**
     * Show payment analytics and reports (accountant dashboard)
     */
    public function reports(Request $request): View
    {
        // Date range for filtering (default: last 30 days)
        $dateFrom = $request->get('date_from', now()->subDays(30)->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));

        // Overall statistics
        $stats = [
            'total_revenue' => Payment::where('status', 'completed')->sum('amount'),
            'total_payments' => Payment::count(),
            'completed_count' => Payment::where('status', 'completed')->count(),
            'pending_count' => Payment::where('status', 'pending')->count(),
            'failed_count' => Payment::where('status', 'failed')->count(),
            'success_rate' => Payment::count() > 0 
                ? round((Payment::where('status', 'completed')->count() / Payment::count()) * 100, 1)
                : 0,
            'avg_payment' => Payment::where('status', 'completed')->avg('amount') ?? 0,
        ];

        // This month vs last month comparison
        $thisMonthRevenue = Payment::where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');

        $lastMonthRevenue = Payment::where('status', 'completed')
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->sum('amount');

        $stats['this_month_revenue'] = $thisMonthRevenue;
        $stats['last_month_revenue'] = $lastMonthRevenue;
        $stats['month_growth'] = $lastMonthRevenue > 0 
            ? round((($thisMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1)
            : 0;

        // Revenue by payment method (for pie chart)
        $revenueByMethod = [
            'Bank Transfer' => Payment::where('status', 'completed')->where('payment_method', 'bank_transfer')->sum('amount'),
            'Mobile Money' => Payment::where('status', 'completed')->where('payment_method', 'mobile_money')->sum('amount'),
            'Cash' => Payment::where('status', 'completed')->where('payment_method', 'cash')->sum('amount'),
            'Card' => Payment::where('status', 'completed')->where('payment_method', 'card')->sum('amount'),
            'Zananco' => Payment::where('status', 'completed')->where('payment_method', 'zananco_billmaster')->sum('amount'),
        ];

        // Payments by status (for pie chart)
        $paymentsByStatus = [
            'Completed' => Payment::where('status', 'completed')->count(),
            'Pending' => Payment::where('status', 'pending')->count(),
            'Processing' => Payment::where('status', 'processing')->count(),
            'Failed' => Payment::where('status', 'failed')->count(),
            'Cancelled' => Payment::where('status', 'cancelled')->count(),
        ];

        // Daily revenue (last 30 days for line chart)
        $dailyRevenue = Payment::where('status', 'completed')
            ->whereDate('created_at', '>=', now()->subDays(30))
            ->selectRaw('DATE(created_at) as date, SUM(amount) as revenue, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $dailyRevenueChart = [
            'labels' => $dailyRevenue->pluck('date')->map(fn($d) => date('M d', strtotime($d)))->toArray(),
            'revenue' => $dailyRevenue->pluck('revenue')->toArray(),
            'counts' => $dailyRevenue->pluck('count')->toArray(),
        ];

        // Monthly revenue trends (last 12 months)
        $monthlyRevenue = Payment::where('status', 'completed')
            ->whereDate('created_at', '>=', now()->subMonths(12))
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(amount) as revenue, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $monthlyRevenueChart = [
            'labels' => $monthlyRevenue->pluck('month')->map(fn($m) => date('M Y', strtotime($m . '-01')))->toArray(),
            'revenue' => $monthlyRevenue->pluck('revenue')->toArray(),
            'counts' => $monthlyRevenue->pluck('count')->toArray(),
        ];

        // Revenue by program
        $revenueByProgram = Payment::where('status', 'completed')
            ->with('student')
            ->get()
            ->groupBy(fn($payment) => $payment->student->program ?? 'Unknown')
            ->map(fn($group) => [
                'revenue' => $group->sum('amount'),
                'count' => $group->count(),
            ])
            ->sortByDesc('revenue')
            ->take(10);

        // Top paying students
        $topStudents = Payment::where('status', 'completed')
            ->with('student')
            ->selectRaw('student_id, SUM(amount) as total_paid, COUNT(*) as payment_count')
            ->groupBy('student_id')
            ->orderByDesc('total_paid')
            ->limit(10)
            ->get();

        // Payment method trends over months (for stacked chart)
        $methodTrends = Payment::where('status', 'completed')
            ->whereDate('created_at', '>=', now()->subMonths(6))
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, payment_method, SUM(amount) as revenue')
            ->groupBy('month', 'payment_method')
            ->orderBy('month')
            ->get()
            ->groupBy('month');

        return view('payments.reports', compact(
            'stats',
            'revenueByMethod',
            'paymentsByStatus',
            'dailyRevenueChart',
            'monthlyRevenueChart',
            'revenueByProgram',
            'topStudents',
            'methodTrends',
            'dateFrom',
            'dateTo'
        ));
    }

    /**
     * Show all payments (admin view)
     */
    public function index(Request $request): View
    {
        $query = Payment::with('student');

        // Search
        if ($search = $request->get('search')) {
            $query->where(function($q) use ($search) {
                $q->whereHas('student', function($sq) use ($search) {
                    $sq->where('first_name', 'like', "%{$search}%")
                       ->orWhere('last_name', 'like', "%{$search}%")
                       ->orWhere('student_id', 'like', "%{$search}%");
                })
                ->orWhere('reference_number', 'like', "%{$search}%")
                ->orWhere('transaction_id', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        // Filter by payment method
        if ($method = $request->get('payment_method')) {
            $query->where('payment_method', $method);
        }

        // Filter by program
        if ($program = $request->get('program')) {
            $query->whereHas('student', function($q) use ($program) {
                $q->where('program', $program);
            });
        }

        // Filter by date range
        if ($dateFrom = $request->get('date_from')) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        if ($dateTo = $request->get('date_to')) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        // Filter by amount range
        if ($amountMin = $request->get('amount_min')) {
            $query->where('amount', '>=', $amountMin);
        }
        if ($amountMax = $request->get('amount_max')) {
            $query->where('amount', '<=', $amountMax);
        }

        // Calculate comprehensive stats
        $stats = [
            'total_revenue' => Payment::where('status', 'completed')->sum('amount'),
            'pending_amount' => Payment::where('status', 'pending')->sum('amount'),
            'pending_count' => Payment::where('status', 'pending')->count(),
            'completed_count' => Payment::where('status', 'completed')->count(),
            'completed_rate' => Payment::count() > 0 
                ? round((Payment::where('status', 'completed')->count() / Payment::count()) * 100, 1)
                : 0,
            'failed_count' => Payment::where('status', 'failed')->count(),
            'total_count' => Payment::count(),
            'today_revenue' => Payment::where('status', 'completed')->whereDate('created_at', today())->sum('amount'),
            'today_count' => Payment::where('status', 'completed')->whereDate('created_at', today())->count(),
            'this_month_revenue' => Payment::where('status', 'completed')->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->sum('amount'),
        ];

        // Calculate by payment method
        $stats['by_method'] = [
            'bank_transfer' => Payment::where('status', 'completed')->where('payment_method', 'bank_transfer')->sum('amount'),
            'mobile_money' => Payment::where('status', 'completed')->where('payment_method', 'mobile_money')->sum('amount'),
            'cash' => Payment::where('status', 'completed')->where('payment_method', 'cash')->sum('amount'),
            'card' => Payment::where('status', 'completed')->where('payment_method', 'card')->sum('amount'),
            'zananco_billmaster' => Payment::where('status', 'completed')->where('payment_method', 'zananco_billmaster')->sum('amount'),
        ];

        $payments = $query->orderByDesc('created_at')->paginate(15);

        // Get unique programs for filter dropdown
        $programs = Student::select('program')->distinct()->orderBy('program')->pluck('program');

        return view('payments.index', compact('payments', 'stats', 'programs'));
    }

    /**
     * Show payment form for a student
     */
    public function create(Student $student): View
    {
        return view('payments.create', compact('student'));
    }

    /**
     * Store a new payment and initiate with gateway
     */
    public function store(Request $request, Student $student)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:255',
        ]);

        try {
            // Create payment record
            $payment = Payment::create([
                'student_id' => $student->id,
                'amount' => $validated['amount'],
                'description' => $validated['description'] ?? 'Course Tuition Payment',
                'status' => 'pending',
                'reference_number' => 'PAY_' . time() . '_' . $student->id,
            ]);

            // Initiate with Zananco
            $result = $this->gateway->initiatePayment(
                amount: (string) $validated['amount'],
                description: $validated['description'] ?? 'Course Tuition',
                customerName: $student->first_name . ' ' . $student->last_name,
                customerEmail: $student->email,
                phoneNumber: $student->phone ?? '260',
                reference: $payment->reference_number
            );

            if ($result['success']) {
                $payment->update([
                    'transaction_id' => $result['transaction_id'] ?? null,
                    'status' => 'processing',
                ]);

                return redirect($result['payment_url'])
                    ->with('info', 'Redirecting to payment gateway...');
            }

            $payment->markAsFailed($result['error'], $result);
            return back()->withErrors(['error' => 'Failed to initiate payment: ' . $result['error']]);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Payment error: ' . $e->getMessage()]);
        }
    }

    /**
     * Payment callback from Zananco (webhook)
     */
    public function callback(Request $request)
    {
        $data = $request->all();

        // Verify signature
        if (!$this->gateway->verifyWebhookSignature(
            $data,
            $request->get('signature', '')
        )) {
            return response()->json(['error' => 'Invalid signature'], 401);
        }

        $transactionId = $data['transaction_id'] ?? null;
        $referenceNumber = $data['reference'] ?? null;

        if (!$transactionId || !$referenceNumber) {
            return response()->json(['error' => 'Missing transaction data'], 400);
        }

        $payment = Payment::where('reference_number', $referenceNumber)->first();
        if (!$payment) {
            return response()->json(['error' => 'Payment not found'], 404);
        }

        // Verify with gateway
        $verification = $this->gateway->verifyPayment($transactionId);

        if ($verification['success'] && $verification['status'] === 'completed') {
            $payment->markAsCompleted($transactionId, $verification['response']);
            return response()->json(['success' => true, 'message' => 'Payment confirmed']);
        }

        $payment->markAsFailed('Payment verification failed', $verification['response'] ?? []);
        return response()->json(['success' => false, 'error' => 'Payment verification failed']);
    }

    /**
     * Show payment details
     */
    public function show(Payment $payment): View
    {
        $payment->load('student');
        return view('payments.show', compact('payment'));
    }

    /**
     * Student payment history (admin viewing)
     */
    public function studentPayments(Student $student): View
    {
        $payments = $student->payments()
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('payments.student-history', compact('student', 'payments'));
    }

    /**
     * Show payment history for logged-in student
     */
    public function myPayments(): View
    {
        $student = auth()->user()->student;
        
        if (!$student) {
            abort(403, 'Student record not found for this user.');
        }

        $payments = $student->payments()
            ->orderByDesc('created_at')
            ->paginate(15);

        // Calculate stats
        $stats = [
            'total_paid' => $student->payments()->where('status', 'completed')->sum('amount'),
            'pending_payments' => $student->payments()->where('status', 'pending')->count(),
            'total_payments' => $student->payments()->count(),
            'last_payment' => $student->payments()->where('status', 'completed')->latest('paid_at')->first(),
            'tuition_fee' => $student->tuition_fee,
            'balance' => $student->getBalance(),
        ];

        return view('payments.my-history', compact('student', 'payments', 'stats'));
    }

    /**
     * Manual payment verification (admin)
     */
    public function verify(Payment $payment)
    {
        if (!$payment->transaction_id) {
            return back()->withErrors(['error' => 'No transaction ID found']);
        }

        $result = $this->gateway->verifyPayment($payment->transaction_id);

        if ($result['success'] && $result['status'] === 'completed') {
            $payment->markAsCompleted($payment->transaction_id, $result['response']);
            return back()->with('success', 'Payment verified and marked as completed');
        }

        return back()->withErrors(['error' => $result['error'] ?? 'Verification failed']);
    }

    /**
     * Export payments to CSV
     */
    public function export(Request $request)
    {
        $query = Payment::with('student');

        // Apply same filters as index
        if ($search = $request->get('search')) {
            $query->where(function($q) use ($search) {
                $q->whereHas('student', function($sq) use ($search) {
                    $sq->where('first_name', 'like', "%{$search}%")
                       ->orWhere('last_name', 'like', "%{$search}%")
                       ->orWhere('student_id', 'like', "%{$search}%");
                })
                ->orWhere('reference_number', 'like', "%{$search}%")
                ->orWhere('transaction_id', 'like', "%{$search}%");
            });
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($method = $request->get('payment_method')) {
            $query->where('payment_method', $method);
        }

        if ($program = $request->get('program')) {
            $query->whereHas('student', function($q) use ($program) {
                $q->where('program', $program);
            });
        }

        if ($dateFrom = $request->get('date_from')) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        if ($dateTo = $request->get('date_to')) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        if ($amountMin = $request->get('amount_min')) {
            $query->where('amount', '>=', $amountMin);
        }
        if ($amountMax = $request->get('amount_max')) {
            $query->where('amount', '<=', $amountMax);
        }

        $payments = $query->orderByDesc('created_at')->get();

        $filename = 'payments_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function() use ($payments) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Student ID', 'Student Name', 'Program', 'Amount', 'Description', 'Status', 'Reference Number', 'Transaction ID', 'Payment Method', 'Paid At', 'Created At']);

            foreach ($payments as $payment) {
                fputcsv($file, [
                    $payment->student->student_id,
                    $payment->student->first_name . ' ' . $payment->student->last_name,
                    $payment->student->program ?? 'N/A',
                    $payment->amount,
                    $payment->description,
                    $payment->status,
                    $payment->reference_number,
                    $payment->transaction_id ?? 'N/A',
                    $payment->payment_method,
                    $payment->paid_at?->format('Y-m-d H:i:s') ?? 'N/A',
                    $payment->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
