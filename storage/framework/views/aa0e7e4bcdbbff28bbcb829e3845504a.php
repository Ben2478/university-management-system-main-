

<?php $__env->startSection('content'); ?>
<div class="container" style="max-width: 600px; margin: 30px auto;">
    <h2>Edit Course</h2>

    <?php if($errors->any()): ?>
        <div class="alert alert-error" style="background: #e74c3c; color: white; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
            <ul style="margin: 0; padding-left: 20px;">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?php echo e(route('courses.update', $course)); ?>" method="POST" style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <div style="margin-bottom: 20px;">
            <label for="course_code" style="display: block; margin-bottom: 8px; font-weight: bold;">Course Code:</label>
            <input type="text" id="course_code" name="course_code" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;" value="<?php echo e($course->course_code); ?>">
        </div>

        <div style="margin-bottom: 20px;">
            <label for="course_name" style="display: block; margin-bottom: 8px; font-weight: bold;">Course Name:</label>
            <input type="text" id="course_name" name="course_name" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;" value="<?php echo e($course->course_name); ?>">
        </div>

        <div style="margin-bottom: 20px;">
            <label for="description" style="display: block; margin-bottom: 8px; font-weight: bold;">Description:</label>
            <textarea id="description" name="description" rows="4" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;"><?php echo e($course->description); ?></textarea>
        </div>

        <div style="margin-bottom: 20px;">
            <label for="credit_hours" style="display: block; margin-bottom: 8px; font-weight: bold;">Credit Hours:</label>
            <input type="number" id="credit_hours" name="credit_hours" min="1" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;" value="<?php echo e($course->credit_hours); ?>">
        </div>

        <div style="margin-bottom: 20px;">
            <label for="instructor" style="display: block; margin-bottom: 8px; font-weight: bold;">Instructor:</label>
            <input type="text" id="instructor" name="instructor" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;" value="<?php echo e($course->instructor); ?>">
        </div>

        <div style="margin-bottom: 20px;">
            <label for="semester" style="display: block; margin-bottom: 8px; font-weight: bold;">Semester:</label>
            <select id="semester" name="semester" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                <option value="1" <?php echo e($course->semester == '1' ? 'selected' : ''); ?>>Semester 1</option>
                <option value="2" <?php echo e($course->semester == '2' ? 'selected' : ''); ?>>Semester 2</option>
            </select>
        </div>

        <div style="display: flex; gap: 10px;">
            <button type="submit" class="btn-success" style="flex: 1; padding: 12px; background: #27ae60; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px;">Update Course</button>
            <a href="<?php echo e(route('courses.show', $course)); ?>" class="btn-secondary" style="flex: 1; padding: 12px; background: #95a5a6; color: white; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; text-align: center; font-size: 16px;">Cancel</a>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make(auth()->user()->role === 'super_admin' ? 'layouts.super-admin' : (auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.app'), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Bensonngulube\OneDrive - zescozambia\Desktop\Laravel test\resources\views/courses/edit.blade.php ENDPATH**/ ?>