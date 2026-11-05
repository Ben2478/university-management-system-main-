@extends('layouts.lecturer')

@section('content')
<style>
    .lecturer-dashboard { padding-top:1.25rem; }
    .lecturer-dashboard .card { min-width:0; border:1px solid #e8e5ee; border-radius:.65rem; box-shadow:0 2px 8px rgba(37,27,57,.045); }
    .lecturer-courses-table-wrap { width:100%; overflow-x:auto; }
    .lecturer-courses-table { width:100%; min-width:640px; border-collapse:collapse; }
    @media (max-width:600px) { .lecturer-dashboard { padding:1rem; } .lecturer-dashboard > .container { padding:0; } }
</style>
<div class="container lecturer-dashboard dashboard-workspace" data-dashboard="lecturer">
    @include('components.dashboard-heading', ['area' => 'Teaching workspace', 'title' => 'Teaching overview', 'description' => 'Welcome back, ' . $lecturer->full_name . '.'])
    @include('components.dashboard-metrics', ['metrics' => [
        ['label' => 'My Courses', 'value' => $stats['courses_count'], 'detail' => 'Assigned teaching courses', 'icon' => 'courses'],
        ['label' => 'Active Enrollments', 'value' => $stats['active_enrollments'], 'detail' => 'Across your assigned courses', 'icon' => 'students', 'tone' => 'green'],
    ]])
    <div class="card lecturer-course-section">
        <h2 style="margin:0 0 1rem 0;">My Courses</h2>
        @if($courses->count() === 0)
            <p style="color:#666; margin:0;">No courses assigned yet. Ask the admin to assign you to courses.</p>
        @else
            <div class="lecturer-courses-table-wrap">
            <table class="lecturer-courses-table">
                <thead>
                    <tr style="background:#f6f5ff;">
                        <th style="padding: 1rem; text-align:left;">Course</th>
                        <th style="padding: 1rem; text-align:left;">Code</th>
                        <th style="padding: 1rem; text-align:left;">Program</th>
                        <th style="padding: 1rem; text-align:center;">Active Students</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($courses as $course)
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding: 1rem; font-weight:800; color:#2c3e50;">{{ $course->course_name }}</td>
                            <td style="padding: 1rem; color:#2c3e50;">{{ $course->course_code }}</td>
                            <td style="padding: 1rem; color:#2c3e50;">{{ $course->department ?? 'N/A' }}</td>
                            <td style="padding: 1rem; text-align:center;">
                                <span style="padding:.25rem .6rem; background:#ecfdf5; color:#065f46; border-radius:.35rem; font-weight:900;">{{ $course->enrollments_count }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        @endif
    </div>
</div>
@endsection
