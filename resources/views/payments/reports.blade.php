@extends('layouts.admin')

@section('title', 'Payment Analytics & Reports')

@section('content')
<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .stat-card {
        background: white;
        padding: 1.5rem;
        border-radius: 0.5rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        border-left: 4px solid;
    }
    
    .stat-card.success { border-left-color: #27ae60; }
    .stat-card.info { border-left-color: #1abc9c; }
    .stat-card.primary { border-left-color: #3498db; }
    .stat-card.warning { border-left-color: #f39c12; }
    
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
    }
    
    .stat-growth {
        font-size: 0.75rem;
        margin-top: 0.5rem;
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
    
    .badge {
        padding: 0.35rem 0.75rem;
        border-radius: 0.25rem;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .badge.success { background: #27ae60; color: white; }
    .badge.warning { background: #f39c12; color: white; }
    .badge.danger { background: #e74c3c; color: white; }
    
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
    
    .header-actions {
        display: flex;
        gap: 0.5rem;
    }
    
    @media (max-width: 768px) {
        .chart-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 style="margin-bottom: 0.5rem;">💰 Payment Analytics & Reports</h1>
            <p style="color: #7f8c8d; margin: 0;">Comprehensive revenue insights and payment trends</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('payments.index') }}" class="btn btn-secondary btn-sm">
                All Payments
            </a>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="stats-grid">
        <div class="stat-card success">
            <div class="stat-label">Total Revenue</div>
            <div class="stat-value">ZMW {{ number_format($stats['total_revenue'], 2) }}</div>
        </div>

        <div class="stat-card info">
            <div class="stat-label">This Month</div>
            <div class="stat-value">ZMW {{ number_format($stats['this_month_revenue'], 2) }}</div>
            <div class="stat-growth" style="color: {{ $stats['month_growth'] >= 0 ? '#27ae60' : '#e74c3c' }};">
                {{ $stats['month_growth'] >= 0 ? '↑' : '↓' }} {{ abs($stats['month_growth']) }}% vs last month
            </div>
        </div>

        <div class="stat-card primary">
            <div class="stat-label">Success Rate</div>
            <div class="stat-value">{{ $stats['success_rate'] }}%</div>
        </div>

        <div class="stat-card warning">
            <div class="stat-label">Avg Payment</div>
            <div class="stat-value">ZMW {{ number_format($stats['avg_payment'], 2) }}</div>
        </div>
    </div>

    <!-- Daily Revenue & Method Distribution -->
    <div class="chart-grid">
        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title">📈 Daily Revenue Trends (Last 30 Days)</div>
                <span class="badge success">ZMW {{ number_format(array_sum($dailyRevenueChart['revenue']), 2) }}</span>
            </div>
            <canvas id="dailyRevenueChart" style="max-height: 300px;"></canvas>
        </div>

        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title">💳 Revenue by Method</div>
            </div>
            <canvas id="methodPieChart" style="max-height: 250px;"></canvas>
            <div class="method-list">
                @foreach($revenueByMethod as $method => $amount)
                <div class="method-item">
                    <span>{{ $method }}</span>
                    <strong>ZMW {{ number_format($amount, 0) }}</strong>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Monthly Trends & Status -->
    <div class="chart-grid">
        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title">📅 Monthly Revenue Trends (Last 12 Months)</div>
            </div>
            <canvas id="monthlyRevenueChart" style="max-height: 300px;"></canvas>
        </div>

        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title">📊 Payment Status</div>
            </div>
            <canvas id="statusPieChart" style="max-height: 250px;"></canvas>
            <div class="method-list">
                <div class="method-item">
                    <span><span class="badge success">Completed</span></span>
                    <strong>{{ $stats['completed_count'] }}</strong>
                </div>
                <div class="method-item">
                    <span><span class="badge warning">Pending</span></span>
                    <strong>{{ $stats['pending_count'] }}</strong>
                </div>
                <div class="method-item">
                    <span><span class="badge danger">Failed</span></span>
                    <strong>{{ $stats['failed_count'] }}</strong>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue by Program & Top Students -->
    <div class="chart-grid">
        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title">🎓 Revenue by Program (Top 10)</div>
            </div>
            <canvas id="programRevenueChart" style="max-height: 300px;"></canvas>
        </div>

        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title">🏆 Top 10 Paying Students</div>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Student</th>
                        <th style="text-align: right;">Payments</th>
                        <th style="text-align: right;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topStudents as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <strong>{{ $item->student->first_name }} {{ $item->student->last_name }}</strong><br>
                            <small style="color: #7f8c8d;">{{ $item->student->student_id }}</small>
                        </td>
                        <td style="text-align: right;">{{ $item->payment_count }}</td>
                        <td style="text-align: right;"><strong>ZMW {{ number_format($item->total_paid, 2) }}</strong></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align: center; color: #7f8c8d;">No data available</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Payment Method Trends -->
    <div class="chart-card">
        <div class="chart-header">
            <div class="chart-title">📊 Payment Method Trends (Last 6 Months)</div>
        </div>
        <canvas id="methodTrendsChart" style="max-height: 300px;"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Daily Revenue Chart
    new Chart(document.getElementById('dailyRevenueChart'), {
        type: 'line',
        data: {
            labels: @json($dailyRevenueChart['labels']),
            datasets: [{
                label: 'Revenue (ZMW)',
                data: @json($dailyRevenueChart['revenue']),
                borderColor: 'rgb(39, 174, 96)',
                backgroundColor: 'rgba(39, 174, 96, 0.1)',
                tension: 0.3,
                fill: true,
                yAxisID: 'y'
            }, {
                label: 'Payment Count',
                data: @json($dailyRevenueChart['counts']),
                borderColor: 'rgb(52, 152, 219)',
                backgroundColor: 'rgba(52, 152, 219, 0.1)',
                tension: 0.3,
                fill: false,
                yAxisID: 'y1'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    beginAtZero: true,
                    title: { display: true, text: 'Revenue (ZMW)' }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    beginAtZero: true,
                    ticks: { precision: 0 },
                    grid: { drawOnChartArea: false },
                    title: { display: true, text: 'Count' }
                }
            }
        }
    });

    // Method Pie Chart
    new Chart(document.getElementById('methodPieChart'), {
        type: 'doughnut',
        data: {
            labels: @json(array_keys($revenueByMethod)),
            datasets: [{
                data: @json(array_values($revenueByMethod)),
                backgroundColor: ['#3498db', '#27ae60', '#1abc9c', '#f39c12', '#e74c3c']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } }
        }
    });

    // Monthly Revenue Chart
    new Chart(document.getElementById('monthlyRevenueChart'), {
        type: 'bar',
        data: {
            labels: @json($monthlyRevenueChart['labels']),
            datasets: [{
                label: 'Revenue (ZMW)',
                data: @json($monthlyRevenueChart['revenue']),
                backgroundColor: 'rgba(39, 174, 96, 0.8)',
                yAxisID: 'y'
            }, {
                label: 'Payment Count',
                data: @json($monthlyRevenueChart['counts']),
                backgroundColor: 'rgba(52, 152, 219, 0.8)',
                yAxisID: 'y1'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    beginAtZero: true,
                    title: { display: true, text: 'Revenue (ZMW)' }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    beginAtZero: true,
                    ticks: { precision: 0 },
                    grid: { drawOnChartArea: false },
                    title: { display: true, text: 'Count' }
                }
            }
        }
    });

    // Status Pie Chart
    new Chart(document.getElementById('statusPieChart'), {
        type: 'doughnut',
        data: {
            labels: @json(array_keys($paymentsByStatus)),
            datasets: [{
                data: @json(array_values($paymentsByStatus)),
                backgroundColor: ['#27ae60', '#f39c12', '#1abc9c', '#e74c3c', '#95a5a6']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } }
        }
    });

    // Program Revenue Chart
    new Chart(document.getElementById('programRevenueChart'), {
        type: 'bar',
        data: {
            labels: @json($revenueByProgram->keys()->toArray()),
            datasets: [{
                label: 'Revenue (ZMW)',
                data: @json($revenueByProgram->pluck('revenue')->toArray()),
                backgroundColor: 'rgba(52, 152, 219, 0.8)'
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { x: { beginAtZero: true } }
        }
    });

    // Method Trends Chart
    const methodTrendsData = @json($methodTrends);
    const months = Object.keys(methodTrendsData);
    const methods = ['bank_transfer', 'mobile_money', 'cash', 'card', 'zananco_billmaster'];
    const methodLabels = {
        'bank_transfer': 'Bank Transfer',
        'mobile_money': 'Mobile Money',
        'cash': 'Cash',
        'card': 'Card',
        'zananco_billmaster': 'Zananco'
    };
    const colors = [
        'rgba(52, 152, 219, 0.7)',
        'rgba(39, 174, 96, 0.7)',
        'rgba(26, 188, 156, 0.7)',
        'rgba(243, 156, 18, 0.7)',
        'rgba(231, 76, 60, 0.7)'
    ];

    const datasets = methods.map((method, index) => ({
        label: methodLabels[method],
        data: months.map(month => {
            const monthData = methodTrendsData[month];
            const methodData = monthData.find(item => item.payment_method === method);
            return methodData ? methodData.revenue : 0;
        }),
        backgroundColor: colors[index],
        borderColor: colors[index].replace('0.7', '1'),
        borderWidth: 2
    }));

    new Chart(document.getElementById('methodTrendsChart'), {
        type: 'line',
        data: {
            labels: months.map(m => {
                const date = new Date(m + '-01');
                return date.toLocaleDateString('en-US', { month: 'short', year: 'numeric' });
            }),
            datasets: datasets
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: { legend: { position: 'top' } },
            scales: {
                y: {
                    stacked: true,
                    beginAtZero: true
                }
            }
        }
    });
});
</script>
@endsection
