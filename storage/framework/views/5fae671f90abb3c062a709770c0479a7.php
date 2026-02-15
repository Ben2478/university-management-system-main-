

<?php $__env->startSection('content'); ?>
<div class="container" style="max-width: 800px; margin: 30px auto; padding: 0 20px;">
    <div style="margin-bottom: 2rem;">
        <h1 style="margin: 0 0 0.5rem 0;">Add New Course</h1>
        <p style="margin: 0; color: #666;">Create a new course and assign it to a program</p>
    </div>

    <?php if($errors->any()): ?>
        <div style="padding: 1rem; margin-bottom: 2rem; background: #f8d7da; border-left: 4px solid #e74c3c; border-radius: 0.25rem;">
            <strong style="color: #721c24;">Please fix the following errors:</strong>
            <ul style="margin: 0.5rem 0 0 0; padding-left: 20px; color: #721c24;">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?php echo e(route('courses.store')); ?>" method="POST" style="background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
        <?php echo csrf_field(); ?>

        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem;">
            <!-- Course Code -->
            <div>
                <label for="course_code" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50;">Course Code <span style="color: #e74c3c;">*</span></label>
                <input type="text" id="course_code" name="course_code" required placeholder="e.g., CS101" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem;" value="<?php echo e(old('course_code')); ?>">
                <small style="color: #666; font-size: 0.875rem;">Unique identifier for the course</small>
            </div>

            <!-- Course Name -->
            <div>
                <label for="course_name" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50;">Course Name <span style="color: #e74c3c;">*</span></label>
                <input type="text" id="course_name" name="course_name" required placeholder="e.g., Introduction to Programming" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem;" value="<?php echo e(old('course_name')); ?>">
            </div>

            <!-- Credits -->
            <div>
                <label for="credits" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50;">Credits <span style="color: #e74c3c;">*</span></label>
                <input type="number" id="credits" name="credits" required min="1" max="6" placeholder="e.g., 3" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem;" value="<?php echo e(old('credits', 3)); ?>">
                <small style="color: #666; font-size: 0.875rem;">Number of credit hours (1-6)</small>
            </div>

            <!-- Semester -->
            <div>
                <label for="semester" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50;">Semester <span style="color: #e74c3c;">*</span></label>
                <select id="semester" name="semester" required style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem; background: white;">
                    <option value="">Select Semester</option>
                    <?php for($i = 1; $i <= 8; $i++): ?>
                        <option value="<?php echo e($i); ?>" <?php echo e(old('semester') == $i ? 'selected' : ''); ?>>Semester <?php echo e($i); ?></option>
                    <?php endfor; ?>
                </select>
            </div>

            <!-- Program/Department -->
            <div>
                <label for="department" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50;">Program <span style="color: #e74c3c;">*</span></label>
                <select id="department" name="department" required style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem; background: white;">
                    <option value="">Select Program</option>
                    <?php $__currentLoopData = $programs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $program): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($program); ?>" <?php echo e(old('department') == $program ? 'selected' : ''); ?>><?php echo e($program); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <small style="color: #666; font-size: 0.875rem;">Students can only enroll in courses from their program</small>
            </div>

            <!-- Status -->
            <div>
                <label for="status" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50;">Status <span style="color: #e74c3c;">*</span></label>
                <select id="status" name="status" required style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem; background: white;">
                    <option value="active" <?php echo e(old('status', 'active') == 'active' ? 'selected' : ''); ?>>Active</option>
                    <option value="inactive" <?php echo e(old('status') == 'inactive' ? 'selected' : ''); ?>>Inactive</option>
                </select>
            </div>

            <!-- Instructor -->
            <div>
                <label for="instructor" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50;">Instructor</label>
                <input type="text" id="instructor" name="instructor" placeholder="e.g., Dr. John Smith" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem;" value="<?php echo e(old('instructor')); ?>">
            </div>

            <!-- Tuition Fee -->
            <div>
                <label for="tuition_fee" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50;">Course Fee (K)</label>
                <input type="number" id="tuition_fee" name="tuition_fee" min="0" step="0.01" placeholder="0.00" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem;" value="<?php echo e(old('tuition_fee', 0)); ?>">
            </div>
        </div>

        <!-- Description (Full Width) -->
        <div style="margin-top: 1.5rem;">
            <label for="description" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50;">Description</label>
            <textarea id="description" name="description" rows="4" placeholder="Enter course description, objectives, and requirements..." style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem; resize: vertical;"><?php echo e(old('description')); ?></textarea>
        </div>

        <!-- Buttons -->
        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            <button type="submit" style="flex: 1; padding: 0.875rem; background: #27ae60; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 1rem; font-weight: 600;">
                ✓ Create Course
            </button>
            <a href="<?php echo e(route('courses.index')); ?>" style="flex: 1; padding: 0.875rem; background: #95a5a6; color: white; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; text-align: center; font-size: 1rem; font-weight: 600; display: block;">
                × Cancel
            </a>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make(auth()->user()->role === 'super_admin' ? 'layouts.super-admin' : (auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.app'), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Bensonngulube\OneDrive - zescozambia\Desktop\Laravel test\resources\views/courses/create.blade.php ENDPATH**/ ?>