<?php

namespace App\Http\Controllers;

use App\Models\AcademicCalendar;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AcademicCalendarController extends Controller
{
    public function index()
    {
        $calendars = AcademicCalendar::orderByDesc('academic_year')
            ->orderBy('semester')
            ->paginate(20);

        return view('academic-calendar.index', compact('calendars'));
    }

    public function create()
    {
        return view('academic-calendar.create');
    }

    public function store(Request $request)
    {
        AcademicCalendar::create($this->validatedCalendar($request));

        return redirect()->route('academic-calendar.index')->with('success', 'Semester calendar created.');
    }

    public function edit(AcademicCalendar $academicCalendar)
    {
        return view('academic-calendar.edit', ['calendar' => $academicCalendar]);
    }

    public function update(Request $request, AcademicCalendar $academicCalendar)
    {
        $academicCalendar->update($this->validatedCalendar($request, $academicCalendar));

        return redirect()->route('academic-calendar.index')->with('success', 'Semester calendar updated.');
    }

    public function destroy(AcademicCalendar $academicCalendar)
    {
        $academicCalendar->delete();

        return redirect()->route('academic-calendar.index')->with('success', 'Semester calendar deleted.');
    }

    private function validatedCalendar(Request $request, ?AcademicCalendar $calendar = null): array
    {
        $validated = $request->validate([
            'academic_year' => 'required|string|max:20',
            'semester' => 'required|integer|between:1,2',
            'semester_start_date' => 'required|date',
            'semester_end_date' => 'required|date|after_or_equal:semester_start_date',
            'term_1_start_date' => 'required|date|after_or_equal:semester_start_date|before_or_equal:semester_end_date',
            'term_1_end_date' => 'required|date|after_or_equal:term_1_start_date|before_or_equal:semester_end_date',
            'term_2_start_date' => 'required|date|after:term_1_end_date|before_or_equal:semester_end_date',
            'term_2_end_date' => 'required|date|after_or_equal:term_2_start_date|before_or_equal:semester_end_date',
            'term_3_start_date' => 'required|date|after:term_2_end_date|before_or_equal:semester_end_date',
            'term_3_end_date' => 'required|date|after_or_equal:term_3_start_date|before_or_equal:semester_end_date',
        ]);

        $sameSemester = AcademicCalendar::where('academic_year', $validated['academic_year'])
            ->where('semester', $validated['semester'])
            ->when($calendar, fn ($query) => $query->where('id', '!=', $calendar->id));

        if ($sameSemester->exists()) {
            throw ValidationException::withMessages([
                'semester' => 'A calendar for this semester and academic year already exists.',
            ]);
        }

        $overlappingSemester = AcademicCalendar::where('academic_year', $validated['academic_year'])
            ->where('semester', '!=', $validated['semester'])
            ->where('semester_start_date', '<=', $validated['semester_end_date'])
            ->where('semester_end_date', '>=', $validated['semester_start_date'])
            ->when($calendar, fn ($query) => $query->where('id', '!=', $calendar->id));

        if ($overlappingSemester->exists()) {
            throw ValidationException::withMessages([
                'semester_start_date' => 'The semester dates cannot overlap another semester in this academic year.',
            ]);
        }

        return $validated;
    }
}