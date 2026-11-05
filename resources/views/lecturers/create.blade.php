@extends('layouts.super-admin')

@section('content')
<link rel="stylesheet" href="{{ asset('css/operations-management.css') }}?v={{ filemtime(public_path('css/operations-management.css')) }}">
<div class="container operations-management" style="max-width: 900px; margin: 30px auto; padding: 0 20px;">
    <div class="ops-header" style="margin-bottom: 1.5rem;">
        <h1 style="margin: 0 0 .25rem 0;">Add Lecturer</h1>
        <p style="margin:0; color:#666;">Create a lecturer account (login will be generated)</p>
    </div>

    @if($errors->any())
        <div style="padding: 1rem; margin-bottom: 1.5rem; background: #f8d7da; border-left: 4px solid #e74c3c; border-radius: 0.25rem;">
            <strong style="color: #721c24;">Please fix the following errors:</strong>
            <ul style="margin: .5rem 0 0 0; padding-left: 20px; color: #721c24;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('lecturers.store') }}" class="form-container">
        @csrf

        <div class="ops-grid">
            <div>
                <label style="display:block; margin-bottom:.5rem; font-weight:700;">First Name *</label>
                <input name="first_name" value="{{ old('first_name') }}" required style="width:100%; padding:.75rem; border:1px solid #ddd; border-radius:8px;" />
            </div>
            <div>
                <label style="display:block; margin-bottom:.5rem; font-weight:700;">Last Name *</label>
                <input name="last_name" value="{{ old('last_name') }}" required style="width:100%; padding:.75rem; border:1px solid #ddd; border-radius:8px;" />
            </div>
            <div>
                <label style="display:block; margin-bottom:.5rem; font-weight:700;">Email *</label>
                <input type="email" name="email" value="{{ old('email') }}" required style="width:100%; padding:.75rem; border:1px solid #ddd; border-radius:8px;" />
            </div>
            <div>
                <label style="display:block; margin-bottom:.5rem; font-weight:700;">Phone</label>
                <input name="phone" value="{{ old('phone') }}" style="width:100%; padding:.75rem; border:1px solid #ddd; border-radius:8px;" />
            </div>
            <div>
                <label style="display:block; margin-bottom:.5rem; font-weight:700;">School</label>
                <select name="school_code" style="width:100%; padding:.75rem; border:1px solid #ddd; border-radius:8px; background:#fff;">
                    <option value="">Select School</option>
                    @foreach(($schools ?? []) as $code => $label)
                        <option value="{{ $code }}" {{ old('school_code') == $code ? 'selected' : '' }}>{{ $label }} ({{ $code }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label style="display:block; margin-bottom:.5rem; font-weight:700;">Status *</label>
                <select name="status" required style="width:100%; padding:.75rem; border:1px solid #ddd; border-radius:8px; background:#fff;">
                    <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
        </div>

        <div class="ops-actions" style="margin-top: 1.75rem;">
            <button type="submit" class="btn btn-success">Create Lecturer</button>
            <a href="{{ route('lecturers.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
