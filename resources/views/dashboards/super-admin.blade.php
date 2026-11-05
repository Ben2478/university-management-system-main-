@extends('layouts.super-admin')

@section('content')
<style>
    .super-dashboard { padding-top: 1.5rem !important; }
    .super-dashboard-heading { display:flex; justify-content:space-between; align-items:center; gap:1rem; margin-bottom:1.5rem; }
    .super-dashboard-heading h1 { font-size:1.55rem; line-height:1.25; }
    .super-dashboard-period { display:flex; align-items:center; gap:.6rem; color:#746d81; font-size:.82rem; }
    .super-dashboard-period label { white-space:nowrap; }
    .super-dashboard-period select { width:auto; min-width:150px; }
    .super-dashboard-actions { display:flex; flex-wrap:wrap; gap:.65rem; margin-bottom:1.5rem; }
    .super-dashboard-actions .btn { min-height:42px; display:inline-flex; align-items:center; gap:.5rem; border-radius:.55rem; }
    .super-dashboard-stats { display:grid; grid-template-columns:repeat(auto-fit,minmax(175px,1fr)); gap:.85rem; margin-bottom:1.5rem; }
    .super-dashboard-stat { min-width:0; margin:0; padding:1.1rem; border:1px solid #e8e5ee; border-left:4px solid var(--stat-color); border-radius:.65rem; box-shadow:0 2px 8px rgba(37,27,57,.045); }
    .super-dashboard-stat h2 { font-size:1.8rem !important; overflow-wrap:anywhere; }
    .super-dashboard-charts { display:grid; grid-template-columns:minmax(0,1.65fr) minmax(260px,1fr); gap:1rem; margin-bottom:1.25rem; }
    .super-dashboard-chart-card { min-width:0; margin:0; padding:1.1rem; border:1px solid #e8e5ee; border-radius:.65rem; box-shadow:0 2px 8px rgba(37,27,57,.045); }
    .super-dashboard-chart-card h3, .super-dashboard-panel h3 { font-size:.98rem; }
    .super-dashboard-chart-card canvas { display:block; width:100% !important; height:270px !important; max-height:270px !important; }
    .super-dashboard-lower { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); align-items:start; gap:1rem; }
    .super-dashboard-panel { min-width:0; margin:0; padding:1.1rem; border:1px solid #e8e5ee; border-radius:.65rem; box-shadow:0 2px 8px rgba(37,27,57,.045); }
    .super-dashboard-panel table { min-width:280px; }
    @media (max-width:1050px) {
        .super-dashboard-charts { grid-template-columns:minmax(0,1.3fr) minmax(240px,1fr); }
        .super-dashboard-lower { grid-template-columns:repeat(2,minmax(0,1fr)); }
        .super-dashboard-panel:first-child { grid-column:1/-1; }
    }
    @media (max-width:700px) {
        .super-dashboard { padding-top:1rem !important; }
        .super-dashboard-heading { align-items:stretch; flex-direction:column; }
        .super-dashboard-heading h1 { font-size:1.3rem; }
        .super-dashboard-period { justify-content:space-between; }
        .super-dashboard-period select { flex:1; }
        .super-dashboard-actions { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); }
        .super-dashboard-actions .btn { justify-content:center; padding:.6rem .5rem; text-align:center; }
        .super-dashboard-stats { grid-template-columns:repeat(2,minmax(0,1fr)); gap:.65rem; }
        .super-dashboard-stat { padding:.85rem; }
        .super-dashboard-stat h2 { font-size:1.45rem !important; }
        .super-dashboard-charts, .super-dashboard-lower { grid-template-columns:minmax(0,1fr); gap:.8rem; }
        .super-dashboard-panel:first-child { grid-column:auto; }
        .super-dashboard-chart-card canvas { height:235px !important; max-height:235px !important; }
        .super-dashboard-panel { overflow:hidden; }
    }
</style>
<div class="container super-dashboard dashboard-workspace" data-dashboard="super-admin">
    <!-- Header with Time Period Filter -->
    <div class="super-dashboard-heading">
        @include('components.dashboard-heading', ['area' => 'University operations', 'title' => 'University overview', 'description' => 'Welcome back, ' . auth()->user()->name . '.'])
        <form method="GET" class="super-dashboard-period">
            <label for="dashboard-period">Time Period</label>
            <select id="dashboard-period" name="period" onchange="this.form.submit()">
                <option value="all" {{ $period === 'all' ? 'selected' : '' }}>All Time</option>
                <option value="today" {{ $period === 'today' ? 'selected' : '' }}>Today</option>
                <option value="week" {{ $period === 'week' ? 'selected' : '' }}>This Week</option>
                <option value="month" {{ $period === 'month' ? 'selected' : '' }}>This Month</option>
                <option value="semester" {{ $period === 'semester' ? 'selected' : '' }}>This Semester</option>
                <option value="year" {{ $period === 'year' ? 'selected' : '' }}>This Year</option>
            </select>
        </form>
    </div>

    <p class="dashboard-filter-scope" role="status">{{ $periodNotice ?? 'All-time transactions' }}. The filter applies to completed revenue, approved receipt counts, and recent transaction lists. Student/user totals, workloads, approval rate, activity, and charts retain their stated scopes.</p>

    <!-- System Alerts -->
    @if(count($alerts) > 0)
    <div style="margin-bottom: 2rem;">
        @foreach($alerts as $alert)
        <div style="padding: 1rem; margin-bottom: 0.75rem; border-radius: 0.25rem; border-left: 4px solid 
            {{ $alert['type'] === 'danger' ? '#e74c3c' : ($alert['type'] === 'warning' ? '#f39c12' : '#3498db') }};
            background-color: {{ $alert['type'] === 'danger' ? '#fee' : ($alert['type'] === 'warning' ? '#fffbea' : '#e3f2fd') }};">
            <span style="font-size: 1.25rem; margin-right: 0.5rem;">{{ $alert['icon'] }}</span>
            <span style="color: #333;">{{ $alert['message'] }}</span>
            <a href="{{ $alert['link'] }}" style="margin-left: 1rem; color: #3498db; text-decoration: none; font-weight: 500;">View →</a>
        </div>
        @endforeach
    </div>
    @endif

    <!-- Quick Actions -->
    <div class="super-dashboard-actions">
        <a href="{{ route('users.create') }}" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 0.5rem;">
            <span>👤</span> Create User
        </a>
        <a href="{{ route('receipts.index', ['status' => 'pending']) }}" class="btn" style="background: #f39c12; color: white; display: inline-flex; align-items: center; gap: 0.5rem;">
            <span>📄</span> Review Receipts ({{ $stats['pending_receipts'] }})
        </a>
        <a href="{{ route('payments.index') }}" class="btn" style="background: #27ae60; color: white; display: inline-flex; align-items: center; gap: 0.5rem;">
            <span>💰</span> View Payments
        </a>
        <a href="{{ route('students.index') }}" class="btn" style="background: #3498db; color: white; display: inline-flex; align-items: center; gap: 0.5rem;">
            <span>📚</span> Manage Students
        </a>
    </div>

    <!-- Stats Grid -->
    @include('components.dashboard-metrics', ['metrics' => [
        ['label' => 'Total Students', 'value' => number_format($stats['total_students']), 'detail' => $stats['active_students'] . ' active students', 'icon' => 'students', 'tone' => 'blue'],
        ['label' => 'Total Users', 'value' => number_format($stats['total_users']), 'detail' => $stats['recent_registrations'] . ' registered in the last 30 days', 'icon' => 'account'],
        ['label' => 'Total Revenue', 'value' => 'K' . number_format($stats['total_revenue'], 0), 'detail' => $stats['completed_payments'] . ' completed transactions', 'icon' => 'payments', 'tone' => 'green'],
        ['label' => 'Pending Receipts', 'value' => $stats['pending_receipts'], 'detail' => 'K' . number_format($stats['pending_receipts_amount'], 0) . ' awaiting review', 'icon' => 'receipts', 'tone' => 'rose'],
    ]])

    <!-- Charts Row -->
    <div class="super-dashboard-charts">
        <!-- Payment Trend Chart -->
        <div class="card super-dashboard-chart-card">
            <h3 style="margin: 0 0 1.5rem 0;">Payment Trend (Last 12 Months)</h3>
            @include('components.dashboard-chart', ['chartId' => 'paymentTrendChart', 'label' => 'Payment trend for the last twelve months', 'hasData' => collect($payment_trend['data'])->contains(fn ($amount) => (float) $amount !== 0.0), 'emptyMessage' => 'No payment activity in the last twelve months.'])
        </div>

        <!-- Enrollment by Program Chart -->
        <div class="card super-dashboard-chart-card">
            <h3 style="margin: 0 0 1.5rem 0;">Enrollment by Program</h3>
            @include('components.dashboard-chart', ['chartId' => 'enrollmentChart', 'label' => 'Students by programme', 'hasData' => array_sum($enrollment_by_program) > 0, 'emptyMessage' => 'No student programme records to display yet.'])
        </div>
    </div>

    <!-- Recent Activity & Tables Row -->
    <div class="super-dashboard-lower">
        <!-- Recent Activity Feed -->
        <div class="card super-dashboard-panel">
            <h3 style="margin: 0 0 1.5rem 0;">Recent Activity</h3>
            <div style="max-height: 400px; overflow-y: auto;">
                @forelse($recent_activity as $activity)
                <div style="padding: 0.75rem 0; border-bottom: 1px solid #eee; display: flex; gap: 0.75rem;">
                    <span style="font-size: 1.5rem;">{{ $activity['icon'] }}</span>
                    <div style="flex: 1;">
                        <p style="margin: 0 0 0.25rem 0; font-size: 0.875rem; color: #333;">{{ $activity['description'] }}</p>
                        <p style="margin: 0; font-size: 0.75rem; color: #999;">{{ $activity['time']->diffForHumans() }}</p>
                    </div>
                </div>
                @empty
                <p style="color: #999; text-align: center; padding: 2rem 0;">No recent activity</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Payments -->
        <div class="card super-dashboard-panel">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <h3 style="margin: 0;">Recent Payments</h3>
                <a href="{{ route('payments.index') }}" style="color: #3498db; text-decoration: none; font-size: 0.875rem;">View All →</a>
            </div>
            <div style="max-height: 400px; overflow-y: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="border-bottom: 2px solid #ddd;">
                            <th style="text-align: left; padding: 0.5rem; font-size: 0.875rem;">Student</th>
                            <th style="text-align: right; padding: 0.5rem; font-size: 0.875rem;">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recent_payments as $payment)
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding: 0.75rem 0.5rem;">
                                <div style="font-size: 0.875rem; color: #333;">{{ $payment->student->first_name ?? 'N/A' }} {{ $payment->student->last_name ?? '' }}</div>
                                <div style="font-size: 0.75rem; color: #999;">{{ $payment->created_at->format('M d, H:i') }}</div>
                            </td>
                            <td style="padding: 0.75rem 0.5rem; text-align: right; font-weight: 600; color: #27ae60;">K{{ number_format($payment->amount, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" style="padding: 2rem; text-align: center; color: #999;">No payments yet</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Receipts -->
        <div class="card super-dashboard-panel">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <h3 style="margin: 0;">Recent Receipts</h3>
                <a href="{{ route('receipts.index') }}" style="color: #3498db; text-decoration: none; font-size: 0.875rem;">View All →</a>
            </div>
            <div style="max-height: 400px; overflow-y: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="border-bottom: 2px solid #ddd;">
                            <th style="text-align: left; padding: 0.5rem; font-size: 0.875rem;">Student</th>
                            <th style="text-align: center; padding: 0.5rem; font-size: 0.875rem;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recent_receipts as $receipt)
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding: 0.75rem 0.5rem;">
                                <div style="font-size: 0.875rem; color: #333;">{{ $receipt->student->first_name ?? 'N/A' }} {{ $receipt->student->last_name ?? '' }}</div>
                                <div style="font-size: 0.75rem; color: #999;">{{ $receipt->created_at->format('M d, H:i') }}</div>
                            </td>
                            <td style="padding: 0.75rem 0.5rem; text-align: center;">
                                <span style="padding: 0.25rem 0.75rem; border-radius: 0.25rem; font-size: 0.75rem; font-weight: 600;
                                    background-color: {{ $receipt->status === 'approved' ? '#d4edda' : ($receipt->status === 'rejected' ? '#f8d7da' : '#fff3cd') }};
                                    color: {{ $receipt->status === 'approved' ? '#155724' : ($receipt->status === 'rejected' ? '#721c24' : '#856404') }};">
                                    {{ ucfirst($receipt->status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" style="padding: 2rem; text-align: center; color: #999;">No receipts yet</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Payment Trend Chart
    renderDashboardChart('paymentTrendChart', {
        type: 'line',
        data: {
            labels: @json($payment_trend['labels']),
            datasets: [{
                label: 'Revenue (K)',
                data: @json($payment_trend['data']),
                borderColor: '#27ae60',
                backgroundColor: 'rgba(39, 174, 96, 0.1)',
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#27ae60',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'K' + context.parsed.y.toLocaleString();
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'K' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });

    // Enrollment by Program Chart
    const enrollmentData = @json($enrollment_by_program);
    renderDashboardChart('enrollmentChart', {
        type: 'doughnut',
        data: {
            labels: Object.keys(enrollmentData),
            datasets: [{
                data: Object.values(enrollmentData),
                backgroundColor: [
                    '#3498db',
                    '#9b59b6',
                    '#e74c3c',
                    '#f39c12',
                    '#27ae60',
                ],
                borderWidth: 2,
                borderColor: '#fff',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        font: {
                            size: 12
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.label + ': ' + context.parsed + ' students';
                        }
                    }
                }
            }
        }
    });
</script>
@endsection
