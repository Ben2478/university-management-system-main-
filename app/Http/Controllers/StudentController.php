<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StudentController extends Controller
{
    // Display all students
    public function index(Request $request)
    {
        // Check if user is accountant - show financial view
        if (auth()->user()->isAdmin()) {
            return $this->accountantView($request);
        }
        
        // Super admin - show academic/management view
        $query = Student::query();

        // Search by name, email, or student ID
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('student_id', 'like', "%{$search}%");
            });
        }

        // Filter by program
        if ($request->filled('program')) {
            $query->where('program', $request->program);
        }

        // Filter by year
        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        // Filter by semester
        if ($request->filled('semester')) {
            $query->where('semester', $request->semester);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $students = $query->paginate(15)->withQueryString();
        
        // Get stats for super admin
        $stats = [
            'total' => Student::count(),
            'active' => Student::where('status', 'active')->count(),
            'inactive' => Student::where('status', 'inactive')->count(),
            'graduated' => Student::where('status', 'graduated')->count(),
        ];
        
        // Get unique programs for filter
        $programs = Student::select('program')->distinct()->orderBy('program')->pluck('program');
        
        return view('students.admin-index', compact('students', 'stats', 'programs'));
    }

    // Accountant view - financial focus
    private function accountantView(Request $request)
    {
        $query = Student::with(['payments', 'receipts']);

        // Search by name, email, or student ID
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('student_id', 'like', "%{$search}%");
            });
        }

        // Filter by program
        if ($request->filled('program')) {
            $query->where('program', $request->program);
        }

        // Filter by financial status
        if ($request->filled('financial_status')) {
            if ($request->financial_status === 'owing') {
                $query->whereRaw('tuition_fee > (SELECT COALESCE(SUM(amount), 0) FROM payments WHERE payments.student_id = students.id AND payments.status = "completed")');
            } elseif ($request->financial_status === 'paid') {
                $query->whereRaw('tuition_fee <= (SELECT COALESCE(SUM(amount), 0) FROM payments WHERE payments.student_id = students.id AND payments.status = "completed")');
            }
        }

        $students = $query->paginate(15)->withQueryString();
        
        // Calculate financial data for each student
        $students->getCollection()->transform(function ($student) {
            $totalPaid = $student->payments()->where('status', 'completed')->sum('amount');
            $student->total_paid = $totalPaid;
            $student->balance = $student->tuition_fee - $totalPaid;
            $student->pending_receipts = $student->receipts()->where('status', 'pending')->count();
            return $student;
        });
        
        // Get financial stats
        $stats = [
            'total' => Student::count(),
            'active' => Student::where('status', 'active')->count(),
            'total_fees' => Student::sum('tuition_fee'),
            'total_collected' => \App\Models\Payment::where('status', 'completed')->sum('amount'),
            'pending_receipts' => \App\Models\Receipt::where('status', 'pending')->count(),
            'students_owing' => Student::whereRaw('tuition_fee > (SELECT COALESCE(SUM(amount), 0) FROM payments WHERE payments.student_id = students.id AND payments.status = "completed")')->count(),
        ];
        
        // Calculate outstanding balance
        $stats['outstanding_balance'] = $stats['total_fees'] - $stats['total_collected'];
        
        // Get unique programs for filter
        $programs = Student::select('program')->distinct()->orderBy('program')->pluck('program');
        
        return view('students.index', compact('students', 'stats', 'programs'));
    }

    // Export students to Excel
    public function export()
    {
        $students = Student::all();
        
        $filename = 'students_' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($students) {
            $file = fopen('php://output', 'w');
            
            // Add headers
            fputcsv($file, ['Student ID', 'First Name', 'Last Name', 'Email', 'Phone', 'Program', 'Year', 'Semester', 'Status', 'Enrollment Date']);
            
            // Add data
            foreach ($students as $student) {
                fputcsv($file, [
                    $student->student_id,
                    $student->first_name,
                    $student->last_name,
                    $student->email,
                    $student->phone,
                    $student->program,
                    $student->year,
                    $student->semester,
                    $student->status,
                    $student->enrollment_date,
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    // Show create student form
    public function create()
    {
        return view('students.create');
    }

    // Store student
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|unique:students',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:students',
            'nrc' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'enrollment_date' => 'required|date',
            'program' => 'required|string|max:255',
            'year' => 'required|integer|min:1|max:5',
            'semester' => 'required|integer|min:1|max:2',
        ]);

        // Generate random password for student
        $password = Str::random(8);

        // Create user account for the student
        $user = User::create([
            'name' => $validated['first_name'] . ' ' . $validated['last_name'],
            'email' => $validated['email'],
            'password' => Hash::make($password),
            'role' => 'student',
            'phone' => $validated['phone'] ?? null,
        ]);

        // Create student record linked to user
        $validated['user_id'] = $user->id;
        $student = Student::create($validated);

        // Store credentials in session to display them
        return redirect()->route('students.show', $student)->with([
            'success' => 'Student created successfully',
            'login_credentials' => [
                'email' => $validated['email'],
                'password' => $password,
                'student_id' => $validated['student_id']
            ]
        ]);
    }

    // Show student details
    public function show(Student $student)
    {
        return view('students.show', compact('student'));
    }

    // Show edit form
    public function edit(Student $student)
    {
        return view('students.edit', compact('student'));
    }

    // Update student
    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email,' . $student->id,
            'nrc' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'program' => 'required|string|max:255',
            'status' => 'required|in:active,inactive,graduated,suspended',
        ]);

        $student->update($validated);
        return redirect()->route('students.show', $student)->with('success', 'Student updated successfully');
    }

    // Delete student
    public function destroy(Student $student)
    {
        $student->delete();
        return redirect()->route('students.index')->with('success', 'Student deleted successfully');
    }
}
