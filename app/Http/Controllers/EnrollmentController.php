<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Student;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class EnrollmentController extends Controller
{
    // Display all enrollments
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Check if user is a student
        if ($user->isStudent()) {
            return $this->studentCourses();
        }
        
        // Admin view - show all enrollments
        $query = Enrollment::with('student', 'course');

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

        // Filter by program
        if ($request->filled('program')) {
            $query->where('program', $request->program);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('enrollment_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('enrollment_date', '<=', $request->date_to);
        }

        $enrollments = $query->latest()->paginate(15);

        // Calculate stats
        $stats = [
            'total' => Enrollment::count(),
            'active' => Enrollment::where('status', 'active')->count(),
            'completed' => Enrollment::where('status', 'completed')->count(),
            'dropped' => Enrollment::where('status', 'dropped')->count(),
        ];

        return view('enrollments.index', compact('enrollments', 'stats'));
    }
    
    // Student-specific course view
    private function studentCourses()
    {
        $student = Student::where('user_id', auth()->id())->first();
        
        if (!$student) {
            return redirect()->route('dashboard')->with('error', 'Student profile not found');
        }
        
        // Get enrollments with related data
        $enrollments = Enrollment::where('student_id', $student->id)
            ->with('course')
            ->get();
        
        // Categorize courses
        $activeCourses = $enrollments->where('status', 'active');
        $completedCourses = $enrollments->where('status', 'completed');
        $droppedCourses = $enrollments->where('status', 'dropped');
        
        // Calculate stats
        $stats = [
            'total' => $enrollments->count(),
            'active' => $activeCourses->count(),
            'completed' => $completedCourses->count(),
            'dropped' => $droppedCourses->count(),
        ];
        
        // Enhance each enrollment with performance data
        $coursesData = $activeCourses->map(function($enrollment) use ($student) {
            // Get grades for this course
            $grade = \App\Models\Grade::where('student_id', $student->id)
                ->where('course_id', $enrollment->course_id)
                ->first();
            
            // Get attendance for this course
            $totalClasses = \App\Models\Attendance::where('course_id', $enrollment->course_id)
                ->distinct('attendance_date')
                ->count('attendance_date');
            
            $attendedClasses = \App\Models\Attendance::where('student_id', $student->id)
                ->where('course_id', $enrollment->course_id)
                ->where('status', 'present')
                ->count();
            
            $attendanceRate = $totalClasses > 0 ? round(($attendedClasses / $totalClasses) * 100) : 0;
            
            return [
                'enrollment' => $enrollment,
                'course' => $enrollment->course,
                'grade' => $grade ? $grade->grade : 'N/A',
                'score' => $grade ? $grade->overall_score : 0,
                'attendance_rate' => $attendanceRate,
                'attended' => $attendedClasses,
                'total_classes' => $totalClasses,
                'progress' => $grade && $grade->overall_score > 0 ? min(100, $grade->overall_score) : 0,
                'status_color' => $attendanceRate >= 75 && (!$grade || $grade->overall_score >= 50) ? 'success' : ($attendanceRate >= 60 ? 'warning' : 'danger'),
            ];
        });
        
        return view('enrollments.student-courses', compact('coursesData', 'stats', 'student', 'completedCourses', 'droppedCourses'));
    }

    // Show create enrollment form
    public function create()
    {
        $students = Student::where('status', 'active')
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();
        $courses = Course::where('status', 'active')
            ->orderBy('course_code')
            ->get();

        return view('enrollments.create', compact('students', 'courses'));
    }

    // Store enrollment
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_ids' => 'required|array|min:1',
            'student_ids.*' => 'required|integer|distinct|exists:students,id',
            'course_ids' => 'required|array|min:1',
            'course_ids.*' => 'required|integer|distinct|exists:courses,id',
            'status' => 'required|in:active,completed,dropped,suspended',
            'enrollment_date' => 'nullable|date',
        ]);

        $students = Student::whereIn('id', $validated['student_ids'])
            ->where('status', 'active')
            ->get();
        $courses = Course::whereIn('id', $validated['course_ids'])
            ->where('status', 'active')
            ->get();

        if ($students->count() !== count($validated['student_ids'])) {
            throw ValidationException::withMessages([
                'student_ids' => 'Select only active students.',
            ]);
        }

        if ($courses->count() !== count($validated['course_ids'])) {
            throw ValidationException::withMessages([
                'course_ids' => 'Select only active courses.',
            ]);
        }

        foreach ($students as $student) {
            foreach ($courses as $course) {
                if ($course->department !== $student->program) {
                    throw ValidationException::withMessages([
                        'course_ids' => "{$course->course_code} is not part of {$student->program}.",
                    ]);
                }
            }
        }

        $enrollmentDate = $validated['enrollment_date'] ?? now()->toDateString();
        $createdCount = 0;
        $skippedCount = 0;

        DB::transaction(function () use ($students, $courses, $validated, $enrollmentDate, &$createdCount, &$skippedCount) {
            foreach ($students as $student) {
                foreach ($courses as $course) {
                    $exists = Enrollment::where('student_id', $student->id)
                        ->where('course_id', $course->id)
                        ->exists();

                    if ($exists) {
                        $skippedCount++;
                        continue;
                    }

                    Enrollment::create([
                        'student_id' => $student->id,
                        'course_id' => $course->id,
                        'program' => $student->program,
                        'enrollment_date' => $enrollmentDate,
                        'status' => $validated['status'],
                    ]);

                    $createdCount++;
                }
            }
        });

        $message = "Created {$createdCount} enrollment(s).";
        if ($skippedCount > 0) {
            $message .= " Skipped {$skippedCount} existing student-course pair(s).";
        }

        return redirect()->route('enrollments.index')->with('success', $message);
    }

    // Show enrollment details
    public function show(Enrollment $enrollment)
    {
        return view('enrollments.show', compact('enrollment'));
    }

    // Show edit form
    public function edit(Enrollment $enrollment)
    {
        $students = Student::all();
        $courses = Course::where('status', 'active')->get();
        return view('enrollments.edit', compact('enrollment', 'students', 'courses'));
    }

    // Update enrollment
    public function update(Request $request, Enrollment $enrollment)
    {
        $validated = $request->validate([
            'status' => 'required|in:active,completed,dropped,suspended',
            'enrollment_date' => 'nullable|date',
        ]);

        $enrollment->update($validated);
        return redirect()->route('enrollments.show', $enrollment)->with('success', 'Enrollment updated successfully');
    }

    // Delete enrollment
    public function destroy(Enrollment $enrollment)
    {
        $enrollment->delete();
        return redirect()->route('enrollments.index')->with('success', 'Enrollment deleted successfully');
    }
    
    // Show available courses for student enrollment
    public function availableCourses(Request $request)
    {
        $validated = $request->validate([
            'term' => 'nullable|integer|between:1,3',
        ]);

        $user = auth()->user();
        
        if (!$user->isStudent()) {
            return redirect()->route('dashboard')->with('error', 'Only students can enroll in courses');
        }
        
        $student = Student::where('user_id', $user->id)->first();
        
        if (!$student) {
            return redirect()->route('dashboard')->with('error', 'Student profile not found');
        }
        
        // Get courses student is already enrolled in
        $enrolledCourseIds = Enrollment::where('student_id', $student->id)
            ->whereIn('status', ['active', 'completed'])
            ->pluck('course_id')
            ->toArray();
        
        // Get available courses (active, matching program/department, not already enrolled)
        $availableCourses = Course::where('status', 'active')
            ->when($student->program, function($query) use ($student) {
                // Match by department if program exists
                return $query->where('department', $student->program);
            })
            ->when($validated['term'] ?? null, function ($query, $term) {
                $query->where('term', $term);
            })
            ->whereNotIn('id', $enrolledCourseIds)
            ->orderBy('term')
            ->orderBy('course_code')
            ->get();
        
        return view('enrollments.available-courses', compact('availableCourses', 'student'))
            ->with('selectedTerm', $validated['term'] ?? null);
    }
    
    // Enroll student in a course
    public function enrollInCourse(Request $request, Course $course)
    {
        $user = auth()->user();
        
        if (!$user->isStudent()) {
            return redirect()->route('dashboard')->with('error', 'Only students can enroll in courses');
        }
        
        $student = Student::where('user_id', $user->id)->first();
        
        if (!$student) {
            return redirect()->route('dashboard')->with('error', 'Student profile not found');
        }
        
        // Check if already enrolled
        $exists = Enrollment::where('student_id', $student->id)
            ->where('course_id', $course->id)
            ->whereIn('status', ['active', 'completed'])
            ->exists();
        
        if ($exists) {
            return back()->with('error', 'You are already enrolled in this course');
        }
        
        // Create enrollment
        Enrollment::create([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'program' => $student->program,
            'enrollment_date' => now(),
            'status' => 'active',
        ]);
        
        return redirect()->route('enrollments.index')
            ->with('success', "Successfully enrolled in {$course->course_code} - {$course->course_name}!");
    }
}
