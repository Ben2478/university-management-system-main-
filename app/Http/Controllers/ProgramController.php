<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\Course;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    public function index()
    {
        $programs = Program::withCount(['courses', 'students'])->paginate(15);
        
        $stats = [
            'total' => Program::count(),
            'active' => Program::where('status', 'active')->count(),
            'total_courses' => Course::count(),
            'total_students' => \App\Models\Student::count(),
        ];
        
        return view('programs.index', compact('programs', 'stats'));
    }

    public function create()
    {
        return view('programs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:programs',
            'description' => 'nullable|string',
            'department' => 'nullable|string|max:100',
            'duration_years' => 'required|integer|min:1|max:10',
            'status' => 'required|in:active,inactive',
        ]);

        $program = Program::create($validated);
        
        // Redirect to add courses for this program
        if ($request->has('add_courses')) {
            return redirect()->route('programs.add-courses', $program)
                ->with('success', 'Program created! Now add courses for this program.');
        }
        
        return redirect()->route('programs.index')
            ->with('success', 'Program created successfully!');
    }

    public function show(Program $program)
    {
        $program->load(['courses', 'students']);
        return view('programs.show', compact('program'));
    }

    public function edit(Program $program)
    {
        return view('programs.edit', compact('program'));
    }

    public function update(Request $request, Program $program)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:programs,name,' . $program->id,
            'description' => 'nullable|string',
            'department' => 'nullable|string|max:100',
            'duration_years' => 'required|integer|min:1|max:10',
            'status' => 'required|in:active,inactive',
        ]);

        $program->update($validated);
        
        return redirect()->route('programs.show', $program)
            ->with('success', 'Program updated successfully!');
    }

    public function destroy(Program $program)
    {
        // Check if program has courses or students
        if ($program->courses()->count() > 0 || $program->students()->count() > 0) {
            return back()->with('error', 'Cannot delete program with existing courses or students. Set status to inactive instead.');
        }
        
        $program->delete();
        return redirect()->route('programs.index')
            ->with('success', 'Program deleted successfully!');
    }
    
    // Show form to add courses for a program
    public function addCourses(Program $program)
    {
        return view('programs.add-courses', compact('program'));
    }
    
    // Store multiple courses for a program
    public function storeCourses(Request $request, Program $program)
    {
        $validated = $request->validate([
            'courses' => 'required|array|min:1',
            'courses.*.course_code' => 'required|string|unique:courses,course_code',
            'courses.*.course_name' => 'required|string|max:255',
            'courses.*.credits' => 'required|integer|min:1|max:6',
            'courses.*.semester' => 'required|integer|min:1|max:8',
            'courses.*.description' => 'nullable|string',
            'courses.*.instructor' => 'nullable|string',
            'courses.*.tuition_fee' => 'nullable|numeric|min:0',
        ]);

        foreach ($validated['courses'] as $courseData) {
            Course::create([
                'course_code' => $courseData['course_code'],
                'course_name' => $courseData['course_name'],
                'credits' => $courseData['credits'],
                'semester' => $courseData['semester'],
                'description' => $courseData['description'] ?? null,
                'instructor' => $courseData['instructor'] ?? null,
                'tuition_fee' => $courseData['tuition_fee'] ?? 0,
                'department' => $program->name,
                'status' => 'active',
            ]);
        }

        return redirect()->route('programs.show', $program)
            ->with('success', count($validated['courses']) . ' courses added successfully!');
    }
}
