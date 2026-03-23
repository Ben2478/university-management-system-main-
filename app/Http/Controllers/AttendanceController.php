<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Student;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    private function lecturerCourseIds(): array
    {
        $user = auth()->user();
        if (!$user || !method_exists($user, 'isLecturer') || !$user->isLecturer()) {
            return [];
        }

        $lecturer = $user->lecturer;
        if (!$lecturer) {
            return [];
        }

        return $lecturer->courses()->pluck('courses.id')->toArray();
    }

    private function ensureCanManageCourse(int $courseId): void
    {
        $user = auth()->user();
        if (method_exists($user, 'isLecturer') && $user->isLecturer()) {
            if (!in_array($courseId, $this->lecturerCourseIds(), true)) {
                abort(403, 'You are not assigned to this course.');
            }
        }
    }

    // Display all attendance records
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Check if user is a student - show only their attendance
        if ($user->isStudent()) {
            $student = Student::where('user_id', $user->id)->first();
            if (!$student) {
                return redirect()->route('dashboard')->with('error', 'Student profile not found');
            }
            
            $attendances = Attendance::where('student_id', $student->id)
                ->with('course')
                ->latest('attendance_date')
                ->get();
            
            $totalRecords = $attendances->count();
            $presentCount = $attendances->where('status', 'present')->count();
            $absentCount = $attendances->where('status', 'absent')->count();
            $lateCount = $attendances->where('status', 'late')->count();
            
            $stats = [
                'total' => $totalRecords,
                'present' => $presentCount,
                'absent' => $absentCount,
                'late' => $lateCount,
                'attendance_rate' => $totalRecords > 0 ? round(($presentCount / $totalRecords) * 100, 1) : 0,
            ];
            
            return view('attendance.student-view', compact('attendances', 'stats', 'student'));
        }

        if ($user->isParent()) {
            abort(403, 'Unauthorized');
        }

        // Lecturer view - only attendance for assigned courses
        if (method_exists($user, 'isLecturer') && $user->isLecturer()) {
            $courseIds = $this->lecturerCourseIds();
            $query = Attendance::with('student', 'course')->whereIn('course_id', $courseIds);

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->whereHas('student', function($sq) use ($search) {
                        $sq->where('first_name', 'like', "%{$search}%")
                          ->orWhere('last_name', 'like', "%{$search}%")
                          ->orWhere('student_id', 'like', "%{$search}%");
                    })->orWhereHas('course', function($cq) use ($search) {
                        $cq->where('course_name', 'like', "%{$search}%")
                           ->orWhere('course_code', 'like', "%{$search}%");
                    });
                });
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('course_id')) {
                $query->where('course_id', $request->course_id);
            }

            if ($request->filled('date_from')) {
                $query->whereDate('attendance_date', '>=', $request->date_from);
            }
            if ($request->filled('date_to')) {
                $query->whereDate('attendance_date', '<=', $request->date_to);
            }

            $attendance = $query->latest('attendance_date')->paginate(15);

            $allRecords = (clone $query)->get();
            $total = $allRecords->count();
            $present = $allRecords->where('status', 'present')->count();
            $absent = $allRecords->where('status', 'absent')->count();
            $late = $allRecords->where('status', 'late')->count();
            $presentRate = $total > 0 ? round(($present / $total) * 100, 1) : 0;

            $stats = [
                'total' => $total,
                'present_rate' => $presentRate,
                'absent' => $absent,
                'late' => $late,
            ];

            $courses = Course::whereIn('id', $courseIds)->orderBy('course_name')->get();

            return view('attendance.lecturer-index', compact('attendance', 'stats', 'courses'));
        }
        
        // Admin view - show all attendance
        $query = Attendance::with('student', 'course');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('student', function($sq) use ($search) {
                    $sq->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('student_id', 'like', "%{$search}%");
                })->orWhereHas('course', function($cq) use ($search) {
                    $cq->where('course_name', 'like', "%{$search}%")
                       ->orWhere('course_code', 'like', "%{$search}%");
                });
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by course
        if ($request->filled('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        // Filter by program
        if ($request->filled('program')) {
            $query->whereHas('student', function($q) use ($request) {
                $q->where('program', $request->program);
            });
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('attendance_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('attendance_date', '<=', $request->date_to);
        }

        $attendance = $query->latest('attendance_date')->paginate(15);

        // Calculate stats
        $allRecords = Attendance::all();
        $total = $allRecords->count();
        $present = $allRecords->where('status', 'present')->count();
        $absent = $allRecords->where('status', 'absent')->count();
        $late = $allRecords->where('status', 'late')->count();
        $presentRate = $total > 0 ? round(($present / $total) * 100, 1) : 0;

        $stats = [
            'total' => $total,
            'present_rate' => $presentRate,
            'absent' => $absent,
            'late' => $late,
        ];

        $courses = Course::all();
        $programs = Student::whereNotNull('program')
            ->where('status', 'active')
            ->distinct()
            ->pluck('program')
            ->sort()
            ->values();

        return view('attendance.index', compact('attendance', 'stats', 'courses', 'programs'));
    }

    // Export attendance to CSV
    public function export(Request $request)
    {
        $user = auth()->user();

        if ($user->isParent()) {
            abort(403, 'Unauthorized');
        }

        // Student export: only own attendance
        if ($user->isStudent()) {
            $student = Student::where('user_id', $user->id)->first();
            if (!$student) {
                return redirect()->route('dashboard')->with('error', 'Student profile not found');
            }
            $query = Attendance::with('student', 'course')->where('student_id', $student->id);
        } else {
            $query = Attendance::with('student', 'course');
        }

        // Lecturer export: only assigned courses
        if (method_exists($user, 'isLecturer') && $user->isLecturer()) {
            $query->whereIn('course_id', $this->lecturerCourseIds());
        }

        // Apply same filters as index
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('student', function($sq) use ($search) {
                    $sq->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('student_id', 'like', "%{$search}%");
                })->orWhereHas('course', function($cq) use ($search) {
                    $cq->where('course_name', 'like', "%{$search}%")
                       ->orWhere('course_code', 'like', "%{$search}%");
                });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        $records = $query->get();

        $filename = 'attendance_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function() use ($records) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Student ID', 'Student Name', 'Program', 'Course Code', 'Course Name', 'Date', 'Status', 'Remarks']);

            foreach ($records as $record) {
                fputcsv($file, [
                    $record->student->student_id,
                    $record->student->first_name . ' ' . $record->student->last_name,
                    $record->student->program ?? 'N/A',
                    $record->course->course_code,
                    $record->course->course_name,
                    $record->attendance_date->format('Y-m-d'),
                    ucfirst($record->status),
                    $record->remarks ?? '',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // Show create attendance form
    public function create()
    {
        $user = auth()->user();
        if ($user->isStudent() || $user->isParent()) {
            abort(403, 'Unauthorized');
        }

        if (method_exists($user, 'isLecturer') && $user->isLecturer()) {
            $courseIds = $this->lecturerCourseIds();
            $courses = Course::where('status', 'active')->whereIn('id', $courseIds)->orderBy('course_name')->get();

            $studentIds = Enrollment::whereIn('course_id', $courseIds)
                ->whereIn('status', ['active', 'completed'])
                ->pluck('student_id')
                ->unique()
                ->values();
            $students = Student::whereIn('id', $studentIds)->orderBy('first_name')->orderBy('last_name')->get();

            return view('attendance.create', compact('students', 'courses'));
        }

        $students = Student::all();
        $courses = Course::where('status', 'active')->get();
        return view('attendance.create', compact('students', 'courses'));
    }

    // Store attendance
    public function store(Request $request)
    {
        $user = auth()->user();
        if ($user->isStudent() || $user->isParent()) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id',
            'attendance_date' => 'required|date',
            'status' => 'required|in:present,absent,late,excused',
            'remarks' => 'nullable|string',
        ]);

        $this->ensureCanManageCourse((int) $validated['course_id']);

        $enrolled = Enrollment::where('student_id', $validated['student_id'])
            ->where('course_id', $validated['course_id'])
            ->whereIn('status', ['active', 'completed'])
            ->exists();
        if (!$enrolled) {
            return back()->withErrors('Student is not enrolled in the selected course.')->withInput();
        }

        Attendance::create($validated);
        return redirect()->route('attendance.index')->with('success', 'Attendance recorded successfully');
    }

    // Show attendance details
    public function show(Attendance $attendance)
    {
        $user = auth()->user();

        if ($user->isParent()) {
            abort(403, 'Unauthorized');
        }

        if ($user->isStudent()) {
            $student = Student::where('user_id', $user->id)->first();
            if (!$student || (int) $attendance->student_id !== (int) $student->id) {
                abort(403, 'Unauthorized');
            }
        }

        if (method_exists($user, 'isLecturer') && $user->isLecturer()) {
            $this->ensureCanManageCourse((int) $attendance->course_id);
        }

        return view('attendance.show', compact('attendance'));
    }

    // Show edit form
    public function edit(Attendance $attendance)
    {
        $user = auth()->user();
        if ($user->isStudent() || $user->isParent()) {
            abort(403, 'Unauthorized');
        }

        $this->ensureCanManageCourse((int) $attendance->course_id);

        if (method_exists($user, 'isLecturer') && $user->isLecturer()) {
            $courseIds = $this->lecturerCourseIds();
            $courses = Course::where('status', 'active')->whereIn('id', $courseIds)->orderBy('course_name')->get();

            $studentIds = Enrollment::whereIn('course_id', $courseIds)
                ->whereIn('status', ['active', 'completed'])
                ->pluck('student_id')
                ->unique()
                ->values();
            $students = Student::whereIn('id', $studentIds)->orderBy('first_name')->orderBy('last_name')->get();

            return view('attendance.edit', compact('attendance', 'students', 'courses'));
        }

        $students = Student::all();
        $courses = Course::where('status', 'active')->get();
        return view('attendance.edit', compact('attendance', 'students', 'courses'));
    }

    // Update attendance
    public function update(Request $request, Attendance $attendance)
    {
        $user = auth()->user();
        if ($user->isStudent() || $user->isParent()) {
            abort(403, 'Unauthorized');
        }

        $this->ensureCanManageCourse((int) $attendance->course_id);

        $validated = $request->validate([
            'attendance_date' => 'required|date',
            'status' => 'required|in:present,absent,late,excused',
            'remarks' => 'nullable|string',
        ]);

        $attendance->update($validated);
        return redirect()->route('attendance.index')->with('success', 'Attendance updated successfully');
    }

    // Delete attendance
    public function destroy(Attendance $attendance)
    {
        $user = auth()->user();
        if ($user->isStudent() || $user->isParent()) {
            abort(403, 'Unauthorized');
        }

        $this->ensureCanManageCourse((int) $attendance->course_id);

        $attendance->delete();
        return redirect()->route('attendance.index')->with('success', 'Attendance deleted successfully');
    }

    // Get attendance report for a student
    public function studentReport(Student $student)
    {
        $user = auth()->user();

        if ($user->isParent()) {
            abort(403, 'Unauthorized');
        }

        if ($user->isStudent()) {
            $selfStudent = Student::where('user_id', $user->id)->first();
            if (!$selfStudent || (int) $selfStudent->id !== (int) $student->id) {
                abort(403, 'Unauthorized');
            }
        }

        if (method_exists($user, 'isLecturer') && $user->isLecturer()) {
            $courseIds = $this->lecturerCourseIds();
            $allowed = Enrollment::where('student_id', $student->id)
                ->whereIn('course_id', $courseIds)
                ->whereIn('status', ['active', 'completed'])
                ->exists();
            if (!$allowed) {
                abort(403, 'Unauthorized');
            }
        }

        $attendance = $student->attendance()->with('course')->get();
        $presentCount = $attendance->where('status', 'present')->count();
        $absentCount = $attendance->where('status', 'absent')->count();
        $lateCount = $attendance->where('status', 'late')->count();

        return view('attendance.student-report', compact('student', 'attendance', 'presentCount', 'absentCount', 'lateCount'));
    }
}
