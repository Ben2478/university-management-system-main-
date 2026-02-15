

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <a href="<?php echo e(route('payments.index')); ?>" class="text-blue-600 hover:text-blue-900">&larr; Back to Payments</a>
        <h1 class="text-3xl font-bold text-gray-900 mt-4">Payment Details</h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Payment Info -->
        <div class="md:col-span-2 bg-white rounded-lg shadow p-8">
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Payment Information</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Student</p>
                        <p class="text-lg font-medium text-gray-900">
                            <?php echo e($payment->student->first_name); ?> <?php echo e($payment->student->last_name); ?>

                        </p>
                        <p class="text-sm text-gray-500"><?php echo e($payment->student->student_id); ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Amount</p>
                        <p class="text-lg font-bold text-gray-900">K<?php echo e(number_format($payment->amount, 2)); ?></p>
                    </div>
                </div>
            </div>

            <hr class="my-6">

            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Transaction Details</h2>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Reference Number:</span>
                        <span class="text-sm font-mono text-gray-900"><?php echo e($payment->reference_number); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Transaction ID:</span>
                        <span class="text-sm font-mono text-gray-900"><?php echo e($payment->transaction_id ?? 'Pending'); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Payment Method:</span>
                        <span class="text-sm text-gray-900"><?php echo e(ucfirst(str_replace('_', ' ', $payment->payment_method))); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Status:</span>
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                            <?php if($payment->status === 'completed'): ?> bg-green-100 text-green-800
                            <?php elseif($payment->status === 'pending'): ?> bg-yellow-100 text-yellow-800
                            <?php elseif($payment->status === 'processing'): ?> bg-blue-100 text-blue-800
                            <?php else: ?> bg-red-100 text-red-800
                            <?php endif; ?>">
                            <?php echo e(ucfirst($payment->status)); ?>

                        </span>
                    </div>
                </div>
            </div>

            <hr class="my-6">

            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Description</h2>
                <p class="text-gray-700"><?php echo e($payment->description); ?></p>
            </div>

            <?php if($payment->notes): ?>
                <hr class="my-6">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Notes</h2>
                    <p class="text-gray-700"><?php echo e($payment->notes); ?></p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Timeline -->
        <div class="bg-white rounded-lg shadow p-8">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Timeline</h2>
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-600">Created</p>
                    <p class="text-sm font-medium text-gray-900"><?php echo e($payment->created_at->format('M d, Y H:i')); ?></p>
                </div>

                <div>
                    <p class="text-sm text-gray-600">Updated</p>
                    <p class="text-sm font-medium text-gray-900"><?php echo e($payment->updated_at->format('M d, Y H:i')); ?></p>
                </div>

                <?php if($payment->paid_at): ?>
                    <div>
                        <p class="text-sm text-gray-600">Paid</p>
                        <p class="text-sm font-medium text-gray-900"><?php echo e($payment->paid_at->format('M d, Y H:i')); ?></p>
                    </div>
                <?php endif; ?>
            </div>

            <?php if($payment->status !== 'completed'): ?>
                <form action="<?php echo e(route('payments.verify', $payment)); ?>" method="POST" class="mt-6">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="w-full bg-green-600 text-white py-2 rounded-md font-medium hover:bg-green-700">
                        Verify Payment
                    </button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <?php if($payment->gateway_response): ?>
        <div class="mt-6 bg-white rounded-lg shadow p-8">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Gateway Response</h2>
            <pre class="bg-gray-100 p-4 rounded text-xs overflow-auto"><?php echo e(json_encode($payment->gateway_response, JSON_PRETTY_PRINT)); ?></pre>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Bensonngulube\OneDrive - zescozambia\Desktop\Laravel test\resources\views/payments/show.blade.php ENDPATH**/ ?>