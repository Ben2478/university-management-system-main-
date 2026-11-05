@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/operations-management.css') }}?v={{ filemtime(public_path('css/operations-management.css')) }}">
<div class="container operations-management">
    <div class="ops-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
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
                <label for="student_ids">Students <span style="color: #e74c3c;">*</span></label>
                <select id="student_ids" name="student_ids[]" multiple required onchange="updateCourseOptions()" style="min-height: 180px;">
                    @foreach($students as $student)
                        <option value="{{ $student->id }}" data-program="{{ $student->program }}" {{ in_array($student->id, old('student_ids', [])) ? 'selected' : '' }}>
                            {{ $student->first_name }} {{ $student->last_name }} ({{ $student->student_id }}) - {{ $student->program }}
                        </option>
                    @endforeach
                </select>
                <small style="color: #666; font-size: 0.85rem;">Select one or more students. Hold Ctrl (Windows) / Cmd (Mac) for multiple.</small>
            </div>

            <div class="form-group">
                <label for="course_ids">Courses <span style="color: #e74c3c;">*</span></label>
                <select id="course_ids" name="course_ids[]" multiple required style="min-height: 180px;">
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" data-department="{{ $course->department }}" {{ in_array($course->id, old('course_ids', [])) ? 'selected' : '' }}>
                            {{ $course->course_name }} ({{ $course->course_code }}) - {{ $course->department }} - Term {{ $course->term }}
                        </option>
                    @endforeach
                </select>
                <small style="color: #666; font-size: 0.85rem;">Choose courses belonging to the selected students' programme(s). Hold Ctrl (Windows) / Cmd (Mac) for multiple.</small>
            </div>

            <div class="form-group">
                <label for="status">Status <span style="color: #e74c3c;">*</span></label>
                <select id="status" name="status" required>
                    <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="dropped" {{ old('status') == 'dropped' ? 'selected' : '' }}>Dropped</option>
                    <option value="suspended" {{ old('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                </select>
            </div>

            <div class="form-group">
                <label for="enrollment_date">Enrollment Date</label>
                <input type="date" id="enrollment_date" name="enrollment_date" value="{{ old('enrollment_date') }}">
            </div>

            <div class="ops-actions" style="gap: 1rem;">
                <button type="submit" class="btn btn-success">Create Enrollment</button>
                <a href="{{ route('enrollments.index') }}" class="btn btn-secondary" style="text-align: center;">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
    function updateCourseOptions() {
        const studentSelect = document.getElementById('student_ids');
        const courseSelect = document.getElementById('course_ids');
        const selectedPrograms = new Set(
            Array.from(studentSelect.selectedOptions).map(option => option.dataset.program)
        );

        Array.from(courseSelect.options).forEach(option => {
            const matchesProgram = selectedPrograms.size === 0 || selectedPrograms.has(option.dataset.department);
            option.hidden = !matchesProgram;
            option.disabled = !matchesProgram;
            if (!matchesProgram) option.selected = false;
        });
    }

    document.addEventListener('DOMContentLoaded', updateCourseOptions);
</script>

@endsection
