@extends('layouts.super-admin')

@section('content')
<div class="container" style="max-width: 1000px; margin: 30px auto;">
    <div style="display:flex; justify-content:space-between; align-items:center; gap:1rem; margin-bottom: 1.25rem;">
        <div>
            <h2 style="margin:0 0 .25rem 0;">{{ $lecturer->full_name }}</h2>
            <div style="color:#666;">{{ $lecturer->lecturer_code }} • {{ $lecturer->email }}</div>
        </div>
        <div style="display:flex; gap:.5rem;">
            <a href="{{ route('lecturers.edit', $lecturer) }}" style="padding:.6rem .9rem; background:#f39c12; color:#fff; border-radius:.35rem; text-decoration:none; font-weight:800;">Edit</a>
            <form action="{{ route('lecturers.destroy', $lecturer) }}" method="POST" onsubmit="return confirm('Delete this lecturer?');">
                @csrf
                @method('DELETE')
                <button type="submit" style="padding:.6rem .9rem; background:#e74c3c; color:#fff; border:none; border-radius:.35rem; cursor:pointer; font-weight:800;">Delete</button>
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
        <div style="display:grid; grid-template-columns: repeat(2, 1fr); gap: 1rem;">
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

    <a href="{{ route('lecturers.index') }}" style="display:inline-block; margin-top: 1rem; padding:.6rem .9rem; background:#95a5a6; color:#fff; border-radius:.35rem; text-decoration:none; font-weight:800;">Back</a>
</div>
@endsection
