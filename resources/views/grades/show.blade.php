@extends(auth()->user()->role === 'super_admin' ? 'layouts.super-admin' : (auth()->user()->role === 'admin' ? 'layouts.admin' : (auth()->user()->role === 'lecturer' ? 'layouts.lecturer' : 'layouts.app')))

@section('content')
<link rel="stylesheet" href="{{ asset('css/academic-records.css') }}?v={{ filemtime(public_path('css/academic-records.css')) }}">
<div class="container academic-records records-detail-page">
    <div class="records-header">
        <h2>Grade Record</h2>
        @if(!auth()->user()->isStudent() && !auth()->user()->isParent())
            <div class="records-actions">
                <a href="{{ route('grades.edit', $grade) }}" class="btn-primary" style="padding: 10px 15px; background: #3498db; color: white; text-decoration: none; border-radius: 4px;">Edit</a>
                <form action="{{ route('grades.destroy', $grade) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-danger" style="padding: 10px 15px; background: #e74c3c; color: white; border: none; border-radius: 4px; cursor: pointer;">Delete</button>
                </form>
            </div>
        @endif
    </div>

    <div class="records-surface">
        <div class="records-grid">
            <div>
                <h4 style="color: #34495e; margin-bottom: 10px;">Student</h4>
                <p style="font-size: 18px; font-weight: bold; color: #2c3e50;">{{ $grade->student->user->name }}</p>
            </div>
            <div>
                <h4 style="color: #34495e; margin-bottom: 10px;">Course</h4>
                <p style="font-size: 18px; font-weight: bold; color: #2c3e50;">{{ $grade->course->course_name }}</p>
            </div>
            <div>
                <h4 style="color: #34495e; margin-bottom: 10px;">Assignment</h4>
                <p style="font-size: 18px; font-weight: bold; color: #2c3e50;">{{ $grade->assignment_score ?? '-' }}</p>
            </div>
            <div>
                <h4 style="color: #34495e; margin-bottom: 10px;">Midterm</h4>
                <p style="font-size: 18px; font-weight: bold; color: #2c3e50;">{{ $grade->midterm_score ?? '-' }}</p>
            </div>
            <div>
                <h4 style="color: #34495e; margin-bottom: 10px;">Final</h4>
                <p style="font-size: 18px; font-weight: bold; color: #2c3e50;">{{ $grade->final_score ?? '-' }}</p>
            </div>
            <div>
                <h4 style="color: #34495e; margin-bottom: 10px;">Overall</h4>
                <p style="font-size: 18px; font-weight: bold; color: #2c3e50;">{{ $grade->overall_score ?? '-' }}/100</p>
            </div>
            <div>
                <h4 style="color: #34495e; margin-bottom: 10px;">Grade</h4>
                <p style="font-size: 18px; font-weight: bold; color: #f39c12;">
                    <span class="grade-badge grade-{{ strtolower($grade->grade ?? '') }}">{{ $grade->grade ?? '-' }}</span>
                </p>
            </div>
            <div>
                <h4 style="color: #34495e; margin-bottom: 10px;">Graded Date</h4>
                <p style="font-size: 18px; font-weight: bold; color: #2c3e50;">{{ $grade->graded_date ? $grade->graded_date->format('Y-m-d') : '-' }}</p>
            </div>
        </div>

        @if($grade->remarks)
            <div style="margin-top: 20px;">
                <h4 style="color: #34495e; margin-bottom: 10px;">Remarks</h4>
                <p style="color: #555;">{{ $grade->remarks }}</p>
            </div>
        @endif
    </div>

    <a href="{{ route('grades.index') }}" class="btn-secondary" style="padding: 10px 15px; background: #95a5a6; color: white; text-decoration: none; border-radius: 4px;">Back to Grades</a>
</div>
@endsection
