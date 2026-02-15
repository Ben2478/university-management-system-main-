

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
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border-left: 4px solid;
    }
    .stat-card.total { border-left-color: #3b82f6; }
    .stat-card.super-admin { border-left-color: #8b5cf6; }
    .stat-card.admin { border-left-color: #10b981; }
    .stat-card.student { border-left-color: #f59e0b; }
    .stat-card.parent { border-left-color: #ec4899; }
    .stat-card.inactive { border-left-color: #ef4444; }
    .stat-label { color: #6b7280; font-size: 0.875rem; margin-bottom: 0.5rem; }
    .stat-value { font-size: 1.875rem; font-weight: bold; color: #111827; }
    
    .filter-section {
        background: white;
        padding: 1.5rem;
        border-radius: 0.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
    }
    .filter-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-top: 1rem;
    }
    
    .role-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
    }
    .role-super-admin { background: #ede9fe; color: #7c3aed; }
    .role-admin { background: #d1fae5; color: #059669; }
    .role-student { background: #fef3c7; color: #d97706; }
    .role-parent { background: #fce7f3; color: #db2777; }
</style>

<div class="container mx-auto px-4 py-8">
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">User Management</h1>
            <p class="text-gray-600 mt-2">Manage system users and permissions</p>
        </div>
        <div class="flex gap-2">
            <a href="<?php echo e(route('users.export', request()->query())); ?>" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                📊 Export to Excel
            </a>
            <a href="<?php echo e(route('users.create')); ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                ➕ Add New User
            </a>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
            <ul>
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card total">
            <div class="stat-label">👥 Total Users</div>
            <div class="stat-value"><?php echo e($stats['total_users']); ?></div>
        </div>
        <div class="stat-card super-admin">
            <div class="stat-label">🔵 Super Admins</div>
            <div class="stat-value"><?php echo e($stats['super_admins']); ?></div>
        </div>
        <div class="stat-card admin">
            <div class="stat-label">🟢 Admins</div>
            <div class="stat-value"><?php echo e($stats['admins']); ?></div>
        </div>
        <div class="stat-card student">
            <div class="stat-label">🟡 Students</div>
            <div class="stat-value"><?php echo e($stats['students']); ?></div>
        </div>
        <div class="stat-card parent">
            <div class="stat-label">🟣 Parents</div>
            <div class="stat-value"><?php echo e($stats['parents']); ?></div>
        </div>
        <div class="stat-card inactive">
            <div class="stat-label">🔴 Inactive</div>
            <div class="stat-value"><?php echo e($stats['inactive']); ?></div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">🔍 Search & Filters</h3>
        <form method="GET" action="<?php echo e(route('users.index')); ?>">
            <div class="filter-grid">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <input type="text" name="search" value="<?php echo e(request('search')); ?>" 
                           placeholder="Name, email, phone" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                    <select name="role" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                        <option value="">All Roles</option>
                        <option value="super_admin" <?php echo e(request('role') == 'super_admin' ? 'selected' : ''); ?>>Super Admin</option>
                        <option value="admin" <?php echo e(request('role') == 'admin' ? 'selected' : ''); ?>>Admin</option>
                        <option value="student" <?php echo e(request('role') == 'student' ? 'selected' : ''); ?>>Student</option>
                        <option value="parent" <?php echo e(request('role') == 'parent' ? 'selected' : ''); ?>>Parent</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                        <option value="">All Status</option>
                        <option value="active" <?php echo e(request('status') == 'active' ? 'selected' : ''); ?>>Active</option>
                        <option value="inactive" <?php echo e(request('status') == 'inactive' ? 'selected' : ''); ?>>Inactive</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date From</label>
                    <input type="date" name="date_from" value="<?php echo e(request('date_from')); ?>" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date To</label>
                    <input type="date" name="date_to" value="<?php echo e(request('date_to')); ?>" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md">
                </div>
            </div>
            <div class="mt-4 flex gap-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md">Apply Filters</button>
                <a href="<?php echo e(route('users.index')); ?>" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-md">Clear</a>
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Phone</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Registered</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50 <?php echo e($user->deleted_at ? 'opacity-50' : ''); ?>">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-mono text-gray-900">#<?php echo e($user->id); ?></span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <p class="text-sm font-medium text-gray-900"><?php echo e($user->name); ?></p>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <p class="text-sm text-gray-700"><?php echo e($user->email); ?></p>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <p class="text-sm text-gray-600"><?php echo e($user->phone ?? '-'); ?></p>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="role-badge role-<?php echo e($user->role); ?>">
                                <?php echo e(ucfirst(str_replace('_', ' ', $user->role))); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php if($user->deleted_at): ?>
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Inactive
                                </span>
                            <?php else: ?>
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Active
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-600">
                                <div><?php echo e($user->created_at->format('M d, Y')); ?></div>
                                <div class="text-xs text-gray-500"><?php echo e($user->created_at->diffForHumans()); ?></div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="<?php echo e(route('users.show', $user)); ?>" class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                            <?php if(!$user->deleted_at): ?>
                                <a href="<?php echo e(route('users.edit', $user)); ?>" class="text-green-600 hover:text-green-900 mr-3">Edit</a>
                                <?php if($user->id !== auth()->id()): ?>
                                    <form action="<?php echo e(route('users.destroy', $user)); ?>" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to deactivate this user?');">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="text-red-600 hover:text-red-900">Deactivate</button>
                                    </form>
                                <?php endif; ?>
                            <?php else: ?>
                                <form action="<?php echo e(route('users.restore', $user->id)); ?>" method="POST" class="inline">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="text-green-600 hover:text-green-900">Restore</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">No users found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        <?php echo e($users->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Bensonngulube\OneDrive - zescozambia\Desktop\Laravel test\resources\views/users/index.blade.php ENDPATH**/ ?>