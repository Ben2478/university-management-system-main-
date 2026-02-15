

<?php $__env->startSection('content'); ?>
<div class="container" style="max-width: 800px; margin: 30px auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h2><?php echo e($course->course_name); ?></h2>
        <div style="display: flex; gap: 10px;">
            <a href="<?php echo e(route('courses.edit', $course)); ?>" class="btn-primary" style="padding: 10px 15px; background: #3498db; color: white; text-decoration: none; border-radius: 4px;">Edit</a>
            <form action="<?php echo e(route('courses.destroy', $course)); ?>" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?');">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <button type="submit" class="btn-danger" style="padding: 10px 15px; background: #e74c3c; color: white; border: none; border-radius: 4px; cursor: pointer;">Delete</button>
            </form>
        </div>
    </div>

    <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 30px;">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div>
                <h4 style="color: #34495e; margin-bottom: 10px;">Course Code</h4>
                <p style="font-size: 18px; font-weight: bold; color: #2c3e50;"><?php echo e($course->course_code); ?></p>
            </div>
            <div>
                <h4 style="color: #34495e; margin-bottom: 10px;">Credit Hours</h4>
                <p style="font-size: 18px; font-weight: bold; color: #2c3e50;"><?php echo e($course->credit_hours); ?></p>
            </div>
            <div>
                <h4 style="color: #34495e; margin-bottom: 10px;">Instructor</h4>
                <p style="font-size: 18px; font-weight: bold; color: #2c3e50;"><?php echo e($course->instructor ?? 'Not assigned'); ?></p>
            </div>
            <div>
                <h4 style="color: #34495e; margin-bottom: 10px;">Semester</h4>
                <p style="font-size: 18px; font-weight: bold; color: #2c3e50;">Semester <?php echo e($course->semester); ?></p>
            </div>
        </div>

        <div style="margin-top: 20px;">
            <h4 style="color: #34495e; margin-bottom: 10px;">Description</h4>
            <p style="color: #555;"><?php echo e($course->description ?? 'No description'); ?></p>
        </div>
    </div>

    <a href="<?php echo e(route('courses.index')); ?>" class="btn-secondary" style="padding: 10px 15px; background: #95a5a6; color: white; text-decoration: none; border-radius: 4px;">Back to Courses</a>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make(auth()->user()->role === 'super_admin' ? 'layouts.super-admin' : (auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.app'), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Bensonngulube\OneDrive - zescozambia\Desktop\Laravel test\resources\views/courses/show.blade.php ENDPATH**/ ?>