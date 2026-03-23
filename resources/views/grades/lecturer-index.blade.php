@extends('layouts.lecturer')

@section('content')
<div class="container">
    <div style="display:flex; justify-content:space-between; align-items:center; gap:1rem; margin-bottom: 1.5rem;">
        <div>
            <h2 style="margin-bottom:.25rem;">Grades</h2>
            <div style="color:#6b7280;">Only grades for your assigned courses</div>
        </div>
        <a href="{{ route('grades.create') }}" style="background:#6d28d9; color:#fff; padding:.65rem 1rem; border-radius:.5rem; text-decoration:none;">+ Add Grade</a>
    </div>

    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem; margin-bottom: 1.25rem;">
        <div class="card" style="border-left: 4px solid #6d28d9;">
            <div style="color:#6b7280; font-size:.875rem;">Total Records</div>
            <div style="font-size:2rem; font-weight:800;">{{ $stats['total'] ?? 0 }}</div>
        </div>
        <div class="card" style="border-left: 4px solid #3498db;">
            <div style="color:#6b7280; font-size:.875rem;">Average Score</div>
            <div style="font-size:2rem; font-weight:800;">{{ $stats['average'] ?? 0 }}</div>
        </div>
        <div class="card" style="border-left: 4px solid #27ae60;">
            <div style="color:#6b7280; font-size:.875rem;">Distinctions (A/B)</div>
            <div style="font-size:2rem; font-weight:800;">{{ $stats['distinction'] ?? 0 }}</div>
        </div>
        <div class="card" style="border-left: 4px solid #e74c3c;">
            <div style="color:#6b7280; font-size:.875rem;">Need Attention (D/F)</div>
            <div style="font-size:2rem; font-weight:800;">{{ $stats['need_attention'] ?? 0 }}</div>
        </div>
    </div>

    <div class="card">
        <form method="GET" action="{{ route('grades.index') }}" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem; align-items:end;">
            <div>
                <label style="display:block; font-weight:600; margin-bottom:.35rem;">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Student or course" style="width:100%; padding:.6rem .75rem; border:1px solid #e5e7eb; border-radius:.5rem;">
            </div>
            <div>
                <label style="display:block; font-weight:600; margin-bottom:.35rem;">Course</label>
                <select name="course_id" style="width:100%; padding:.6rem .75rem; border:1px solid #e5e7eb; border-radius:.5rem;">
                    <option value="">All my courses</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ (string)request('course_id') === (string)$course->id ? 'selected' : '' }}>
                            {{ $course->course_code }} - {{ $course->course_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div style="display:flex; gap:.5rem;">
                <button type="submit" style="background:#6d28d9; color:#fff; border:none; padding:.65rem 1rem; border-radius:.5rem; cursor:pointer;">Filter</button>
                <a href="{{ route('grades.index') }}" style="background:#9ca3af; color:#fff; text-decoration:none; padding:.65rem 1rem; border-radius:.5rem;">Reset</a>
            </div>
        </form>
    </div>

    <div class="card" style="padding:0; overflow:hidden;">
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse;">
                <thead>
                    <tr style="background:#f3f4f6;">
                        <th style="text-align:left; padding:.85rem; font-size:.875rem;">Student</th>
                        <th style="text-align:left; padding:.85rem; font-size:.875rem;">Course</th>
                        <th style="text-align:left; padding:.85rem; font-size:.875rem;">Overall</th>
                        <th style="text-align:left; padding:.85rem; font-size:.875rem;">Grade</th>
                        <th style="text-align:left; padding:.85rem; font-size:.875rem;">Date</th>
                        <th style="text-align:left; padding:.85rem; font-size:.875rem;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($grades as $grade)
                        <tr style="border-top:1px solid #eef2f7;">
                            <td style="padding:.85rem;">
                                <div style="font-weight:700;">{{ $grade->student->first_name }} {{ $grade->student->last_name }}</div>
                                <div style="color:#6b7280; font-size:.875rem;">{{ $grade->student->student_id }}</div>
                            </td>
                            <td style="padding:.85rem;">
                                <div style="font-weight:700;">{{ $grade->course->course_code }}</div>
                                <div style="color:#6b7280; font-size:.875rem;">{{ $grade->course->course_name }}</div>
                            </td>
                            <td style="padding:.85rem; font-weight:700;">{{ $grade->overall_score ?? '-' }}</td>
                            <td style="padding:.85rem;">
                                <span style="display:inline-block; padding:.2rem .6rem; border-radius:999px; background:#ede9fe; color:#5b21b6; font-weight:800;">
                                    {{ $grade->grade ?? '-' }}
                                </span>
                            </td>
                            <td style="padding:.85rem; color:#6b7280;">{{ $grade->graded_date ? $grade->graded_date->format('Y-m-d') : '-' }}</td>
                            <td style="padding:.85rem;">
                                <div style="display:flex; gap:.5rem; flex-wrap:wrap;">
                                    <a href="{{ route('grades.show', $grade) }}" style="background:#2563eb; color:#fff; padding:.45rem .7rem; border-radius:.5rem; text-decoration:none; font-size:.875rem;">View</a>
                                    <a href="{{ route('grades.edit', $grade) }}" style="background:#0ea5e9; color:#fff; padding:.45rem .7rem; border-radius:.5rem; text-decoration:none; font-size:.875rem;">Edit</a>
                                    <form action="{{ route('grades.destroy', $grade) }}" method="POST" onsubmit="return confirm('Delete this grade?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="background:#ef4444; color:#fff; border:none; padding:.45rem .7rem; border-radius:.5rem; cursor:pointer; font-size:.875rem;">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="padding:1.25rem; color:#6b7280;">No grades found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="padding: 1rem;">
            {{ $grades->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection
