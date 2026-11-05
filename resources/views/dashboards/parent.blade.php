@extends('layouts.parent')

@section('content')
<style>
    .parent-dashboard { padding-top:1.25rem !important; }
    .parent-dashboard-stats { display:grid; grid-template-columns:repeat(auto-fit,minmax(210px,1fr)); gap:1rem; margin-bottom:1.5rem; }
    .parent-children-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(min(100%,260px),1fr)); gap:1rem; }
    .parent-dashboard .card, .parent-child-card { min-width:0; border:1px solid #e8e5ee; border-radius:.65rem; box-shadow:0 2px 8px rgba(37,27,57,.045); }
    .parent-child-card { padding:1.15rem; background:#fff; }
    @media (max-width:600px) { .parent-dashboard { padding:1rem !important; } .parent-dashboard-stats { grid-template-columns:1fr; gap:.7rem; } }
</style>
<div class="container parent-dashboard dashboard-workspace" data-dashboard="parent">
    @include('components.dashboard-heading', ['area' => 'Family workspace', 'title' => 'Family overview', 'description' => 'Welcome back, ' . auth()->user()->name . '.'])
    @include('components.dashboard-metrics', ['metrics' => [
        ['label' => 'Children Enrolled', 'value' => $stats['children_count'], 'detail' => 'Linked student accounts', 'icon' => 'students', 'tone' => 'blue'],
        ['label' => 'Total Balance Due', 'value' => 'K' . number_format($stats['total_balance_due'], 2), 'detail' => 'Outstanding tuition balance', 'icon' => 'payments', 'tone' => 'rose'],
        ['label' => 'Total Paid', 'value' => 'K' . number_format($stats['total_paid'], 2), 'detail' => 'Payments received', 'icon' => 'receipts', 'tone' => 'green'],
    ]])

    <!-- Children Overview -->
    <div class="card parent-children-section">
        <h3 style="margin-top: 0;">Children's Accounts</h3>
        @if($children_summary->isEmpty())
            <p style="color: #666;">No children accounts linked.</p>
        @else
            <div class="parent-children-grid">
                @foreach($children_summary as $child)
                <div class="parent-child-card">
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
