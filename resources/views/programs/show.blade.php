@extends(auth()->user()->role === 'super_admin' ? 'layouts.super-admin' : (auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.app'))

@section('content')
<link rel="stylesheet" href="{{ asset('css/academic-management.css') }}">
<div class="container academic-management">
    <div class="academic-header">
        <div>
            <h1 style="margin: 0 0 0.5rem 0;">{{ $program->name }}</h1>
            <p style="margin: 0; color: #666;">
                @if($program->department){{ $program->department }} • @endif
                {{ $program->duration_years }} Year Program
            </p>
        </div>
        <div class="academic-actions">
            <a href="{{ route('programs.add-courses', $program) }}" class="academic-primary" style="padding: 0.75rem 1.5rem; background: #9b59b6; color: white; border-radius: 0.25rem; text-decoration: none; font-weight: 600;">
                + Add Courses
            </a>
            <a href="{{ route('programs.edit', $program) }}" style="padding: 0.75rem 1.5rem; background: #f39c12; color: white; border-radius: 0.25rem; text-decoration: none; font-weight: 600;">
                Edit Program
            </a>
            <a href="{{ route('programs.index') }}" class="academic-secondary" style="padding: 0.75rem 1.5rem; background: #95a5a6; color: white; border-radius: 0.25rem; text-decoration: none; font-weight: 600;">
                ← Back
            </a>
        </div>
    </div>

    @if(session('success'))
    <div style="padding: 1rem; margin-bottom: 2rem; background: #d4edda; border-left: 4px solid #27ae60; border-radius: 0.25rem;">
        <span style="color: #155724; font-weight: 600;">✓ {{ session('success') }}</span>
    </div>
    @endif

    <!-- Program Details -->
    <div class="academic-surface" style="margin-bottom: 2rem;">
        <h3 style="margin: 0 0 1rem 0; color: #2c3e50; padding-bottom: 1rem; border-bottom: 2px solid #ecf0f1;">Program Information</h3>
        
        <div class="academic-grid academic-details" style="margin-bottom: 1.5rem;">
            <div>
                <p style="margin: 0 0 0.25rem 0; color: #999; font-size: 0.875rem;">Status</p>
                @if($program->status === 'active')
                    <span style="padding: 0.25rem 0.75rem; background: #d4edda; color: #155724; border-radius: 0.25rem; font-size: 0.875rem; font-weight: 600;">
                        ● Active
                    </span>
                @else
                    <span style="padding: 0.25rem 0.75rem; background: #f8d7da; color: #721c24; border-radius: 0.25rem; font-size: 0.875rem; font-weight: 600;">
                        ● Inactive
                    </span>
                @endif
            </div>
            <div>
                <p style="margin: 0 0 0.25rem 0; color: #999; font-size: 0.875rem;">Total Courses</p>
                <strong style="color: #2c3e50; font-size: 1.25rem;">{{ $program->courses->count() }}</strong>
            </div>
            <div>
                <p style="margin: 0 0 0.25rem 0; color: #999; font-size: 0.875rem;">Enrolled Students</p>
                <strong style="color: #2c3e50; font-size: 1.25rem;">{{ $program->students->count() }}</strong>
            </div>
        </div>

        @if($program->description)
        <div>
            <p style="margin: 0 0 0.5rem 0; color: #999; font-size: 0.875rem;">Description</p>
            <p style="margin: 0; color: #666; line-height: 1.6;">{{ $program->description }}</p>
        </div>
        @endif
    </div>

    <!-- Courses List -->
    <div class="academic-surface">
        <div class="academic-header">
            <h3 style="margin: 0; color: #2c3e50;">Courses ({{ $program->courses->count() }})</h3>
            <a href="{{ route('programs.add-courses', $program) }}" style="padding: 0.5rem 1rem; background: #9b59b6; color: white; border-radius: 0.25rem; text-decoration: none; font-size: 0.875rem; font-weight: 600;">
                + Add More Courses
            </a>
        </div>

        @if($program->courses->count() > 0)
        <div class="academic-table-scroll" role="region" aria-label="Program courses" tabindex="0">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #34495e; color: white;">
                    <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Course Code</th>
                    <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Course Name</th>
                    <th style="padding: 0.75rem; text-align: center; font-weight: 600;">Credits</th>
                    <th style="padding: 0.75rem; text-align: center; font-weight: 600;">Term</th>
                    <th style="padding: 0.75rem; text-align: center; font-weight: 600;">Enrollments</th>
                    <th style="padding: 0.75rem; text-align: center; font-weight: 600;">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($program->courses->sortBy('term') as $course)
                <tr style="border-bottom: 1px solid #ecf0f1;">
                    <td style="padding: 0.75rem;">
                        <strong style="color: #2c3e50;">{{ $course->course_code }}</strong>
                    </td>
                    <td style="padding: 0.75rem;">{{ $course->course_name }}</td>
                    <td style="padding: 0.75rem; text-align: center;">{{ $course->credits }}</td>
                    <td style="padding: 0.75rem; text-align: center;">Term {{ $course->term }}</td>
                    <td style="padding: 0.75rem; text-align: center;">
                        <span style="padding: 0.25rem 0.75rem; background: #fff3e0; color: #e65100; border-radius: 0.25rem; font-weight: 600;">
                            {{ $course->enrollments->count() }}
                        </span>
                    </td>
                    <td style="padding: 0.75rem; text-align: center;">
                        @if($course->status === 'active')
                            <span style="padding: 0.25rem 0.75rem; background: #d4edda; color: #155724; border-radius: 0.25rem; font-size: 0.75rem; font-weight: 600;">
                                Active
                            </span>
                        @else
                            <span style="padding: 0.25rem 0.75rem; background: #f8d7da; color: #721c24; border-radius: 0.25rem; font-size: 0.75rem; font-weight: 600;">
                                Inactive
                            </span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        </div>
        @else
        <div style="text-align: center; padding: 3rem; background: #f8f9fa; border-radius: 0.5rem;">
            <p style="color: #999; font-size: 1rem; margin-bottom: 1rem;">📚 No courses added yet</p>
            <a href="{{ route('programs.add-courses', $program) }}" style="padding: 0.75rem 1.5rem; background: #9b59b6; color: white; border-radius: 0.25rem; text-decoration: none; font-weight: 600;">
                + Add Courses Now
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
