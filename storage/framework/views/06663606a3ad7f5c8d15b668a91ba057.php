

<?php $__env->startSection('content'); ?>
<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <a href="<?php echo e(route('enrollments.index')); ?>" style="color: #3498db; text-decoration: none;">← Back to Enrollments</a>
            <h1 style="margin-top: 0.5rem;">New Enrollment</h1>
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

        <form action="<?php echo e(route('enrollments.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>

            <div class="form-group">
                <label for="student_id">Student <span style="color: #e74c3c;">*</span></label>
                <select id="student_id" name="student_id" required>
                    <option value="">-- Select Student --</option>
                    <?php $__currentLoopData = \App\Models\Student::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($student->id); ?>" <?php echo e(old('student_id') == $student->id ? 'selected' : ''); ?>>
                            <?php echo e($student->first_name); ?> <?php echo e($student->last_name); ?> (<?php echo e($student->student_id); ?>)
                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div class="form-group">
                <label for="course_id">Course <span style="color: #e74c3c;">*</span></label>
                <select id="course_id" name="course_id" required>
                    <option value="">-- Select Course --</option>
                    <?php $__currentLoopData = \App\Models\Course::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($course->id); ?>" <?php echo e(old('course_id') == $course->id ? 'selected' : ''); ?>>
                            <?php echo e($course->course_name); ?> (<?php echo e($course->course_code); ?>)
                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div class="form-group">
                <label for="program">Program of Study <span style="color: #e74c3c;">*</span></label>
                <select id="program" name="program" required>
                    <option value="">-- Select Program --</option>
                    <option value="Computer Science" <?php echo e(old('program') == 'Computer Science' ? 'selected' : ''); ?>>Computer Science</option>
                    <option value="Information Technology" <?php echo e(old('program') == 'Information Technology' ? 'selected' : ''); ?>>Information Technology</option>
                    <option value="Software Engineering" <?php echo e(old('program') == 'Software Engineering' ? 'selected' : ''); ?>>Software Engineering</option>
                    <option value="Data Science" <?php echo e(old('program') == 'Data Science' ? 'selected' : ''); ?>>Data Science</option>
                    <option value="Cybersecurity" <?php echo e(old('program') == 'Cybersecurity' ? 'selected' : ''); ?>>Cybersecurity</option>
                    <option value="Business Administration" <?php echo e(old('program') == 'Business Administration' ? 'selected' : ''); ?>>Business Administration</option>
                    <option value="Engineering" <?php echo e(old('program') == 'Engineering' ? 'selected' : ''); ?>>Engineering</option>
                    <option value="Medicine" <?php echo e(old('program') == 'Medicine' ? 'selected' : ''); ?>>Medicine</option>
                </select>
            </div>

            <div class="form-group">
                <label for="status">Status <span style="color: #e74c3c;">*</span></label>
                <select id="status" name="status" required>
                    <option value="">-- Select Status --</option>
                    <option value="active" <?php echo e(old('status') == 'active' ? 'selected' : ''); ?>>Active</option>
                    <option value="completed" <?php echo e(old('status') == 'completed' ? 'selected' : ''); ?>>Completed</option>
                    <option value="dropped" <?php echo e(old('status') == 'dropped' ? 'selected' : ''); ?>>Dropped</option>
                    <option value="suspended" <?php echo e(old('status') == 'suspended' ? 'selected' : ''); ?>>Suspended</option>
                </select>
            </div>

            <div class="form-group">
                <label for="enrollment_date">Enrollment Date</label>
                <input type="date" id="enrollment_date" name="enrollment_date" value="<?php echo e(old('enrollment_date')); ?>">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <button type="submit" class="btn btn-success">Create Enrollment</button>
                <a href="<?php echo e(route('enrollments.index')); ?>" class="btn btn-secondary" style="text-align: center;">Cancel</a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Bensonngulube\OneDrive - zescozambia\Desktop\Laravel test\resources\views/enrollments/create.blade.php ENDPATH**/ ?>