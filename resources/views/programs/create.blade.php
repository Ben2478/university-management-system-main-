@extends(auth()->user()->role === 'super_admin' ? 'layouts.super-admin' : (auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.app'))

@section('content')
<div class="container" style="max-width: 700px; margin: 30px auto; padding: 0 20px;">
    <div style="margin-bottom: 2rem;">
        <h1 style="margin: 0 0 0.5rem 0;">Add New Program</h1>
        <p style="margin: 0; color: #666;">Create a new academic program and optionally add courses</p>
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

    <form action="{{ route('programs.store') }}" method="POST" style="background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
        @csrf

        <div style="margin-bottom: 1.5rem;">
            <label for="name" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50;">Program Name <span style="color: #e74c3c;">*</span></label>
            <input type="text" id="name" name="name" required placeholder="e.g., Computer Science" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem;" value="{{ old('name') }}">
            <small style="color: #666; font-size: 0.875rem;">This will be used to match courses and students</small>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
            <div>
                <label for="department" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50;">Department/Faculty</label>
                <input type="text" id="department" name="department" placeholder="e.g., Faculty of Science" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem;" value="{{ old('department') }}">
            </div>

            <div>
                <label for="duration_years" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50;">Duration (Years) <span style="color: #e74c3c;">*</span></label>
                <input type="number" id="duration_years" name="duration_years" required min="1" max="10" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem;" value="{{ old('duration_years', 4) }}">
            </div>
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label for="status" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50;">Status <span style="color: #e74c3c;">*</span></label>
            <select id="status" name="status" required style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem; background: white;">
                <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label for="description" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50;">Description</label>
            <textarea id="description" name="description" rows="4" placeholder="Enter program description, requirements, career prospects..." style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem; resize: vertical;">{{ old('description') }}</textarea>
        </div>

        <!-- Option to add courses after creation -->
        <div style="padding: 1rem; background: #e3f2fd; border-left: 4px solid #1976d2; border-radius: 0.25rem; margin-bottom: 1.5rem;">
            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                <input type="checkbox" name="add_courses" value="1" checked style="width: 18px; height: 18px; cursor: pointer;">
                <span style="font-weight: 600; color: #1565c0;">Add courses for this program after creating it</span>
            </label>
            <small style="color: #0d47a1; display: block; margin-top: 0.5rem;">Recommended: You'll be taken to a page to add multiple courses at once</small>
        </div>

        <!-- Buttons -->
        <div style="display: flex; gap: 1rem;">
            <button type="submit" style="flex: 1; padding: 0.875rem; background: #27ae60; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 1rem; font-weight: 600;">
                ✓ Create Program
            </button>
            <a href="{{ route('programs.index') }}" style="flex: 1; padding: 0.875rem; background: #95a5a6; color: white; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; text-align: center; font-size: 1rem; font-weight: 600; display: block;">
                × Cancel
            </a>
        </div>
    </form>
</div>
@endsection
