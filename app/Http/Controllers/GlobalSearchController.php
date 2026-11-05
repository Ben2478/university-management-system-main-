<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lecturer;
use App\Models\Program;
use App\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GlobalSearchController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
        ]);

        $term = trim($validated['q'] ?? '');
        if (mb_strlen($term) < 2) {
            return response()->json(['results' => []]);
        }

        $user = $request->user();
        $results = collect();

        if ($user->isSuperAdmin() || $user->isAdmin()) {
            $results = $results->merge($this->students($term));
            $results = $results->merge($this->courses(Course::query(), $term));
            $results = $results->merge($this->programs($term));
            $results = $results->merge($this->lecturers($term));
        } elseif ($user->isLecturer()) {
            $lecturer = Lecturer::where('user_id', $user->id)->first();
            if ($lecturer) {
                $courses = Course::whereHas('lecturers', fn ($query) => $query->where('lecturers.id', $lecturer->id));
                $results = $results->merge($this->courses($courses, $term));
            }
        } elseif ($user->isStudent()) {
            $student = Student::where('user_id', $user->id)->first();
            if ($student) {
                if ($this->matches($term, [$student->first_name, $student->last_name, $student->student_id, $student->email])) {
                    $results->push($this->result(
                        'Student record',
                        $student->full_name,
                        $student->student_id . ' · ' . $student->program,
                        route('enrollments.index')
                    ));
                }

                $results = $results->merge($this->courses(
                    Course::where('department', $student->program),
                    $term
                ));
            }
        } elseif ($user->isParent()) {
            $children = $user->childrenAsParent();
            $matchingChildren = (clone $children)->where(function ($query) use ($term) {
                $query->where('first_name', 'like', "%{$term}%")
                    ->orWhere('last_name', 'like', "%{$term}%")
                    ->orWhere('student_id', 'like', "%{$term}%");
            })->get();

            foreach ($matchingChildren as $child) {
                $results->push($this->result(
                    'Student',
                    $child->full_name,
                    $child->student_id . ' · ' . $child->program,
                    route('dashboard')
                ));
            }

            $childPrograms = (clone $children)->select('program')->distinct()->pluck('program');
            $results = $results->merge($this->courses(
                Course::whereIn('department', $childPrograms),
                $term
            ));
        }

        return response()->json(['results' => $results->take(12)->values()]);
    }

    private function students(string $term): array
    {
        return Student::query()
            ->where(function ($query) use ($term) {
                $query->where('first_name', 'like', "%{$term}%")
                    ->orWhere('last_name', 'like', "%{$term}%")
                    ->orWhere('student_id', 'like', "%{$term}%")
                    ->orWhere('email', 'like', "%{$term}%");
            })
            ->limit(5)
            ->get()
            ->map(fn (Student $student) => $this->result(
                'Student',
                $student->full_name,
                $student->student_id . ' · ' . $student->program,
                route('students.show', $student)
            ))
            ->all();
    }

    private function courses($query, string $term): array
    {
        return $query->where(function ($builder) use ($term) {
                $builder->where('course_name', 'like', "%{$term}%")
                    ->orWhere('course_code', 'like', "%{$term}%")
                    ->orWhere('department', 'like', "%{$term}%");
            })
            ->limit(5)
            ->get()
            ->map(fn (Course $course) => $this->result(
                'Course',
                $course->course_name,
                $course->course_code . ' · ' . $course->department,
                route('courses.show', $course)
            ))
            ->all();
    }

    private function programs(string $term): array
    {
        return Program::where('name', 'like', "%{$term}%")
            ->orWhere('department', 'like', "%{$term}%")
            ->limit(4)
            ->get()
            ->map(fn (Program $program) => $this->result(
                'Programme',
                $program->name,
                $program->department ?? 'Academic programme',
                route('programs.show', $program)
            ))
            ->all();
    }

    private function lecturers(string $term): array
    {
        return Lecturer::where(function ($query) use ($term) {
                $query->where('first_name', 'like', "%{$term}%")
                    ->orWhere('last_name', 'like', "%{$term}%")
                    ->orWhere('lecturer_code', 'like', "%{$term}%")
                    ->orWhere('email', 'like', "%{$term}%");
            })
            ->limit(3)
            ->get()
            ->map(fn (Lecturer $lecturer) => $this->result(
                'Lecturer',
                $lecturer->full_name,
                $lecturer->lecturer_code,
                route('lecturers.show', $lecturer)
            ))
            ->all();
    }

    private function matches(string $term, array $values): bool
    {
        foreach ($values as $value) {
            if (str_contains(mb_strtolower((string) $value), mb_strtolower($term))) {
                return true;
            }
        }

        return false;
    }

    private function result(string $type, string $title, string $detail, string $url): array
    {
        return compact('type', 'title', 'detail', 'url');
    }
}