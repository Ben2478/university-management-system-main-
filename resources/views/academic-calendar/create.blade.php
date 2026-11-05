@extends(auth()->user()->role === 'super_admin' ? 'layouts.super-admin' : 'layouts.admin')

@section('content')
<link rel="stylesheet" href="{{ asset('css/operations-management.css') }}?v={{ filemtime(public_path('css/operations-management.css')) }}">
<div class="container operations-management" style="max-width: 900px; margin: 2rem auto;">
    <h1>Add Semester Calendar</h1>
    <p>Set the semester timeframe and its three term date ranges.</p>
    @include('academic-calendar._form', [
        'action' => route('academic-calendar.store'),
        'method' => 'POST',
        'calendar' => null,
        'submitLabel' => 'Save Calendar',
    ])
</div>
@endsection