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
                <select id="student_id" name="student_id" required onchange="updateStudentProgram()">
                    <option value="">-- Select Student --</option>
                    @foreach(\App\Models\Student::all() as $student)
                        <option value="{{ $student->id }}" data-program="{{ $student->program }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                            {{ $student->first_name }} {{ $student->last_name }} ({{ $student->student_id }}) - {{ $student->program }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="program">Student's Program <span style="color: #e74c3c;">*</span></label>
                <input type="text" id="program" name="program" readonly style="background: #f5f5f5;" value="{{ old('program') }}" required>
                <small style="color: #666; font-size: 0.85rem;">Auto-filled based on selected student</small>
            </div>

            <div class="form-group">
                <label for="course_id">Course <span style="color: #e74c3c;">*</span></label>
                <select id="course_id" name="course_id" required>
                    <option value="">-- Select Student First --</option>
                </select>
                <small style="color: #666; font-size: 0.85rem;">Only courses from the student's program will be shown</small>
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

<script>
    // Store all courses data
    const allCourses = {!! json_encode(\App\Models\Course::where('status', 'active')->get(['id', 'course_name', 'course_code', 'department'])) !!};
    
    function updateStudentProgram() {
        const studentSelect = document.getElementById('student_id');
        const programInput = document.getElementById('program');
        const courseSelect = document.getElementById('course_id');
        
        const selectedOption = studentSelect.options[studentSelect.selectedIndex];
        const studentProgram = selectedOption.getAttribute('data-program');
        
        if (studentProgram) {
            programInput.value = studentProgram;
            
            // Filter and populate courses for this program
            courseSelect.innerHTML = '<option value="">-- Select Course --</option>';
            
            const programCourses = allCourses.filter(course => course.department === studentProgram);
            
            if (programCourses.length === 0) {
                courseSelect.innerHTML = '<option value="">No courses available for this program</option>';
            } else {
                programCourses.forEach(course => {
                    const option = document.createElement('option');
                    option.value = course.id;
                    option.textContent = `${course.course_name} (${course.course_code})`;
                    courseSelect.appendChild(option);
                });
            }
        } else {
            programInput.value = '';
            courseSelect.innerHTML = '<option value="">-- Select Student First --</option>';
        }
    }
    
    // Trigger on page load if there's an old value
    window.addEventListener('DOMContentLoaded', function() {
        const studentSelect = document.getElementById('student_id');
        if (studentSelect.value) {
            updateStudentProgram();
        }
    });
</script>

@endsection
