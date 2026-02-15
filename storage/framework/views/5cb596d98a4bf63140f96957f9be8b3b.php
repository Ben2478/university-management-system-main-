

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <a href="<?php echo e(route('users.index')); ?>" class="text-blue-600 hover:text-blue-900">&larr; Back to Users</a>
        <h1 class="text-3xl font-bold text-gray-900 mt-4">User Details</h1>
    </div>

    <?php if(session('success')): ?>
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- User Info Card -->
        <div class="md:col-span-2 bg-white rounded-lg shadow p-8">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900"><?php echo e($user->name); ?></h2>
                    <p class="text-gray-600"><?php echo e($user->email); ?></p>
                </div>
                <span class="px-4 py-2 rounded-full text-sm font-semibold
                    <?php if($user->role === 'super_admin'): ?> bg-purple-100 text-purple-800
                    <?php elseif($user->role === 'admin'): ?> bg-green-100 text-green-800
                    <?php elseif($user->role === 'student'): ?> bg-yellow-100 text-yellow-800
                    <?php else: ?> bg-pink-100 text-pink-800
                    <?php endif; ?>">
                    <?php echo e(ucfirst(str_replace('_', ' ', $user->role))); ?>

                </span>
            </div>

            <hr class="my-6">

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-600">User ID</p>
                    <p class="text-lg font-medium text-gray-900">#<?php echo e($user->id); ?></p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Phone</p>
                    <p class="text-lg font-medium text-gray-900"><?php echo e($user->phone ?? 'Not provided'); ?></p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Status</p>
                    <p class="text-lg font-medium">
                        <?php if($user->deleted_at): ?>
                            <span class="text-red-600">Inactive</span>
                        <?php else: ?>
                            <span class="text-green-600">Active</span>
                        <?php endif; ?>
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Account Age</p>
                    <p class="text-lg font-medium text-gray-900"><?php echo e($activityStats['account_age_days']); ?> days</p>
                </div>
            </div>

            <?php if($user->address): ?>
                <hr class="my-6">
                <div>
                    <p class="text-sm text-gray-600">Address</p>
                    <p class="text-gray-900 mt-1"><?php echo e($user->address); ?></p>
                </div>
            <?php endif; ?>

            <!-- Related Student Record -->
            <?php if($user->role === 'student' && $user->student): ?>
                <hr class="my-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">📚 Student Record</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Student ID</p>
                            <p class="font-medium"><?php echo e($user->student->student_id); ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Program</p>
                            <p class="font-medium"><?php echo e($user->student->program ?? 'N/A'); ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Year/Semester</p>
                            <p class="font-medium"><?php echo e($user->student->year ?? 'N/A'); ?> / <?php echo e($user->student->semester ?? 'N/A'); ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Status</p>
                            <p class="font-medium"><?php echo e(ucfirst($user->student->status)); ?></p>
                        </div>
                    </div>
                    <a href="<?php echo e(route('students.show', $user->student)); ?>" class="inline-block mt-4 text-blue-600 hover:text-blue-900">
                        View Full Student Profile →
                    </a>
                </div>
            <?php endif; ?>

            <!-- Children (if parent) -->
            <?php if($user->role === 'parent' && $user->childrenAsParent->count() > 0): ?>
                <hr class="my-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">👨‍👩‍👧‍👦 Children</h3>
                    <div class="space-y-3">
                        <?php $__currentLoopData = $user->childrenAsParent; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                                <div>
                                    <p class="font-medium"><?php echo e($child->first_name); ?> <?php echo e($child->last_name); ?></p>
                                    <p class="text-sm text-gray-600"><?php echo e($child->student_id); ?> - <?php echo e($child->program ?? 'N/A'); ?></p>
                                </div>
                                <a href="<?php echo e(route('students.show', $child)); ?>" class="text-blue-600 hover:text-blue-900 text-sm">
                                    View Profile →
                                </a>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Activity & Actions Sidebar -->
        <div class="space-y-6">
            <!-- Activity Stats -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">📊 Activity</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-600">Registered</p>
                        <p class="font-medium"><?php echo e($user->created_at->format('M d, Y')); ?></p>
                        <p class="text-xs text-gray-500"><?php echo e($user->created_at->diffForHumans()); ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Last Updated</p>
                        <p class="font-medium"><?php echo e($user->updated_at->format('M d, Y')); ?></p>
                        <p class="text-xs text-gray-500"><?php echo e($user->updated_at->diffForHumans()); ?></p>
                    </div>
                    <?php if($user->deleted_at): ?>
                        <div>
                            <p class="text-sm text-gray-600">Deactivated</p>
                            <p class="font-medium text-red-600"><?php echo e($user->deleted_at->format('M d, Y')); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">⚙️ Actions</h3>
                <div class="space-y-3">
                    <?php if(!$user->deleted_at): ?>
                        <a href="<?php echo e(route('users.edit', $user)); ?>" class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                            Edit User
                        </a>
                        
                        <!-- Password Reset Button -->
                        <button onclick="document.getElementById('reset-password-modal').classList.remove('hidden')" 
                                class="block w-full text-center bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md">
                            Reset Password
                        </button>

                        <?php if($user->id !== auth()->id()): ?>
                            <form action="<?php echo e(route('users.destroy', $user)); ?>" method="POST" 
                                  onsubmit="return confirm('Are you sure you want to deactivate this user?');">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="block w-full text-center bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md">
                                    Deactivate User
                                </button>
                            </form>
                        <?php endif; ?>
                    <?php else: ?>
                        <form action="<?php echo e(route('users.restore', $user->id)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="block w-full text-center bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md">
                                Restore User
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Password Reset Modal -->
<div id="reset-password-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-8 max-w-md w-full mx-4">
        <h3 class="text-xl font-bold text-gray-900 mb-4">Reset Password for <?php echo e($user->name); ?></h3>
        <form action="<?php echo e(route('users.reset-password', $user)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                <input type="password" name="new_password" required minlength="8"
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                <input type="password" name="new_password_confirmation" required minlength="8"
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="flex gap-3">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-md">
                    Reset Password
                </button>
                <button type="button" onclick="document.getElementById('reset-password-modal').classList.add('hidden')" 
                        class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-md">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Bensonngulube\OneDrive - zescozambia\Desktop\Laravel test\resources\views/users/show.blade.php ENDPATH**/ ?>