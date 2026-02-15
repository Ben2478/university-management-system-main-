

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="card">
        <a href="<?php echo e(route('receipts.index')); ?>" style="color: #3498db; text-decoration: none;">← Back to Receipts</a>
        <h2 style="margin-top: 1rem;">Review Receipt</h2>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin: 2rem 0;">
            <!-- Receipt Details -->
            <div>
                <h4>Receipt Details</h4>
                <p><strong>Student:</strong> <?php echo e($receipt->student->first_name); ?> <?php echo e($receipt->student->last_name); ?></p>
                <p><strong>Student ID:</strong> <?php echo e($receipt->student->student_id); ?></p>
                <p><strong>Amount Claimed:</strong> K<?php echo e(number_format($receipt->amount, 2)); ?></p>
                <p><strong>File:</strong> <?php echo e($receipt->original_filename); ?></p>
                <p><strong>Uploaded:</strong> <?php echo e($receipt->uploaded_at->format('M d, Y H:i')); ?></p>
                <p style="margin-top: 1rem;"><a href="<?php echo e(asset('storage/' . $receipt->file_path)); ?>" target="_blank" class="btn">View Receipt File</a></p>
            </div>

            <!-- Student Balance Info -->
            <div>
                <h4>Student Account</h4>
                <p><strong>Tuition Fee:</strong> K<?php echo e(number_format($receipt->student->tuition_fee, 2)); ?></p>
                <p><strong>Already Paid:</strong> K<?php echo e(number_format($receipt->student->getPaidAmount(), 2)); ?></p>
                <p><strong>Current Balance:</strong> K<?php echo e(number_format($receipt->student->getBalance(), 2)); ?></p>
                <p style="margin-top: 1rem; font-size: 0.9rem; color: #666;">
                    If approved, new balance will be: <strong>K<?php echo e(number_format($receipt->student->getBalance() - $receipt->amount, 2)); ?></strong>
                </p>
            </div>
        </div>

        <?php if($receipt->isPending()): ?>
            <div style="border-top: 2px solid #ddd; padding-top: 2rem; margin-top: 2rem;">
                <h3>Action Required</h3>

                <!-- Approve Form -->
                <div style="background: #d4edda; padding: 1.5rem; border-radius: 8px; margin-bottom: 1rem;">
                    <h4 style="color: #155724; margin-top: 0;">✓ Approve Receipt</h4>
                    <form action="<?php echo e(route('receipts.approve', $receipt)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="form-group">
                            <label for="notes">Notes (Optional)</label>
                            <textarea id="notes" name="notes" placeholder="e.g., Receipt verified, amount matches claimed" style="height: 100px;"></textarea>
                        </div>
                        <button type="submit" class="btn btn-success">Approve Receipt</button>
                    </form>
                </div>

                <!-- Reject Form -->
                <div style="background: #f8d7da; padding: 1.5rem; border-radius: 8px;">
                    <h4 style="color: #721c24; margin-top: 0;">✗ Reject Receipt</h4>
                    <form action="<?php echo e(route('receipts.reject', $receipt)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="form-group">
                            <label for="reason">Reason for Rejection <span style="color: #e74c3c;">*</span></label>
                            <textarea id="reason" name="reason" placeholder="e.g., Amount doesn't match, Receipt unclear, Duplicate submission" required style="height: 100px;"></textarea>
                            <small style="color: #666;">This reason will be shown to the student.</small>
                        </div>
                        <button type="submit" class="btn btn-danger">Reject Receipt</button>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <div style="border-top: 2px solid #ddd; padding-top: 1.5rem; margin-top: 2rem;">
                <h4>Review History</h4>
                <p><strong>Status:</strong> 
                    <span class="badge 
                        <?php if($receipt->status === 'approved'): ?> badge-success
                        <?php else: ?> badge-danger
                        <?php endif; ?>">
                        <?php echo e(ucfirst($receipt->status)); ?>

                    </span>
                </p>
                <p><strong>Reviewed By:</strong> <?php echo e($receipt->reviewer->name ?? 'Unknown'); ?></p>
                <p><strong>Reviewed At:</strong> <?php echo e($receipt->reviewed_at->format('M d, Y H:i')); ?></p>
                <p><strong>Notes:</strong> <?php echo e($receipt->accountant_notes ?: 'No notes provided'); ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Bensonngulube\OneDrive - zescozambia\Desktop\Laravel test\resources\views/receipts/pending.blade.php ENDPATH**/ ?>