@extends(auth()->user()->role === 'super_admin' ? 'layouts.super-admin' : 'layouts.admin')

@section('content')
<link rel="stylesheet" href="{{ asset('css/operations-management.css') }}?v={{ filemtime(public_path('css/operations-management.css')) }}">
<div class="container operations-management" style="max-width: 900px; margin: 2rem auto;">
    <h1>Edit Semester Calendar</h1>
    <p>{{ $calendar->academic_year }} · Semester {{ $calendar->semester }}</p>
    @include('academic-calendar._form', [
        'action' => route('academic-calendar.update', $calendar),
        'method' => 'PUT',
        'calendar' => $calendar,
        'submitLabel' => 'Update Calendar',
    ])
</div>
@endsection