

<?php $__env->startSection('content'); ?>
<div class="container" style="max-width: 600px; margin: 30px auto;">
    <h2>Edit Grade</h2>

    <?php if($errors->any()): ?>
        <div class="alert alert-error" style="background: #e74c3c; color: white; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
            <ul style="margin: 0; padding-left: 20px;">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?php echo e(route('grades.update', $grade)); ?>" method="POST" style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <div style="margin-bottom: 20px;">
            <label for="student_id" style="display: block; margin-bottom: 8px; font-weight: bold;">Student:</label>
            <select id="student_id" name="student_id" required disabled style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; background: #f5f5f5;">
                <option value="<?php echo e($grade->student_id); ?>"><?php echo e($grade->student->user->name); ?></option>
            </select>
            <input type="hidden" name="student_id" value="<?php echo e($grade->student_id); ?>">
        </div>

        <div style="margin-bottom: 20px;">
            <label for="course_id" style="display: block; margin-bottom: 8px; font-weight: bold;">Course:</label>
            <select id="course_id" name="course_id" required disabled style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; background: #f5f5f5;">
                <option value="<?php echo e($grade->course_id); ?>"><?php echo e($grade->course->course_name); ?></option>
            </select>
            <input type="hidden" name="course_id" value="<?php echo e($grade->course_id); ?>">
        </div>

        <div style="margin-bottom: 20px;">
            <label for="score" style="display: block; margin-bottom: 8px; font-weight: bold;">Score (0-100):</label>
            <input type="number" id="score" name="score" min="0" max="100" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;" value="<?php echo e($grade->score); ?>">
        </div>

        <div style="margin-bottom: 20px;">
            <label for="grade_letter" style="display: block; margin-bottom: 8px; font-weight: bold;">Grade Letter:</label>
            <select id="grade_letter" name="grade_letter" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                <option value="A" <?php echo e($grade->grade_letter == 'A' ? 'selected' : ''); ?>>A (90-100)</option>
                <option value="B" <?php echo e($grade->grade_letter == 'B' ? 'selected' : ''); ?>>B (80-89)</option>
                <option value="C" <?php echo e($grade->grade_letter == 'C' ? 'selected' : ''); ?>>C (70-79)</option>
                <option value="D" <?php echo e($grade->grade_letter == 'D' ? 'selected' : ''); ?>>D (60-69)</option>
                <option value="F" <?php echo e($grade->grade_letter == 'F' ? 'selected' : ''); ?>>F (Below 60)</option>
            </select>
        </div>

        <div style="margin-bottom: 20px;">
            <label for="remarks" style="display: block; margin-bottom: 8px; font-weight: bold;">Remarks:</label>
            <textarea id="remarks" name="remarks" rows="3" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;"><?php echo e($grade->remarks); ?></textarea>
        </div>

        <div style="display: flex; gap: 10px;">
            <button type="submit" class="btn-success" style="flex: 1; padding: 12px; background: #27ae60; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px;">Update Grade</button>
            <a href="<?php echo e(route('grades.index')); ?>" class="btn-secondary" style="flex: 1; padding: 12px; background: #95a5a6; color: white; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; text-align: center; font-size: 16px;">Cancel</a>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make(auth()->user()->role === 'super_admin' ? 'layouts.super-admin' : (auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.app'), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Bensonngulube\OneDrive - zescozambia\Desktop\Laravel test\resources\views/grades/edit.blade.php ENDPATH**/ ?>