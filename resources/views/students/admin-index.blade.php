@extends('layouts.super-admin')

@section('content')
<link rel="stylesheet" href="{{ asset('css/student-management.css') }}">
<div class="student-management">
    <div class="student-page-heading">
        <h1>Student Management</h1>
        <a href="{{ route('students.create') }}" class="btn btn-primary">+ Add New Student</a>
    </div>

    <!-- Stats Grid -->
    <div class="student-stats">
        <div class="student-metric">
            <p>Total Students</p>
            <h2>{{ $stats['total'] }}</h2>
        </div>

        <div class="student-metric">
            <p>Active Students</p>
            <h2>{{ $stats['active'] }}</h2>
        </div>

        <div class="student-metric">
            <p>Inactive</p>
            <h2>{{ $stats['inactive'] }}</h2>
        </div>

        <div class="student-metric">
            <p>Graduated</p>
            <h2>{{ $stats['graduated'] }}</h2>
        </div>
    </div>

    <!-- Filters -->
    <div class="student-filter-section">
        <form method="GET" action="{{ route('students.index') }}">
            <div class="filter-grid">
                <div>
                    <label for="student-search">Search</label>
                          <input id="student-search" type="text" name="search" placeholder="Search by name, ID, or email"
                           value="{{ request('search') }}">
                </div>

                <div>
                    <label for="student-program">Program</label>
                    <select id="student-program" name="program">
                        <option value="">All Programs</option>
                        @foreach($programs as $program)
                            <option value="{{ $program }}" {{ request('program') == $program ? 'selected' : '' }}>
                                {{ $program }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="student-status">Status</label>
                    <select id="student-status" name="status">
                        <option value="">All Statuses</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="graduated" {{ request('status') == 'graduated' ? 'selected' : '' }}>Graduated</option>
                        <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                    </select>
                </div>

                <div class="student-actions" style="align-self: end;">
                    <button type="submit" class="btn btn-primary">Search</button>
                    <a href="{{ route('students.index') }}" class="btn btn-secondary">Clear</a>
                </div>
            </div>
        </form>
    </div>

    <!-- Students Table -->
    <div class="student-section">
        <div class="student-table-scroll" role="region" aria-label="Student directory" tabindex="0">
            <table>
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Program</th>
                        <th>Year/Sem</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 0.75rem;">
                            <strong>{{ $student->student_id }}</strong>
                        </td>
                        <td style="padding: 0.75rem;">
                            <strong>{{ $student->first_name }} {{ $student->last_name }}</strong>
                        </td>
                        <td style="padding: 0.75rem;">{{ $student->email }}</td>
                        <td style="padding: 0.75rem;">{{ $student->program }}</td>
                        <td style="padding: 0.75rem;">Year {{ $student->year }}, Sem {{ $student->semester }}</td>
                        <td style="padding: 0.75rem;">
                            <span class="status-badge {{ $student->status }}">
                                {{ ucfirst($student->status) }}
                            </span>
                        </td>
                        <td style="padding: 0.75rem;">
                            <div class="student-row-actions">
                                <a href="{{ route('students.show', $student->id) }}" class="btn btn-sm btn-info">View</a>
                                <a href="{{ route('students.edit', $student->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('students.destroy', $student->id) }}" method="POST" style="display: inline;" 
                                      onsubmit="return confirm('Are you sure you want to delete this student?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 2rem; color: #666;">
                            No students found. <a href="{{ route('students.create') }}" style="color: #3498db;">Add your first student</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($students->hasPages())
        <div class="student-pagination">
            {{ $students->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
