<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Student;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class GradeController extends Controller
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

    private function calculateGradeLetter(?float $overall): ?string
    {
        if ($overall === null) {
            return null;
        }

        // Keep in sync with App\\Models\\Grade::calculateGrade()
        if ($overall >= 90) return 'A';
        if ($overall >= 80) return 'B';
        if ($overall >= 70) return 'C';
        if ($overall >= 60) return 'D';
        return 'F';
    }

    // Display all grades
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Check if user is a student - show only their grades
        if ($user->isStudent()) {
            $student = Student::where('user_id', $user->id)->first();
            if (!$student) {
                return redirect()->route('dashboard')->with('error', 'Student profile not found');
            }
            
            $grades = Grade::where('student_id', $student->id)
                ->with('course')
                ->latest()
                ->get();
            
            // Calculate GPA (4.0 scale)
            $gradePoints = [
                'A' => 4.0, 'A+' => 4.0,
                'B' => 3.0, 'B+' => 3.5,
                'C' => 2.0, 'C+' => 2.5,
                'D' => 1.0, 'D+' => 1.5,
                'F' => 0.0
            ];
            
            $totalPoints = 0;
            $totalCredits = 0;
            foreach ($grades as $grade) {
                if (isset($gradePoints[$grade->grade])) {
                    $credits = $grade->course->credits ?? 3;
                    $totalPoints += $gradePoints[$grade->grade] * $credits;
                    $totalCredits += $credits;
                }
            }
            $gpa = $totalCredits > 0 ? round($totalPoints / $totalCredits, 2) : 0;
            
            // Performance analytics
            $bestCourse = $grades->sortByDesc('overall_score')->first();
            $worstCourse = $grades->where('overall_score', '>', 0)->sortBy('overall_score')->first();
            $atRiskCourses = $grades->where('overall_score', '<', 60)->where('overall_score', '>', 0);
            
            // Grade distribution
            $gradeDistribution = [
                'A' => $grades->where('grade', 'A')->count(),
                'B' => $grades->where('grade', 'B')->count(),
                'C' => $grades->where('grade', 'C')->count(),
                'D' => $grades->where('grade', 'D')->count(),
                'F' => $grades->where('grade', 'F')->count(),
            ];
            
            $stats = [
                'total' => $grades->count(),
                'average' => $grades->avg('overall_score') ? round($grades->avg('overall_score'), 2) : 0,
                'distinction' => $grades->whereIn('grade', ['A', 'B'])->count(),
                'need_attention' => $grades->whereIn('grade', ['D', 'F'])->count(),
                'gpa' => $gpa,
                'passing' => $grades->where('overall_score', '>=', 50)->count(),
            ];
            
            return view('grades.student-view', compact('grades', 'stats', 'student', 'bestCourse', 'worstCourse', 'atRiskCourses', 'gradeDistribution'));
        }

        if ($user->isParent()) {
            abort(403, 'Unauthorized');
        }

        // Lecturer view - only grades for assigned courses
        if (method_exists($user, 'isLecturer') && $user->isLecturer()) {
            $courseIds = $this->lecturerCourseIds();
            $query = Grade::with('student', 'course')->whereIn('course_id', $courseIds);

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

            if ($request->filled('course_id')) {
                $query->where('course_id', $request->course_id);
            }

            $grades = $query->latest()->paginate(15);

            $stats = [
                'total' => (clone $query)->count(),
                'average' => (clone $query)->avg('overall_score') ? round((clone $query)->avg('overall_score'), 2) : 0,
                'distinction' => (clone $query)->whereIn('grade', ['A', 'B'])->count(),
                'need_attention' => (clone $query)->whereIn('grade', ['D', 'F'])->count(),
            ];

            $courses = Course::whereIn('id', $courseIds)->orderBy('course_name')->get();

            return view('grades.lecturer-index', compact('grades', 'stats', 'courses'));
        }
        
        // Admin view - show all grades
        $query = Grade::with('student', 'course');

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

        // Filter by course
        if ($request->filled('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        // Filter by grade
        if ($request->filled('grade')) {
            $query->where('grade', $request->grade);
        }

        // Filter by score range
        if ($request->filled('score_min')) {
            $query->where('overall_score', '>=', $request->score_min);
        }
        if ($request->filled('score_max')) {
            $query->where('overall_score', '<=', $request->score_max);
        }

        $grades = $query->latest()->paginate(15);

        // Calculate stats
        $allGrades = Grade::all();
        $stats = [
            'total' => $allGrades->count(),
            'average' => $allGrades->avg('overall_score') ? round($allGrades->avg('overall_score'), 2) : 0,
            'distinction' => $allGrades->whereIn('grade', ['A', 'B'])->count(),
            'need_attention' => $allGrades->whereIn('grade', ['D', 'F'])->count(),
        ];

        $courses = Course::all();

        // Get unique programs from students
        $programs = Student::whereNotNull('program')
            ->where('status', 'active')
            ->distinct()
            ->pluck('program')
            ->sort()
            ->values();

        return view('grades.index', compact('grades', 'stats', 'courses', 'programs'));
    }

    // Export grades to CSV
    public function export(Request $request)
    {
        $user = auth()->user();

        if ($user->isParent()) {
            abort(403, 'Unauthorized');
        }

        // Student export: only own grades
        if ($user->isStudent()) {
            $student = Student::where('user_id', $user->id)->first();
            if (!$student) {
                return redirect()->route('dashboard')->with('error', 'Student profile not found');
            }
            $query = Grade::with('student', 'course')->where('student_id', $student->id);
        } else {
            $query = Grade::with('student', 'course');
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

        if ($request->filled('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        if ($request->filled('grade')) {
            $query->where('grade', $request->grade);
        }

        $grades = $query->get();

        $filename = 'grades_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function() use ($grades) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Student ID', 'Student Name', 'Course Code', 'Course Name', 'Assignment', 'Midterm', 'Final', 'Overall Score', 'Grade', 'Remarks']);

            foreach ($grades as $grade) {
                fputcsv($file, [
                    $grade->student->student_id,
                    $grade->student->first_name . ' ' . $grade->student->last_name,
                    $grade->course->course_code,
                    $grade->course->course_name,
                    $grade->assignment_score ?? '-',
                    $grade->midterm_score ?? '-',
                    $grade->final_score ?? '-',
                    $grade->overall_score ?? '-',
                    $grade->grade ?? '-',
                    $grade->remarks ?? '',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // Get students by program
    public function getStudentsByProgram(Request $request)
    {
        $user = auth()->user();
        if ($user->isStudent() || $user->isParent()) {
            abort(403, 'Unauthorized');
        }

        $program = $request->get('program');

        $studentsQuery = Student::where('program', $program)
            ->where('status', 'active');

        // Lecturer: only students enrolled in lecturer courses
        if (method_exists($user, 'isLecturer') && $user->isLecturer()) {
            $courseIds = $this->lecturerCourseIds();
            $allowedStudentIds = Enrollment::whereIn('course_id', $courseIds)
                ->whereIn('status', ['active', 'completed'])
                ->pluck('student_id')
                ->unique()
                ->values();

            $studentsQuery->whereIn('id', $allowedStudentIds);
        }

        $students = $studentsQuery
            ->orderBy('first_name')
            ->get(['id', 'first_name', 'last_name', 'student_id', 'program', 'year', 'semester']);

        return response()->json($students);
    }

    // Show create grade form
    public function create()
    {
        $user = auth()->user();

        if ($user->isStudent() || $user->isParent()) {
            abort(403, 'Unauthorized');
        }

        if (method_exists($user, 'isLecturer') && $user->isLecturer()) {
            $courseIds = $this->lecturerCourseIds();
            $courses = Course::whereIn('id', $courseIds)->orderBy('course_name')->get();

            $studentIds = Enrollment::whereIn('course_id', $courseIds)
                ->whereIn('status', ['active', 'completed'])
                ->pluck('student_id')
                ->unique()
                ->values();

            $students = Student::whereIn('id', $studentIds)
                ->orderBy('first_name')
                ->orderBy('last_name')
                ->get();

            return view('grades.create', compact('students', 'courses'));
        }

        $students = Student::all();
        $courses = Course::all();
        return view('grades.create', compact('students', 'courses'));
    }

    // Store grade
    public function store(Request $request)
    {
        $user = auth()->user();
        if ($user->isStudent() || $user->isParent()) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id',
            'assignment_score' => 'nullable|numeric|min:0|max:100',
            'midterm_score' => 'nullable|numeric|min:0|max:100',
            'final_score' => 'nullable|numeric|min:0|max:100',
            'overall_score' => 'nullable|numeric|min:0|max:100',
            'remarks' => 'nullable|string',
        ]);

        $this->ensureCanManageCourse((int) $validated['course_id']);

        // Ensure student is enrolled in the course
        $enrolled = Enrollment::where('student_id', $validated['student_id'])
            ->where('course_id', $validated['course_id'])
            ->whereIn('status', ['active', 'completed'])
            ->exists();
        if (!$enrolled) {
            return back()->withErrors('Student is not enrolled in the selected course.')->withInput();
        }

        // Compute overall score if not explicitly provided
        $overall = $validated['overall_score'] ?? null;
        if ($overall === null) {
            $assignment = (float) ($validated['assignment_score'] ?? 0);
            $midterm = (float) ($validated['midterm_score'] ?? 0);
            $final = (float) ($validated['final_score'] ?? 0);
            $overall = round(($assignment * 0.3) + ($midterm * 0.3) + ($final * 0.4), 2);
            $validated['overall_score'] = $overall;
        }

        $validated['grade'] = $this->calculateGradeLetter((float) $validated['overall_score']);
        $validated['graded_date'] = now()->toDateString();

        Grade::updateOrCreate(
            ['student_id' => $validated['student_id'], 'course_id' => $validated['course_id']],
            $validated
        );
        return redirect()->route('grades.index')->with('success', 'Grade recorded successfully');
    }

    // Show grade details
    public function show(Grade $grade)
    {
        $user = auth()->user();

        if ($user->isParent()) {
            abort(403, 'Unauthorized');
        }

        if ($user->isStudent()) {
            $student = Student::where('user_id', $user->id)->first();
            if (!$student || (int) $grade->student_id !== (int) $student->id) {
                abort(403, 'Unauthorized');
            }
        }

        if (method_exists($user, 'isLecturer') && $user->isLecturer()) {
            $this->ensureCanManageCourse((int) $grade->course_id);
        }

        return view('grades.show', compact('grade'));
    }

    // Show edit form
    public function edit(Grade $grade)
    {
        $user = auth()->user();
        if ($user->isStudent() || $user->isParent()) {
            abort(403, 'Unauthorized');
        }

        $this->ensureCanManageCourse((int) $grade->course_id);

        if (method_exists($user, 'isLecturer') && $user->isLecturer()) {
            $courseIds = $this->lecturerCourseIds();
            $courses = Course::whereIn('id', $courseIds)->orderBy('course_name')->get();

            $studentIds = Enrollment::whereIn('course_id', $courseIds)
                ->whereIn('status', ['active', 'completed'])
                ->pluck('student_id')
                ->unique()
                ->values();

            $students = Student::whereIn('id', $studentIds)
                ->orderBy('first_name')
                ->orderBy('last_name')
                ->get();

            return view('grades.edit', compact('grade', 'students', 'courses'));
        }

        $students = Student::all();
        $courses = Course::all();
        return view('grades.edit', compact('grade', 'students', 'courses'));
    }

    // Update grade
    public function update(Request $request, Grade $grade)
    {
        $user = auth()->user();
        if ($user->isStudent() || $user->isParent()) {
            abort(403, 'Unauthorized');
        }

        $this->ensureCanManageCourse((int) $grade->course_id);

        $validated = $request->validate([
            'assignment_score' => 'nullable|numeric|min:0|max:100',
            'midterm_score' => 'nullable|numeric|min:0|max:100',
            'final_score' => 'nullable|numeric|min:0|max:100',
            'overall_score' => 'nullable|numeric|min:0|max:100',
            'remarks' => 'nullable|string',
        ]);

        $overall = $validated['overall_score'] ?? null;
        if ($overall === null) {
            $assignment = (float) ($validated['assignment_score'] ?? $grade->assignment_score ?? 0);
            $midterm = (float) ($validated['midterm_score'] ?? $grade->midterm_score ?? 0);
            $final = (float) ($validated['final_score'] ?? $grade->final_score ?? 0);
            $validated['overall_score'] = round(($assignment * 0.3) + ($midterm * 0.3) + ($final * 0.4), 2);
        }

        $validated['grade'] = $this->calculateGradeLetter((float) $validated['overall_score']);
        $validated['graded_date'] = now()->toDateString();

        $grade->update($validated);
        return redirect()->route('grades.show', $grade)->with('success', 'Grade updated successfully');
    }

    // Delete grade
    public function destroy(Grade $grade)
    {
        $user = auth()->user();
        if ($user->isStudent() || $user->isParent()) {
            abort(403, 'Unauthorized');
        }

        $this->ensureCanManageCourse((int) $grade->course_id);

        $grade->delete();
        return redirect()->route('grades.index')->with('success', 'Grade deleted successfully');
    }
}
