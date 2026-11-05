@extends('layouts.student')

@section('content')
<div class="container dashboard-workspace dashboard-setup" data-dashboard="student-setup">
    @include('components.dashboard-heading', ['area' => 'Student workspace', 'title' => 'Your student account', 'description' => 'Welcome, ' . auth()->user()->name . '.'])

    <section class="card dashboard-setup-state" aria-labelledby="student-setup-title">
        <h2 id="student-setup-title">Student profile pending</h2>
        <p>{{ $message ?? 'Your account has been created successfully, but your student profile needs to be set up by an administrator.' }}</p>
        <p>Please contact the administration office to complete your student registration. Provide your registered email: <strong>{{ auth()->user()->email }}</strong>.</p>
        <p>Once your student profile is created, you will have access to your courses, grades, attendance, and payments.</p>
        <a href="{{ route('profile.edit') }}">Profile Settings</a>
    </section>
</div>
@endsection
