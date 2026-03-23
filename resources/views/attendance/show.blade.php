@extends(auth()->user()->role === 'super_admin' ? 'layouts.super-admin' : (auth()->user()->role === 'admin' ? 'layouts.admin' : (auth()->user()->role === 'lecturer' ? 'layouts.lecturer' : 'layouts.app')))

@section('content')
<div class="container" style="max-width: 800px; margin: 30px auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h2>Attendance Record</h2>
        @if(!auth()->user()->isStudent() && !auth()->user()->isParent())
            <div style="display: flex; gap: 10px;">
                <a href="{{ route('attendance.edit', $attendance) }}" class="btn-primary" style="padding: 10px 15px; background: #3498db; color: white; text-decoration: none; border-radius: 4px;">Edit</a>
                <form action="{{ route('attendance.destroy', $attendance) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-danger" style="padding: 10px 15px; background: #e74c3c; color: white; border: none; border-radius: 4px; cursor: pointer;">Delete</button>
                </form>
            </div>
        @endif
    </div>

    <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 30px;">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div>
                <h4 style="color: #34495e; margin-bottom: 10px;">Student</h4>
                <p style="font-size: 18px; font-weight: bold; color: #2c3e50;">{{ $attendance->student->user->name }}</p>
            </div>
            <div>
                <h4 style="color: #34495e; margin-bottom: 10px;">Course</h4>
                <p style="font-size: 18px; font-weight: bold; color: #2c3e50;">{{ $attendance->course->course_name }}</p>
            </div>
            <div>
                <h4 style="color: #34495e; margin-bottom: 10px;">Date</h4>
                <p style="font-size: 18px; font-weight: bold; color: #2c3e50;">{{ $attendance->attendance_date->format('M d, Y') }}</p>
            </div>
            <div>
                <h4 style="color: #34495e; margin-bottom: 10px;">Status</h4>
                <p style="font-size: 18px; font-weight: bold;">
                    @php
                        $statusColors = [
                            'present' => '#27ae60',
                            'absent' => '#e74c3c',
                            'late' => '#f39c12',
                            'excused' => '#3498db'
                        ];
                    @endphp
                    <span style="padding: 5px 10px; background: {{ $statusColors[$attendance->status] ?? '#95a5a6' }}; color: white; border-radius: 4px; text-transform: capitalize;">{{ $attendance->status }}</span>
                </p>
            </div>
        </div>

        @if($attendance->remarks)
            <div style="margin-top: 20px;">
                <h4 style="color: #34495e; margin-bottom: 10px;">Remarks</h4>
                <p style="color: #555;">{{ $attendance->remarks }}</p>
            </div>
        @endif
    </div>

    <a href="{{ route('attendance.index') }}" class="btn-secondary" style="padding: 10px 15px; background: #95a5a6; color: white; text-decoration: none; border-radius: 4px;">Back to Attendance</a>
</div>
@endsection
