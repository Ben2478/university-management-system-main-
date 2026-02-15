@extends('layouts.parent')

@section('content')
<div class="container" style="padding: 2rem 0;">
    <h1 style="margin-bottom: 0.5rem;">Parent Portal</h1>
    <p style="color: #666; margin-bottom: 2rem;">Welcome, {{ auth()->user()->name }}! Monitor your children's academic progress.</p>

    <!-- Stats Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
        <div class="card" style="border-left: 4px solid #3498db;">
            <p style="margin: 0; color: #666; font-size: 0.875rem;">Children Enrolled</p>
            <h2 style="margin: 0.5rem 0; color: #3498db;">{{ $stats['children_count'] }}</h2>
        </div>

        <div class="card" style="border-left: 4px solid #e74c3c;">
            <p style="margin: 0; color: #666; font-size: 0.875rem;">Total Balance Due</p>
            <h2 style="margin: 0.5rem 0; color: #e74c3c;">K{{ number_format($stats['total_balance_due'], 2) }}</h2>
        </div>

        <div class="card" style="border-left: 4px solid #27ae60;">
            <p style="margin: 0; color: #666; font-size: 0.875rem;">Total Paid</p>
            <h2 style="margin: 0.5rem 0; color: #27ae60;">K{{ number_format($stats['total_paid'], 2) }}</h2>
        </div>
    </div>

    <!-- Children Overview -->
    <div class="card">
        <h3 style="margin-top: 0;">Children's Accounts</h3>
        @if($children_summary->isEmpty())
            <p style="color: #666;">No children accounts linked.</p>
        @else
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                @foreach($children_summary as $child)
                <div style="padding: 1.5rem; border: 1px solid #ddd; border-radius: 0.25rem; background: #f8f9fa;">
                    <h4 style="margin: 0 0 1rem 0;">{{ $child['name'] }}</h4>
                    <p style="margin: 0 0 0.5rem 0; font-size: 0.875rem; color: #666;">
                        <strong>Student ID:</strong> {{ $child['student_id'] }}
                    </p>
                    <p style="margin: 0 0 0.5rem 0; font-size: 0.875rem; color: #666;">
                        <strong>Active Courses:</strong> {{ $child['enrollments'] }}
                    </p>
                    <div style="padding: 1rem 0; border-top: 1px solid #ddd; border-bottom: 1px solid #ddd; margin: 1rem 0;">
                        <p style="margin: 0 0 0.5rem 0; color: #666; font-size: 0.875rem;">Balance Due</p>
                        <h3 style="margin: 0; color: #e74c3c;">K{{ number_format($child['balance'], 2) }}</h3>
                    </div>
                    <a href="{{ route('students.show', $child['student_id']) }}" class="btn btn-primary" style="width: 100%; text-align: center;">View Details</a>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
