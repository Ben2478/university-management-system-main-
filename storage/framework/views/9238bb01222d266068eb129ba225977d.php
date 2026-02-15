

<?php $__env->startSection('content'); ?>
<div class="container" style="padding: 2rem 0;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h1>👨‍🎓 Student Management</h1>
        <a href="<?php echo e(route('students.create')); ?>" class="btn btn-primary">+ Add New Student</a>
    </div>

    <!-- Stats Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
        <div class="card" style="border-left: 4px solid #3498db;">
            <p style="margin: 0; color: #666; font-size: 0.875rem; text-transform: uppercase;">Total Students</p>
            <h2 style="margin: 0.5rem 0; color: #3498db;"><?php echo e($stats['total']); ?></h2>
        </div>

        <div class="card" style="border-left: 4px solid #27ae60;">
            <p style="margin: 0; color: #666; font-size: 0.875rem; text-transform: uppercase;">Active Students</p>
            <h2 style="margin: 0.5rem 0; color: #27ae60;"><?php echo e($stats['active']); ?></h2>
        </div>

        <div class="card" style="border-left: 4px solid #f39c12;">
            <p style="margin: 0; color: #666; font-size: 0.875rem; text-transform: uppercase;">Inactive</p>
            <h2 style="margin: 0.5rem 0; color: #f39c12;"><?php echo e($stats['inactive']); ?></h2>
        </div>

        <div class="card" style="border-left: 4px solid #9b59b6;">
            <p style="margin: 0; color: #666; font-size: 0.875rem; text-transform: uppercase;">Graduated</p>
            <h2 style="margin: 0.5rem 0; color: #9b59b6;"><?php echo e($stats['graduated']); ?></h2>
        </div>
    </div>

    <!-- Filters -->
    <div class="card" style="margin-bottom: 2rem;">
        <form method="GET" action="<?php echo e(route('students.index')); ?>">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <input type="text" name="search" placeholder="Search by name, ID, or email" 
                           value="<?php echo e(request('search')); ?>" 
                           style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 0.25rem;">
                </div>

                <div>
                    <select name="program" style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 0.25rem;">
                        <option value="">All Programs</option>
                        <?php $__currentLoopData = $programs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $program): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($program); ?>" <?php echo e(request('program') == $program ? 'selected' : ''); ?>>
                                <?php echo e($program); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div>
                    <select name="status" style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 0.25rem;">
                        <option value="">All Statuses</option>
                        <option value="active" <?php echo e(request('status') == 'active' ? 'selected' : ''); ?>>Active</option>
                        <option value="inactive" <?php echo e(request('status') == 'inactive' ? 'selected' : ''); ?>>Inactive</option>
                        <option value="graduated" <?php echo e(request('status') == 'graduated' ? 'selected' : ''); ?>>Graduated</option>
                        <option value="suspended" <?php echo e(request('status') == 'suspended' ? 'selected' : ''); ?>>Suspended</option>
                    </select>
                </div>

                <div style="display: flex; gap: 0.5rem;">
                    <button type="submit" class="btn btn-primary">Search</button>
                    <a href="<?php echo e(route('students.index')); ?>" class="btn btn-secondary">Clear</a>
                </div>
            </div>
        </form>
    </div>

    <!-- Students Table -->
    <div class="card">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid #ddd; background: #f8f9fa;">
                        <th style="text-align: left; padding: 0.75rem;">Student ID</th>
                        <th style="text-align: left; padding: 0.75rem;">Name</th>
                        <th style="text-align: left; padding: 0.75rem;">Email</th>
                        <th style="text-align: left; padding: 0.75rem;">Program</th>
                        <th style="text-align: left; padding: 0.75rem;">Year/Sem</th>
                        <th style="text-align: left; padding: 0.75rem;">Status</th>
                        <th style="text-align: left; padding: 0.75rem;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 0.75rem;">
                            <strong><?php echo e($student->student_id); ?></strong>
                        </td>
                        <td style="padding: 0.75rem;">
                            <strong><?php echo e($student->first_name); ?> <?php echo e($student->last_name); ?></strong>
                        </td>
                        <td style="padding: 0.75rem;"><?php echo e($student->email); ?></td>
                        <td style="padding: 0.75rem;"><?php echo e($student->program); ?></td>
                        <td style="padding: 0.75rem;">Year <?php echo e($student->year); ?>, Sem <?php echo e($student->semester); ?></td>
                        <td style="padding: 0.75rem;">
                            <?php
                                $statusColors = [
                                    'active' => '#27ae60',
                                    'inactive' => '#95a5a6',
                                    'graduated' => '#3498db',
                                    'suspended' => '#e74c3c',
                                ];
                                $color = $statusColors[$student->status] ?? '#95a5a6';
                            ?>
                            <span style="background: <?php echo e($color); ?>; color: white; padding: 0.25rem 0.75rem; border-radius: 0.25rem; font-size: 0.75rem; font-weight: 600;">
                                <?php echo e(ucfirst($student->status)); ?>

                            </span>
                        </td>
                        <td style="padding: 0.75rem;">
                            <div style="display: flex; gap: 0.5rem;">
                                <a href="<?php echo e(route('students.show', $student->id)); ?>" class="btn btn-sm btn-info">View</a>
                                <a href="<?php echo e(route('students.edit', $student->id)); ?>" class="btn btn-sm btn-warning">Edit</a>
                                <form action="<?php echo e(route('students.destroy', $student->id)); ?>" method="POST" style="display: inline;" 
                                      onsubmit="return confirm('Are you sure you want to delete this student?');">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 2rem; color: #666;">
                            No students found. <a href="<?php echo e(route('students.create')); ?>" style="color: #3498db;">Add your first student</a>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if($students->hasPages()): ?>
        <div style="margin-top: 1.5rem; display: flex; justify-content: center;">
            <?php echo e($students->links()); ?>

        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.super-admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Bensonngulube\OneDrive - zescozambia\Desktop\Laravel test\resources\views/students/admin-index.blade.php ENDPATH**/ ?>