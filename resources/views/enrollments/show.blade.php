@extends('layouts.app')

@section('content')
<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <a href="{{ route('enrollments.index') }}" style="color: #3498db; text-decoration: none;">← Back to Enrollments</a>
            <h1 style="margin-top: 0.5rem;">Enrollment Details</h1>
        </div>
        <div style="display: flex; gap: 0.5rem;">
            <a href="{{ route('enrollments.edit', $enrollment->id) }}" class="btn btn-warning">Edit</a>
            <form action="{{ route('enrollments.destroy', $enrollment->id) }}" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
            </form>
        </div>
    </div>

    <div class="card">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
            <div>
                <h3 style="margin-top: 0;">Student Information</h3>
                <p><strong>Name:</strong> {{ $enrollment->student->first_name }} {{ $enrollment->student->last_name }}</p>
                <p><strong>Student ID:</strong> {{ $enrollment->student->student_id }}</p>
                <p><strong>Email:</strong> {{ $enrollment->student->email }}</p>
                <p><strong>Year:</strong> {{ $enrollment->student->year }}</p>
                <p><strong>Semester:</strong> {{ $enrollment->student->semester }}</p>
            </div>

            <div>
                <h3 style="margin-top: 0;">Course Information</h3>
                <p><strong>Course Name:</strong> {{ $enrollment->course->course_name }}</p>
                <p><strong>Course Code:</strong> {{ $enrollment->course->course_code }}</p>
                <p><strong>Credits:</strong> {{ $enrollment->course->credits ?? 'N/A' }}</p>
                <p><strong>Instructor:</strong> {{ $enrollment->course->instructor ?? 'N/A' }}</p>
            </div>
        </div>

        <hr style="margin: 2rem 0; border: none; border-top: 1px solid #ddd;">

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
            <div>
                <h3 style="margin-top: 0;">Enrollment Information</h3>
                <p><strong>Program:</strong> {{ $enrollment->program ?? 'N/A' }}</p>
                <p><strong>Status:</strong> 
                    <span style="padding: 0.25rem 0.75rem; border-radius: 0.25rem; font-size: 0.875rem; font-weight: bold;
                        background-color: {{ $enrollment->status === 'active' ? '#d4edda' : ($enrollment->status === 'completed' ? '#cfe2ff' : '#f8d7da') }};
                        color: {{ $enrollment->status === 'active' ? '#155724' : ($enrollment->status === 'completed' ? '#084298' : '#721c24') }};">
                        {{ ucfirst($enrollment->status) }}
                    </span>
                </p>
                <p><strong>Enrollment Date:</strong> {{ $enrollment->enrollment_date ? $enrollment->enrollment_date->format('M d, Y') : 'N/A' }}</p>
                <p><strong>Enrolled Since:</strong> {{ $enrollment->created_at->format('M d, Y') }}</p>
            </div>

            @if($enrollment->grade)
            <div>
                <h3 style="margin-top: 0;">Grade Information</h3>
                <div style="background: #f8f9fa; padding: 1rem; border-radius: 0.25rem;">
                    <p><strong>Latest Grade:</strong> {{ $enrollment->grade->grade ?? 'Pending' }}</p>
                    <p><strong>Score:</strong> {{ $enrollment->grade->score ?? 'Pending' }}/100</p>
                    <p><strong>Date Graded:</strong> {{ $enrollment->grade->graded_at ? $enrollment->grade->graded_at->format('M d, Y') : 'Not yet graded' }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
