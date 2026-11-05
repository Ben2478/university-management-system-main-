@extends('layouts.parent')

@section('content')
<div class="container dashboard-workspace dashboard-setup" data-dashboard="parent-setup">
    @include('components.dashboard-heading', ['area' => 'Family workspace', 'title' => 'Your family account', 'description' => 'Welcome, ' . auth()->user()->name . '.'])

    <section class="card dashboard-setup-state" aria-labelledby="parent-setup-title">
        <h2 id="parent-setup-title">Student linking pending</h2>
        <p>{{ $message ?? 'Your parent account has been created successfully, but it has not yet been linked to a student.' }}</p>
        <p>Please contact the administration office to link your account to a student. Provide the following details:</p>
        <ul>
            <li>Your registered email: <strong>{{ auth()->user()->email }}</strong></li>
            <li>Your student's ID or name</li>
        </ul>
        <p>Once your account is linked, you will have access to your student's academic progress, attendance, and payment history.</p>
        <a href="{{ route('profile.edit') }}">Profile Settings</a>
    </section>
</div>
@endsection
