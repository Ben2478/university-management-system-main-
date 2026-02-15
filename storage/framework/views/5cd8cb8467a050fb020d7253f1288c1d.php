

<?php $__env->startSection('content'); ?>
<div class="container" style="padding: 2rem 0;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 style="margin: 0 0 0.5rem 0;">My Courses</h1>
            <p style="margin: 0; color: #666;">Student ID: <?php echo e($student->student_id); ?> • <?php echo e($student->program); ?> - Year <?php echo e($student->year); ?>, Semester <?php echo e($student->semester); ?></p>
        </div>
        <a href="<?php echo e(route('enrollments.available')); ?>" class="btn" style="background: #3498db; color: white; padding: 0.75rem 1.5rem; border-radius: 0.25rem; text-decoration: none; display: flex; align-items: center; gap: 0.5rem;">
            📚 Browse Available Courses
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
            <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase;">Total Courses</p>
            <h2 style="margin: 0.5rem 0 0 0; color: #3498db; font-size: 2rem;"><?php echo e($stats['total']); ?></h2>
        </div>
        <div class="card" style="border-left: 4px solid #27ae60; padding: 1.25rem;">
            <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase;">Active</p>
            <h2 style="margin: 0.5rem 0 0 0; color: #27ae60; font-size: 2rem;"><?php echo e($stats['active']); ?></h2>
        </div>
        <div class="card" style="border-left: 4px solid #9b59b6; padding: 1.25rem;">
            <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase;">Completed</p>
            <h2 style="margin: 0.5rem 0 0 0; color: #9b59b6; font-size: 2rem;"><?php echo e($stats['completed']); ?></h2>
        </div>
        <div class="card" style="border-left: 4px solid #e74c3c; padding: 1.25rem;">
            <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase;">Dropped</p>
            <h2 style="margin: 0.5rem 0 0 0; color: #e74c3c; font-size: 2rem;"><?php echo e($stats['dropped']); ?></h2>
        </div>
    </div>

    <!-- Active Courses -->
    <?php if($coursesData->count() > 0): ?>
    <h2 style="margin: 0 0 1.5rem 0; color: #2c3e50;">Active Courses</h2>
    <div style="display: grid; gap: 1.5rem; margin-bottom: 3rem;">
        <?php $__currentLoopData = $coursesData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="card" style="border-left: 4px solid 
            <?php echo e($data['status_color'] === 'success' ? '#27ae60' : ($data['status_color'] === 'warning' ? '#f39c12' : '#e74c3c')); ?>;">
            
            <!-- Course Header -->
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1.5rem;">
                <div>
                    <h3 style="margin: 0 0 0.5rem 0; color: #2c3e50;"><?php echo e($data['course']->course_code); ?> - <?php echo e($data['course']->course_name); ?></h3>
                    <p style="margin: 0; color: #666; font-size: 0.875rem;">
                        Credits: <?php echo e($data['course']->credits); ?> • 
                        Enrolled: <?php echo e($data['enrollment']->enrollment_date->format('M d, Y')); ?>

                    </p>
                </div>
                <span style="padding: 0.5rem 1rem; border-radius: 0.25rem; font-size: 0.875rem; font-weight: 600;
                    background-color: <?php echo e($data['status_color'] === 'success' ? '#d4edda' : ($data['status_color'] === 'warning' ? '#fff3cd' : '#f8d7da')); ?>;
                    color: <?php echo e($data['status_color'] === 'success' ? '#155724' : ($data['status_color'] === 'warning' ? '#856404' : '#721c24')); ?>;">
                    <?php if($data['status_color'] === 'success'): ?>
                        🟢 On Track
                    <?php elseif($data['status_color'] === 'warning'): ?>
                        🟡 At Risk
                    <?php else: ?>
                        🔴 Action Needed
                    <?php endif; ?>
                </span>
            </div>

            <!-- Performance Metrics -->
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem; padding-bottom: 1.5rem; border-bottom: 1px solid #eee;">
                <!-- Current Grade -->
                <div>
                    <p style="margin: 0 0 0.5rem 0; color: #666; font-size: 0.75rem; text-transform: uppercase;">Current Grade</p>
                    <div style="display: flex; align-items: baseline; gap: 0.5rem;">
                        <h3 style="margin: 0; color: #2c3e50; font-size: 1.5rem;"><?php echo e($data['grade']); ?></h3>
                        <?php if($data['score'] > 0): ?>
                        <span style="color: #999; font-size: 0.875rem;">(<?php echo e($data['score']); ?>%)</span>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Attendance -->
                <div>
                    <p style="margin: 0 0 0.5rem 0; color: #666; font-size: 0.75rem; text-transform: uppercase;">Attendance</p>
                    <div style="display: flex; align-items: baseline; gap: 0.5rem;">
                        <h3 style="margin: 0; font-size: 1.5rem;
                            color: <?php echo e($data['attendance_rate'] >= 75 ? '#27ae60' : ($data['attendance_rate'] >= 60 ? '#f39c12' : '#e74c3c')); ?>;">
                            <?php echo e($data['attendance_rate']); ?>%
                        </h3>
                        <span style="color: #999; font-size: 0.875rem;">(<?php echo e($data['attended']); ?>/<?php echo e($data['total_classes']); ?>)</span>
                    </div>
                </div>

                <!-- Progress -->
                <div>
                    <p style="margin: 0 0 0.5rem 0; color: #666; font-size: 0.75rem; text-transform: uppercase;">Progress</p>
                    <div style="background: #f0f0f0; height: 8px; border-radius: 4px; overflow: hidden; margin-bottom: 0.25rem;">
                        <div style="height: 100%; background: #3498db; width: <?php echo e($data['progress']); ?>%; transition: width 0.3s;"></div>
                    </div>
                    <span style="color: #999; font-size: 0.75rem;"><?php echo e($data['progress']); ?>% Complete</span>
                </div>
            </div>

            <!-- Quick Actions -->
            <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
                <a href="<?php echo e(route('grades.index')); ?>" class="btn btn-sm" style="background: #3498db; color: white; padding: 0.5rem 1rem; border-radius: 0.25rem; text-decoration: none; font-size: 0.875rem;">
                    📊 View Grades
                </a>
                <a href="<?php echo e(route('attendance.index')); ?>" class="btn btn-sm" style="background: #9b59b6; color: white; padding: 0.5rem 1rem; border-radius: 0.25rem; text-decoration: none; font-size: 0.875rem;">
                    📅 Attendance Record
                </a>
                <a href="<?php echo e(route('enrollments.show', $data['enrollment'])); ?>" class="btn btn-sm" style="background: #95a5a6; color: white; padding: 0.5rem 1rem; border-radius: 0.25rem; text-decoration: none; font-size: 0.875rem;">
                    ℹ️ Course Details
                </a>
            </div>

            <!-- Alerts -->
            <?php if($data['attendance_rate'] < 75): ?>
            <div style="margin-top: 1rem; padding: 0.75rem 1rem; background: #fff3cd; border-left: 3px solid #f39c12; border-radius: 0.25rem;">
                <span style="color: #856404; font-size: 0.875rem;">⚠️ <strong>Low Attendance:</strong> You need <?php echo e(75 - $data['attendance_rate']); ?>% more attendance to stay on track.</span>
            </div>
            <?php endif; ?>

            <?php if($data['score'] > 0 && $data['score'] < 50): ?>
            <div style="margin-top: 1rem; padding: 0.75rem 1rem; background: #f8d7da; border-left: 3px solid #e74c3c; border-radius: 0.25rem;">
                <span style="color: #721c24; font-size: 0.875rem;">🚨 <strong>Failing Grade:</strong> Seek help from your instructor immediately.</span>
            </div>
            <?php endif; ?>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <?php else: ?>
    <div class="card" style="text-align: center; padding: 3rem;">
        <p style="color: #999; font-size: 1.125rem; margin-bottom: 1rem;">📚 No active courses</p>
        <p style="color: #666; font-size: 0.875rem;">Contact your academic advisor to enroll in courses.</p>
    </div>
    <?php endif; ?>

    <!-- Completed Courses -->
    <?php if($completedCourses->count() > 0): ?>
    <h2 style="margin: 2rem 0 1.5rem 0; color: #2c3e50;">Completed Courses (<?php echo e($completedCourses->count()); ?>)</h2>
    <div class="card">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 2px solid #ddd;">
                    <th style="text-align: left; padding: 0.75rem; font-size: 0.875rem;">Course Code</th>
                    <th style="text-align: left; padding: 0.75rem; font-size: 0.875rem;">Course Name</th>
                    <th style="text-align: center; padding: 0.75rem; font-size: 0.875rem;">Credits</th>
                    <th style="text-align: center; padding: 0.75rem; font-size: 0.875rem;">Final Grade</th>
                    <th style="text-align: center; padding: 0.75rem; font-size: 0.875rem;">Completed</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $completedCourses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $enrollment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $finalGrade = \App\Models\Grade::where('student_id', $student->id)
                        ->where('course_id', $enrollment->course_id)
                        ->first();
                ?>
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 0.75rem;"><?php echo e($enrollment->course->course_code); ?></td>
                    <td style="padding: 0.75rem;"><?php echo e($enrollment->course->course_name); ?></td>
                    <td style="padding: 0.75rem; text-align: center;"><?php echo e($enrollment->course->credits); ?></td>
                    <td style="padding: 0.75rem; text-align: center; font-weight: 600; color: #27ae60;">
                        <?php echo e($finalGrade ? $finalGrade->grade : 'N/A'); ?>

                    </td>
                    <td style="padding: 0.75rem; text-align: center; color: #666; font-size: 0.875rem;">
                        <?php echo e($enrollment->updated_at->format('M Y')); ?>

                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.student', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Bensonngulube\OneDrive - zescozambia\Desktop\Laravel test\resources\views/enrollments/student-courses.blade.php ENDPATH**/ ?>