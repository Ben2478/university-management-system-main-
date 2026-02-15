

<?php $__env->startSection('title', 'Accountant Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<style>
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
        .chart-grid {
            grid-template-columns: 1fr;
        }
        .stats-grid {
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        }
    }
</style>

<div class="container">
    <!-- Header -->
    <div style="margin-bottom: 2rem;">
        <h1 style="margin-bottom: 0.5rem;">💼 Accountant Dashboard Overview</h1>
        <p style="color: #7f8c8d; margin: 0;">Welcome back! Here's your financial summary for today</p>
    </div>

    <!-- Alerts -->
    <?php if(isset($alerts) && count($alerts) > 0): ?>
        <?php $__currentLoopData = $alerts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $alert): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="alert-box <?php echo e($alert['type']); ?>">
            <span style="font-size: 1.5rem;"><?php echo e($alert['icon']); ?></span>
            <div style="flex: 1;">
                <strong><?php echo e($alert['message']); ?></strong>
            </div>
            <a href="<?php echo e($alert['link']); ?>" class="btn btn-sm btn-primary">View</a>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>

    <!-- Key Stats -->
    <div class="stats-grid">
        <div class="stat-card danger">
            <div class="stat-label">Pending Reviews</div>
            <div class="stat-value"><?php echo e($stats['pending_receipts']); ?></div>
            <div class="stat-subtext">ZMW <?php echo e(number_format($stats['pending_amount'], 0)); ?></div>
        </div>

        <div class="stat-card success">
            <div class="stat-label">Today's Revenue</div>
            <div class="stat-value">ZMW <?php echo e(number_format($stats['today_revenue'], 0)); ?></div>
            <div class="stat-subtext"><?php echo e($stats['approved_today']); ?> receipts approved</div>
        </div>

        <div class="stat-card primary">
            <div class="stat-label">Total Collected</div>
            <div class="stat-value">ZMW <?php echo e(number_format($stats['total_collected'], 0)); ?></div>
            <div class="stat-subtext"><?php echo e($stats['total_students']); ?> students</div>
        </div>

        <div class="stat-card warning">
            <div class="stat-label">Outstanding Balance</div>
            <div class="stat-value">ZMW <?php echo e(number_format($stats['outstanding_balance'], 0)); ?></div>
            <div class="stat-subtext"><?php echo e($stats['students_with_balance']); ?> students owing</div>
        </div>

        <div class="stat-card info">
            <div class="stat-label">This Month</div>
            <div class="stat-value">ZMW <?php echo e(number_format($stats['this_month_revenue'], 0)); ?></div>
            <div class="stat-subtext"><?php echo e($stats['approval_rate']); ?>% approval rate</div>
        </div>

        <div class="stat-card success">
            <div class="stat-label">Fully Paid</div>
            <div class="stat-value"><?php echo e($stats['fully_paid']); ?></div>
            <div class="stat-subtext"><?php echo e(round(($stats['fully_paid']/$stats['total_students'])*100, 1)); ?>% of students</div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions">
        <a href="<?php echo e(route('receipts.pending')); ?>" class="action-btn">
            <div class="action-icon">📋</div>
            <div style="font-weight: 600;">Review Receipts</div>
            <div style="font-size: 0.75rem; color: #7f8c8d; margin-top: 0.25rem;"><?php echo e($stats['pending_receipts']); ?> pending</div>
        </a>
        
        <a href="<?php echo e(route('receipts.reports')); ?>" class="action-btn">
            <div class="action-icon">📊</div>
            <div style="font-weight: 600;">Receipt Analytics</div>
            <div style="font-size: 0.75rem; color: #7f8c8d; margin-top: 0.25rem;">View trends</div>
        </a>
        
        <a href="<?php echo e(route('payments.reports')); ?>" class="action-btn">
            <div class="action-icon">💰</div>
            <div style="font-weight: 600;">Payment Analytics</div>
            <div style="font-size: 0.75rem; color: #7f8c8d; margin-top: 0.25rem;">Revenue insights</div>
        </a>
        
        <a href="<?php echo e(route('students.index')); ?>" class="action-btn">
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
                <span class="badge success">ZMW <?php echo e(number_format(array_sum($daily_revenue), 0)); ?></span>
            </div>
            <canvas id="dailyRevenueChart" style="max-height: 250px;"></canvas>
        </div>

        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title">💳 Revenue by Method</div>
            </div>
            <canvas id="methodPieChart" style="max-height: 200px;"></canvas>
            <div class="method-list">
                <?php $__currentLoopData = $revenue_by_method; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $method => $amount): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="method-item">
                    <span><?php echo e($method); ?></span>
                    <strong>ZMW <?php echo e(number_format($amount, 0)); ?></strong>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>

    <!-- Receipt Status & Owing Programs -->
    <div class="chart-grid">
        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title">📋 Receipt Status Breakdown</div>
            </div>
            <canvas id="receiptStatusChart" style="max-height: 250px;"></canvas>
            <div class="method-list">
                <?php $__currentLoopData = $receipt_status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status => $count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="method-item">
                    <span><?php echo e($status); ?></span>
                    <strong><?php echo e($count); ?></strong>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title">🎓 Students Owing by Program</div>
            </div>
            <canvas id="owingProgramChart" style="max-height: 250px;"></canvas>
            <?php if($owing_by_program->count() > 0): ?>
            <div class="method-list">
                <?php $__currentLoopData = $owing_by_program; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $program => $count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="method-item">
                    <span><?php echo e($program); ?></span>
                    <strong><?php echo e($count); ?> students</strong>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Pending Receipts Table -->
    <div class="chart-card" style="margin-bottom: 2rem;">
        <div class="chart-header">
            <div class="chart-title">⏳ Pending Receipts Requiring Review</div>
            <a href="<?php echo e(route('receipts.pending')); ?>" class="btn btn-primary btn-sm">View All</a>
        </div>

        <?php if($pending_receipts->isEmpty()): ?>
            <p style="text-align: center; color: #7f8c8d; padding: 2rem 0;">✅ No pending receipts at this time!</p>
        <?php else: ?>
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
                    <?php $__currentLoopData = $pending_receipts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $receipt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $waitingHours = now()->diffInHours($receipt->uploaded_at);
                        $waitingClass = $waitingHours > 72 ? 'danger' : ($waitingHours > 48 ? 'warning' : 'info');
                    ?>
                    <tr>
                        <td>
                            <strong><?php echo e($receipt->student->first_name); ?> <?php echo e($receipt->student->last_name); ?></strong><br>
                            <small style="color: #7f8c8d;"><?php echo e($receipt->student->student_id); ?></small>
                        </td>
                        <td><strong>ZMW <?php echo e(number_format($receipt->amount, 2)); ?></strong></td>
                        <td>
                            <span class="badge info"><?php echo e(ucfirst(str_replace('_', ' ', $receipt->payment_method))); ?></span>
                        </td>
                        <td><?php echo e($receipt->uploaded_at->format('M d, Y')); ?><br><small style="color: #7f8c8d;"><?php echo e($receipt->uploaded_at->format('H:i')); ?></small></td>
                        <td><span class="badge <?php echo e($waitingClass); ?>"><?php echo e($waitingHours); ?>h</span></td>
                        <td>
                            <a href="<?php echo e(route('receipts.show', $receipt->id)); ?>" class="btn btn-sm btn-primary">Review</a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <!-- Recent Approved Receipts -->
    <?php if(isset($recent_approved) && $recent_approved->count() > 0): ?>
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
                <?php $__currentLoopData = $recent_approved; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $receipt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td>
                        <strong><?php echo e($receipt->student->first_name); ?> <?php echo e($receipt->student->last_name); ?></strong><br>
                        <small style="color: #7f8c8d;"><?php echo e($receipt->student->student_id); ?></small>
                    </td>
                    <td><strong>ZMW <?php echo e(number_format($receipt->amount, 2)); ?></strong></td>
                    <td><?php echo e($receipt->reviewer->name ?? 'System'); ?></td>
                    <td><?php echo e($receipt->reviewed_at->format('M d, Y H:i')); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Daily Revenue Chart
    new Chart(document.getElementById('dailyRevenueChart'), {
        type: 'line',
        data: {
            labels: <?php echo json_encode($daily_labels, 15, 512) ?>,
            datasets: [{
                label: 'Revenue (ZMW)',
                data: <?php echo json_encode($daily_revenue, 15, 512) ?>,
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
    new Chart(document.getElementById('methodPieChart'), {
        type: 'doughnut',
        data: {
            labels: <?php echo json_encode(array_keys($revenue_by_method), 15, 512) ?>,
            datasets: [{
                data: <?php echo json_encode(array_values($revenue_by_method), 15, 512) ?>,
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
    new Chart(document.getElementById('receiptStatusChart'), {
        type: 'bar',
        data: {
            labels: <?php echo json_encode(array_keys($receipt_status), 15, 512) ?>,
            datasets: [{
                label: 'Receipts',
                data: <?php echo json_encode(array_values($receipt_status), 15, 512) ?>,
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
    new Chart(document.getElementById('owingProgramChart'), {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($owing_by_program->keys()->toArray(), 15, 512) ?>,
            datasets: [{
                label: 'Students',
                data: <?php echo json_encode($owing_by_program->values()->toArray(), 15, 512) ?>,
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Bensonngulube\OneDrive - zescozambia\Desktop\Laravel test\resources\views/dashboards/admin.blade.php ENDPATH**/ ?>