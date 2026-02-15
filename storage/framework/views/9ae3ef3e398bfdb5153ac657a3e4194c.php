

<?php $__env->startSection('content'); ?>
<div class="container" style="padding: 2rem 0;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 style="margin: 0 0 0.5rem 0;">Course Management</h1>
            <p style="margin: 0; color: #666;">Manage and organize courses by programs</p>
        </div>
        <a href="<?php echo e(route('courses.create')); ?>" class="btn" style="background: #27ae60; color: white; padding: 0.75rem 1.5rem; border-radius: 0.25rem; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; font-weight: 600;">
            + Add New Course
        </a>
    </div>

    <?php if(session('success')): ?>
    <div style="padding: 1rem; margin-bottom: 2rem; background: #d4edda; border-left: 4px solid #27ae60; border-radius: 0.25rem;">
        <span style="color: #155724; font-weight: 600;">✓ <?php echo e(session('success')); ?></span>
    </div>
    <?php endif; ?>

    <!-- Stats Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
        <div class="card" style="border-left: 4px solid #3498db; padding: 1.25rem;">
            <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase; font-weight: 600;">Total Courses</p>
            <h2 style="margin: 0.5rem 0 0 0; color: #3498db; font-size: 2rem;"><?php echo e($stats['total']); ?></h2>
        </div>
        <div class="card" style="border-left: 4px solid #27ae60; padding: 1.25rem;">
            <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase; font-weight: 600;">Active Courses</p>
            <h2 style="margin: 0.5rem 0 0 0; color: #27ae60; font-size: 2rem;"><?php echo e($stats['active']); ?></h2>
        </div>
        <div class="card" style="border-left: 4px solid #9b59b6; padding: 1.25rem;">
            <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase; font-weight: 600;">Programs</p>
            <h2 style="margin: 0.5rem 0 0 0; color: #9b59b6; font-size: 2rem;"><?php echo e($stats['departments']); ?></h2>
        </div>
        <div class="card" style="border-left: 4px solid #e67e22; padding: 1.25rem;">
            <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase; font-weight: 600;">Total Enrollments</p>
            <h2 style="margin: 0.5rem 0 0 0; color: #e67e22; font-size: 2rem;"><?php echo e($stats['enrolled']); ?></h2>
        </div>
    </div>

    <!-- Filters -->
    <div class="card" style="margin-bottom: 2rem;">
        <form method="GET" action="<?php echo e(route('courses.index')); ?>" style="display: grid; grid-template-columns: 2fr 1fr 1fr auto; gap: 1rem; align-items: end;">
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; font-size: 0.875rem; color: #2c3e50;">Search</label>
                <input type="text" name="search" placeholder="Search by course name or code..." value="<?php echo e(request('search')); ?>" style="width: 100%; padding: 0.625rem; border: 1px solid #ddd; border-radius: 0.25rem;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; font-size: 0.875rem; color: #2c3e50;">Program</label>
                <select name="department" style="width: 100%; padding: 0.625rem; border: 1px solid #ddd; border-radius: 0.25rem; background: white;">
                    <option value="">All Programs</option>
                    <?php $__currentLoopData = \App\Models\Course::distinct('department')->whereNotNull('department')->pluck('department'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($dept); ?>" <?php echo e(request('department') == $dept ? 'selected' : ''); ?>><?php echo e($dept); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; font-size: 0.875rem; color: #2c3e50;">Status</label>
                <select name="status" style="width: 100%; padding: 0.625rem; border: 1px solid #ddd; border-radius: 0.25rem; background: white;">
                    <option value="">All Status</option>
                    <option value="active" <?php echo e(request('status') == 'active' ? 'selected' : ''); ?>>Active</option>
                    <option value="inactive" <?php echo e(request('status') == 'inactive' ? 'selected' : ''); ?>>Inactive</option>
                </select>
            </div>
            <div style="display: flex; gap: 0.5rem;">
                <button type="submit" style="padding: 0.625rem 1.25rem; background: #3498db; color: white; border: none; border-radius: 0.25rem; cursor: pointer; font-weight: 600;">
                    🔍 Filter
                </button>
                <?php if(request()->hasAny(['search', 'department', 'status'])): ?>
                    <a href="<?php echo e(route('courses.index')); ?>" style="padding: 0.625rem 1.25rem; background: #95a5a6; color: white; border-radius: 0.25rem; text-decoration: none; font-weight: 600;">
                        Clear
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Courses Table -->
    <?php if($courses->count() > 0): ?>
    <div class="card">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #34495e; color: white;">
                    <th style="padding: 1rem; text-align: left; font-weight: 600;">Course Code</th>
                    <th style="padding: 1rem; text-align: left; font-weight: 600;">Course Name</th>
                    <th style="padding: 1rem; text-align: left; font-weight: 600;">Program</th>
                    <th style="padding: 1rem; text-align: center; font-weight: 600;">Credits</th>
                    <th style="padding: 1rem; text-align: center; font-weight: 600;">Semester</th>
                    <th style="padding: 1rem; text-align: center; font-weight: 600;">Enrollments</th>
                    <th style="padding: 1rem; text-align: center; font-weight: 600;">Status</th>
                    <th style="padding: 1rem; text-align: center; font-weight: 600;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr style="border-bottom: 1px solid #ecf0f1;">
                    <td style="padding: 1rem;">
                        <strong style="color: #2c3e50;"><?php echo e($course->course_code); ?></strong>
                    </td>
                    <td style="padding: 1rem;">
                        <div style="font-weight: 500; color: #2c3e50;"><?php echo e($course->course_name); ?></div>
                        <?php if($course->instructor): ?>
                            <small style="color: #7f8c8d;">👤 <?php echo e($course->instructor); ?></small>
                        <?php endif; ?>
                    </td>
                    <td style="padding: 1rem;">
                        <span style="padding: 0.25rem 0.75rem; background: #e3f2fd; color: #1976d2; border-radius: 0.25rem; font-size: 0.75rem; font-weight: 600;">
                            <?php echo e($course->department ?? 'N/A'); ?>

                        </span>
                    </td>
                    <td style="padding: 1rem; text-align: center;">
                        <span style="font-weight: 600; color: #34495e;"><?php echo e($course->credits ?? 'N/A'); ?></span>
                    </td>
                    <td style="padding: 1rem; text-align: center;">
                        <span style="font-weight: 600; color: #34495e;"><?php echo e($course->semester ?? 'N/A'); ?></span>
                    </td>
                    <td style="padding: 1rem; text-align: center;">
                        <span style="padding: 0.25rem 0.75rem; background: #fff3e0; color: #e65100; border-radius: 0.25rem; font-weight: 600;">
                            <?php echo e($course->enrollments->count()); ?>

                        </span>
                    </td>
                    <td style="padding: 1rem; text-align: center;">
                        <?php if($course->status === 'active'): ?>
                            <span style="padding: 0.25rem 0.75rem; background: #d4edda; color: #155724; border-radius: 0.25rem; font-size: 0.75rem; font-weight: 600;">
                                ● Active
                            </span>
                        <?php else: ?>
                            <span style="padding: 0.25rem 0.75rem; background: #f8d7da; color: #721c24; border-radius: 0.25rem; font-size: 0.75rem; font-weight: 600;">
                                ● Inactive
                            </span>
                        <?php endif; ?>
                    </td>
                    <td style="padding: 1rem; text-align: center;">
                        <div style="display: flex; gap: 0.5rem; justify-content: center;">
                            <a href="<?php echo e(route('courses.show', $course)); ?>" style="padding: 0.375rem 0.75rem; background: #3498db; color: white; border-radius: 0.25rem; text-decoration: none; font-size: 0.75rem; font-weight: 600;">
                                View
                            </a>
                            <a href="<?php echo e(route('courses.edit', $course)); ?>" style="padding: 0.375rem 0.75rem; background: #f39c12; color: white; border-radius: 0.25rem; text-decoration: none; font-size: 0.75rem; font-weight: 600;">
                                Edit
                            </a>
                            <form action="<?php echo e(route('courses.destroy', $course)); ?>" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this course?');">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" style="padding: 0.375rem 0.75rem; background: #e74c3c; color: white; border: none; border-radius: 0.25rem; cursor: pointer; font-size: 0.75rem; font-weight: 600;">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <?php if($courses->hasPages()): ?>
        <div style="padding: 1.5rem; border-top: 1px solid #ecf0f1;">
            <?php echo e($courses->links()); ?>

        </div>
        <?php endif; ?>
    </div>
    <?php else: ?>
    <div class="card" style="text-align: center; padding: 3rem;">
        <p style="color: #999; font-size: 1.125rem; margin-bottom: 1rem;">📚 No courses found</p>
        <p style="color: #666; font-size: 0.875rem; margin-bottom: 1.5rem;">
            <?php if(request()->hasAny(['search', 'department', 'status'])): ?>
                Try adjusting your filters or search terms.
            <?php else: ?>
                Get started by adding your first course.
            <?php endif; ?>
        </p>
        <?php if(request()->hasAny(['search', 'department', 'status'])): ?>
            <a href="<?php echo e(route('courses.index')); ?>" style="padding: 0.75rem 1.5rem; background: #95a5a6; color: white; border-radius: 0.25rem; text-decoration: none; font-weight: 600;">
                Clear Filters
            </a>
        <?php else: ?>
            <a href="<?php echo e(route('courses.create')); ?>" style="padding: 0.75rem 1.5rem; background: #27ae60; color: white; border-radius: 0.25rem; text-decoration: none; font-weight: 600;">
                + Add Your First Course
            </a>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make(auth()->user()->role === 'super_admin' ? 'layouts.super-admin' : (auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.app'), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Bensonngulube\OneDrive - zescozambia\Desktop\Laravel test\resources\views/courses/index.blade.php ENDPATH**/ ?>