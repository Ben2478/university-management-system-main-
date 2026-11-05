@extends(auth()->user()->role === 'super_admin' ? 'layouts.super-admin' : (auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.app'))

@section('content')
<link rel="stylesheet" href="{{ asset('css/academic-management.css') }}">
<div class="container academic-management academic-form-page">
    <div class="academic-header">
        <h1 style="margin: 0 0 0.5rem 0;">Add New Course</h1>
        <p style="margin: 0; color: #666;">Create a new course and assign it to a program</p>
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

    <form action="{{ route('courses.store') }}" method="POST" class="academic-surface">
        @csrf

        <div class="academic-grid">
            <!-- Course Code -->
            <div>
                <label for="course_code" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50;">Course Code <span style="color: #e74c3c;">*</span></label>
                <input type="text" id="course_code" name="course_code" required placeholder="e.g., CS101" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem;" value="{{ old('course_code') }}">
                <small style="color: #666; font-size: 0.875rem;">Unique identifier for the course</small>
            </div>

            <!-- Course Name -->
            <div>
                <label for="course_name" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50;">Course Name <span style="color: #e74c3c;">*</span></label>
                <input type="text" id="course_name" name="course_name" required placeholder="e.g., Introduction to Programming" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem;" value="{{ old('course_name') }}">
            </div>

            <!-- Credits -->
            <div>
                <label for="credits" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50;">Credits <span style="color: #e74c3c;">*</span></label>
                <input type="number" id="credits" name="credits" required min="1" max="6" placeholder="e.g., 3" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem;" value="{{ old('credits', 3) }}">
                <small style="color: #666; font-size: 0.875rem;">Number of credit hours (1-6)</small>
            </div>

            <!-- Term -->
            <div>
                <label for="term" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50;">Term <span style="color: #e74c3c;">*</span></label>
                <select id="term" name="term" required style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem; background: white;">
                    <option value="">Select Term</option>
                    @for($i = 1; $i <= 3; $i++)
                        <option value="{{ $i }}" {{ old('term') == $i ? 'selected' : '' }}>Term {{ $i }}</option>
                    @endfor
                </select>
            </div>

            <!-- Program/Department -->
            <div>
                <label for="department" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50;">Program <span style="color: #e74c3c;">*</span></label>
                <select id="department" name="department" required style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem; background: white;">
                    <option value="">Select Program</option>
                    @foreach($programs as $program)
                        <option value="{{ $program }}" {{ old('department') == $program ? 'selected' : '' }}>{{ $program }}</option>
                    @endforeach
                </select>
                <small style="color: #666; font-size: 0.875rem;">Students can only enroll in courses from their program</small>
            </div>

            <!-- Status -->
            <div>
                <label for="status" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50;">Status <span style="color: #e74c3c;">*</span></label>
                <select id="status" name="status" required style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem; background: white;">
                    <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <!-- Instructor -->
            <div>
                <label for="instructor" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50;">Instructor</label>
                <select id="instructor" name="instructor" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem; background: white;">
                    <option value="">Select an instructor</option>
                    @foreach(($lecturers ?? []) as $lecturer)
                        <option value="{{ $lecturer->full_name }}" {{ old('instructor') === $lecturer->full_name ? 'selected' : '' }}>
                            {{ $lecturer->full_name }} ({{ $lecturer->lecturer_code }})
                        </option>
                    @endforeach
                </select>
                @if(($lecturers ?? collect())->isEmpty())
                    <small style="color: #666;">Add an active lecturer before assigning an instructor.</small>
                @endif
            </div>

            <!-- Lecturers (Multi) -->
            <div class="academic-wide">
                <label for="lecturer_ids" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50;">Assign Lecturers</label>
                <select id="lecturer_ids" name="lecturer_ids[]" multiple style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem; background: white; min-height: 120px;">
                    @foreach(($lecturers ?? []) as $lecturer)
                        <option value="{{ $lecturer->id }}" {{ collect(old('lecturer_ids', []))->contains($lecturer->id) ? 'selected' : '' }}>
                            {{ $lecturer->full_name }} ({{ $lecturer->lecturer_code }})
                        </option>
                    @endforeach
                </select>
                <small style="color: #666; font-size: 0.875rem;">Hold Ctrl (Windows) / Cmd (Mac) to select multiple lecturers</small>
            </div>

            <!-- Tuition Fee -->
            <div>
                <label for="tuition_fee" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50;">Course Fee (K)</label>
                <input type="number" id="tuition_fee" name="tuition_fee" min="0" step="0.01" placeholder="0.00" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem;" value="{{ old('tuition_fee', 0) }}">
            </div>
        </div>

        <!-- Description (Full Width) -->
        <div style="margin-top: 1.5rem;">
            <label for="description" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50;">Description</label>
            <textarea id="description" name="description" rows="4" placeholder="Enter course description, objectives, and requirements..." style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem; resize: vertical;">{{ old('description') }}</textarea>
        </div>

        <!-- Buttons -->
        <div class="academic-actions academic-form-actions">
            <button type="submit" style="flex: 1; padding: 0.875rem; background: #27ae60; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 1rem; font-weight: 600;">
                ✓ Create Course
            </button>
            <a href="{{ route('courses.index') }}" class="academic-secondary" style="flex: 1; padding: 0.875rem; background: #95a5a6; color: white; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; text-align: center; font-size: 1rem; font-weight: 600; display: block;">
                × Cancel
            </a>
        </div>
    </form>
</div>
@endsection
