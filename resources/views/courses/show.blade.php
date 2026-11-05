@extends(auth()->user()->role === 'super_admin' ? 'layouts.super-admin' : (auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.app'))

@section('content')
<link rel="stylesheet" href="{{ asset('css/academic-management.css') }}">
<div class="container academic-management academic-form-page">
    <div class="academic-header">
        <h2>{{ $course->course_name }}</h2>
        <div class="academic-actions">
            <a href="{{ route('courses.edit', $course) }}" class="btn-primary" style="padding: 10px 15px; background: #3498db; color: white; text-decoration: none; border-radius: 4px;">Edit</a>
            <form action="{{ route('courses.destroy', $course) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger academic-danger" style="padding: 10px 15px; background: #e74c3c; color: white; border: none; border-radius: 4px; cursor: pointer;">Delete</button>
            </form>
        </div>
    </div>

    <div class="academic-surface" style="margin-bottom: 24px;">
        <div class="academic-grid academic-details">
            <div>
                <h4 style="color: #34495e; margin-bottom: 10px;">Course Code</h4>
                <p style="font-size: 18px; font-weight: bold; color: #2c3e50;">{{ $course->course_code }}</p>
            </div>
            <div>
                <h4 style="color: #34495e; margin-bottom: 10px;">Credit Hours</h4>
                <p style="font-size: 18px; font-weight: bold; color: #2c3e50;">{{ $course->credit_hours ?? $course->credits ?? 'N/A' }}</p>
            </div>
            <div>
                <h4 style="color: #34495e; margin-bottom: 10px;">Instructor</h4>
                <p style="font-size: 18px; font-weight: bold; color: #2c3e50;">{{ $course->instructor ?? 'Not assigned' }}</p>
            </div>
            <div>
                <h4 style="color: #34495e; margin-bottom: 10px;">Lecturers</h4>
                @if($course->lecturers && $course->lecturers->count() > 0)
                    <p style="color: #2c3e50; font-weight: bold;">
                        {{ $course->lecturers->pluck('full_name')->join(', ') }}
                    </p>
                @else
                    <p style="font-size: 18px; font-weight: bold; color: #2c3e50;">Not assigned</p>
                @endif
            </div>
            <div>
                <h4 style="color: #34495e; margin-bottom: 10px;">Term</h4>
                <p style="font-size: 18px; font-weight: bold; color: #2c3e50;">{{ $course->term ? 'Term ' . $course->term : 'N/A' }}</p>
            </div>
        </div>

        <div style="margin-top: 20px;">
            <h4 style="color: #34495e; margin-bottom: 10px;">Description</h4>
            <p style="color: #555;">{{ $course->description ?? 'No description' }}</p>
        </div>
    </div>

    <a href="{{ route('courses.index') }}" class="btn-secondary academic-secondary" style="padding: 10px 15px; background: #95a5a6; color: white; text-decoration: none; border-radius: 4px;">Back to Courses</a>
</div>
@endsection
