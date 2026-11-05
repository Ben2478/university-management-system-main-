@extends(auth()->user()->role === 'super_admin' ? 'layouts.super-admin' : 'layouts.admin')

@section('content')
<link rel="stylesheet" href="{{ asset('css/operations-management.css') }}?v={{ filemtime(public_path('css/operations-management.css')) }}">
<div class="container operations-management" style="padding: 2rem 0;">
    <div class="ops-header" style="display: flex; justify-content: space-between; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
        <div>
            <h1 style="margin: 0;">Academic Calendar</h1>
            <p style="margin: 0.5rem 0 0; color: #666;">Manage semester dates and term timeframes.</p>
        </div>
        <a href="{{ route('academic-calendar.create') }}" class="btn btn-primary">Add Semester Calendar</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($calendars->isEmpty())
        <div class="card" style="padding: 2rem; text-align: center;">
            <p>No semester calendars have been added.</p>
            <a href="{{ route('academic-calendar.create') }}" class="btn btn-primary">Create the first calendar</a>
        </div>
    @else
        <div style="display: grid; gap: 1rem;">
            @foreach($calendars as $calendar)
                <section class="card" style="padding: 1.25rem;">
                    <div class="ops-flex" style="display: flex; justify-content: space-between; align-items: start; gap: 1rem;">
                        <div>
                            <h2 style="margin: 0;">{{ $calendar->academic_year }} · Semester {{ $calendar->semester }}</h2>
                            <p style="margin: 0.5rem 0;">Semester: {{ $calendar->semester_start_date->format('M j, Y') }} – {{ $calendar->semester_end_date->format('M j, Y') }}</p>
                        </div>
                        <div class="ops-actions" style="display: flex; gap: 0.5rem;">
                            <a href="{{ route('academic-calendar.edit', $calendar) }}" class="btn btn-secondary">Edit</a>
                            <form action="{{ route('academic-calendar.destroy', $calendar) }}" method="POST" onsubmit="return confirm('Delete this semester calendar?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </div>
                    </div>
                    <div class="ops-stats" style="gap: 0.75rem; margin-top: 0.75rem;">
                        @for($term = 1; $term <= 3; $term++)
                            <div style="padding: 0.75rem; background: #f5f7f8; border-left: 3px solid #3498db;">
                                <strong>Term {{ $term }}</strong>
                                <div>{{ $calendar->{"term_{$term}_start_date"}->format('M j, Y') }} – {{ $calendar->{"term_{$term}_end_date"}->format('M j, Y') }}</div>
                            </div>
                        @endfor
                    </div>
                </section>
            @endforeach
        </div>
        <div style="margin-top: 1rem;">{{ $calendars->links() }}</div>
    @endif
</div>
@endsection