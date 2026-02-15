

<?php $__env->startSection('content'); ?>
<div class="container" style="max-width: 600px; margin: 30px auto;">
    <h2>Record Attendance</h2>

    <?php if($errors->any()): ?>
        <div class="alert alert-error" style="background: #e74c3c; color: white; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
            <ul style="margin: 0; padding-left: 20px;">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?php echo e(route('attendance.store')); ?>" method="POST" style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
        <?php echo csrf_field(); ?>

        <div style="margin-bottom: 20px;">
            <label for="student_id" style="display: block; margin-bottom: 8px; font-weight: bold;">Student:</label>
            <select id="student_id" name="student_id" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                <option value="">Select Student</option>
                <?php $__currentLoopData = \App\Models\Student::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($student->id); ?>"><?php echo e($student->first_name); ?> <?php echo e($student->last_name); ?> (<?php echo e($student->student_id); ?>)</option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

        <div style="margin-bottom: 20px;">
            <label for="course_id" style="display: block; margin-bottom: 8px; font-weight: bold;">Course:</label>
            <select id="course_id" name="course_id" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                <option value="">Select Course</option>
                <?php $__currentLoopData = \App\Models\Course::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($course->id); ?>"><?php echo e($course->course_name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

        <div style="margin-bottom: 20px;">
            <label for="attendance_date" style="display: block; margin-bottom: 8px; font-weight: bold;">Date:</label>
            <input type="date" id="attendance_date" name="attendance_date" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;" value="<?php echo e(old('attendance_date', date('Y-m-d'))); ?>">
        </div>

        <div style="margin-bottom: 20px;">
            <label for="status" style="display: block; margin-bottom: 8px; font-weight: bold;">Status:</label>
            <select id="status" name="status" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                <option value="present" <?php echo e(old('status') == 'present' ? 'selected' : ''); ?>>Present</option>
                <option value="absent" <?php echo e(old('status') == 'absent' ? 'selected' : ''); ?>>Absent</option>
                <option value="late" <?php echo e(old('status') == 'late' ? 'selected' : ''); ?>>Late</option>
                <option value="excused" <?php echo e(old('status') == 'excused' ? 'selected' : ''); ?>>Excused</option>
            </select>
        </div>

        <div style="margin-bottom: 20px;">
            <label for="remarks" style="display: block; margin-bottom: 8px; font-weight: bold;">Remarks:</label>
            <textarea id="remarks" name="remarks" rows="3" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;"><?php echo e(old('remarks')); ?></textarea>
        </div>

        <div style="display: flex; gap: 10px;">
            <button type="submit" class="btn-success" style="flex: 1; padding: 12px; background: #27ae60; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px;">Record Attendance</button>
            <a href="<?php echo e(route('attendance.index')); ?>" class="btn-secondary" style="flex: 1; padding: 12px; background: #95a5a6; color: white; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; text-align: center; font-size: 16px;">Cancel</a>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make(auth()->user()->role === 'super_admin' ? 'layouts.super-admin' : (auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.app'), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Bensonngulube\OneDrive - zescozambia\Desktop\Laravel test\resources\views/attendance/create.blade.php ENDPATH**/ ?>