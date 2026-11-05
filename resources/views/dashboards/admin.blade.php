@extends('layouts.admin')

@section('title', 'Accountant Dashboard')

@section('content')
<style>
    .accountant-dashboard { padding-top:1.5rem !important; color:#272336; }
    .accountant-dashboard > .container { max-width:100%; }
    .accountant-dashboard-heading { margin-bottom:1.5rem !important; }
    .accountant-dashboard-heading h1 { font-size:1.45rem; line-height:1.25; }
    .accountant-dashboard .stats-grid { grid-template-columns:repeat(auto-fit,minmax(175px,1fr)); gap:.85rem; margin-bottom:1.5rem; }
    .accountant-dashboard .stat-card, .accountant-dashboard .chart-card, .accountant-dashboard .action-btn { border:1px solid #e8e5ee; border-radius:.65rem; box-shadow:0 2px 8px rgba(37,27,57,.045); }
    .accountant-dashboard .stat-card { border-left:4px solid; }
    .accountant-dashboard .chart-grid { grid-template-columns:minmax(0,1.65fr) minmax(260px,1fr); gap:1rem; margin-bottom:1.25rem; }
    .accountant-dashboard .chart-card { min-width:0; overflow-x:auto; padding:1.1rem; }
    .accountant-dashboard .chart-header { gap:.75rem; }
    .accountant-dashboard .chart-title { line-height:1.35; }
    .accountant-dashboard .chart-card canvas { display:block; width:100% !important; height:250px !important; max-height:250px !important; }
    .accountant-dashboard .table { min-width:620px; }
    .accountant-dashboard .quick-actions { grid-template-columns:repeat(auto-fit,minmax(190px,1fr)); gap:.85rem; margin-bottom:1.5rem; }
    .accountant-dashboard .action-btn { padding:1.1rem; }
    .accountant-dashboard .alert-box { border-radius:.6rem; }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .stat-card {
        background: white;
        padding: 1.5rem;
        border-radius: 0.5rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        border-left: 4px solid;
        transition: transform 0.2s;
    }
    
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    
    .stat-card.danger { border-left-color: #e74c3c; }
    .stat-card.success { border-left-color: #27ae60; }
    .stat-card.primary { border-left-color: #3498db; }
    .stat-card.warning { border-left-color: #f39c12; }
    .stat-card.info { border-left-color: #1abc9c; }
    
    .stat-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        color: #7f8c8d;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    
    .stat-value {
        font-size: 1.75rem;
        font-weight: bold;
        color: #2c3e50;
        margin-bottom: 0.25rem;
    }
    
    .stat-subtext {
        font-size: 0.75rem;
        color: #95a5a6;
    }
    
    .chart-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .chart-card {
        background: white;
        padding: 1.5rem;
        border-radius: 0.5rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .chart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #ecf0f1;
    }
    
    .chart-title {
        font-size: 1rem;
        font-weight: 600;
        color: #2c3e50;
    }
    
    .alert-box {
        background: white;
        padding: 1rem;
        border-radius: 0.5rem;
        margin-bottom: 1rem;
        border-left: 4px solid;
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .alert-box.danger { border-left-color: #e74c3c; background: #fdf2f2; }
    .alert-box.warning { border-left-color: #f39c12; background: #fef9f3; }
    
    .table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.875rem;
    }
    
    .table th {
        background: #ecf0f1;
        padding: 0.75rem;
        text-align: left;
        font-weight: 600;
        color: #2c3e50;
    }
    
    .table td {
        padding: 0.75rem;
        border-bottom: 1px solid #ecf0f1;
    }
    
    .table tr:hover {
        background: #f8f9fa;
    }
    
    .badge {
        padding: 0.35rem 0.75rem;
        border-radius: 0.25rem;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
    }
    
    .badge.warning { background: #f39c12; color: white; }
    .badge.success { background: #27ae60; color: white; }
    .badge.danger { background: #e74c3c; color: white; }
    .badge.info { background: #3498db; color: white; }
    
    .method-list {
        font-size: 0.875rem;
        margin-top: 1rem;
    }
    
    .method-item {
        display: flex;
        justify-content: space-between;
        padding: 0.5rem 0;
        border-bottom: 1px solid #ecf0f1;
    }
    
    .quick-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }
    
    .action-btn {
        background: white;
        padding: 1.5rem;
        border-radius: 0.5rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        text-align: center;
        text-decoration: none;
        color: #2c3e50;
        transition: all 0.2s;
        border: 2px solid transparent;
    }
    
    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        border-color: #3498db;
    }
    
    .action-icon {
        font-size: 2rem;
        margin-bottom: 0.5rem;
    }
    
    @media (max-width: 768px) {
        .accountant-dashboard { padding-top:1rem !important; }
        .chart-grid {
            grid-template-columns: 1fr;
        }
        .stats-grid {
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        }
        .accountant-dashboard .chart-grid { grid-template-columns:minmax(0,1fr); gap:.8rem; }
        .accountant-dashboard .stats-grid { grid-template-columns:repeat(2,minmax(0,1fr)); gap:.65rem; }
        .accountant-dashboard .stat-card { padding:1rem; }
        .accountant-dashboard .stat-value { font-size:1.45rem; overflow-wrap:anywhere; }
        .accountant-dashboard .quick-actions { grid-template-columns:repeat(2,minmax(0,1fr)); gap:.65rem; }
        .accountant-dashboard .action-btn { padding:.9rem .65rem; }
        .accountant-dashboard .action-icon { font-size:1.55rem; }
        .accountant-dashboard .chart-header { align-items:flex-start; }
        .accountant-dashboard .chart-title { font-size:.92rem; }
        .accountant-dashboard .chart-card canvas { height:225px !important; max-height:225px !important; }
    }
    @media (max-width:420px) {
        .accountant-dashboard .stats-grid { grid-template-columns:1fr 1fr; }
        .accountant-dashboard .quick-actions { grid-template-columns:1fr 1fr; }
        .accountant-dashboard .badge { white-space:nowrap; }
    }
</style>

<div class="container accountant-dashboard dashboard-workspace" data-dashboard="accountant">
    <!-- Header -->
    @include('components.dashboard-heading', ['area' => 'Finance workspace', 'title' => 'Financial overview', 'description' => 'Welcome back, ' . auth()->user()->name . '.'])

    <!-- Alerts -->
    @if(isset($alerts) && count($alerts) > 0)
        @foreach($alerts as $alert)
        <div class="alert-box {{ $alert['type'] }}">
            <span style="font-size: 1.5rem;">{{ $alert['icon'] }}</span>
            <div style="flex: 1;">
                <strong>{{ $alert['message'] }}</strong>
            </div>
            <a href="{{ $alert['link'] }}" class="btn btn-sm btn-primary">View</a>
        </div>
        @endforeach
    @endif

    <!-- Key Stats -->
    @include('components.dashboard-metrics', ['metrics' => [
        ['label' => 'Pending Reviews', 'value' => $stats['pending_receipts'], 'detail' => 'ZMW ' . number_format($stats['pending_amount'], 0) . ' awaiting review', 'icon' => 'receipts', 'tone' => 'rose'],
        ['label' => "Today's Revenue", 'value' => 'ZMW ' . number_format($stats['today_revenue'], 0), 'detail' => $stats['approved_today'] . ' receipts approved today', 'icon' => 'payments', 'tone' => 'green'],
        ['label' => 'Total Collected', 'value' => 'ZMW ' . number_format($stats['total_collected'], 0), 'detail' => $stats['total_students'] . ' students', 'icon' => 'finance'],
        ['label' => 'Outstanding Balance', 'value' => 'ZMW ' . number_format($stats['outstanding_balance'], 0), 'detail' => $stats['students_with_balance'] . ' students owing', 'icon' => 'reports', 'tone' => 'amber'],
        ['label' => 'This Month', 'value' => 'ZMW ' . number_format($stats['this_month_revenue'], 0), 'detail' => $stats['approval_rate'] . '% approval rate', 'icon' => 'calendar', 'tone' => 'blue'],
        ['label' => 'Fully Paid', 'value' => $stats['fully_paid'], 'detail' => ($stats['total_students'] > 0 ? round($stats['fully_paid'] / $stats['total_students'] * 100, 1) : 0) . '% of students', 'icon' => 'attendance', 'tone' => 'green'],
    ]])

    <!-- Quick Actions -->
    <div class="quick-actions">
        <a href="{{ route('receipts.pending') }}" class="action-btn">
            <div class="action-icon">📋</div>
            <div style="font-weight: 600;">Review Receipts</div>
            <div style="font-size: 0.75rem; color: #7f8c8d; margin-top: 0.25rem;">{{ $stats['pending_receipts'] }} pending</div>
        </a>
        
        <a href="{{ route('receipts.reports') }}" class="action-btn">
            <div class="action-icon">📊</div>
            <div style="font-weight: 600;">Receipt Analytics</div>
            <div style="font-size: 0.75rem; color: #7f8c8d; margin-top: 0.25rem;">View trends</div>
        </a>
        
        <a href="{{ route('payments.reports') }}" class="action-btn">
            <div class="action-icon">💰</div>
            <div style="font-weight: 600;">Payment Analytics</div>
            <div style="font-size: 0.75rem; color: #7f8c8d; margin-top: 0.25rem;">Revenue insights</div>
        </a>
        
        <a href="{{ route('students.index') }}" class="action-btn">
            <div class="action-icon">👨‍🎓</div>
            <div style="font-weight: 600;">Student Finances</div>
            <div style="font-size: 0.75rem; color: #7f8c8d; margin-top: 0.25rem;">View balances</div>
        </a>
    </div>

    <!-- Daily Revenue & Payment Methods -->
    <div class="chart-grid">
        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title">📈 Daily Revenue Trend (Last 7 Days)</div>
                <span class="badge success">ZMW {{ number_format(array_sum($daily_revenue), 0) }}</span>
            </div>
            @include('components.dashboard-chart', ['chartId' => 'dailyRevenueChart', 'label' => 'Daily revenue for the last seven days', 'hasData' => collect($daily_revenue)->contains(fn ($amount) => (float) $amount !== 0.0), 'emptyMessage' => 'No completed payments in the last seven days.'])
        </div>

        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title">💳 Revenue by Method</div>
            </div>
            @include('components.dashboard-chart', ['chartId' => 'methodPieChart', 'label' => 'Revenue by payment method', 'hasData' => array_sum($revenue_by_method) != 0, 'emptyMessage' => 'No completed payments by method yet.'])
            <div class="method-list">
                @foreach($revenue_by_method as $method => $amount)
                <div class="method-item">
                    <span>{{ $method }}</span>
                    <strong>ZMW {{ number_format($amount, 0) }}</strong>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Receipt Status & Owing Programs -->
    <div class="chart-grid">
        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title">📋 Receipt Status Breakdown</div>
            </div>
            @include('components.dashboard-chart', ['chartId' => 'receiptStatusChart', 'label' => 'Receipt status breakdown', 'hasData' => array_sum($receipt_status) > 0, 'emptyMessage' => 'No receipts have been submitted yet.'])
            <div class="method-list">
                @foreach($receipt_status as $status => $count)
                <div class="method-item">
                    <span>{{ $status }}</span>
                    <strong>{{ $count }}</strong>
                </div>
                @endforeach
            </div>
        </div>

        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title">🎓 Students Owing by Program</div>
            </div>
            @include('components.dashboard-chart', ['chartId' => 'owingProgramChart', 'label' => 'Students owing by programme', 'hasData' => $owing_by_program->sum() > 0, 'emptyMessage' => 'No outstanding student balances by programme.'])
            @if($owing_by_program->count() > 0)
            <div class="method-list">
                @foreach($owing_by_program as $program => $count)
                <div class="method-item">
                    <span>{{ $program }}</span>
                    <strong>{{ $count }} students</strong>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>

    <!-- Pending Receipts Table -->
    <div class="chart-card" style="margin-bottom: 2rem;">
        <div class="chart-header">
            <div class="chart-title">⏳ Pending Receipts Requiring Review</div>
            <a href="{{ route('receipts.pending') }}" class="btn btn-primary btn-sm">View All</a>
        </div>

        @if($pending_receipts->isEmpty())
            <p style="text-align: center; color: #7f8c8d; padding: 2rem 0;">✅ No pending receipts at this time!</p>
        @else
            <table class="table">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Amount</th>
                        <th>Payment Method</th>
                        <th>Uploaded</th>
                        <th>Waiting Time</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pending_receipts as $receipt)
                    @php
                        $waitingHours = now()->diffInHours($receipt->uploaded_at);
                        $waitingClass = $waitingHours > 72 ? 'danger' : ($waitingHours > 48 ? 'warning' : 'info');
                    @endphp
                    <tr>
                        <td>
                            <strong>{{ $receipt->student->first_name }} {{ $receipt->student->last_name }}</strong><br>
                            <small style="color: #7f8c8d;">{{ $receipt->student->student_id }}</small>
                        </td>
                        <td><strong>ZMW {{ number_format($receipt->amount, 2) }}</strong></td>
                        <td>
                            <span class="badge info">{{ ucfirst(str_replace('_', ' ', $receipt->payment_method)) }}</span>
                        </td>
                        <td>{{ $receipt->uploaded_at->format('M d, Y') }}<br><small style="color: #7f8c8d;">{{ $receipt->uploaded_at->format('H:i') }}</small></td>
                        <td><span class="badge {{ $waitingClass }}">{{ $waitingHours }}h</span></td>
                        <td>
                            <a href="{{ route('receipts.show', $receipt->id) }}" class="btn btn-sm btn-primary">Review</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <!-- Recent Approved Receipts -->
    @if(isset($recent_approved) && $recent_approved->count() > 0)
    <div class="chart-card">
        <div class="chart-header">
            <div class="chart-title">✅ Recently Approved Receipts</div>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Amount</th>
                    <th>Reviewed By</th>
                    <th>Approved</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recent_approved as $receipt)
                <tr>
                    <td>
                        <strong>{{ $receipt->student->first_name }} {{ $receipt->student->last_name }}</strong><br>
                        <small style="color: #7f8c8d;">{{ $receipt->student->student_id }}</small>
                    </td>
                    <td><strong>ZMW {{ number_format($receipt->amount, 2) }}</strong></td>
                    <td>{{ $receipt->reviewer->name ?? 'System' }}</td>
                    <td>{{ $receipt->reviewed_at->format('M d, Y H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Daily Revenue Chart
    renderDashboardChart('dailyRevenueChart', {
        type: 'line',
        data: {
            labels: @json($daily_labels),
            datasets: [{
                label: 'Revenue (ZMW)',
                data: @json($daily_revenue),
                borderColor: 'rgb(39, 174, 96)',
                backgroundColor: 'rgba(39, 174, 96, 0.1)',
                tension: 0.3,
                fill: true,
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
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

    // Payment Method Pie Chart
    renderDashboardChart('methodPieChart', {
        type: 'doughnut',
        data: {
            labels: @json(array_keys($revenue_by_method)),
            datasets: [{
                data: @json(array_values($revenue_by_method)),
                backgroundColor: ['#3498db', '#27ae60', '#1abc9c', '#f39c12', '#e74c3c']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } }
        }
    });

    // Receipt Status Chart
    renderDashboardChart('receiptStatusChart', {
        type: 'bar',
        data: {
            labels: @json(array_keys($receipt_status)),
            datasets: [{
                label: 'Receipts',
                data: @json(array_values($receipt_status)),
                backgroundColor: ['#f39c12', '#27ae60', '#e74c3c']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0 }
                }
            }
        }
    });

    // Owing by Program Chart
    renderDashboardChart('owingProgramChart', {
        type: 'bar',
        data: {
            labels: @json($owing_by_program->keys()->toArray()),
            datasets: [{
                label: 'Students',
                data: @json($owing_by_program->values()->toArray()),
                backgroundColor: 'rgba(52, 152, 219, 0.8)'
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: { precision: 0 }
                }
            }
        }
    });
});
</script>
@endsection
