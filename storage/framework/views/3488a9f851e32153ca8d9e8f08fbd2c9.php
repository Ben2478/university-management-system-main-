

<?php $__env->startSection('content'); ?>
<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <a href="<?php echo e(route('enrollments.show', $enrollment->id)); ?>" style="color: #3498db; text-decoration: none;">← Back to Enrollment</a>
            <h1 style="margin-top: 0.5rem;">Edit Enrollment</h1>
        </div>
    </div>

    <div class="card">
        <?php if($errors->any()): ?>
            <div class="alert alert-error">
                <ul style="margin: 0;">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?php echo e(route('enrollments.update', $enrollment->id)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div class="form-group">
                <label for="student_id">Student <span style="color: #e74c3c;">*</span></label>
                <select id="student_id" name="student_id" required disabled>
                    <option value="<?php echo e($enrollment->student->id); ?>" selected>
                        <?php echo e($enrollment->student->first_name); ?> <?php echo e($enrollment->student->last_name); ?> (<?php echo e($enrollment->student->student_id); ?>)
                    </option>
                </select>
                <small style="color: #666;">Cannot change student after enrollment created</small>
            </div>

            <div class="form-group">
                <label for="course_id">Course <span style="color: #e74c3c;">*</span></label>
                <select id="course_id" name="course_id" required disabled>
                    <option value="<?php echo e($enrollment->course->id); ?>" selected>
                        <?php echo e($enrollment->course->name); ?> (<?php echo e($enrollment->course->course_code); ?>)
                    </option>
                </select>
                <small style="color: #666;">Cannot change course after enrollment created</small>
            </div>

            <div class="form-group">
                <label for="status">Status <span style="color: #e74c3c;">*</span></label>
                <select id="status" name="status" required>
                    <option value="active" <?php echo e($enrollment->status === 'active' ? 'selected' : ''); ?>>Active</option>
                    <option value="completed" <?php echo e($enrollment->status === 'completed' ? 'selected' : ''); ?>>Completed</option>
                    <option value="dropped" <?php echo e($enrollment->status === 'dropped' ? 'selected' : ''); ?>>Dropped</option>
                    <option value="suspended" <?php echo e($enrollment->status === 'suspended' ? 'selected' : ''); ?>>Suspended</option>
                </select>
            </div>

            <div class="form-group">
                <label for="enrollment_date">Enrollment Date</label>
                <input type="date" id="enrollment_date" name="enrollment_date" 
                    value="<?php echo e($enrollment->enrollment_date ? $enrollment->enrollment_date->format('Y-m-d') : ''); ?>">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <button type="submit" class="btn btn-success">Update Enrollment</button>
                <a href="<?php echo e(route('enrollments.show', $enrollment->id)); ?>" class="btn btn-secondary" style="text-align: center;">Cancel</a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Bensonngulube\OneDrive - zescozambia\Desktop\Laravel test\resources\views/enrollments/edit.blade.php ENDPATH**/ ?>