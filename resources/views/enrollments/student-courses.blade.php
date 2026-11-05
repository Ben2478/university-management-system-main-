@extends('layouts.student')

@section('content')
<link rel="stylesheet" href="{{ asset('css/operations-management.css') }}?v={{ filemtime(public_path('css/operations-management.css')) }}">
<div class="container operations-management" style="padding: 2rem 0;">
    <div class="ops-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 style="margin: 0 0 0.5rem 0;">My Courses</h1>
            <p style="margin: 0; color: #666;">Student ID: {{ $student->student_id }} • {{ $student->program }} - Year {{ $student->year }}, Semester {{ $student->semester }}</p>
        </div>
        <a href="{{ route('enrollments.available') }}" class="btn" style="background: #3498db; color: white; padding: 0.75rem 1.5rem; border-radius: 0.25rem; text-decoration: none; display: flex; align-items: center; gap: 0.5rem;">
            📚 Browse Available Courses
        </a>
    </div>

    @if(session('success'))
    <div style="padding: 1rem; margin-bottom: 2rem; background: #d4edda; border-left: 4px solid #27ae60; border-radius: 0.25rem;">
        <span style="color: #155724; font-weight: 600;">✓ {{ session('success') }}</span>
    </div>
    @endif

    <!-- Stats Cards -->
    <div class="ops-stats">
        <div class="card" style="border-left: 4px solid #3498db; padding: 1.25rem;">
            <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase;">Total Courses</p>
            <h2 style="margin: 0.5rem 0 0 0; color: #3498db; font-size: 2rem;">{{ $stats['total'] }}</h2>
        </div>
        <div class="card" style="border-left: 4px solid #27ae60; padding: 1.25rem;">
            <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase;">Active</p>
            <h2 style="margin: 0.5rem 0 0 0; color: #27ae60; font-size: 2rem;">{{ $stats['active'] }}</h2>
        </div>
        <div class="card" style="border-left: 4px solid #9b59b6; padding: 1.25rem;">
            <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase;">Completed</p>
            <h2 style="margin: 0.5rem 0 0 0; color: #9b59b6; font-size: 2rem;">{{ $stats['completed'] }}</h2>
        </div>
        <div class="card" style="border-left: 4px solid #e74c3c; padding: 1.25rem;">
            <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase;">Dropped</p>
            <h2 style="margin: 0.5rem 0 0 0; color: #e74c3c; font-size: 2rem;">{{ $stats['dropped'] }}</h2>
        </div>
    </div>

    <!-- Active Courses -->
    @if($coursesData->count() > 0)
    <h2 style="margin: 0 0 1.5rem 0; color: #2c3e50;">Active Courses</h2>
    <div style="display: grid; gap: 1.5rem; margin-bottom: 3rem;">
        @foreach($coursesData as $data)
        <div class="card" style="border-left: 4px solid 
            {{ $data['status_color'] === 'success' ? '#27ae60' : ($data['status_color'] === 'warning' ? '#f39c12' : '#e74c3c') }};">
            
            <!-- Course Header -->
            <div class="ops-flex" style="justify-content: space-between; align-items: start; margin-bottom: 1.5rem;">
                <div>
                    <h3 style="margin: 0 0 0.5rem 0; color: #2c3e50;">{{ $data['course']->course_code }} - {{ $data['course']->course_name }}</h3>
                    <p style="margin: 0; color: #666; font-size: 0.875rem;">
                        Credits: {{ $data['course']->credits }} • 
                        Enrolled: {{ $data['enrollment']->enrollment_date->format('M d, Y') }}
                    </p>
                </div>
                <span style="padding: 0.5rem 1rem; border-radius: 0.25rem; font-size: 0.875rem; font-weight: 600;
                    background-color: {{ $data['status_color'] === 'success' ? '#d4edda' : ($data['status_color'] === 'warning' ? '#fff3cd' : '#f8d7da') }};
                    color: {{ $data['status_color'] === 'success' ? '#155724' : ($data['status_color'] === 'warning' ? '#856404' : '#721c24') }};">
                    @if($data['status_color'] === 'success')
                        🟢 On Track
                    @elseif($data['status_color'] === 'warning')
                        🟡 At Risk
                    @else
                        🔴 Action Needed
                    @endif
                </span>
            </div>

            <!-- Performance Metrics -->
            <div class="ops-grid" style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem; padding-bottom: 1.5rem; border-bottom: 1px solid #eee;">
                <!-- Current Grade -->
                <div>
                    <p style="margin: 0 0 0.5rem 0; color: #666; font-size: 0.75rem; text-transform: uppercase;">Current Grade</p>
                    <div style="display: flex; align-items: baseline; gap: 0.5rem;">
                        <h3 style="margin: 0; color: #2c3e50; font-size: 1.5rem;">{{ $data['grade'] }}</h3>
                        @if($data['score'] > 0)
                        <span style="color: #999; font-size: 0.875rem;">({{ $data['score'] }}%)</span>
                        @endif
                    </div>
                </div>

                <!-- Attendance -->
                <div>
                    <p style="margin: 0 0 0.5rem 0; color: #666; font-size: 0.75rem; text-transform: uppercase;">Attendance</p>
                    <div style="display: flex; align-items: baseline; gap: 0.5rem;">
                        <h3 style="margin: 0; font-size: 1.5rem;
                            color: {{ $data['attendance_rate'] >= 75 ? '#27ae60' : ($data['attendance_rate'] >= 60 ? '#f39c12' : '#e74c3c') }};">
                            {{ $data['attendance_rate'] }}%
                        </h3>
                        <span style="color: #999; font-size: 0.875rem;">({{ $data['attended'] }}/{{ $data['total_classes'] }})</span>
                    </div>
                </div>

                <!-- Progress -->
                <div>
                    <p style="margin: 0 0 0.5rem 0; color: #666; font-size: 0.75rem; text-transform: uppercase;">Progress</p>
                    <div style="background: #f0f0f0; height: 8px; border-radius: 4px; overflow: hidden; margin-bottom: 0.25rem;">
                        <div style="height: 100%; background: #3498db; width: {{ $data['progress'] }}%; transition: width 0.3s;"></div>
                    </div>
                    <span style="color: #999; font-size: 0.75rem;">{{ $data['progress'] }}% Complete</span>
                </div>
            </div>

            <!-- Quick Actions -->
            <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
                <a href="{{ route('grades.index') }}" class="btn btn-sm" style="background: #3498db; color: white; padding: 0.5rem 1rem; border-radius: 0.25rem; text-decoration: none; font-size: 0.875rem;">
                    📊 View Grades
                </a>
                <a href="{{ route('attendance.index') }}" class="btn btn-sm" style="background: #9b59b6; color: white; padding: 0.5rem 1rem; border-radius: 0.25rem; text-decoration: none; font-size: 0.875rem;">
                    📅 Attendance Record
                </a>
                <a href="{{ route('enrollments.show', $data['enrollment']) }}" class="btn btn-sm" style="background: #95a5a6; color: white; padding: 0.5rem 1rem; border-radius: 0.25rem; text-decoration: none; font-size: 0.875rem;">
                    ℹ️ Course Details
                </a>
            </div>

            <!-- Alerts -->
            @if($data['attendance_rate'] < 75)
            <div style="margin-top: 1rem; padding: 0.75rem 1rem; background: #fff3cd; border-left: 3px solid #f39c12; border-radius: 0.25rem;">
                <span style="color: #856404; font-size: 0.875rem;">⚠️ <strong>Low Attendance:</strong> You need {{ 75 - $data['attendance_rate'] }}% more attendance to stay on track.</span>
            </div>
            @endif

            @if($data['score'] > 0 && $data['score'] < 50)
            <div style="margin-top: 1rem; padding: 0.75rem 1rem; background: #f8d7da; border-left: 3px solid #e74c3c; border-radius: 0.25rem;">
                <span style="color: #721c24; font-size: 0.875rem;">🚨 <strong>Failing Grade:</strong> Seek help from your instructor immediately.</span>
            </div>
            @endif
        </div>
        @endforeach
    </div>
    @else
    <div class="card" style="text-align: center; padding: 3rem;">
        <p style="color: #999; font-size: 1.125rem; margin-bottom: 1rem;">📚 No active courses</p>
        <p style="color: #666; font-size: 0.875rem;">Contact your academic advisor to enroll in courses.</p>
    </div>
    @endif

    <!-- Completed Courses -->
    @if($completedCourses->count() > 0)
    <h2 style="margin: 2rem 0 1.5rem 0; color: #2c3e50;">Completed Courses ({{ $completedCourses->count() }})</h2>
    <div class="card ops-table-scroll" role="region" aria-label="Completed courses" tabindex="0">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 2px solid #ddd;">
                    <th style="text-align: left; padding: 0.75rem; font-size: 0.875rem;">Course Code</th>
                    <th style="text-align: left; padding: 0.75rem; font-size: 0.875rem;">Course Name</th>
                    <th style="text-align: center; padding: 0.75rem; font-size: 0.875rem;">Credits</th>
                    <th style="text-align: center; padding: 0.75rem; font-size: 0.875rem;">Final Grade</th>
                    <th style="text-align: center; padding: 0.75rem; font-size: 0.875rem;">Completed</th>
                </tr>
            </thead>
            <tbody>
                @foreach($completedCourses as $enrollment)
                @php
                    $finalGrade = \App\Models\Grade::where('student_id', $student->id)
                        ->where('course_id', $enrollment->course_id)
                        ->first();
                @endphp
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 0.75rem;">{{ $enrollment->course->course_code }}</td>
                    <td style="padding: 0.75rem;">{{ $enrollment->course->course_name }}</td>
                    <td style="padding: 0.75rem; text-align: center;">{{ $enrollment->course->credits }}</td>
                    <td style="padding: 0.75rem; text-align: center; font-weight: 600; color: #27ae60;">
                        {{ $finalGrade ? $finalGrade->grade : 'N/A' }}
                    </td>
                    <td style="padding: 0.75rem; text-align: center; color: #666; font-size: 0.875rem;">
                        {{ $enrollment->updated_at->format('M Y') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
@endsection
