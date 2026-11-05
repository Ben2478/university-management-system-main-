@extends(auth()->user()->role === 'super_admin' ? 'layouts.super-admin' : (auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.app'))

@section('content')
<link rel="stylesheet" href="{{ asset('css/academic-management.css') }}">
<div class="container academic-management">
    <div class="academic-header">
        <div>
            <h1 style="margin: 0 0 0.5rem 0;">Course Management</h1>
            <p style="margin: 0; color: #666;">Manage and organize courses by programs</p>
        </div>
        <a href="{{ route('courses.create') }}" class="btn academic-primary" style="background: #27ae60; color: white; padding: 0.75rem 1.5rem; border-radius: 0.25rem; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; font-weight: 600;">
            + Add New Course
        </a>
    </div>

    @if(session('success'))
    <div style="padding: 1rem; margin-bottom: 2rem; background: #d4edda; border-left: 4px solid #27ae60; border-radius: 0.25rem;">
        <span style="color: #155724; font-weight: 600;">✓ {{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div style="padding: 1rem; margin-bottom: 2rem; background: #f8d7da; border-left: 4px solid #dc3545; border-radius: 0.25rem;">
        <span style="color: #721c24; font-weight: 600;">{{ session('error') }}</span>
    </div>
    @endif

    <!-- Stats Cards -->
    <div class="academic-stats">
        <div class="card" style="border-left: 4px solid #3498db; padding: 1.25rem;">
            <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase; font-weight: 600;">Total Courses</p>
            <h2 style="margin: 0.5rem 0 0 0; color: #3498db; font-size: 2rem;">{{ $stats['total'] }}</h2>
        </div>
        <div class="card" style="border-left: 4px solid #27ae60; padding: 1.25rem;">
            <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase; font-weight: 600;">Active Courses</p>
            <h2 style="margin: 0.5rem 0 0 0; color: #27ae60; font-size: 2rem;">{{ $stats['active'] }}</h2>
        </div>
        <div class="card" style="border-left: 4px solid #9b59b6; padding: 1.25rem;">
            <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase; font-weight: 600;">Programs</p>
            <h2 style="margin: 0.5rem 0 0 0; color: #9b59b6; font-size: 2rem;">{{ $stats['departments'] }}</h2>
        </div>
        <div class="card" style="border-left: 4px solid #e67e22; padding: 1.25rem;">
            <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase; font-weight: 600;">Total Enrollments</p>
            <h2 style="margin: 0.5rem 0 0 0; color: #e67e22; font-size: 2rem;">{{ $stats['enrolled'] }}</h2>
        </div>
    </div>

    <!-- Filters -->
    <div class="academic-surface">
        <form method="GET" action="{{ route('courses.index') }}" class="academic-filters">
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; font-size: 0.875rem; color: #2c3e50;">Search</label>
                <input type="text" name="search" placeholder="Search by course name or code..." value="{{ request('search') }}" style="width: 100%; padding: 0.625rem; border: 1px solid #ddd; border-radius: 0.25rem;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; font-size: 0.875rem; color: #2c3e50;">Program</label>
                <select name="department" style="width: 100%; padding: 0.625rem; border: 1px solid #ddd; border-radius: 0.25rem; background: white;">
                    <option value="">All Programs</option>
                    @foreach(\App\Models\Course::distinct('department')->whereNotNull('department')->pluck('department') as $dept)
                        <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; font-size: 0.875rem; color: #2c3e50;">Term</label>
                <select name="term" style="width: 100%; padding: 0.625rem; border: 1px solid #ddd; border-radius: 0.25rem; background: white;">
                    <option value="">All Terms</option>
                    @for($term = 1; $term <= 3; $term++)
                        <option value="{{ $term }}" {{ request('term') == $term ? 'selected' : '' }}>Term {{ $term }}</option>
                    @endfor
                </select>
            </div>
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; font-size: 0.875rem; color: #2c3e50;">Status</label>
                <select name="status" style="width: 100%; padding: 0.625rem; border: 1px solid #ddd; border-radius: 0.25rem; background: white;">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="academic-actions">
                <button type="submit" style="padding: 0.625rem 1.25rem; background: #3498db; color: white; border: none; border-radius: 0.25rem; cursor: pointer; font-weight: 600;">
                    🔍 Filter
                </button>
                @if(request()->hasAny(['search', 'department', 'term', 'status']))
                    <a href="{{ route('courses.index') }}" class="academic-secondary" style="padding: 0.625rem 1.25rem; background: #95a5a6; color: white; border-radius: 0.25rem; text-decoration: none; font-weight: 600;">
                        Clear
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Courses Table -->
    @if($courses->count() > 0)
    <div class="academic-surface">
        <div class="academic-table-scroll" role="region" aria-label="Courses" tabindex="0">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #34495e; color: white;">
                    <th style="padding: 1rem; text-align: left; font-weight: 600;">Course Code</th>
                    <th style="padding: 1rem; text-align: left; font-weight: 600;">Course Name</th>
                    <th style="padding: 1rem; text-align: left; font-weight: 600;">Program</th>
                    <th style="padding: 1rem; text-align: center; font-weight: 600;">Credits</th>
                    <th style="padding: 1rem; text-align: center; font-weight: 600;">Term</th>
                    <th style="padding: 1rem; text-align: center; font-weight: 600;">Enrollments</th>
                    <th style="padding: 1rem; text-align: center; font-weight: 600;">Status</th>
                    <th style="padding: 1rem; text-align: center; font-weight: 600;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($courses as $course)
                <tr style="border-bottom: 1px solid #ecf0f1;">
                    <td style="padding: 1rem;">
                        <strong style="color: #2c3e50;">{{ $course->course_code }}</strong>
                    </td>
                    <td style="padding: 1rem;">
                        <div style="font-weight: 500; color: #2c3e50;">{{ $course->course_name }}</div>
                        @php
                            $lecturerNames = $course->lecturers?->pluck('full_name')->filter()->values() ?? collect();
                        @endphp
                        @if($lecturerNames->count() > 0)
                            <small style="color: #7f8c8d;">👨‍🏫 {{ $lecturerNames->take(2)->join(', ') }}@if($lecturerNames->count() > 2) +{{ $lecturerNames->count() - 2 }} more @endif</small>
                        @elseif($course->instructor)
                            <small style="color: #7f8c8d;">👤 {{ $course->instructor }}</small>
                        @endif
                    </td>
                    <td style="padding: 1rem;">
                        <span style="padding: 0.25rem 0.75rem; background: #e3f2fd; color: #1976d2; border-radius: 0.25rem; font-size: 0.75rem; font-weight: 600;">
                            {{ $course->department ?? 'N/A' }}
                        </span>
                    </td>
                    <td style="padding: 1rem; text-align: center;">
                        <span style="font-weight: 600; color: #34495e;">{{ $course->credits ?? 'N/A' }}</span>
                    </td>
                    <td style="padding: 1rem; text-align: center;">
                        <span style="font-weight: 600; color: #34495e;">{{ $course->term ? 'Term ' . $course->term : 'N/A' }}</span>
                    </td>
                    <td style="padding: 1rem; text-align: center;">
                        <span style="padding: 0.25rem 0.75rem; background: #fff3e0; color: #e65100; border-radius: 0.25rem; font-weight: 600;">
                            {{ $course->enrollments_count ?? 0 }}
                        </span>
                    </td>
                    <td style="padding: 1rem; text-align: center;">
                        @if($course->status === 'active')
                            <span style="padding: 0.25rem 0.75rem; background: #d4edda; color: #155724; border-radius: 0.25rem; font-size: 0.75rem; font-weight: 600;">
                                ● Active
                            </span>
                        @else
                            <span style="padding: 0.25rem 0.75rem; background: #f8d7da; color: #721c24; border-radius: 0.25rem; font-size: 0.75rem; font-weight: 600;">
                                ● Inactive
                            </span>
                        @endif
                    </td>
                    <td style="padding: 1rem; text-align: center;">
                        <div class="academic-actions" style="justify-content: center;">
                            <a href="{{ route('courses.show', $course) }}" style="padding: 0.375rem 0.75rem; background: #3498db; color: white; border-radius: 0.25rem; text-decoration: none; font-size: 0.75rem; font-weight: 600;">
                                View
                            </a>
                            <a href="{{ route('courses.edit', $course) }}" style="padding: 0.375rem 0.75rem; background: #f39c12; color: white; border-radius: 0.25rem; text-decoration: none; font-size: 0.75rem; font-weight: 600;">
                                Edit
                            </a>
                            <form action="{{ route('courses.destroy', $course) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this course?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="academic-danger" style="padding: 0.375rem 0.75rem; background: #e74c3c; color: white; border: none; border-radius: 0.25rem; cursor: pointer; font-size: 0.75rem; font-weight: 600;">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        </div>

        <!-- Pagination -->
        @if($courses->hasPages())
        <div class="academic-pagination">
            {{ $courses->links() }}
        </div>
        @endif
    </div>
    @else
    <div class="academic-surface academic-empty">
        <p style="color: #999; font-size: 1.125rem; margin-bottom: 1rem;">📚 No courses found</p>
        <p style="color: #666; font-size: 0.875rem; margin-bottom: 1.5rem;">
            @if(request()->hasAny(['search', 'department', 'term', 'status']))
                Try adjusting your filters or search terms.
            @else
                Get started by adding your first course.
            @endif
        </p>
        @if(request()->hasAny(['search', 'department', 'term', 'status']))
            <a href="{{ route('courses.index') }}" style="padding: 0.75rem 1.5rem; background: #95a5a6; color: white; border-radius: 0.25rem; text-decoration: none; font-weight: 600;">
                Clear Filters
            </a>
        @else
            <a href="{{ route('courses.create') }}" style="padding: 0.75rem 1.5rem; background: #27ae60; color: white; border-radius: 0.25rem; text-decoration: none; font-weight: 600;">
                + Add Your First Course
            </a>
        @endif
    </div>
    @endif
</div>
@endsection
