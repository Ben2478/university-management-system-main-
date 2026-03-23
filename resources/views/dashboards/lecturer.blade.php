@extends('layouts.lecturer')

@section('content')
<div class="container">
    <div style="margin-bottom: 1.5rem;">
        <h1 style="margin:0 0 .25rem 0;">Welcome, {{ $lecturer->full_name }}</h1>
        <p style="margin:0; color:#666;">Here are your assigned courses and active enrollments</p>
    </div>

    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
        <div class="card" style="border-left: 4px solid #6d28d9;">
            <div style="color:#666; font-size:.8rem; font-weight:800; text-transform:uppercase;">My Courses</div>
            <div style="font-size:2rem; font-weight:900; color:#6d28d9;">{{ $stats['courses_count'] }}</div>
        </div>
        <div class="card" style="border-left: 4px solid #10b981;">
            <div style="color:#666; font-size:.8rem; font-weight:800; text-transform:uppercase;">Active Enrollments</div>
            <div style="font-size:2rem; font-weight:900; color:#10b981;">{{ $stats['active_enrollments'] }}</div>
        </div>
    </div>

    <div class="card">
        <h2 style="margin:0 0 1rem 0;">My Courses</h2>
        @if($courses->count() === 0)
            <p style="color:#666; margin:0;">No courses assigned yet. Ask the admin to assign you to courses.</p>
        @else
            <table style="width:100%; border-collapse:collapse;">
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
        @endif
    </div>
</div>
@endsection
