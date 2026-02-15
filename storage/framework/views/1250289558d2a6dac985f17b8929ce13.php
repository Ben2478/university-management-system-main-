

<?php $__env->startSection('content'); ?>
<div class="container" style="padding: 2rem 0;">
    <div style="margin-bottom: 2rem;">
        <h1 style="margin: 0 0 0.5rem 0;">Upload Payment Receipt</h1>
        <p style="margin: 0; color: #666;">Submit proof of payment for verification</p>
    </div>

    <!-- Payment Summary Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
        <div class="card" style="border-left: 4px solid #3498db; padding: 1.25rem;">
            <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase; font-weight: 600;">Total Tuition Fee</p>
            <h2 style="margin: 0.5rem 0 0 0; color: #3498db; font-size: 2rem;">K<?php echo e(number_format($student->tuition_fee, 2)); ?></h2>
        </div>
        <div class="card" style="border-left: 4px solid #27ae60; padding: 1.25rem;">
            <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase; font-weight: 600;">Amount Paid</p>
            <h2 style="margin: 0.5rem 0 0 0; color: #27ae60; font-size: 2rem;">K<?php echo e(number_format($student->tuition_fee - $balance, 2)); ?></h2>
        </div>
        <div class="card" style="border-left: 4px solid #e74c3c; padding: 1.25rem;">
            <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase; font-weight: 600;">Balance Due</p>
            <h2 style="margin: 0.5rem 0 0 0; color: #e74c3c; font-size: 2rem;">K<?php echo e(number_format($balance, 2)); ?></h2>
        </div>
    </div>

    <?php if($errors->any()): ?>
        <div style="padding: 1rem; margin-bottom: 2rem; background: #f8d7da; border-left: 4px solid #e74c3c; border-radius: 0.25rem;">
            <strong style="color: #721c24;">Please fix the following errors:</strong>
            <ul style="margin: 0.5rem 0 0 0; padding-left: 20px; color: #721c24;">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- Upload Form -->
    <div class="card">
        <h3 style="margin: 0 0 1.5rem 0; color: #2c3e50;">Upload Receipt</h3>
        
        <form action="<?php echo e(route('receipts.store', $student)); ?>" method="POST" enctype="multipart/form-data" id="receiptForm">
            <?php echo csrf_field(); ?>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                <div>
                    <label for="amount" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50;">Amount Paid (K) <span style="color: #e74c3c;">*</span></label>
                    <input type="number" id="amount" name="amount" step="0.01" min="0.01" max="<?php echo e($balance); ?>" required placeholder="0.00" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 0.25rem; font-size: 1rem;" value="<?php echo e(old('amount')); ?>">
                    <small style="color: #666; font-size: 0.875rem;">Maximum: K<?php echo e(number_format($balance, 2)); ?></small>
                </div>

                <div>
                    <label for="payment_date" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50;">Payment Date</label>
                    <input type="date" id="payment_date" name="payment_date" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 0.25rem; font-size: 1rem; background: white;" value="<?php echo e(old('payment_date', date('Y-m-d'))); ?>">
                </div>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label for="receipt_file" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50;">Receipt File <span style="color: #e74c3c;">*</span></label>
                <div style="border: 2px dashed #ddd; border-radius: 0.5rem; padding: 2rem; text-align: center; background: #f8f9fa;">
                    <input type="file" id="receipt_file" name="receipt_file" accept=".pdf,.jpg,.jpeg,.png" required style="display: none;" onchange="displayFileName(this)">
                    <label for="receipt_file" style="cursor: pointer; display: block;">
                        <div style="font-size: 3rem; color: #3498db; margin-bottom: 0.5rem;">📎</div>
                        <p style="margin: 0; color: #666; font-weight: 600;">Click to upload or drag and drop</p>
                        <p style="margin: 0.5rem 0 0 0; color: #999; font-size: 0.875rem;">PDF, JPG, PNG (Max 5MB)</p>
                        <p id="fileName" style="margin: 1rem 0 0 0; color: #27ae60; font-weight: 600;"></p>
                    </label>
                </div>
            </div>

            <div style="display: flex; gap: 1rem;">
                <button type="submit" style="flex: 1; padding: 1rem; background: #27ae60; color: white; border: none; border-radius: 0.5rem; cursor: pointer; font-size: 1rem; font-weight: 600;">
                    ✓ Upload Receipt
                </button>
                <a href="<?php echo e(route('receipts.student-history')); ?>" style="flex: 1; padding: 1rem; background: #95a5a6; color: white; border: none; border-radius: 0.5rem; cursor: pointer; text-decoration: none; text-align: center; font-size: 1rem; font-weight: 600; display: block;">
                    ← Back to History
                </a>
            </div>
        </form>

    </div>

    <!-- Instructions Card -->
    <div class="card" style="background: #e3f2fd; border-left: 4px solid #1976d2;">
        <h3 style="margin: 0 0 1rem 0; color: #0d47a1;">📋 Upload Instructions</h3>
        <ol style="margin: 0; padding-left: 1.5rem; color: #0d47a1; line-height: 2;">
            <li>Take a <strong>clear photo or scan</strong> of your bank receipt or mobile money confirmation</li>
            <li>Ensure the <strong>amount, date, and transaction reference</strong> are clearly visible</li>
            <li>Supported formats: <strong>JPG, PNG, PDF</strong> (maximum 5MB)</li>
            <li>Enter the <strong>exact amount</strong> you paid</li>
            <li>Submit for accountant verification</li>
            <li>You'll receive notification once your receipt is <strong>approved or needs correction</strong></li>
        </ol>
    </div>
</div>

<script>
function displayFileName(input) {
    const fileName = input.files[0]?.name;
    if (fileName) {
        document.getElementById('fileName').textContent = '✓ ' + fileName;
    }
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.student', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Bensonngulube\OneDrive - zescozambia\Desktop\Laravel test\resources\views/receipts/create.blade.php ENDPATH**/ ?>