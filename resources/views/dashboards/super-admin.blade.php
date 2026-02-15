@extends('layouts.super-admin')

@section('content')
<div class="container" style="padding: 2rem 0;">
    <!-- Header with Time Period Filter -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 style="margin: 0 0 0.5rem 0;">Super Admin Dashboard</h1>
            <p style="margin: 0; color: #666;">Welcome back! Here's what's happening with your system.</p>
        </div>
        <form method="GET" style="display: flex; gap: 0.5rem; align-items: center;">
            <label style="color: #666; font-size: 0.875rem;">Time Period:</label>
            <select name="period" onchange="this.form.submit()" style="padding: 0.5rem 1rem; border: 1px solid #ddd; border-radius: 0.25rem; font-size: 0.875rem;">
                <option value="all" {{ $period === 'all' ? 'selected' : '' }}>All Time</option>
                <option value="today" {{ $period === 'today' ? 'selected' : '' }}>Today</option>
                <option value="week" {{ $period === 'week' ? 'selected' : '' }}>This Week</option>
                <option value="month" {{ $period === 'month' ? 'selected' : '' }}>This Month</option>
                <option value="semester" {{ $period === 'semester' ? 'selected' : '' }}>This Semester</option>
                <option value="year" {{ $period === 'year' ? 'selected' : '' }}>This Year</option>
            </select>
        </form>
    </div>

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
    <div style="display: flex; gap: 1rem; margin-bottom: 2rem; flex-wrap: wrap;">
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
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
        <div class="card" style="border-left: 4px solid #3498db; padding: 1.25rem;">
            <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Total Students</p>
            <h2 style="margin: 0.5rem 0 0.25rem 0; color: #3498db; font-size: 2rem;">{{ number_format($stats['total_students']) }}</h2>
            <p style="margin: 0; color: #999; font-size: 0.75rem;">{{ $stats['active_students'] }} active</p>
        </div>

        <div class="card" style="border-left: 4px solid #9b59b6; padding: 1.25rem;">
            <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Total Users</p>
            <h2 style="margin: 0.5rem 0 0.25rem 0; color: #9b59b6; font-size: 2rem;">{{ number_format($stats['total_users']) }}</h2>
            <p style="margin: 0; color: #999; font-size: 0.75rem;">+{{ $stats['recent_registrations'] }} last 30 days</p>
        </div>

        <div class="card" style="border-left: 4px solid #27ae60; padding: 1.25rem;">
            <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Total Revenue</p>
            <h2 style="margin: 0.5rem 0 0.25rem 0; color: #27ae60; font-size: 2rem;">K{{ number_format($stats['total_revenue'], 0) }}</h2>
            <p style="margin: 0; color: #999; font-size: 0.75rem;">{{ $stats['completed_payments'] }} transactions</p>
        </div>

        <div class="card" style="border-left: 4px solid #e74c3c; padding: 1.25rem;">
            <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Pending Receipts</p>
            <h2 style="margin: 0.5rem 0 0.25rem 0; color: #e74c3c; font-size: 2rem;">{{ $stats['pending_receipts'] }}</h2>
            <p style="margin: 0; color: #999; font-size: 0.75rem;">K{{ number_format($stats['pending_receipts_amount'], 0) }} total</p>
        </div>

        <div class="card" style="border-left: 4px solid #f39c12; padding: 1.25rem;">
            <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Enrollments</p>
            <h2 style="margin: 0.5rem 0 0.25rem 0; color: #f39c12; font-size: 2rem;">{{ number_format($stats['total_enrollments']) }}</h2>
            <p style="margin: 0; color: #999; font-size: 0.75rem;">{{ $stats['active_enrollments'] }} active</p>
        </div>

        <div class="card" style="border-left: 4px solid #16a085; padding: 1.25rem;">
            <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Approval Rate</p>
            <h2 style="margin: 0.5rem 0 0.25rem 0; color: #16a085; font-size: 2rem;">{{ $stats['approval_rate'] }}%</h2>
            <p style="margin: 0; color: #999; font-size: 0.75rem;">{{ $stats['approved_receipts'] }} approved</p>
        </div>
    </div>

    <!-- Charts Row -->
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; margin-bottom: 2rem;">
        <!-- Payment Trend Chart -->
        <div class="card">
            <h3 style="margin: 0 0 1.5rem 0;">Payment Trend (Last 12 Months)</h3>
            <canvas id="paymentTrendChart" style="max-height: 300px;"></canvas>
        </div>

        <!-- Enrollment by Program Chart -->
        <div class="card">
            <h3 style="margin: 0 0 1.5rem 0;">Enrollment by Program</h3>
            <canvas id="enrollmentChart" style="max-height: 300px;"></canvas>
        </div>
    </div>

    <!-- Recent Activity & Tables Row -->
    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 2rem;">
        <!-- Recent Activity Feed -->
        <div class="card">
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
        <div class="card">
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
        <div class="card">
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
    const paymentCtx = document.getElementById('paymentTrendChart').getContext('2d');
    new Chart(paymentCtx, {
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
    const enrollmentCtx = document.getElementById('enrollmentChart').getContext('2d');
    const enrollmentData = @json($enrollment_by_program);
    new Chart(enrollmentCtx, {
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
