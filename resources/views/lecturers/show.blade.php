@extends('layouts.super-admin')

@section('content')
<link rel="stylesheet" href="{{ asset('css/operations-management.css') }}?v={{ filemtime(public_path('css/operations-management.css')) }}">
<div class="container operations-management" style="max-width: 1000px; margin: 30px auto;">
    <div class="ops-header" style="display:flex; justify-content:space-between; align-items:center; gap:1rem; margin-bottom: 1.25rem;">
        <div>
            <h2 style="margin:0 0 .25rem 0;">{{ $lecturer->full_name }}</h2>
            <div style="color:#666;">{{ $lecturer->lecturer_code }} • {{ $lecturer->email }}</div>
        </div>
        <div class="ops-actions">
            <a href="{{ route('lecturers.edit', $lecturer) }}" class="btn btn-warning">Edit</a>
            <form action="{{ route('lecturers.destroy', $lecturer) }}" method="POST" onsubmit="return confirm('Delete this lecturer?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div style="padding: 1rem; margin-bottom: 1.25rem; background: #d4edda; border-left: 4px solid #27ae60; border-radius: 0.25rem;">
            <strong style="color:#155724;">✓ {{ session('success') }}</strong>
        </div>
    @endif

    @if(session('login_credentials'))
        <div style="padding: 1rem; margin-bottom: 1.25rem; background: #fff3cd; border-left: 4px solid #f39c12; border-radius: 0.25rem;">
            <strong style="color:#856404;">Lecturer Login Credentials (save these now):</strong>
            <div style="margin-top:.5rem; color:#856404;">
                Email: <strong>{{ session('login_credentials.email') }}</strong><br>
                Password: <strong>{{ session('login_credentials.password') }}</strong>
            </div>
        </div>
    @endif

    <div class="card" style="padding: 1.5rem;">
        <div class="ops-grid">
            <div>
                <div style="color:#666; font-weight:700; font-size:.85rem;">School</div>
                <div style="font-weight:800; color:#2c3e50;">{{ $lecturer->school_code ? (($schools[$lecturer->school_code] ?? 'School') . ' (' . $lecturer->school_code . ')') : 'N/A' }}</div>
            </div>
            <div>
                <div style="color:#666; font-weight:700; font-size:.85rem;">Status</div>
                <div style="font-weight:800; color:#2c3e50;">{{ ucfirst($lecturer->status) }}</div>
            </div>
            <div>
                <div style="color:#666; font-weight:700; font-size:.85rem;">Phone</div>
                <div style="font-weight:800; color:#2c3e50;">{{ $lecturer->phone ?? 'N/A' }}</div>
            </div>
            <div>
                <div style="color:#666; font-weight:700; font-size:.85rem;">Assigned Courses</div>
                <div style="font-weight:800; color:#2c3e50;">{{ $lecturer->courses->count() }}</div>
            </div>
        </div>

        <div style="margin-top: 1.25rem;">
            <h3 style="margin:0 0 .75rem 0;">Courses</h3>
            @if($lecturer->courses->count() === 0)
                <div style="color:#666;">No courses assigned yet. Assign lecturers from the course edit page.</div>
            @else
                <ul style="margin:0; padding-left: 1.25rem; color:#2c3e50;">
                    @foreach($lecturer->courses as $course)
                        <li style="margin:.35rem 0;">
                            <a href="{{ route('courses.show', $course) }}" style="color:#3498db; text-decoration:none; font-weight:700;">{{ $course->course_name }}</a>
                            <span style="color:#7f8c8d;">({{ $course->course_code }})</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

    <a href="{{ route('lecturers.index') }}" class="btn btn-secondary" style="margin-top: 1rem;">Back</a>
</div>
@endsection
