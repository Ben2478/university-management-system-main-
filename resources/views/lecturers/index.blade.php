@extends('layouts.super-admin')

@section('content')
<link rel="stylesheet" href="{{ asset('css/operations-management.css') }}?v={{ filemtime(public_path('css/operations-management.css')) }}">
<div class="container operations-management" style="padding: 2rem 0;">
    <div class="ops-header" style="display:flex; justify-content:space-between; align-items:center; gap: 1rem; margin-bottom: 1.5rem;">
        <div>
            <h1 style="margin:0 0 .25rem 0;">Lecturers</h1>
            <p style="margin:0; color:#666;">Manage lecturers and assign them to courses</p>
        </div>
        <a href="{{ route('lecturers.create') }}" class="btn" style="background:#27ae60; color:#fff; text-decoration:none; font-weight:600; border-radius:.35rem; padding:.75rem 1rem;">+ Add Lecturer</a>
    </div>

    @if(session('success'))
        <div style="padding: 1rem; margin-bottom: 1.5rem; background: #d4edda; border-left: 4px solid #27ae60; border-radius: 0.25rem;">
            <strong style="color:#155724;">✓ {{ session('success') }}</strong>
        </div>
    @endif

    <div class="ops-stats">
        <div class="card" style="border-left:4px solid #3498db; padding:1.25rem;">
            <div style="color:#666; font-size:.75rem; text-transform:uppercase; font-weight:700;">Total</div>
            <div style="color:#3498db; font-size:2rem; font-weight:800;">{{ $stats['total'] }}</div>
        </div>
        <div class="card" style="border-left:4px solid #27ae60; padding:1.25rem;">
            <div style="color:#666; font-size:.75rem; text-transform:uppercase; font-weight:700;">Active</div>
            <div style="color:#27ae60; font-size:2rem; font-weight:800;">{{ $stats['active'] }}</div>
        </div>
        <div class="card" style="border-left:4px solid #e74c3c; padding:1.25rem;">
            <div style="color:#666; font-size:.75rem; text-transform:uppercase; font-weight:700;">Inactive</div>
            <div style="color:#e74c3c; font-size:2rem; font-weight:800;">{{ $stats['inactive'] }}</div>
        </div>
    </div>

    <div class="card ops-table-scroll" role="region" aria-label="Lecturers" tabindex="0">
        <table style="width:100%; border-collapse:collapse;">
            <thead>
                <tr style="background:#34495e; color:#fff;">
                    <th style="padding:1rem; text-align:left;">Code</th>
                    <th style="padding:1rem; text-align:left;">Name</th>
                    <th style="padding:1rem; text-align:left;">School</th>
                    <th style="padding:1rem; text-align:center;">Courses</th>
                    <th style="padding:1rem; text-align:center;">Status</th>
                    <th style="padding:1rem; text-align:center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($lecturers as $lecturer)
                    <tr style="border-bottom:1px solid #ecf0f1;">
                        <td style="padding:1rem; font-weight:700; color:#2c3e50;">{{ $lecturer->lecturer_code }}</td>
                        <td style="padding:1rem;">
                            <div style="font-weight:600; color:#2c3e50;">{{ $lecturer->full_name }}</div>
                            <small style="color:#7f8c8d;">{{ $lecturer->email }}</small>
                        </td>
                        <td style="padding:1rem;">
                            <span style="padding:.25rem .6rem; background:#eef2ff; color:#3730a3; border-radius:.35rem; font-weight:700; font-size:.8rem;">
                                {{ $lecturer->school_code ? (($schools[$lecturer->school_code] ?? 'School') . ' (' . $lecturer->school_code . ')') : 'N/A' }}
                            </span>
                        </td>
                        <td style="padding:1rem; text-align:center;">
                            <span style="padding:.25rem .6rem; background:#fff3e0; color:#e65100; border-radius:.35rem; font-weight:700;">{{ $lecturer->courses_count }}</span>
                        </td>
                        <td style="padding:1rem; text-align:center;">
                            @if($lecturer->status === 'active')
                                <span style="padding:.25rem .6rem; background:#d4edda; color:#155724; border-radius:.35rem; font-weight:700; font-size:.8rem;">● Active</span>
                            @else
                                <span style="padding:.25rem .6rem; background:#f8d7da; color:#721c24; border-radius:.35rem; font-weight:700; font-size:.8rem;">● Inactive</span>
                            @endif
                        </td>
                        <td style="padding:1rem; text-align:center;">
                            <div class="action-buttons" style="justify-content:center;">
                                <a href="{{ route('lecturers.show', $lecturer) }}" class="btn btn-primary">View</a>
                                <a href="{{ route('lecturers.edit', $lecturer) }}" class="btn btn-warning">Edit</a>
                                <form action="{{ route('lecturers.destroy', $lecturer) }}" method="POST" onsubmit="return confirm('Delete this lecturer?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="padding:2rem; text-align:center; color:#666;">No lecturers found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($lecturers->hasPages())
            <div style="padding: 1.25rem; border-top:1px solid #ecf0f1;">
                {{ $lecturers->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
