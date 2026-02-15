@extends('layouts.app')

@section('content')
<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <a href="{{ route('enrollments.show', $enrollment->id) }}" style="color: #3498db; text-decoration: none;">← Back to Enrollment</a>
            <h1 style="margin-top: 0.5rem;">Edit Enrollment</h1>
        </div>
    </div>

    <div class="card">
        @if($errors->any())
            <div class="alert alert-error">
                <ul style="margin: 0;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('enrollments.update', $enrollment->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="student_id">Student <span style="color: #e74c3c;">*</span></label>
                <select id="student_id" name="student_id" required disabled>
                    <option value="{{ $enrollment->student->id }}" selected>
                        {{ $enrollment->student->first_name }} {{ $enrollment->student->last_name }} ({{ $enrollment->student->student_id }})
                    </option>
                </select>
                <small style="color: #666;">Cannot change student after enrollment created</small>
            </div>

            <div class="form-group">
                <label for="course_id">Course <span style="color: #e74c3c;">*</span></label>
                <select id="course_id" name="course_id" required disabled>
                    <option value="{{ $enrollment->course->id }}" selected>
                        {{ $enrollment->course->name }} ({{ $enrollment->course->course_code }})
                    </option>
                </select>
                <small style="color: #666;">Cannot change course after enrollment created</small>
            </div>

            <div class="form-group">
                <label for="status">Status <span style="color: #e74c3c;">*</span></label>
                <select id="status" name="status" required>
                    <option value="active" {{ $enrollment->status === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="completed" {{ $enrollment->status === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="dropped" {{ $enrollment->status === 'dropped' ? 'selected' : '' }}>Dropped</option>
                    <option value="suspended" {{ $enrollment->status === 'suspended' ? 'selected' : '' }}>Suspended</option>
                </select>
            </div>

            <div class="form-group">
                <label for="enrollment_date">Enrollment Date</label>
                <input type="date" id="enrollment_date" name="enrollment_date" 
                    value="{{ $enrollment->enrollment_date ? $enrollment->enrollment_date->format('Y-m-d') : '' }}">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <button type="submit" class="btn btn-success">Update Enrollment</button>
                <a href="{{ route('enrollments.show', $enrollment->id) }}" class="btn btn-secondary" style="text-align: center;">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
