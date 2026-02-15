@extends('layouts.student')

@section('content')
<div class="container" style="padding: 2rem 0;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 style="margin: 0 0 0.5rem 0;">Available Courses</h1>
            <p style="margin: 0; color: #666;">Browse and enroll in courses for your program: <strong>{{ $student->program }}</strong></p>
        </div>
        <a href="{{ route('enrollments.index') }}" class="btn" style="background: #95a5a6; color: white; padding: 0.75rem 1.5rem; border-radius: 0.25rem; text-decoration: none;">
            ← Back to My Courses
        </a>
    </div>

    @if(session('success'))
    <div style="padding: 1rem; margin-bottom: 2rem; background: #d4edda; border-left: 4px solid #27ae60; border-radius: 0.25rem;">
        <span style="color: #155724; font-weight: 600;">✓ {{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div style="padding: 1rem; margin-bottom: 2rem; background: #f8d7da; border-left: 4px solid #e74c3c; border-radius: 0.25rem;">
        <span style="color: #721c24; font-weight: 600;">✗ {{ session('error') }}</span>
    </div>
    @endif

    @if($availableCourses->count() > 0)
    <div style="display: grid; gap: 1.5rem;">
        @foreach($availableCourses as $course)
        <div class="card" style="border-left: 4px solid #3498db;">
            <div style="display: flex; justify-content: space-between; align-items: start; gap: 2rem;">
                <!-- Course Info -->
                <div style="flex: 1;">
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                        <h3 style="margin: 0; color: #2c3e50;">{{ $course->course_code }}</h3>
                        <span style="padding: 0.25rem 0.75rem; background: #e3f2fd; color: #1976d2; border-radius: 0.25rem; font-size: 0.75rem; font-weight: 600;">
                            Semester {{ $course->semester }}
                        </span>
                        <span style="padding: 0.25rem 0.75rem; background: #f3e5f5; color: #7b1fa2; border-radius: 0.25rem; font-size: 0.75rem; font-weight: 600;">
                            {{ $course->credits }} Credits
                        </span>
                    </div>
                    
                    <h4 style="margin: 0 0 1rem 0; color: #34495e; font-weight: 500;">{{ $course->course_name }}</h4>
                    
                    @if($course->description)
                    <p style="margin: 0 0 1rem 0; color: #666; line-height: 1.6;">{{ $course->description }}</p>
                    @endif
                    
                    <div style="display: flex; gap: 1.5rem; flex-wrap: wrap; color: #666; font-size: 0.875rem;">
                        @if($course->department)
                        <div>
                            <strong>Department:</strong> {{ $course->department }}
                        </div>
                        @endif
                        
                        @if($course->tuition_fee > 0)
                        <div>
                            <strong>Course Fee:</strong> K{{ number_format($course->tuition_fee, 2) }}
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Enroll Button -->
                <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 0.5rem;">
                    <form action="{{ route('enrollments.enroll', $course) }}" method="POST" onsubmit="return confirm('Are you sure you want to enroll in {{ $course->course_code }}?');">
                        @csrf
                        <button type="submit" class="btn" style="background: #27ae60; color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 0.25rem; cursor: pointer; font-size: 1rem; font-weight: 600; white-space: nowrap;">
                            ✓ Enroll Now
                        </button>
                    </form>
                    <span style="color: #999; font-size: 0.75rem; text-align: right;">Click to add to your courses</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Course Count Summary -->
    <div style="margin-top: 2rem; padding: 1rem; background: #f8f9fa; border-radius: 0.25rem; text-align: center;">
        <p style="margin: 0; color: #666;">
            Showing <strong>{{ $availableCourses->count() }}</strong> available course(s) for <strong>{{ $student->program }}</strong>
        </p>
    </div>
    @else
    <div class="card" style="text-align: center; padding: 3rem;">
        <p style="color: #999; font-size: 1.125rem; margin-bottom: 1rem;">📚 No courses available</p>
        <p style="color: #666; font-size: 0.875rem; margin-bottom: 1.5rem;">
            @if($student->program)
                All available courses for <strong>{{ $student->program }}</strong> have been enrolled or are inactive.
            @else
                Your program is not set. Contact your academic advisor.
            @endif
        </p>
        <a href="{{ route('enrollments.index') }}" class="btn" style="background: #3498db; color: white; padding: 0.75rem 1.5rem; border-radius: 0.25rem; text-decoration: none;">
            Return to My Courses
        </a>
    </div>
    @endif
</div>
@endsection
