<?php

namespace App\Services;

use App\Models\AcademicCalendar;
use App\Models\Course;
use App\Models\Receipt;
use App\Models\User;

class DashboardInsights
{
    public function forUser(User $user): array
    {
        $today = today()->toDateString();
        $calendar = AcademicCalendar::whereDate('semester_start_date', '<=', $today)
            ->whereDate('semester_end_date', '>=', $today)
            ->orderByDesc('semester_start_date')->first();
        $term = null;
        if ($calendar) {
            foreach ([1, 2, 3] as $number) {
                if ($today >= $calendar->{"term_{$number}_start_date"}->toDateString()
                    && $today <= $calendar->{"term_{$number}_end_date"}->toDateString()) {
                    $term = $number;
                    break;
                }
            }
        }

        $nextCalendar = $calendar ? null : AcademicCalendar::whereDate('semester_start_date', '>', $today)
            ->orderBy('semester_start_date')->first();
        $hasCalendar = $calendar !== null || $nextCalendar !== null || AcademicCalendar::exists();
        $actions = [];

        if ($user->isSuperAdmin() || $user->isAdmin()) {
            $pending = Receipt::where('status', 'pending')->count();
            if ($pending > 0) {
                $actions[] = $this->action('Receipts awaiting review', $pending, route('receipts.pending'), 'receipts');
            }
            if (!$hasCalendar) {
                $actions[] = $this->action('Configure the academic calendar', null, route('academic-calendar.create'), 'calendar');
            }
            if ($user->isSuperAdmin()) {
                $unassigned = Course::where('status', 'active')->doesntHave('lecturers')
                    ->where(fn ($query) => $query->whereNull('instructor')->orWhere('instructor', ''))->count();
                if ($unassigned > 0) {
                    $actions[] = $this->action('Courses without a lecturer', $unassigned, route('courses.index', ['status' => 'active']), 'courses');
                }
            }
        } elseif ($user->isStudent()) {
            $student = $user->student;
            if ($student) {
                $pending = $student->receipts()->where('status', 'pending')->count();
                if ($pending > 0) {
                    $actions[] = $this->action('Receipts awaiting verification', $pending, route('receipts.student-history'), 'receipts');
                }
                if ($student->getBalance() > 0) {
                    $actions[] = $this->action('Outstanding tuition: K' . number_format($student->getBalance(), 2), null, route('receipts.create'), 'payments');
                }
                if (!$student->enrollments()->where('status', 'active')->exists()) {
                    $actions[] = $this->action('Browse available courses', null, route('enrollments.available'), 'courses');
                }
            }
        } elseif ($user->isLecturer()) {
            if ($user->lecturer && $user->lecturer->courses()->exists()) {
                $actions[] = $this->action('Record attendance for your courses', null, route('attendance.index'), 'attendance');
                $actions[] = $this->action('Review course grades', null, route('grades.index'), 'grades');
            }
        } elseif ($user->isParent()) {
            $balance = $user->childrenAsParent()->get()->sum(fn ($student) => max(0, $student->getBalance()));
            if ($balance > 0) {
                $actions[] = $this->action('Family tuition balance: K' . number_format($balance, 2), null, route('dashboard'), 'payments');
            }
        }

        return compact('calendar', 'term', 'nextCalendar', 'hasCalendar', 'actions');
    }

    private function action(string $label, ?int $count, string $url, string $icon): array
    {
        return compact('label', 'count', 'url', 'icon');
    }
}