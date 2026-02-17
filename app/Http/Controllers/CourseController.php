<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    // Get programs from database
    private function getPrograms()
    {
        return \App\Models\Program::where('status', 'active')
            ->pluck('name')
            ->toArray();
    }
    
    // Display all courses
    public function index(Request $request)
    {
        $query = Course::query();

        // Search by course name or code
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('course_name', 'like', "%{$search}%")
                  ->orWhere('course_code', 'like', "%{$search}%");
            });
        }

        // Filter by department
        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $courses = $query->paginate(15)->withQueryString();
        
        // Get stats
        $stats = [
            'total' => Course::count(),
            'active' => Course::where('status', 'active')->count(),
            'departments' => Course::distinct('department')->count('department'),
            'enrolled' => \App\Models\Enrollment::count(),
        ];
        
        return view('courses.index', compact('courses', 'stats'));
    }

    // Show create course form
    public function create()
    {
        $programs = $this->getPrograms();
        
        // Check if programs exist
        if (empty($programs)) {
            return redirect()->route('programs.index')
                ->with('error', 'Please create at least one program before adding courses. Courses must belong to a program.');
        }
        
        return view('courses.create', compact('programs'));
    }

    // Store course
    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_code' => 'required|unique:courses',
            'course_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'credits' => 'required|integer|min:1|max:6',
            'department' => 'required|string',
            'semester' => 'required|integer|min:1|max:8',
            'instructor' => 'nullable|string',
            'tuition_fee' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        Course::create($validated);
        return redirect()->route('courses.index')->with('success', 'Course created successfully');
    }

    // Show course details
    public function show(Course $course)
    {
        return view('courses.show', compact('course'));
    }

    // Show edit form
    public function edit(Course $course)
    {
        $programs = $this->getPrograms();
        return view('courses.edit', compact('course', 'programs'));
    }

    // Update course
    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'course_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'credits' => 'required|integer|min:1|max:6',
            'department' => 'required|string',
            'semester' => 'required|integer|min:1|max:8',
            'instructor' => 'nullable|string',
            'tuition_fee' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        $course->update($validated);
        return redirect()->route('courses.show', $course)->with('success', 'Course updated successfully');
    }

    // Delete course
    public function destroy(Course $course)
    {
        $course->delete();
        return redirect()->route('courses.index')->with('success', 'Course deleted successfully');
    }
}
