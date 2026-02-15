@extends('layouts.app')

@section('content')
<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <a href="{{ route('enrollments.index') }}" style="color: #3498db; text-decoration: none;">← Back to Enrollments</a>
            <h1 style="margin-top: 0.5rem;">New Enrollment</h1>
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

        <form action="{{ route('enrollments.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="student_id">Student <span style="color: #e74c3c;">*</span></label>
                <select id="student_id" name="student_id" required>
                    <option value="">-- Select Student --</option>
                    @foreach(\App\Models\Student::all() as $student)
                        <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                            {{ $student->first_name }} {{ $student->last_name }} ({{ $student->student_id }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="course_id">Course <span style="color: #e74c3c;">*</span></label>
                <select id="course_id" name="course_id" required>
                    <option value="">-- Select Course --</option>
                    @foreach(\App\Models\Course::all() as $course)
                        <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                            {{ $course->course_name }} ({{ $course->course_code }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="program">Program of Study <span style="color: #e74c3c;">*</span></label>
                <select id="program" name="program" required>
                    <option value="">-- Select Program --</option>
                    <option value="Computer Science" {{ old('program') == 'Computer Science' ? 'selected' : '' }}>Computer Science</option>
                    <option value="Information Technology" {{ old('program') == 'Information Technology' ? 'selected' : '' }}>Information Technology</option>
                    <option value="Software Engineering" {{ old('program') == 'Software Engineering' ? 'selected' : '' }}>Software Engineering</option>
                    <option value="Data Science" {{ old('program') == 'Data Science' ? 'selected' : '' }}>Data Science</option>
                    <option value="Cybersecurity" {{ old('program') == 'Cybersecurity' ? 'selected' : '' }}>Cybersecurity</option>
                    <option value="Business Administration" {{ old('program') == 'Business Administration' ? 'selected' : '' }}>Business Administration</option>
                    <option value="Engineering" {{ old('program') == 'Engineering' ? 'selected' : '' }}>Engineering</option>
                    <option value="Medicine" {{ old('program') == 'Medicine' ? 'selected' : '' }}>Medicine</option>
                </select>
            </div>

            <div class="form-group">
                <label for="status">Status <span style="color: #e74c3c;">*</span></label>
                <select id="status" name="status" required>
                    <option value="">-- Select Status --</option>
                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="dropped" {{ old('status') == 'dropped' ? 'selected' : '' }}>Dropped</option>
                    <option value="suspended" {{ old('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                </select>
            </div>

            <div class="form-group">
                <label for="enrollment_date">Enrollment Date</label>
                <input type="date" id="enrollment_date" name="enrollment_date" value="{{ old('enrollment_date') }}">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <button type="submit" class="btn btn-success">Create Enrollment</button>
                <a href="{{ route('enrollments.index') }}" class="btn btn-secondary" style="text-align: center;">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
