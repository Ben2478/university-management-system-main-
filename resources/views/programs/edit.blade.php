@extends(auth()->user()->role === 'super_admin' ? 'layouts.super-admin' : (auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.app'))

@section('content')
<link rel="stylesheet" href="{{ asset('css/academic-management.css') }}">
<div class="container academic-management academic-form-page">
    <div class="academic-header">
        <h1 style="margin: 0 0 0.5rem 0;">Edit Program</h1>
        <p style="margin: 0; color: #666;">Update program information</p>
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

    <form action="{{ route('programs.update', $program) }}" method="POST" class="academic-surface">
        @csrf
        @method('PUT')

        <div style="margin-bottom: 1.5rem;">
            <label for="name" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50;">Program Name <span style="color: #e74c3c;">*</span></label>
            <input type="text" id="name" name="name" required style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem;" value="{{ old('name', $program->name) }}">
        </div>

        <div class="academic-grid" style="margin-bottom: 1.5rem;">
            <div>
                <label for="department" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50;">Department/Faculty</label>
                <input type="text" id="department" name="department" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem;" value="{{ old('department', $program->department) }}">
            </div>

            <div>
                <label for="duration_years" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50;">Duration (Years) <span style="color: #e74c3c;">*</span></label>
                <input type="number" id="duration_years" name="duration_years" required min="1" max="10" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem;" value="{{ old('duration_years', $program->duration_years) }}">
            </div>
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label for="status" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50;">Status <span style="color: #e74c3c;">*</span></label>
            <select id="status" name="status" required style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem; background: white;">
                <option value="active" {{ old('status', $program->status) == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ old('status', $program->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label for="description" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50;">Description</label>
            <textarea id="description" name="description" rows="4" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem; resize: vertical;">{{ old('description', $program->description) }}</textarea>
        </div>

        <!-- Buttons -->
        <div class="academic-actions academic-form-actions">
            <button type="submit" style="flex: 1; padding: 0.875rem; background: #3498db; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 1rem; font-weight: 600;">
                ✓ Update Program
            </button>
            <a href="{{ route('programs.show', $program) }}" class="academic-secondary" style="flex: 1; padding: 0.875rem; background: #95a5a6; color: white; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; text-align: center; font-size: 1rem; font-weight: 600; display: block;">
                × Cancel
            </a>
        </div>
    </form>
</div>
@endsection
