@extends(auth()->user()->role === 'super_admin' ? 'layouts.super-admin' : (auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.app'))

@section('content')
<div class="container" style="padding: 2rem 0;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 style="margin: 0 0 0.5rem 0;">Program Management</h1>
            <p style="margin: 0; color: #666;">Manage academic programs and their courses</p>
        </div>
        <a href="{{ route('programs.create') }}" class="btn" style="background: #27ae60; color: white; padding: 0.75rem 1.5rem; border-radius: 0.25rem; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; font-weight: 600;">
            + Add New Program
        </a>
    </div>

    @if(session('success'))
    <div style="padding: 1rem; margin-bottom: 2rem; background: #d4edda; border-left: 4px solid #27ae60; border-radius: 0.25rem;">
        <span style="color: #155724; font-weight: 600;">✓ {{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div style="padding: 1rem; margin-bottom: 2rem; background: #f8d7da; border-left: 4px solid #e74c3c; border-radius: 0.25rem;">
        <span style="color: #721c24; font-weight: 600;">✗ {{ session('error') }}</span>
    </div>
    @endif

    <!-- Stats Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
        <div class="card" style="border-left: 4px solid #3498db; padding: 1.25rem;">
            <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase; font-weight: 600;">Total Programs</p>
            <h2 style="margin: 0.5rem 0 0 0; color: #3498db; font-size: 2rem;">{{ $stats['total'] }}</h2>
        </div>
        <div class="card" style="border-left: 4px solid #27ae60; padding: 1.25rem;">
            <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase; font-weight: 600;">Active Programs</p>
            <h2 style="margin: 0.5rem 0 0 0; color: #27ae60; font-size: 2rem;">{{ $stats['active'] }}</h2>
        </div>
        <div class="card" style="border-left: 4px solid #9b59b6; padding: 1.25rem;">
            <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase; font-weight: 600;">Total Courses</p>
            <h2 style="margin: 0.5rem 0 0 0; color: #9b59b6; font-size: 2rem;">{{ $stats['total_courses'] }}</h2>
        </div>
        <div class="card" style="border-left: 4px solid #e67e22; padding: 1.25rem;">
            <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase; font-weight: 600;">Total Students</p>
            <h2 style="margin: 0.5rem 0 0 0; color: #e67e22; font-size: 2rem;">{{ $stats['total_students'] }}</h2>
        </div>
    </div>

    <!-- Programs List -->
    @if($programs->count() > 0)
    <div style="display: grid; gap: 1.5rem;">
        @foreach($programs as $program)
        <div class="card" style="border-left: 4px solid {{ $program->status === 'active' ? '#27ae60' : '#e74c3c' }};">
            <div style="display: flex; justify-content: space-between; align-items: start; gap: 2rem;">
                <!-- Program Info -->
                <div style="flex: 1;">
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 0.75rem;">
                        <h2 style="margin: 0; color: #2c3e50;">{{ $program->name }}</h2>
                        @if($program->status === 'active')
                            <span style="padding: 0.25rem 0.75rem; background: #d4edda; color: #155724; border-radius: 0.25rem; font-size: 0.75rem; font-weight: 600;">
                                ● Active
                            </span>
                        @else
                            <span style="padding: 0.25rem 0.75rem; background: #f8d7da; color: #721c24; border-radius: 0.25rem; font-size: 0.75rem; font-weight: 600;">
                                ● Inactive
                            </span>
                        @endif
                    </div>

                    @if($program->department)
                        <p style="margin: 0 0 0.5rem 0; color: #7f8c8d; font-size: 0.875rem;">🏛️ {{ $program->department }}</p>
                    @endif

                    @if($program->description)
                        <p style="margin: 0 0 1rem 0; color: #666; line-height: 1.6;">{{ Str::limit($program->description, 150) }}</p>
                    @endif

                    <!-- Stats -->
                    <div style="display: flex; gap: 2rem; flex-wrap: wrap; margin-top: 1rem;">
                        <div>
                            <span style="color: #999; font-size: 0.875rem;">Duration:</span>
                            <strong style="color: #2c3e50; margin-left: 0.5rem;">{{ $program->duration_years }} Years</strong>
                        </div>
                        <div>
                            <span style="color: #999; font-size: 0.875rem;">Courses:</span>
                            <strong style="color: #9b59b6; margin-left: 0.5rem;">{{ $program->courses_count }}</strong>
                        </div>
                        <div>
                            <span style="color: #999; font-size: 0.875rem;">Students:</span>
                            <strong style="color: #e67e22; margin-left: 0.5rem;">{{ $program->students_count }}</strong>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    <a href="{{ route('programs.show', $program) }}" style="padding: 0.5rem 1rem; background: #3498db; color: white; border-radius: 0.25rem; text-decoration: none; text-align: center; font-size: 0.875rem; font-weight: 600; white-space: nowrap;">
                        👁️ View Details
                    </a>
                    <a href="{{ route('programs.add-courses', $program) }}" style="padding: 0.5rem 1rem; background: #9b59b6; color: white; border-radius: 0.25rem; text-decoration: none; text-align: center; font-size: 0.875rem; font-weight: 600; white-space: nowrap;">
                        📚 Add Courses
                    </a>
                    <a href="{{ route('programs.edit', $program) }}" style="padding: 0.5rem 1rem; background: #f39c12; color: white; border-radius: 0.25rem; text-decoration: none; text-align: center; font-size: 0.875rem; font-weight: 600; white-space: nowrap;">
                        ✏️ Edit
                    </a>
                    <form action="{{ route('programs.destroy', $program) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this program?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="width: 100%; padding: 0.5rem 1rem; background: #e74c3c; color: white; border: none; border-radius: 0.25rem; cursor: pointer; font-size: 0.875rem; font-weight: 600; white-space: nowrap;">
                            🗑️ Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    @if($programs->hasPages())
    <div style="margin-top: 2rem;">
        {{ $programs->links() }}
    </div>
    @endif
    @else
    <div class="card" style="text-align: center; padding: 3rem;">
        <p style="color: #999; font-size: 1.125rem; margin-bottom: 1rem;">🎓 No programs found</p>
        <p style="color: #666; font-size: 0.875rem; margin-bottom: 1.5rem;">Get started by adding your first academic program</p>
        <a href="{{ route('programs.create') }}" style="padding: 0.75rem 1.5rem; background: #27ae60; color: white; border-radius: 0.25rem; text-decoration: none; font-weight: 600;">
            + Add Your First Program
        </a>
    </div>
    @endif
</div>
@endsection
