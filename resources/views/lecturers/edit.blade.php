@extends('layouts.super-admin')

@section('content')
<div class="container" style="max-width: 900px; margin: 30px auto; padding: 0 20px;">
    <div style="margin-bottom: 1.5rem;">
        <h1 style="margin: 0 0 .25rem 0;">Edit Lecturer</h1>
        <p style="margin:0; color:#666;">Update lecturer details</p>
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

    <form method="POST" action="{{ route('lecturers.update', $lecturer) }}" style="background:#fff; padding: 2rem; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
        @csrf
        @method('PUT')

        <div style="display:grid; grid-template-columns: repeat(2, 1fr); gap: 1.25rem;">
            <div>
                <label style="display:block; margin-bottom:.5rem; font-weight:700;">First Name *</label>
                <input name="first_name" value="{{ old('first_name', $lecturer->first_name) }}" required style="width:100%; padding:.75rem; border:1px solid #ddd; border-radius:8px;" />
            </div>
            <div>
                <label style="display:block; margin-bottom:.5rem; font-weight:700;">Last Name *</label>
                <input name="last_name" value="{{ old('last_name', $lecturer->last_name) }}" required style="width:100%; padding:.75rem; border:1px solid #ddd; border-radius:8px;" />
            </div>
            <div>
                <label style="display:block; margin-bottom:.5rem; font-weight:700;">Email *</label>
                <input type="email" name="email" value="{{ old('email', $lecturer->email) }}" required style="width:100%; padding:.75rem; border:1px solid #ddd; border-radius:8px;" />
            </div>
            <div>
                <label style="display:block; margin-bottom:.5rem; font-weight:700;">Phone</label>
                <input name="phone" value="{{ old('phone', $lecturer->phone) }}" style="width:100%; padding:.75rem; border:1px solid #ddd; border-radius:8px;" />
            </div>
            <div>
                <label style="display:block; margin-bottom:.5rem; font-weight:700;">School</label>
                <select name="school_code" style="width:100%; padding:.75rem; border:1px solid #ddd; border-radius:8px; background:#fff;">
                    <option value="">Select School</option>
                    @foreach(($schools ?? []) as $code => $label)
                        <option value="{{ $code }}" {{ old('school_code', $lecturer->school_code) == $code ? 'selected' : '' }}>{{ $label }} ({{ $code }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label style="display:block; margin-bottom:.5rem; font-weight:700;">Status *</label>
                <select name="status" required style="width:100%; padding:.75rem; border:1px solid #ddd; border-radius:8px; background:#fff;">
                    <option value="active" {{ old('status', $lecturer->status) == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', $lecturer->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
        </div>

        <div style="display:flex; gap: 1rem; margin-top: 1.75rem;">
            <button type="submit" style="flex:1; padding:.9rem; background:#3498db; color:#fff; border:none; border-radius:8px; font-weight:800; cursor:pointer;">Update Lecturer</button>
            <a href="{{ route('lecturers.show', $lecturer) }}" style="flex:1; padding:.9rem; background:#95a5a6; color:#fff; text-decoration:none; border-radius:8px; font-weight:800; text-align:center;">Cancel</a>
        </div>
    </form>
</div>
@endsection
