@extends('layouts.lecturer')

@section('content')
<link rel="stylesheet" href="{{ asset('css/academic-records.css') }}?v={{ filemtime(public_path('css/academic-records.css')) }}">
<div class="container academic-records">
    <div class="records-header">
        <div>
            <h2 style="margin-bottom:.25rem;">Attendance</h2>
            <div style="color:#6b7280;">Only records for your assigned courses</div>
        </div>
        <a href="{{ route('attendance.create') }}" class="btn">+ Record Attendance</a>
    </div>

    <div class="records-stats">
        <div class="card" style="border-left: 4px solid #6d28d9;">
            <div style="color:#6b7280; font-size:.875rem;">Total Records</div>
            <div style="font-size:2rem; font-weight:800;">{{ $stats['total'] ?? 0 }}</div>
        </div>
        <div class="card" style="border-left: 4px solid #27ae60;">
            <div style="color:#6b7280; font-size:.875rem;">Present Rate</div>
            <div style="font-size:2rem; font-weight:800;">{{ $stats['present_rate'] ?? 0 }}%</div>
        </div>
        <div class="card" style="border-left: 4px solid #e74c3c;">
            <div style="color:#6b7280; font-size:.875rem;">Absences</div>
            <div style="font-size:2rem; font-weight:800;">{{ $stats['absent'] ?? 0 }}</div>
        </div>
        <div class="card" style="border-left: 4px solid #f59e0b;">
            <div style="color:#6b7280; font-size:.875rem;">Late</div>
            <div style="font-size:2rem; font-weight:800;">{{ $stats['late'] ?? 0 }}</div>
        </div>
    </div>

    <div class="records-surface">
        <form method="GET" action="{{ route('attendance.index') }}" class="filter-form">
            <div>
                <label for="search" style="display:block; font-weight:600; margin-bottom:.35rem;">Search</label>
                <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="Student or course" style="width:100%; padding:.6rem .75rem; border:1px solid #e5e7eb; border-radius:.5rem;">
            </div>
            <div>
                <label for="course_id" style="display:block; font-weight:600; margin-bottom:.35rem;">Course</label>
                <select id="course_id" name="course_id" style="width:100%; padding:.6rem .75rem; border:1px solid #e5e7eb; border-radius:.5rem;">
                    <option value="">All my courses</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ (string)request('course_id') === (string)$course->id ? 'selected' : '' }}>
                            {{ $course->course_code }} - {{ $course->course_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="status" style="display:block; font-weight:600; margin-bottom:.35rem;">Status</label>
                <select id="status" name="status" style="width:100%; padding:.6rem .75rem; border:1px solid #e5e7eb; border-radius:.5rem;">
                    <option value="">All</option>
                    @foreach(['present'=>'Present','absent'=>'Absent','late'=>'Late','excused'=>'Excused'] as $k=>$v)
                        <option value="{{ $k }}" {{ request('status') === $k ? 'selected' : '' }}>{{ $v }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="date_from" style="display:block; font-weight:600; margin-bottom:.35rem;">From</label>
                <input type="date" id="date_from" name="date_from" value="{{ request('date_from') }}" style="width:100%; padding:.6rem .75rem; border:1px solid #e5e7eb; border-radius:.5rem;">
            </div>
            <div>
                <label for="date_to" style="display:block; font-weight:600; margin-bottom:.35rem;">To</label>
                <input type="date" id="date_to" name="date_to" value="{{ request('date_to') }}" style="width:100%; padding:.6rem .75rem; border:1px solid #e5e7eb; border-radius:.5rem;">
            </div>
            <div class="filter-actions">
                <button type="submit" style="background:#6d28d9; color:#fff; border:none; padding:.65rem 1rem; border-radius:.5rem; cursor:pointer;">Filter</button>
                <a href="{{ route('attendance.index') }}" class="btn-reset">Reset</a>
            </div>
        </form>
    </div>

    <div class="records-surface">
        <div class="records-table" tabindex="0" role="region" aria-label="Course attendance records">
            <table style="width:100%; border-collapse:collapse;">
                <thead>
                    <tr style="background:#f3f4f6;">
                        <th style="text-align:left; padding:.85rem; font-size:.875rem;">Date</th>
                        <th style="text-align:left; padding:.85rem; font-size:.875rem;">Student</th>
                        <th style="text-align:left; padding:.85rem; font-size:.875rem;">Course</th>
                        <th style="text-align:left; padding:.85rem; font-size:.875rem;">Status</th>
                        <th style="text-align:left; padding:.85rem; font-size:.875rem;">Remarks</th>
                        <th style="text-align:left; padding:.85rem; font-size:.875rem;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendance as $record)
                        <tr style="border-top:1px solid #eef2f7;">
                            <td style="padding:.85rem; color:#6b7280;">{{ $record->attendance_date ? $record->attendance_date->format('Y-m-d') : '-' }}</td>
                            <td style="padding:.85rem;">
                                <div style="font-weight:700;">{{ $record->student->first_name }} {{ $record->student->last_name }}</div>
                                <div style="color:#6b7280; font-size:.875rem;">{{ $record->student->student_id }}</div>
                            </td>
                            <td style="padding:.85rem;">
                                <div style="font-weight:700;">{{ $record->course->course_code }}</div>
                                <div style="color:#6b7280; font-size:.875rem;">{{ $record->course->course_name }}</div>
                            </td>
                            <td style="padding:.85rem;">
                                <span class="status-badge status-{{ $record->status }}">
                                    {{ ucfirst($record->status) }}
                                </span>
                            </td>
                            <td style="padding:.85rem; color:#6b7280;">{{ $record->remarks ?? '-' }}</td>
                            <td style="padding:.85rem;">
                                <div class="action-buttons">
                                    <a href="{{ route('attendance.edit', $record) }}" class="btn-primary">Edit</a>
                                    <form action="{{ route('attendance.destroy', $record) }}" method="POST" onsubmit="return confirm('Delete this attendance record?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="padding:1.25rem; color:#6b7280;">No attendance records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="padding: 1rem;">
            {{ $attendance->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection
