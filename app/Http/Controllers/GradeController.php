<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Student;
use App\Models\Course;
use Illuminate\Http\Request;

class GradeController extends Controller
{
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
        $query = Grade::with('student', 'course');

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
        $program = $request->get('program');
        
        $students = Student::where('program', $program)
            ->where('status', 'active')
            ->orderBy('first_name')
            ->get(['id', 'first_name', 'last_name', 'student_id', 'program', 'year', 'semester']);

        return response()->json($students);
    }

    // Show create grade form
    public function create()
    {
        $students = Student::all();
        $courses = Course::all();
        return view('grades.create', compact('students', 'courses'));
    }

    // Store grade
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id',
            'assignment_score' => 'nullable|numeric|min:0|max:100',
            'midterm_score' => 'nullable|numeric|min:0|max:100',
            'final_score' => 'nullable|numeric|min:0|max:100',
            'overall_score' => 'nullable|numeric|min:0|max:100',
            'remarks' => 'nullable|string',
        ]);

        Grade::create($validated);
        return redirect()->route('grades.index')->with('success', 'Grade recorded successfully');
    }

    // Show grade details
    public function show(Grade $grade)
    {
        return view('grades.show', compact('grade'));
    }

    // Show edit form
    public function edit(Grade $grade)
    {
        $students = Student::all();
        $courses = Course::all();
        return view('grades.edit', compact('grade', 'students', 'courses'));
    }

    // Update grade
    public function update(Request $request, Grade $grade)
    {
        $validated = $request->validate([
            'assignment_score' => 'nullable|numeric|min:0|max:100',
            'midterm_score' => 'nullable|numeric|min:0|max:100',
            'final_score' => 'nullable|numeric|min:0|max:100',
            'overall_score' => 'nullable|numeric|min:0|max:100',
            'remarks' => 'nullable|string',
        ]);

        $grade->update($validated);
        return redirect()->route('grades.show', $grade)->with('success', 'Grade updated successfully');
    }

    // Delete grade
    public function destroy(Grade $grade)
    {
        $grade->delete();
        return redirect()->route('grades.index')->with('success', 'Grade deleted successfully');
    }
}
