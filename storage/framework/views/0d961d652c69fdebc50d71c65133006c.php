

<?php $__env->startSection('content'); ?>
<div class="container" style="padding: 2rem 0;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 style="margin: 0 0 0.5rem 0;">Available Courses</h1>
            <p style="margin: 0; color: #666;">Browse and enroll in courses for your program: <strong><?php echo e($student->program); ?></strong></p>
        </div>
        <a href="<?php echo e(route('enrollments.index')); ?>" class="btn" style="background: #95a5a6; color: white; padding: 0.75rem 1.5rem; border-radius: 0.25rem; text-decoration: none;">
            ← Back to My Courses
        </a>
    </div>

    <?php if(session('success')): ?>
    <div style="padding: 1rem; margin-bottom: 2rem; background: #d4edda; border-left: 4px solid #27ae60; border-radius: 0.25rem;">
        <span style="color: #155724; font-weight: 600;">✓ <?php echo e(session('success')); ?></span>
    </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
    <div style="padding: 1rem; margin-bottom: 2rem; background: #f8d7da; border-left: 4px solid #e74c3c; border-radius: 0.25rem;">
        <span style="color: #721c24; font-weight: 600;">✗ <?php echo e(session('error')); ?></span>
    </div>
    <?php endif; ?>

    <?php if($availableCourses->count() > 0): ?>
    <div style="display: grid; gap: 1.5rem;">
        <?php $__currentLoopData = $availableCourses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="card" style="border-left: 4px solid #3498db;">
            <div style="display: flex; justify-content: space-between; align-items: start; gap: 2rem;">
                <!-- Course Info -->
                <div style="flex: 1;">
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                        <h3 style="margin: 0; color: #2c3e50;"><?php echo e($course->course_code); ?></h3>
                        <span style="padding: 0.25rem 0.75rem; background: #e3f2fd; color: #1976d2; border-radius: 0.25rem; font-size: 0.75rem; font-weight: 600;">
                            Semester <?php echo e($course->semester); ?>

                        </span>
                        <span style="padding: 0.25rem 0.75rem; background: #f3e5f5; color: #7b1fa2; border-radius: 0.25rem; font-size: 0.75rem; font-weight: 600;">
                            <?php echo e($course->credits); ?> Credits
                        </span>
                    </div>
                    
                    <h4 style="margin: 0 0 1rem 0; color: #34495e; font-weight: 500;"><?php echo e($course->course_name); ?></h4>
                    
                    <?php if($course->description): ?>
                    <p style="margin: 0 0 1rem 0; color: #666; line-height: 1.6;"><?php echo e($course->description); ?></p>
                    <?php endif; ?>
                    
                    <div style="display: flex; gap: 1.5rem; flex-wrap: wrap; color: #666; font-size: 0.875rem;">
                        <?php if($course->department): ?>
                        <div>
                            <strong>Department:</strong> <?php echo e($course->department); ?>

                        </div>
                        <?php endif; ?>
                        
                        <?php if($course->tuition_fee > 0): ?>
                        <div>
                            <strong>Course Fee:</strong> K<?php echo e(number_format($course->tuition_fee, 2)); ?>

                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Enroll Button -->
                <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 0.5rem;">
                    <form action="<?php echo e(route('enrollments.enroll', $course)); ?>" method="POST" onsubmit="return confirm('Are you sure you want to enroll in <?php echo e($course->course_code); ?>?');">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn" style="background: #27ae60; color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 0.25rem; cursor: pointer; font-size: 1rem; font-weight: 600; white-space: nowrap;">
                            ✓ Enroll Now
                        </button>
                    </form>
                    <span style="color: #999; font-size: 0.75rem; text-align: right;">Click to add to your courses</span>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <!-- Course Count Summary -->
    <div style="margin-top: 2rem; padding: 1rem; background: #f8f9fa; border-radius: 0.25rem; text-align: center;">
        <p style="margin: 0; color: #666;">
            Showing <strong><?php echo e($availableCourses->count()); ?></strong> available course(s) for <strong><?php echo e($student->program); ?></strong>
        </p>
    </div>
    <?php else: ?>
    <div class="card" style="text-align: center; padding: 3rem;">
        <p style="color: #999; font-size: 1.125rem; margin-bottom: 1rem;">📚 No courses available</p>
        <p style="color: #666; font-size: 0.875rem; margin-bottom: 1.5rem;">
            <?php if($student->program): ?>
                All available courses for <strong><?php echo e($student->program); ?></strong> have been enrolled or are inactive.
            <?php else: ?>
                Your program is not set. Contact your academic advisor.
            <?php endif; ?>
        </p>
        <a href="<?php echo e(route('enrollments.index')); ?>" class="btn" style="background: #3498db; color: white; padding: 0.75rem 1.5rem; border-radius: 0.25rem; text-decoration: none;">
            Return to My Courses
        </a>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.student', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Bensonngulube\OneDrive - zescozambia\Desktop\Laravel test\resources\views/enrollments/available-courses.blade.php ENDPATH**/ ?>