@extends('layouts.student')

@section('content')
<link rel="stylesheet" href="{{ asset('css/academic-records.css') }}?v={{ filemtime(public_path('css/academic-records.css')) }}">
<div class="container academic-records">
    <div class="records-header">
        <h1 style="margin: 0 0 0.5rem 0;">My Grades</h1>
        <p style="margin: 0; color: #666;">View your academic performance and GPA</p>
    </div>

    <!-- Stats Cards -->
    <div class="records-stats">
        <div class="card" style="border-left: 4px solid #3498db; padding: 1.25rem;">
            <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase; font-weight: 600;">Total Courses</p>
            <h2 style="margin: 0.5rem 0 0 0; color: #3498db; font-size: 2rem;">{{ $stats['total'] }}</h2>
        </div>
        <div class="card" style="border-left: 4px solid #9b59b6; padding: 1.25rem;">
            <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase; font-weight: 600;">GPA (4.0 Scale)</p>
            <h2 style="margin: 0.5rem 0 0 0; color: #9b59b6; font-size: 2rem;">{{ $stats['gpa'] }}</h2>
        </div>
        <div class="card" style="border-left: 4px solid #27ae60; padding: 1.25rem;">
            <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase; font-weight: 600;">Average Score</p>
            <h2 style="margin: 0.5rem 0 0 0; color: #27ae60; font-size: 2rem;">{{ $stats['average'] }}%</h2>
        </div>
        <div class="card" style="border-left: 4px solid #f39c12; padding: 1.25rem;">
            <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase; font-weight: 600;">Passing Courses</p>
            <h2 style="margin: 0.5rem 0 0 0; color: #f39c12; font-size: 2rem;">{{ $stats['passing'] }}/{{ $stats['total'] }}</h2>
        </div>
        <div class="card" style="border-left: 4px solid #1abc9c; padding: 1.25rem;">
            <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase; font-weight: 600;">Distinction (A/B)</p>
            <h2 style="margin: 0.5rem 0 0 0; color: #1abc9c; font-size: 2rem;">{{ $stats['distinction'] }}</h2>
        </div>
        <div class="card" style="border-left: 4px solid #e74c3c; padding: 1.25rem;">
            <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase; font-weight: 600;">Need Attention</p>
            <h2 style="margin: 0.5rem 0 0 0; color: #e74c3c; font-size: 2rem;">{{ $stats['need_attention'] }}</h2>
        </div>
    </div>

    @if($grades->count() > 0)
    <!-- Performance Insights -->
    <div class="records-insights">
        <!-- At Risk Alert -->
        @if($atRiskCourses->count() > 0)
        <div style="padding: 1rem; background: #fff3cd; border-left: 4px solid #ffc107; border-radius: 0.25rem;">
            <h4 style="margin: 0 0 0.5rem 0; color: #856404;">⚠️ Courses Needing Attention</h4>
            <p style="margin: 0; color: #856404; font-size: 0.875rem;">You have {{ $atRiskCourses->count() }} course(s) below 60%</p>
            @foreach($atRiskCourses->take(2) as $course)
                <p style="margin: 0.5rem 0 0 0; color: #856404; font-size: 0.875rem;">
                    • {{ $course->course->course_code }}: {{ $course->overall_score }}%
                </p>
            @endforeach
        </div>
        @endif

        <!-- Best Performance -->
        @if($bestCourse)
        <div style="padding: 1rem; background: #d4edda; border-left: 4px solid #27ae60; border-radius: 0.25rem;">
            <h4 style="margin: 0 0 0.5rem 0; color: #155724;">🏆 Top Performance</h4>
            <p style="margin: 0; color: #155724; font-weight: 600;">{{ $bestCourse->course->course_code }}</p>
            <p style="margin: 0.25rem 0 0 0; color: #155724; font-size: 0.875rem;">{{ $bestCourse->overall_score }}% - Grade: {{ $bestCourse->grade }}</p>
        </div>
        @endif

        <!-- GPA Status -->
        <div style="padding: 1rem; background: {{ $stats['gpa'] >= 3.0 ? '#e3f2fd' : ($stats['gpa'] >= 2.0 ? '#fff3e0' : '#ffebee') }}; border-left: 4px solid {{ $stats['gpa'] >= 3.0 ? '#1976d2' : ($stats['gpa'] >= 2.0 ? '#f57c00' : '#c62828') }}; border-radius: 0.25rem;">
            <h4 style="margin: 0 0 0.5rem 0; color: {{ $stats['gpa'] >= 3.0 ? '#0d47a1' : ($stats['gpa'] >= 2.0 ? '#e65100' : '#b71c1c') }};">📊 GPA Status</h4>
            <p style="margin: 0; color: {{ $stats['gpa'] >= 3.0 ? '#0d47a1' : ($stats['gpa'] >= 2.0 ? '#e65100' : '#b71c1c') }}; font-weight: 600;">
                @if($stats['gpa'] >= 3.5)
                    Dean's List - Excellent!
                @elseif($stats['gpa'] >= 3.0)
                    Good Standing
                @elseif($stats['gpa'] >= 2.0)
                    Satisfactory
                @else
                    Academic Warning
                @endif
            </p>
            <p style="margin: 0.25rem 0 0 0; color: {{ $stats['gpa'] >= 3.0 ? '#0d47a1' : ($stats['gpa'] >= 2.0 ? '#e65100' : '#b71c1c') }}; font-size: 0.875rem;">Current GPA: {{ $stats['gpa'] }}</p>
        </div>
    </div>

    <!-- Grade Distribution -->
    <div class="records-surface" style="margin-bottom: 2rem;">
        <h3 style="margin: 0 0 1rem 0;">Grade Distribution</h3>
        <div style="display: flex; gap: 1rem; align-items: end; height: 200px;">
            @foreach($gradeDistribution as $letter => $count)
                @php
                    $percentage = $stats['total'] > 0 ? ($count / $stats['total']) * 100 : 0;
                    $height = $percentage > 0 ? max($percentage, 10) : 0;
                    $colors = ['A' => '#27ae60', 'B' => '#3498db', 'C' => '#f39c12', 'D' => '#e67e22', 'F' => '#e74c3c'];
                @endphp
                <div style="flex: 1; display: flex; flex-direction: column; align-items: center;">
                    <div style="width: 100%; background: {{ $colors[$letter] }}; height: {{ $height }}%; border-radius: 0.25rem 0.25rem 0 0; display: flex; align-items: start; justify-content: center; padding-top: 0.5rem; color: white; font-weight: 600; min-height: 30px;">
                        @if($count > 0){{ $count }}@endif
                    </div>
                    <div style="margin-top: 0.5rem; font-weight: 600; color: #2c3e50;">{{ $letter }}</div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Grades Table -->
    <div class="records-surface">
        <h3 style="margin: 0 0 1.5rem 0;">Detailed Grade Report</h3>
        <div class="records-table" tabindex="0" role="region" aria-label="Detailed grade report">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid #ddd; background: #f8f9fa;">
                        <th style="text-align: left; padding: 1rem; font-size: 0.875rem;">Course Code</th>
                        <th style="text-align: left; padding: 1rem; font-size: 0.875rem;">Course Name</th>
                        <th style="text-align: center; padding: 1rem; font-size: 0.875rem;">Credits</th>
                        <th style="text-align: center; padding: 1rem; font-size: 0.875rem;">Midterm</th>
                        <th style="text-align: center; padding: 1rem; font-size: 0.875rem;">Final</th>
                        <th style="text-align: center; padding: 1rem; font-size: 0.875rem;">Assignments</th>
                        <th style="text-align: center; padding: 1rem; font-size: 0.875rem;">Overall</th>
                        <th style="text-align: center; padding: 1rem; font-size: 0.875rem;">Grade</th>
                        <th style="text-align: center; padding: 1rem; font-size: 0.875rem;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($grades as $grade)
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 1rem; font-weight: 600;">{{ $grade->course->course_code }}</td>
                        <td style="padding: 1rem;">{{ $grade->course->course_name }}</td>
                        <td style="padding: 1rem; text-align: center;">{{ $grade->course->credits ?? 3 }}</td>
                        <td style="padding: 1rem; text-align: center;">{{ $grade->midterm_score ?? '-' }}</td>
                        <td style="padding: 1rem; text-align: center;">{{ $grade->final_score ?? '-' }}</td>
                        <td style="padding: 1rem; text-align: center;">{{ $grade->assignment_score ?? '-' }}</td>
                        <td style="padding: 1rem; text-align: center; font-weight: 600;">{{ $grade->overall_score }}%</td>
                        <td style="padding: 1rem; text-align: center;">
                            <span class="grade-badge grade-{{ strtolower($grade->grade ?? '') }}" style="padding: 0.5rem 1rem; border-radius: 0.25rem; font-weight: 600; font-size: 1.125rem;
                                background-color: {{ in_array($grade->grade, ['A', 'B']) ? '#d4edda' : (in_array($grade->grade, ['C']) ? '#fff3cd' : '#f8d7da') }};
                                color: {{ in_array($grade->grade, ['A', 'B']) ? '#155724' : (in_array($grade->grade, ['C']) ? '#856404' : '#721c24') }};">
                                {{ $grade->grade }}
                            </span>
                        </td>
                        <td style="padding: 1rem; text-align: center;">
                            @if($grade->overall_score >= 50)
                                <span class="status-badge present" style="color: #27ae60; font-weight: 600;">✓ Pass</span>
                            @else
                                <span class="status-badge absent" style="color: #e74c3c; font-weight: 600;">✗ Fail</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div class="card" style="text-align: center; padding: 3rem;">
        <p style="color: #999; font-size: 1.125rem; margin-bottom: 1rem;">📊 No grades recorded yet</p>
        <p style="color: #666; font-size: 0.875rem;">Your grades will appear here once they are posted by your instructors.</p>
    </div>
    @endif
</div>
@endsection
