

<?php $__env->startSection('content'); ?>
<style>
    .card {
        background: white;
        padding: 1.5rem;
        border-radius: 0.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        margin-bottom: 1.5rem;
    }
    
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
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    
    .stat-card.revenue { 
        border-left-color: #10b981; 
        background: linear-gradient(135deg, #ffffff, #d1fae5);
    }
    .stat-card.pending { 
        border-left-color: #f59e0b; 
        background: linear-gradient(135deg, #ffffff, #fef3c7);
    }
    .stat-card.completed { 
        border-left-color: #3b82f6; 
        background: linear-gradient(135deg, #ffffff, #dbeafe);
    }
    .stat-card.failed { 
        border-left-color: #ef4444; 
        background: linear-gradient(135deg, #ffffff, #fee2e2);
    }
    .stat-card.today { 
        border-left-color: #8b5cf6; 
        background: linear-gradient(135deg, #ffffff, #ede9fe);
    }
    
    .stat-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 0.75rem;
    }
    
    .stat-icon {
        font-size: 2rem;
        opacity: 0.8;
    }
    
    .stat-label { 
        color: #6b7280; 
        font-size: 0.75rem; 
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 0.05em;
    }
    
    .stat-value { 
        font-size: 2rem; 
        font-weight: 800; 
        color: #111827;
        line-height: 1;
        margin: 0.5rem 0;
    }
    
    .stat-subtitle { 
        color: #6b7280; 
        font-size: 0.875rem; 
        margin-top: 0.5rem;
    }
    
    .quick-filters {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
        margin-bottom: 1.5rem;
    }
    
    .quick-filter-btn {
        padding: 0.625rem 1.25rem;
        border-radius: 0.5rem;
        border: 2px solid #e5e7eb;
        background: white;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 0.875rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .quick-filter-btn:hover {
        border-color: #3b82f6;
        background: #eff6ff;
        transform: translateY(-1px);
    }
    
    .quick-filter-btn.active {
        background: #3b82f6;
        color: white;
        border-color: #3b82f6;
    }
    
    .quick-filter-btn .badge {
        background: rgba(0,0,0,0.1);
        padding: 0.125rem 0.5rem;
        border-radius: 0.25rem;
        font-size: 0.75rem;
    }
    
    .quick-filter-btn.active .badge {
        background: rgba(255,255,255,0.2);
    }
    
    .filter-section {
        background: white;
        padding: 0;
        border-radius: 0.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        margin-bottom: 1.5rem;
        overflow: hidden;
    }
    
    .filter-header {
        padding: 1rem 1.5rem;
        background: #f9fafb;
        border-bottom: 1px solid #e5e7eb;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: 600;
        color: #374151;
    }
    
    .filter-header:hover {
        background: #f3f4f6;
    }
    
    .filter-content {
        padding: 1.5rem;
        display: none;
    }
    
    .filter-content.active {
        display: block;
    }
    
    .filter-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }
    
    .form-group {
        display: flex;
        flex-direction: column;
    }
    
    .form-group label {
        font-size: 0.875rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
    }
    
    .form-group input,
    .form-group select {
        padding: 0.625rem 0.75rem;
        border: 2px solid #e5e7eb;
        border-radius: 0.375rem;
        font-size: 0.875rem;
        transition: border-color 0.2s;
    }
    
    .form-group input:focus,
    .form-group select:focus {
        outline: none;
        border-color: #3b82f6;
    }
    
    .payment-table {
        background: white;
        border-radius: 0.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        overflow: hidden;
    }
    
    .payment-table table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .payment-table th {
        background: #f9fafb;
        padding: 1rem;
        text-align: left;
        font-size: 0.75rem;
        font-weight: 700;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom: 2px solid #e5e7eb;
    }
    
    .payment-table td {
        padding: 1rem;
        border-bottom: 1px solid #f3f4f6;
    }
    
    .payment-table tr:hover {
        background: #f9fafb;
    }
    
    .payment-table tr:last-child td {
        border-bottom: none;
    }
    
    .status-badge {
        padding: 0.375rem 0.75rem;
        border-radius: 0.375rem;
        font-size: 0.75rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
    }
    
    .status-badge.completed {
        background: #d1fae5;
        color: #065f46;
    }
    
    .status-badge.pending {
        background: #fef3c7;
        color: #92400e;
    }
    
    .status-badge.processing {
        background: #dbeafe;
        color: #1e40af;
    }
    
    .status-badge.failed {
        background: #fee2e2;
        color: #991b1b;
    }
    
    .status-badge.cancelled {
        background: #f3f4f6;
        color: #374151;
    }
    
    .method-badge {
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        font-size: 0.75rem;
        font-weight: 600;
        background: #f3f4f6;
        color: #374151;
    }
    
    .action-btn {
        padding: 0.375rem 0.75rem;
        border-radius: 0.375rem;
        font-size: 0.75rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
        display: inline-block;
        border: none;
        cursor: pointer;
    }
    
    .action-btn.primary {
        background: #3b82f6;
        color: white;
    }
    
    .action-btn.primary:hover {
        background: #2563eb;
    }
    
    .action-btn.secondary {
        background: #f3f4f6;
        color: #374151;
    }
    
    .action-btn.secondary:hover {
        background: #e5e7eb;
    }
    
    .action-btn.success {
        background: #10b981;
        color: white;
    }
    
    .action-btn.success:hover {
        background: #059669;
    }
    
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: #6b7280;
    }
    
    .empty-state-icon {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }
    
    .method-breakdown {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
        margin-top: 1.5rem;
    }
    
    .method-item {
        padding: 1rem;
        background: #f9fafb;
        border-radius: 0.5rem;
        border: 1px solid #e5e7eb;
    }
    
    .method-item-label {
        font-size: 0.75rem;
        color: #6b7280;
        margin-bottom: 0.25rem;
    }
    
    .method-item-value {
        font-size: 1.25rem;
        font-weight: 700;
        color: #111827;
    }
</style>

<div style="margin-bottom: 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
        <div>
            <h1 style="font-size: 1.875rem; font-weight: 800; color: #111827; margin: 0;">💳 Payment Records</h1>
            <p style="color: #6b7280; margin: 0.5rem 0 0 0;">Track and manage all student payment transactions</p>
        </div>
        <div style="display: flex; gap: 0.75rem;">
            <a href="<?php echo e(route('payments.export', request()->query())); ?>" class="action-btn success">
                📊 Export Report
            </a>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div style="padding: 1rem 1.5rem; background: #d1fae5; color: #065f46; border-radius: 0.5rem; margin-bottom: 1.5rem; border-left: 4px solid #10b981;">
            ✓ <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <div style="padding: 1rem 1.5rem; background: #fee2e2; color: #991b1b; border-radius: 0.5rem; margin-bottom: 1.5rem; border-left: 4px solid #ef4444;">
            <strong>⚠️ Errors:</strong>
            <ul style="margin: 0.5rem 0 0 0; padding-left: 1.5rem;">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- Enhanced Payment Stats -->
    <div class="stats-grid">
        <div class="stat-card revenue">
            <div class="stat-header">
                <div class="stat-label">💰 Total Revenue</div>
                <div class="stat-icon">💰</div>
            </div>
            <div class="stat-value">K<?php echo e(number_format($stats['total_revenue'], 0)); ?></div>
            <div class="stat-subtitle"><?php echo e($stats['completed_count']); ?> completed payments</div>
        </div>
        
        <div class="stat-card today">
            <div class="stat-header">
                <div class="stat-label">📅 Today's Revenue</div>
                <div class="stat-icon">📅</div>
            </div>
            <div class="stat-value">K<?php echo e(number_format($stats['today_revenue'], 0)); ?></div>
            <div class="stat-subtitle"><?php echo e($stats['today_count']); ?> payments today</div>
        </div>
        
        <div class="stat-card pending">
            <div class="stat-header">
                <div class="stat-label">⏳ Pending</div>
                <div class="stat-icon">⏳</div>
            </div>
            <div class="stat-value"><?php echo e($stats['pending_count']); ?></div>
            <div class="stat-subtitle">K<?php echo e(number_format($stats['pending_amount'], 0)); ?> pending</div>
        </div>
        
        <div class="stat-card completed">
            <div class="stat-header">
                <div class="stat-label">✅ Success Rate</div>
                <div class="stat-icon">✓</div>
            </div>
            <div class="stat-value"><?php echo e($stats['completed_rate']); ?>%</div>
            <div class="stat-subtitle"><?php echo e($stats['completed_count']); ?> / <?php echo e($stats['total_count']); ?> successful</div>
        </div>
    </div>

    <!-- Payment Method Breakdown -->
    <div class="card">
        <h3 style="margin: 0 0 1rem 0; font-size: 1.125rem; font-weight: 700; color: #111827;">
            💳 Revenue by Payment Method
        </h3>
        <div class="method-breakdown">
            <div class="method-item">
                <div class="method-item-label">🏦 Bank Transfer</div>
                <div class="method-item-value">K<?php echo e(number_format($stats['by_method']['bank_transfer'], 0)); ?></div>
            </div>
            <div class="method-item">
                <div class="method-item-label">📱 Mobile Money</div>
                <div class="method-item-value">K<?php echo e(number_format($stats['by_method']['mobile_money'], 0)); ?></div>
            </div>
            <div class="method-item">
                <div class="method-item-label">💵 Cash</div>
                <div class="method-item-value">K<?php echo e(number_format($stats['by_method']['cash'], 0)); ?></div>
            </div>
            <div class="method-item">
                <div class="method-item-label">💳 Card</div>
                <div class="method-item-value">K<?php echo e(number_format($stats['by_method']['card'], 0)); ?></div>
            </div>
            <div class="method-item">
                <div class="method-item-label">🌐 Zananco</div>
                <div class="method-item-value">K<?php echo e(number_format($stats['by_method']['zananco_billmaster'], 0)); ?></div>
            </div>
        </div>
    </div>

    <!-- Quick Status Filters -->
    <div class="quick-filters">
        <button class="quick-filter-btn <?php echo e(!request('status') ? 'active' : ''); ?>" 
                onclick="window.location.href='<?php echo e(route('payments.index', array_merge(request()->except(['status', 'page']), []))); ?>'">
            📊 All Payments
            <span class="badge"><?php echo e($stats['total_count']); ?></span>
        </button>
        <button class="quick-filter-btn <?php echo e(request('status') == 'completed' ? 'active' : ''); ?>" 
                onclick="window.location.href='<?php echo e(route('payments.index', array_merge(request()->except('page'), ['status' => 'completed']))); ?>'">
            ✓ Completed
            <span class="badge"><?php echo e($stats['completed_count']); ?></span>
        </button>
        <button class="quick-filter-btn <?php echo e(request('status') == 'pending' ? 'active' : ''); ?>" 
                onclick="window.location.href='<?php echo e(route('payments.index', array_merge(request()->except('page'), ['status' => 'pending']))); ?>'">
            ⏳ Pending
            <span class="badge"><?php echo e($stats['pending_count']); ?></span>
        </button>
        <button class="quick-filter-btn <?php echo e(request('status') == 'failed' ? 'active' : ''); ?>" 
                onclick="window.location.href='<?php echo e(route('payments.index', array_merge(request()->except('page'), ['status' => 'failed']))); ?>'">
            ✗ Failed
            <span class="badge"><?php echo e($stats['failed_count']); ?></span>
        </button>
        
        <!-- Quick Date Filters -->
        <div style="margin-left: auto; display: flex; gap: 0.5rem;">
            <button class="quick-filter-btn <?php echo e(request('date_from') == now()->toDateString() ? 'active' : ''); ?>" 
                    onclick="window.location.href='<?php echo e(route('payments.index', array_merge(request()->except('page'), ['date_from' => now()->toDateString(), 'date_to' => now()->toDateString()]))); ?>'">
                📅 Today
            </button>
            <button class="quick-filter-btn <?php echo e(request('date_from') == now()->startOfWeek()->toDateString() && request('date_to') == now()->endOfWeek()->toDateString() ? 'active' : ''); ?>" 
                    onclick="window.location.href='<?php echo e(route('payments.index', array_merge(request()->except('page'), ['date_from' => now()->startOfWeek()->toDateString(), 'date_to' => now()->endOfWeek()->toDateString()]))); ?>'">
                📅 This Week
            </button>
            <button class="quick-filter-btn <?php echo e(request('date_from') == now()->startOfMonth()->toDateString() ? 'active' : ''); ?>" 
                    onclick="window.location.href='<?php echo e(route('payments.index', array_merge(request()->except('page'), ['date_from' => now()->startOfMonth()->toDateString(), 'date_to' => now()->endOfMonth()->toDateString()]))); ?>'">
                📅 This Month
            </button>
        </div>
    </div>

    <!-- Advanced Filters (Collapsible) -->
    <div class="filter-section">
        <div class="filter-header" onclick="toggleFilters()">
            <span>🔍 Advanced Filters & Search</span>
            <span id="filter-toggle">▼</span>
        </div>
        <div class="filter-content" id="filter-content">
            <form method="GET" action="<?php echo e(route('payments.index')); ?>">
                <div class="filter-grid">
                    <div class="form-group">
                        <label>🔎 Search</label>
                        <input type="text" name="search" value="<?php echo e(request('search')); ?>" 
                               placeholder="Student, Reference #, Transaction ID...">
                    </div>
                    <div class="form-group">
                        <label>📊 Status</label>
                        <select name="status">
                            <option value="">All Statuses</option>
                            <option value="pending" <?php echo e(request('status') == 'pending' ? 'selected' : ''); ?>>⏳ Pending</option>
                            <option value="processing" <?php echo e(request('status') == 'processing' ? 'selected' : ''); ?>>🔄 Processing</option>
                            <option value="completed" <?php echo e(request('status') == 'completed' ? 'selected' : ''); ?>>✓ Completed</option>
                            <option value="failed" <?php echo e(request('status') == 'failed' ? 'selected' : ''); ?>>✗ Failed</option>
                            <option value="cancelled" <?php echo e(request('status') == 'cancelled' ? 'selected' : ''); ?>>⊗ Cancelled</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>💳 Payment Method</label>
                        <select name="payment_method">
                            <option value="">All Methods</option>
                            <option value="bank_transfer" <?php echo e(request('payment_method') == 'bank_transfer' ? 'selected' : ''); ?>>🏦 Bank Transfer</option>
                            <option value="mobile_money" <?php echo e(request('payment_method') == 'mobile_money' ? 'selected' : ''); ?>>📱 Mobile Money</option>
                            <option value="cash" <?php echo e(request('payment_method') == 'cash' ? 'selected' : ''); ?>>💵 Cash</option>
                            <option value="card" <?php echo e(request('payment_method') == 'card' ? 'selected' : ''); ?>>💳 Card</option>
                            <option value="zananco_billmaster" <?php echo e(request('payment_method') == 'zananco_billmaster' ? 'selected' : ''); ?>>🌐 Zananco</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>🎓 Program</label>
                        <select name="program">
                            <option value="">All Programs</option>
                            <?php $__currentLoopData = $programs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $program): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($program); ?>" <?php echo e(request('program') == $program ? 'selected' : ''); ?>><?php echo e($program); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>📅 Date From</label>
                        <input type="date" name="date_from" value="<?php echo e(request('date_from')); ?>">
                    </div>
                    <div class="form-group">
                        <label>📅 Date To</label>
                        <input type="date" name="date_to" value="<?php echo e(request('date_to')); ?>">
                    </div>
                    <div class="form-group">
                        <label>💰 Min Amount (K)</label>
                        <input type="number" step="0.01" name="amount_min" value="<?php echo e(request('amount_min')); ?>" 
                               placeholder="0.00">
                    </div>
                    <div class="form-group">
                        <label>💰 Max Amount (K)</label>
                        <input type="number" step="0.01" name="amount_max" value="<?php echo e(request('amount_max')); ?>" 
                               placeholder="10000.00">
                    </div>
                </div>
                <div style="margin-top: 1.5rem; display: flex; gap: 0.75rem;">
                    <button type="submit" class="action-btn primary">
                        Apply Filters
                    </button>
                    <a href="<?php echo e(route('payments.index')); ?>" class="action-btn secondary">
                        Clear All
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Payments Table -->
    <div class="payment-table">
        <?php if($payments->count() > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Reference #</th>
                        <th>Student</th>
                        <th>Program</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Date/Time</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td>
                                <span style="font-family: monospace; font-size: 0.75rem; color: #6b7280; display: block;">
                                    <?php echo e($payment->reference_number); ?>

                                </span>
                                <?php if($payment->transaction_id): ?>
                                    <span style="font-family: monospace; font-size: 0.625rem; color: #9ca3af; display: block; margin-top: 0.25rem;">
                                        TXN: <?php echo e($payment->transaction_id); ?>

                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div>
                                    <p style="font-weight: 600; color: #111827; margin: 0;">
                                        <?php echo e($payment->student->first_name); ?> <?php echo e($payment->student->last_name); ?>

                                    </p>
                                    <p style="font-size: 0.75rem; color: #6b7280; margin: 0.25rem 0 0 0;">
                                        <?php echo e($payment->student->student_id); ?>

                                    </p>
                                </div>
                            </td>
                            <td>
                                <span style="font-size: 0.875rem; color: #374151;">
                                    <?php echo e($payment->student->program ?? 'N/A'); ?>

                                </span>
                            </td>
                            <td>
                                <span style="font-weight: 700; color: #111827; font-size: 1rem;">
                                    K<?php echo e(number_format($payment->amount, 2)); ?>

                                </span>
                            </td>
                            <td>
                                <span class="method-badge">
                                    <?php if($payment->payment_method === 'bank_transfer'): ?> 🏦
                                    <?php elseif($payment->payment_method === 'mobile_money'): ?> 📱
                                    <?php elseif($payment->payment_method === 'cash'): ?> 💵
                                    <?php elseif($payment->payment_method === 'card'): ?> 💳
                                    <?php elseif($payment->payment_method === 'zananco_billmaster'): ?> 🌐
                                    <?php endif; ?>
                                    <?php echo e(ucfirst(str_replace('_', ' ', $payment->payment_method))); ?>

                                </span>
                            </td>
                            <td>
                                <span style="font-size: 0.875rem; color: #374151;">
                                    <?php echo e(Str::limit($payment->description, 30)); ?>

                                </span>
                            </td>
                            <td>
                                <span class="status-badge <?php echo e($payment->status); ?>">
                                    <?php if($payment->status === 'completed'): ?> ✓
                                    <?php elseif($payment->status === 'pending'): ?> ⏳
                                    <?php elseif($payment->status === 'processing'): ?> 🔄
                                    <?php elseif($payment->status === 'failed'): ?> ✗
                                    <?php else: ?> ⊗
                                    <?php endif; ?>
                                    <?php echo e(ucfirst($payment->status)); ?>

                                </span>
                            </td>
                            <td>
                                <div style="font-size: 0.875rem; color: #374151;">
                                    <?php echo e($payment->created_at->format('M d, Y')); ?>

                                </div>
                                <div style="font-size: 0.75rem; color: #6b7280;">
                                    <?php echo e($payment->created_at->format('h:i A')); ?>

                                </div>
                                <div style="font-size: 0.75rem; color: #9ca3af; margin-top: 0.25rem;">
                                    <?php echo e($payment->created_at->diffForHumans()); ?>

                                </div>
                            </td>
                            <td>
                                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                    <a href="<?php echo e(route('payments.show', $payment)); ?>" class="action-btn primary">
                                        👁️ View
                                    </a>
                                    <?php if($payment->status !== 'completed'): ?>
                                        <form action="<?php echo e(route('payments.verify', $payment)); ?>" method="POST" style="display: inline;">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="action-btn success">
                                                ✓ Verify
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="empty-state">
                <div class="empty-state-icon">💳</div>
                <h3 style="font-size: 1.25rem; font-weight: 700; color: #374151; margin: 0 0 0.5rem 0;">
                    No Payments Found
                </h3>
                <p style="margin: 0; color: #6b7280;">
                    <?php if(request()->hasAny(['search', 'status', 'payment_method', 'program', 'date_from', 'amount_min'])): ?>
                        Try adjusting your filters or search terms.
                    <?php else: ?>
                        No payment records available.
                    <?php endif; ?>
                </p>
                <?php if(request()->hasAny(['search', 'status', 'payment_method', 'program', 'date_from', 'amount_min'])): ?>
                    <a href="<?php echo e(route('payments.index')); ?>" class="action-btn primary" style="margin-top: 1rem;">
                        Clear Filters
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <?php if($payments->hasPages()): ?>
        <div style="margin-top: 1.5rem; display: flex; justify-content: center;">
            <?php echo e($payments->links()); ?>

        </div>
    <?php endif; ?>
</div>

<script>
function toggleFilters() {
    const content = document.getElementById('filter-content');
    const toggle = document.getElementById('filter-toggle');
    content.classList.toggle('active');
    toggle.textContent = content.classList.contains('active') ? '▲' : '▼';
}

// Auto-expand filters if any filter is active
<?php if(request()->hasAny(['search', 'status', 'payment_method', 'program', 'date_from', 'date_to', 'amount_min', 'amount_max'])): ?>
    document.addEventListener('DOMContentLoaded', function() {
        toggleFilters();
    });
<?php endif; ?>
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make(auth()->user()->isSuperAdmin() ? 'layouts.super-admin' : 'layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Bensonngulube\OneDrive - zescozambia\Desktop\Laravel test\resources\views/payments/index.blade.php ENDPATH**/ ?>