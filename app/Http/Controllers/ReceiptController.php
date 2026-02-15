<?php

namespace App\Http\Controllers;

use App\Models\Receipt;
use App\Models\Student;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReceiptController extends Controller
{
    /**
     * Show all pending receipts (for accountant)
     */
    public function pendingReceipts(): View
    {
        $receipts = Receipt::where('status', 'pending')
            ->with('student')
            ->orderByDesc('created_at')
            ->paginate(15);

        // Calculate stats
        $stats = [
            'pending_count' => $receipts->total(),
            'pending_amount' => Receipt::where('status', 'pending')->sum('amount'),
            'today_count' => Receipt::where('status', 'pending')->whereDate('uploaded_at', today())->count(),
            'oldest_pending' => Receipt::where('status', 'pending')->oldest('uploaded_at')->first(),
        ];

        return view('receipts.pending-list', compact('receipts', 'stats'));
    }

    /**
     * Show receipt analytics and reports (accountant dashboard)
     */
    public function reports(Request $request): View
    {
        // Date range for filtering (default: last 30 days)
        $dateFrom = $request->get('date_from', now()->subDays(30)->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));

        // Overall statistics
        $stats = [
            'total_receipts' => Receipt::count(),
            'pending_count' => Receipt::where('status', 'pending')->count(),
            'approved_count' => Receipt::where('status', 'approved')->count(),
            'rejected_count' => Receipt::where('status', 'rejected')->count(),
            'total_amount' => Receipt::where('status', 'approved')->sum('amount'),
            'approval_rate' => Receipt::count() > 0 
                ? round((Receipt::where('status', 'approved')->count() / Receipt::count()) * 100, 1)
                : 0,
        ];

        // Average processing time
        $avgProcessingTime = Receipt::whereNotNull('reviewed_at')
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, uploaded_at, reviewed_at)) as avg_hours')
            ->value('avg_hours');
        $stats['avg_processing_hours'] = $avgProcessingTime ? round($avgProcessingTime, 1) : 0;

        // Receipts by status (for pie chart)
        $receiptsByStatus = [
            'Pending' => Receipt::where('status', 'pending')->count(),
            'Approved' => Receipt::where('status', 'approved')->count(),
            'Rejected' => Receipt::where('status', 'rejected')->count(),
        ];

        // Receipts by payment method (for pie chart)
        $receiptsByMethod = [
            'Bank Transfer' => Receipt::where('payment_method', 'bank_transfer')->count(),
            'Mobile Money' => Receipt::where('payment_method', 'mobile_money')->count(),
            'Cash' => Receipt::where('payment_method', 'cash')->count(),
            'Card' => Receipt::where('payment_method', 'card')->count(),
            'Zananco Billmaster' => Receipt::where('payment_method', 'zananco_billmaster')->count(),
        ];

        // Daily receipt submissions (last 30 days for line chart)
        $dailyReceipts = Receipt::whereDate('uploaded_at', '>=', now()->subDays(30))
            ->selectRaw('DATE(uploaded_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Format for Chart.js
        $dailyReceiptsChart = [
            'labels' => $dailyReceipts->pluck('date')->map(fn($d) => date('M d', strtotime($d)))->toArray(),
            'data' => $dailyReceipts->pluck('count')->toArray(),
        ];

        // Monthly trends (last 12 months)
        $monthlyReceipts = Receipt::whereDate('uploaded_at', '>=', now()->subMonths(12))
            ->selectRaw('DATE_FORMAT(uploaded_at, "%Y-%m") as month, COUNT(*) as count, SUM(amount) as total_amount')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $monthlyReceiptsChart = [
            'labels' => $monthlyReceipts->pluck('month')->map(fn($m) => date('M Y', strtotime($m . '-01')))->toArray(),
            'counts' => $monthlyReceipts->pluck('count')->toArray(),
            'amounts' => $monthlyReceipts->pluck('total_amount')->toArray(),
        ];

        // Top students by receipt count
        $topStudents = Receipt::with('student')
            ->selectRaw('student_id, COUNT(*) as receipt_count, SUM(amount) as total_amount')
            ->groupBy('student_id')
            ->orderByDesc('receipt_count')
            ->limit(10)
            ->get();

        // Processing time distribution
        $processingTimeRanges = [
            '< 24 hours' => Receipt::whereNotNull('reviewed_at')
                ->whereRaw('TIMESTAMPDIFF(HOUR, uploaded_at, reviewed_at) < 24')->count(),
            '1-3 days' => Receipt::whereNotNull('reviewed_at')
                ->whereRaw('TIMESTAMPDIFF(HOUR, uploaded_at, reviewed_at) BETWEEN 24 AND 72')->count(),
            '3-7 days' => Receipt::whereNotNull('reviewed_at')
                ->whereRaw('TIMESTAMPDIFF(HOUR, uploaded_at, reviewed_at) BETWEEN 72 AND 168')->count(),
            '> 7 days' => Receipt::whereNotNull('reviewed_at')
                ->whereRaw('TIMESTAMPDIFF(HOUR, uploaded_at, reviewed_at) > 168')->count(),
        ];

        return view('receipts.reports', compact(
            'stats',
            'receiptsByStatus',
            'receiptsByMethod',
            'dailyReceiptsChart',
            'monthlyReceiptsChart',
            'topStudents',
            'processingTimeRanges',
            'dateFrom',
            'dateTo'
        ));
    }

    /**
     * Show all receipts (accountant dashboard)
     */
    public function index(Request $request): View
    {
        $query = Receipt::with('student', 'reviewer');

        // Search
        if ($search = $request->get('search')) {
            $query->where(function($q) use ($search) {
                $q->whereHas('student', function($sq) use ($search) {
                    $sq->where('first_name', 'like', "%{$search}%")
                       ->orWhere('last_name', 'like', "%{$search}%")
                       ->orWhere('student_id', 'like', "%{$search}%");
                })
                ->orWhere('amount', 'like', "%{$search}%")
                ->orWhere('original_filename', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        // Filter by program
        if ($program = $request->get('program')) {
            $query->whereHas('student', function($q) use ($program) {
                $q->where('program', $program);
            });
        }

        // Filter by reviewer
        if ($reviewer = $request->get('reviewer_id')) {
            $query->where('reviewed_by', $reviewer);
        }

        // Filter by date range (uploaded)
        if ($dateFrom = $request->get('date_from')) {
            $query->whereDate('uploaded_at', '>=', $dateFrom);
        }
        if ($dateTo = $request->get('date_to')) {
            $query->whereDate('uploaded_at', '<=', $dateTo);
        }

        // Filter by amount range
        if ($amountMin = $request->get('amount_min')) {
            $query->where('amount', '>=', $amountMin);
        }
        if ($amountMax = $request->get('amount_max')) {
            $query->where('amount', '<=', $amountMax);
        }

        // Calculate enhanced stats
        $stats = [
            'pending_count' => Receipt::where('status', 'pending')->count(),
            'pending_amount' => Receipt::where('status', 'pending')->sum('amount'),
            'approved_count' => Receipt::where('status', 'approved')->count(),
            'approved_amount' => Receipt::where('status', 'approved')->sum('amount'),
            'rejected_count' => Receipt::where('status', 'rejected')->count(),
            'rejected_amount' => Receipt::where('status', 'rejected')->sum('amount'),
            'total_count' => Receipt::count(),
            'approval_rate' => Receipt::count() > 0 
                ? round((Receipt::where('status', 'approved')->count() / Receipt::count()) * 100, 1)
                : 0,
        ];

        // Calculate average processing time
        $avgProcessingTime = Receipt::whereNotNull('reviewed_at')
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, uploaded_at, reviewed_at) / 24) as avg_days')
            ->value('avg_days');
        $stats['avg_processing_days'] = $avgProcessingTime ? round($avgProcessingTime, 1) : 0;

        $receipts = $query->orderByDesc('created_at')->paginate(15);

        // Get unique programs and reviewers for filters
        $programs = Student::select('program')->distinct()->orderBy('program')->pluck('program');
        $reviewers = Receipt::with('reviewer')->whereNotNull('reviewed_by')->get()
            ->pluck('reviewer')->unique('id')->filter();

        return view('receipts.index', compact('receipts', 'stats', 'programs', 'reviewers'));
    }

    /**
     * Show receipt upload form for student
     */
    public function create(Request $request): View
    {
        // If student_id is provided in request (admin uploading for student)
        if ($request->has('student_id')) {
            $student = Student::findOrFail($request->student_id);
        } else {
            // Get the student associated with logged-in user
            $student = auth()->user()->student;
            
            if (!$student) {
                abort(403, 'Student record not found for this user.');
            }
        }
        
        $balance = $student->getBalance();
        return view('receipts.create', compact('student', 'balance'));
    }

    /**
     * Store uploaded receipt
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:bank_transfer,mobile_money,cash,card,zananco_billmaster',
            'receipt_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // 5MB max
        ]);

        try {
            $student = auth()->user()->student;
            
            if (!$student) {
                abort(403, 'Student record not found.');
            }
            
            // Store file
            $file = $request->file('receipt_file');
            $filename = time() . '_' . $student->id . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('receipts', $filename, 'public');

            // Create receipt record
            Receipt::create([
                'student_id' => $student->id,
                'file_path' => $path,
                'original_filename' => $file->getClientOriginalName(),
                'amount' => $validated['amount'],
                'payment_method' => $validated['payment_method'],
                'status' => 'pending',
            ]);

            return redirect()->route('receipts.student-history')
                ->with('success', 'Receipt uploaded successfully. Awaiting accountant approval.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to upload receipt: ' . $e->getMessage()]);
        }
    }

    /**
     * View receipt details
     */
    public function show(Receipt $receipt): View
    {
        return view('receipts.show', compact('receipt'));
    }

    /**
     * Approve receipt
     */
    public function approve(Request $request, Receipt $receipt)
    {
        $validated = $request->validate([
            'notes' => 'nullable|string|max:500',
        ]);

        // Create payment record when approving receipt
        $payment = Payment::create([
            'student_id' => $receipt->student_id,
            'amount' => $receipt->amount,
            'description' => 'Payment via uploaded receipt - ' . $receipt->original_filename,
            'status' => 'completed',
            'reference_number' => 'RCP_' . time() . '_' . $receipt->student_id,
            'payment_method' => $receipt->payment_method,
            'paid_at' => now(),
            'notes' => $validated['notes'] ?? 'Approved from receipt upload',
        ]);

        // Link receipt to payment and approve
        $receipt->update(['payment_id' => $payment->id]);
        $receipt->approve(auth()->id(), $validated['notes'] ?? '');

        return back()->with('success', 'Receipt approved and payment record created successfully.');
    }

    /**
     * Reject receipt
     */
    public function reject(Request $request, Receipt $receipt)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $receipt->reject(auth()->id(), $validated['reason']);

        return back()->with('error', 'Receipt rejected. Student notified of the reason.');
    }

    /**
     * Student receipt history
     */
    public function studentReceipts(Student $student = null): View
    {
        // If no student parameter provided, get the authenticated user's student record
        if (!$student) {
            $student = auth()->user()->student;
            
            if (!$student) {
                abort(403, 'Student record not found.');
            }
        }
        
        $receipts = $student->receipts()
            ->orderByDesc('created_at')
            ->paginate(10);

        $balance = $student->getBalance();
        $paid = $student->getPaidAmount();

        return view('receipts.student-history', compact('student', 'receipts', 'balance', 'paid'));
    }

    /**
     * Download receipt file
     */
    public function download(Receipt $receipt)
    {
        $filePath = storage_path('app/public/' . $receipt->file_path);
        
        if (!file_exists($filePath)) {
            abort(404, 'Receipt file not found.');
        }

        return response()->download($filePath, $receipt->original_filename);
    }

    /**
     * Export receipts to CSV
     */
    public function export(Request $request)
    {
        $query = Receipt::with('student', 'reviewer');

        // Apply same filters as index
        if ($search = $request->get('search')) {
            $query->where(function($q) use ($search) {
                $q->whereHas('student', function($sq) use ($search) {
                    $sq->where('first_name', 'like', "%{$search}%")
                       ->orWhere('last_name', 'like', "%{$search}%")
                       ->orWhere('student_id', 'like', "%{$search}%");
                })
                ->orWhere('amount', 'like', "%{$search}%");
            });
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($program = $request->get('program')) {
            $query->whereHas('student', function($q) use ($program) {
                $q->where('program', $program);
            });
        }

        if ($reviewer = $request->get('reviewer_id')) {
            $query->where('reviewed_by', $reviewer);
        }

        if ($dateFrom = $request->get('date_from')) {
            $query->whereDate('uploaded_at', '>=', $dateFrom);
        }
        if ($dateTo = $request->get('date_to')) {
            $query->whereDate('uploaded_at', '<=', $dateTo);
        }

        if ($amountMin = $request->get('amount_min')) {
            $query->where('amount', '>=', $amountMin);
        }
        if ($amountMax = $request->get('amount_max')) {
            $query->where('amount', '<=', $amountMax);
        }

        $receipts = $query->orderByDesc('created_at')->get();

        $filename = 'receipts_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function() use ($receipts) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Student ID', 'Student Name', 'Program', 'Amount', 'Status', 'File Name', 'File Size (KB)', 'Uploaded At', 'Reviewed At', 'Reviewed By', 'Processing Days', 'Notes']);

            foreach ($receipts as $receipt) {
                $fileSize = 'N/A';
                $filePath = storage_path('app/public/' . $receipt->file_path);
                if (file_exists($filePath)) {
                    $fileSize = round(filesize($filePath) / 1024, 2);
                }

                $processingDays = 'N/A';
                if ($receipt->reviewed_at) {
                    $processingDays = round($receipt->uploaded_at->diffInHours($receipt->reviewed_at) / 24, 1);
                }

                fputcsv($file, [
                    $receipt->student->student_id,
                    $receipt->student->first_name . ' ' . $receipt->student->last_name,
                    $receipt->student->program ?? 'N/A',
                    $receipt->amount,
                    $receipt->status,
                    $receipt->original_filename,
                    $fileSize,
                    $receipt->uploaded_at->format('Y-m-d H:i:s'),
                    $receipt->reviewed_at?->format('Y-m-d H:i:s') ?? 'N/A',
                    $receipt->reviewer->name ?? 'N/A',
                    $processingDays,
                    $receipt->accountant_notes ?? 'N/A',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
