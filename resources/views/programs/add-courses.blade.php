@extends(auth()->user()->role === 'super_admin' ? 'layouts.super-admin' : (auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.app'))

@section('content')
<div class="container" style="padding: 2rem;">
    <div style="margin-bottom: 2rem;">
        <h1 style="margin: 0 0 0.5rem 0;">Add Courses for {{ $program->name }}</h1>
        <p style="margin: 0; color: #666;">Add multiple courses to this program at once</p>
    </div>

    @if($errors->any())
        <div style="padding: 1rem; margin-bottom: 2rem; background: #f8d7da; border-left: 4px solid #e74c3c; border-radius: 0.25rem;">
            <strong style="color: #721c24;">Please fix the following errors:</strong>
            <ul style="margin: 0.5rem 0 0 0; padding-left: 20px; color: #721c24;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('programs.store-courses', $program) }}" method="POST" id="coursesForm" style="background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
        @csrf

        <div id="coursesContainer">
            <!-- Course Row Template -->
            <div class="course-row" style="padding: 1.5rem; border: 2px solid #e3f2fd; border-radius: 0.5rem; margin-bottom: 1.5rem; position: relative;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                    <h3 style="margin: 0; color: #1976d2;">Course #<span class="course-number">1</span></h3>
                    <button type="button" class="remove-course-btn" onclick="removeCourse(this)" style="display: none; padding: 0.5rem 1rem; background: #e74c3c; color: white; border: none; border-radius: 0.25rem; cursor: pointer; font-weight: 600;">
                        × Remove
                    </button>
                </div>

                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 1rem;">
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50;">Course Code <span style="color: #e74c3c;">*</span></label>
                        <input type="text" name="courses[0][course_code]" required placeholder="e.g., CS101" style="width: 100%; padding: 0.625rem; border: 1px solid #ddd; border-radius: 0.25rem;" value="{{ old('courses.0.course_code') }}">
                    </div>

                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50;">Credits <span style="color: #e74c3c;">*</span></label>
                        <input type="number" name="courses[0][credits]" required min="1" max="6" placeholder="3" style="width: 100%; padding: 0.625rem; border: 1px solid #ddd; border-radius: 0.25rem;" value="{{ old('courses.0.credits', 3) }}">
                    </div>

                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50;">Semester <span style="color: #e74c3c;">*</span></label>
                        <select name="courses[0][semester]" required style="width: 100%; padding: 0.625rem; border: 1px solid #ddd; border-radius: 0.25rem; background: white;">
                            @for($i = 1; $i <= 8; $i++)
                                <option value="{{ $i }}" {{ old('courses.0.semester') == $i ? 'selected' : '' }}>Semester {{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50;">Course Name <span style="color: #e74c3c;">*</span></label>
                    <input type="text" name="courses[0][course_name]" required placeholder="e.g., Introduction to Programming" style="width: 100%; padding: 0.625rem; border: 1px solid #ddd; border-radius: 0.25rem;" value="{{ old('courses.0.course_name') }}">
                </div>

                <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50;">Instructor</label>
                        <input type="text" name="courses[0][instructor]" placeholder="e.g., Dr. John Smith" style="width: 100%; padding: 0.625rem; border: 1px solid #ddd; border-radius: 0.25rem;" value="{{ old('courses.0.instructor') }}">
                    </div>

                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50;">Course Fee (K)</label>
                        <input type="number" name="courses[0][tuition_fee]" min="0" step="0.01" placeholder="0.00" style="width: 100%; padding: 0.625rem; border: 1px solid #ddd; border-radius: 0.25rem;" value="{{ old('courses.0.tuition_fee', 0) }}">
                    </div>
                </div>

                <div>
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50;">Description</label>
                    <textarea name="courses[0][description]" rows="2" placeholder="Course description..." style="width: 100%; padding: 0.625rem; border: 1px solid #ddd; border-radius: 0.25rem; resize: vertical;">{{ old('courses.0.description') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Add More Button -->
        <button type="button" onclick="addCourseRow()" style="width: 100%; padding: 0.875rem; background: #3498db; color: white; border: 2px dashed #2980b9; border-radius: 0.5rem; cursor: pointer; font-size: 1rem; font-weight: 600; margin-bottom: 2rem;">
            + Add Another Course
        </button>

        <!-- Submit Buttons -->
        <div style="display: flex; gap: 1rem;">
            <button type="submit" style="flex: 1; padding: 1rem; background: #27ae60; color: white; border: none; border-radius: 0.5rem; cursor: pointer; font-size: 1rem; font-weight: 600;">
                ✓ Save All Courses
            </button>
            <a href="{{ route('programs.show', $program) }}" style="flex: 1; padding: 1rem; background: #95a5a6; color: white; border: none; border-radius: 0.5rem; cursor: pointer; text-decoration: none; text-align: center; font-size: 1rem; font-weight: 600; display: block;">
                Skip for Now
            </a>
        </div>
    </form>
</div>

<script>
let courseCount = 1;

function addCourseRow() {
    const container = document.getElementById('coursesContainer');
    const newRow = container.querySelector('.course-row').cloneNode(true);
    
    // Update course number
    newRow.querySelector('.course-number').textContent = courseCount + 1;
    
    // Update input names
    newRow.querySelectorAll('input, select, textarea').forEach(input => {
        const name = input.getAttribute('name');
        if (name) {
            input.setAttribute('name', name.replace('[0]', `[${courseCount}]`));
            input.value = '';
        }
    });
    
    // Show remove button
    newRow.querySelector('.remove-course-btn').style.display = 'block';
    
    container.appendChild(newRow);
    courseCount++;
    
    updateRemoveButtons();
}

function removeCourse(button) {
    const row = button.closest('.course-row');
    row.remove();
    updateCourseNumbers();
    updateRemoveButtons();
}

function updateCourseNumbers() {
    document.querySelectorAll('.course-number').forEach((el, index) => {
        el.textContent = index + 1;
    });
}

function updateRemoveButtons() {
    const rows = document.querySelectorAll('.course-row');
    rows.forEach((row, index) => {
        const removeBtn = row.querySelector('.remove-course-btn');
        if (rows.length > 1) {
            removeBtn.style.display = 'block';
        } else {
            removeBtn.style.display = 'none';
        }
    });
}
</script>
@endsection
