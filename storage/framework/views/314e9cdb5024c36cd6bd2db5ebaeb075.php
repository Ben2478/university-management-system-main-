

<?php $__env->startSection('title', 'Receipt Analytics & Reports'); ?>

<?php $__env->startSection('content'); ?>
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
    
    .stat-card.primary { border-left-color: #3498db; }
    .stat-card.success { border-left-color: #27ae60; }
    .stat-card.info { border-left-color: #1abc9c; }
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
    
    .badge.primary { background: #3498db; color: white; }
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
        margin-bottom: 1.5rem;
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
            <h1 style="margin-bottom: 0.5rem;">📊 Receipt Analytics & Reports</h1>
            <p style="color: #7f8c8d; margin: 0;">Comprehensive insights into receipt processing and trends</p>
        </div>
        <div class="header-actions">
            <a href="<?php echo e(route('receipts.pending')); ?>" class="btn btn-primary btn-sm">
                Pending Reviews
            </a>
            <a href="<?php echo e(route('receipts.index')); ?>" class="btn btn-secondary btn-sm">
                All Receipts
            </a>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="stats-grid">
        <div class="stat-card primary">
            <div class="stat-label">Total Receipts</div>
            <div class="stat-value"><?php echo e(number_format($stats['total_receipts'])); ?></div>
        </div>

        <div class="stat-card success">
            <div class="stat-label">Total Approved Amount</div>
            <div class="stat-value">ZMW <?php echo e(number_format($stats['total_amount'], 2)); ?></div>
        </div>

        <div class="stat-card info">
            <div class="stat-label">Approval Rate</div>
            <div class="stat-value"><?php echo e($stats['approval_rate']); ?>%</div>
        </div>

        <div class="stat-card warning">
            <div class="stat-label">Avg Processing Time</div>
            <div class="stat-value"><?php echo e($stats['avg_processing_hours']); ?>h</div>
        </div>
    </div>

    <!-- Daily Receipts & Status -->
    <div class="chart-grid">
        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title">📈 Daily Receipt Submissions (Last 30 Days)</div>
                <span class="badge primary"><?php echo e(array_sum($dailyReceiptsChart['data'])); ?> receipts</span>
            </div>
            <canvas id="dailyReceiptsChart" style="max-height: 300px;"></canvas>
        </div>

        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title">📊 Status Distribution</div>
            </div>
            <canvas id="statusPieChart" style="max-height: 250px;"></canvas>
            <div class="method-list">
                <div class="method-item">
                    <span><span class="badge warning">Pending</span></span>
                    <strong><?php echo e($stats['pending_count']); ?></strong>
                </div>
                <div class="method-item">
                    <span><span class="badge success">Approved</span></span>
                    <strong><?php echo e($stats['approved_count']); ?></strong>
                </div>
                <div class="method-item">
                    <span><span class="badge danger">Rejected</span></span>
                    <strong><?php echo e($stats['rejected_count']); ?></strong>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Trends & Methods -->
    <div class="chart-grid">
        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title">📅 Monthly Trends (Last 12 Months)</div>
            </div>
            <canvas id="monthlyTrendsChart" style="max-height: 300px;"></canvas>
        </div>

        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title">💳 Payment Methods</div>
            </div>
            <canvas id="methodPieChart" style="max-height: 250px;"></canvas>
            <div class="method-list">
                <?php $__currentLoopData = $receiptsByMethod; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $method => $count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="method-item">
                    <span><?php echo e($method); ?></span>
                    <strong><?php echo e($count); ?></strong>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>

    <!-- Processing Time & Top Students -->
    <div class="chart-grid">
        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title">⏱️ Processing Time Distribution</div>
            </div>
            <canvas id="processingTimeChart" style="max-height: 300px;"></canvas>
        </div>

        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title">🏆 Top 10 Students</div>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Student</th>
                        <th style="text-align: right;">Receipts</th>
                        <th style="text-align: right;">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $topStudents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($index + 1); ?></td>
                        <td>
                            <strong><?php echo e($item->student->first_name); ?> <?php echo e($item->student->last_name); ?></strong><br>
                            <small style="color: #7f8c8d;"><?php echo e($item->student->student_id); ?></small>
                        </td>
                        <td style="text-align: right;"><?php echo e($item->receipt_count); ?></td>
                        <td style="text-align: right;">ZMW <?php echo e(number_format($item->total_amount, 2)); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="4" style="text-align: center; color: #7f8c8d;">No data available</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Daily Receipts Chart
    new Chart(document.getElementById('dailyReceiptsChart'), {
        type: 'line',
        data: {
            labels: <?php echo json_encode($dailyReceiptsChart['labels'], 15, 512) ?>,
            datasets: [{
                label: 'Receipts Submitted',
                data: <?php echo json_encode($dailyReceiptsChart['data'], 15, 512) ?>,
                borderColor: 'rgb(52, 152, 219)',
                backgroundColor: 'rgba(52, 152, 219, 0.1)',
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0 }
                }
            }
        }
    });

    // Status Pie Chart
    new Chart(document.getElementById('statusPieChart'), {
        type: 'doughnut',
        data: {
            labels: <?php echo json_encode(array_keys($receiptsByStatus), 15, 512) ?>,
            datasets: [{
                data: <?php echo json_encode(array_values($receiptsByStatus), 15, 512) ?>,
                backgroundColor: ['#f39c12', '#27ae60', '#e74c3c']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } }
        }
    });

    // Monthly Trends Chart
    new Chart(document.getElementById('monthlyTrendsChart'), {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($monthlyReceiptsChart['labels'], 15, 512) ?>,
            datasets: [{
                label: 'Receipt Count',
                data: <?php echo json_encode($monthlyReceiptsChart['counts'], 15, 512) ?>,
                backgroundColor: 'rgba(52, 152, 219, 0.8)',
                yAxisID: 'y'
            }, {
                label: 'Total Amount (ZMW)',
                data: <?php echo json_encode($monthlyReceiptsChart['amounts'], 15, 512) ?>,
                backgroundColor: 'rgba(39, 174, 96, 0.8)',
                yAxisID: 'y1'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    beginAtZero: true,
                    ticks: { precision: 0 }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    beginAtZero: true,
                    grid: { drawOnChartArea: false }
                }
            }
        }
    });

    // Payment Method Pie Chart
    new Chart(document.getElementById('methodPieChart'), {
        type: 'pie',
        data: {
            labels: <?php echo json_encode(array_keys($receiptsByMethod), 15, 512) ?>,
            datasets: [{
                data: <?php echo json_encode(array_values($receiptsByMethod), 15, 512) ?>,
                backgroundColor: ['#3498db', '#27ae60', '#1abc9c', '#f39c12', '#e74c3c']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } }
        }
    });

    // Processing Time Chart
    new Chart(document.getElementById('processingTimeChart'), {
        type: 'bar',
        data: {
            labels: <?php echo json_encode(array_keys($processingTimeRanges), 15, 512) ?>,
            datasets: [{
                label: 'Number of Receipts',
                data: <?php echo json_encode(array_values($processingTimeRanges), 15, 512) ?>,
                backgroundColor: ['#27ae60', '#1abc9c', '#f39c12', '#e74c3c']
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
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Bensonngulube\OneDrive - zescozambia\Desktop\Laravel test\resources\views/receipts/reports.blade.php ENDPATH**/ ?>