@extends(auth()->user()->role === 'super_admin' ? 'layouts.super-admin' : (auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.app'))

@section('content')
<div class="container" style="max-width: 800px; margin: 30px auto; padding: 0 20px;">
    <div style="margin-bottom: 2rem;">
        <h1 style="margin: 0 0 0.5rem 0;">Edit Course</h1>
        <p style="margin: 0; color: #666;">Update course information</p>
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

    <form action="{{ route('courses.update', $course) }}" method="POST" style="background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
        @csrf
        @method('PUT')

        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem;">
            <!-- Course Code (Read-only) -->
            <div>
                <label for="course_code" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50;">Course Code</label>
                <input type="text" id="course_code" value="{{ $course->course_code }}" disabled style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem; background: #f8f9fa; color: #666;">
                <small style="color: #666; font-size: 0.875rem;">Course code cannot be changed</small>
            </div>

            <!-- Course Name -->
            <div>
                <label for="course_name" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50;">Course Name <span style="color: #e74c3c;">*</span></label>
                <input type="text" id="course_name" name="course_name" required style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem;" value="{{ old('course_name', $course->course_name) }}">
            </div>

            <!-- Credits -->
            <div>
                <label for="credits" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50;">Credits <span style="color: #e74c3c;">*</span></label>
                <input type="number" id="credits" name="credits" required min="1" max="6" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem;" value="{{ old('credits', $course->credits) }}">
                <small style="color: #666; font-size: 0.875rem;">Number of credit hours (1-6)</small>
            </div>

            <!-- Semester -->
            <div>
                <label for="semester" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50;">Semester <span style="color: #e74c3c;">*</span></label>
                <select id="semester" name="semester" required style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem; background: white;">
                    @for($i = 1; $i <= 8; $i++)
                        <option value="{{ $i }}" {{ old('semester', $course->semester) == $i ? 'selected' : '' }}>Semester {{ $i }}</option>
                    @endfor
                </select>
            </div>

            <!-- Program/Department -->
            <div>
                <label for="department" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50;">Program <span style="color: #e74c3c;">*</span></label>
                <select id="department" name="department" required style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem; background: white;">
                    <option value="">Select Program</option>
                    @foreach($programs as $program)
                        <option value="{{ $program }}" {{ old('department', $course->department) == $program ? 'selected' : '' }}>{{ $program }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Status -->
            <div>
                <label for="status" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50;">Status <span style="color: #e74c3c;">*</span></label>
                <select id="status" name="status" required style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem; background: white;">
                    <option value="active" {{ old('status', $course->status) == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', $course->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <!-- Instructor -->
            <div>
                <label for="instructor" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50;">Instructor</label>
                <input type="text" id="instructor" name="instructor" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem;" value="{{ old('instructor', $course->instructor) }}">
            </div>

            <!-- Lecturers (Multi) -->
            <div style="grid-column: span 2;">
                <label for="lecturer_ids" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50;">Assigned Lecturers</label>
                @php
                    $selectedLecturerIds = collect(old('lecturer_ids', $course->lecturers->pluck('id')->toArray()));
                @endphp
                <select id="lecturer_ids" name="lecturer_ids[]" multiple style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem; background: white; min-height: 120px;">
                    @foreach(($lecturers ?? []) as $lecturer)
                        <option value="{{ $lecturer->id }}" {{ $selectedLecturerIds->contains($lecturer->id) ? 'selected' : '' }}>
                            {{ $lecturer->full_name }} ({{ $lecturer->lecturer_code }})
                        </option>
                    @endforeach
                </select>
                <small style="color: #666; font-size: 0.875rem;">Hold Ctrl (Windows) / Cmd (Mac) to select multiple lecturers</small>
            </div>

            <!-- Tuition Fee -->
            <div>
                <label for="tuition_fee" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50;">Course Fee (K)</label>
                <input type="number" id="tuition_fee" name="tuition_fee" min="0" step="0.01" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem;" value="{{ old('tuition_fee', $course->tuition_fee) }}">
            </div>
        </div>

        <!-- Description (Full Width) -->
        <div style="margin-top: 1.5rem;">
            <label for="description" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50;">Description</label>
            <textarea id="description" name="description" rows="4" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem; resize: vertical;">{{ old('description', $course->description) }}</textarea>
        </div>

        <!-- Buttons -->
        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            <button type="submit" style="flex: 1; padding: 0.875rem; background: #3498db; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 1rem; font-weight: 600;">
                ✓ Update Course
            </button>
            <a href="{{ route('courses.show', $course) }}" style="flex: 1; padding: 0.875rem; background: #95a5a6; color: white; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; text-align: center; font-size: 1rem; font-weight: 600; display: block;">
                × Cancel
            </a>
        </div>
    </form>
</div>
@endsection
