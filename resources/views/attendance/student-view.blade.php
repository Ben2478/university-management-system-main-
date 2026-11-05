@extends('layouts.student')

@section('content')
<link rel="stylesheet" href="{{ asset('css/academic-records.css') }}?v={{ filemtime(public_path('css/academic-records.css')) }}">
<div class="container academic-records">
    <div class="records-header">
        <h1 style="margin: 0 0 0.5rem 0;">My Attendance</h1>
        <p style="margin: 0; color: #666;">Track your class attendance across all courses</p>
    </div>

    <!-- Stats Cards -->
    <div class="records-stats">
        <div class="card" style="border-left: 4px solid #3498db; padding: 1.25rem;">
            <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase;">Total Classes</p>
            <h2 style="margin: 0.5rem 0 0 0; color: #3498db; font-size: 2rem;">{{ $stats['total'] }}</h2>
        </div>
        <div class="card" style="border-left: 4px solid #27ae60; padding: 1.25rem;">
            <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase;">Present</p>
            <h2 style="margin: 0.5rem 0 0 0; color: #27ae60; font-size: 2rem;">{{ $stats['present'] }}</h2>
        </div>
        <div class="card" style="border-left: 4px solid #e74c3c; padding: 1.25rem;">
            <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase;">Absent</p>
            <h2 style="margin: 0.5rem 0 0 0; color: #e74c3c; font-size: 2rem;">{{ $stats['absent'] }}</h2>
        </div>
        <div class="card" style="border-left: 4px solid #f39c12; padding: 1.25rem;">
            <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase;">Attendance Rate</p>
            <h2 style="margin: 0.5rem 0 0 0; 
                color: {{ $stats['attendance_rate'] >= 75 ? '#27ae60' : ($stats['attendance_rate'] >= 60 ? '#f39c12' : '#e74c3c') }}; 
                font-size: 2rem;">
                {{ $stats['attendance_rate'] }}%
            </h2>
        </div>
    </div>

    <!-- Attendance Alert -->
    @if($stats['attendance_rate'] < 75)
    <div style="margin-bottom: 2rem; padding: 1rem; background: {{ $stats['attendance_rate'] < 60 ? '#f8d7da' : '#fff3cd' }}; border-left: 4px solid {{ $stats['attendance_rate'] < 60 ? '#e74c3c' : '#f39c12' }}; border-radius: 0.25rem;">
        <p style="margin: 0; color: {{ $stats['attendance_rate'] < 60 ? '#721c24' : '#856404' }}; font-weight: 600;">
            @if($stats['attendance_rate'] < 60)
                🚨 Critical: Your attendance is below 60%. You may be at risk of failing courses due to low attendance.
            @else
                ⚠️ Warning: Your attendance is below 75%. Please improve your attendance to stay on track.
            @endif
        </p>
    </div>
    @elseif($stats['attendance_rate'] >= 90)
    <div style="margin-bottom: 2rem; padding: 1rem; background: #d4edda; border-left: 4px solid #27ae60; border-radius: 0.25rem;">
        <p style="margin: 0; color: #155724; font-weight: 600;">
            🎉 Excellent! You have a {{ $stats['attendance_rate'] }}% attendance rate. Keep up the great work!
        </p>
    </div>
    @endif

    <!-- Attendance Records -->
    @if($attendances->count() > 0)
    <div class="records-surface">
        <h3 style="margin: 0 0 1.5rem 0;">Attendance Record</h3>
        <div class="records-table" tabindex="0" role="region" aria-label="Student attendance report">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid #ddd; background: #f8f9fa;">
                        <th style="text-align: left; padding: 1rem; font-size: 0.875rem;">Date</th>
                        <th style="text-align: left; padding: 1rem; font-size: 0.875rem;">Course Code</th>
                        <th style="text-align: left; padding: 1rem; font-size: 0.875rem;">Course Name</th>
                        <th style="text-align: center; padding: 1rem; font-size: 0.875rem;">Status</th>
                        <th style="text-align: left; padding: 1rem; font-size: 0.875rem;">Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($attendances as $attendance)
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 1rem;">{{ \Carbon\Carbon::parse($attendance->attendance_date)->format('M d, Y') }}</td>
                        <td style="padding: 1rem; font-weight: 600;">{{ $attendance->course->course_code }}</td>
                        <td style="padding: 1rem;">{{ $attendance->course->course_name }}</td>
                        <td style="padding: 1rem; text-align: center;">
                            <span class="status-badge status-{{ $attendance->status }}" style="padding: 0.5rem 1rem; border-radius: 0.25rem; font-weight: 600; font-size: 0.875rem;
                                background-color: {{ $attendance->status === 'present' ? '#d4edda' : ($attendance->status === 'late' ? '#fff3cd' : ($attendance->status === 'excused' ? '#d1ecf1' : '#f8d7da')) }};
                                color: {{ $attendance->status === 'present' ? '#155724' : ($attendance->status === 'late' ? '#856404' : ($attendance->status === 'excused' ? '#0c5460' : '#721c24')) }};">
                                @if($attendance->status === 'present')
                                    ✓ Present
                                @elseif($attendance->status === 'late')
                                    ⏰ Late
                                @elseif($attendance->status === 'excused')
                                    ℹ️ Excused
                                @else
                                    ✗ Absent
                                @endif
                            </span>
                        </td>
                        <td style="padding: 1rem; color: #666; font-size: 0.875rem;">
                            {{ $attendance->remarks ?? '-' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div class="card" style="text-align: center; padding: 3rem;">
        <p style="color: #999; font-size: 1.125rem; margin-bottom: 1rem;">📅 No attendance records yet</p>
        <p style="color: #666; font-size: 0.875rem;">Your attendance will appear here once classes begin.</p>
    </div>
    @endif
</div>
@endsection
