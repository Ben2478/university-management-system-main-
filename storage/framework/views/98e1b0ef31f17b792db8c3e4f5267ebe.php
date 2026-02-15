

<?php $__env->startSection('content'); ?>
<div class="container" style="padding: 2rem 0;">
    <div style="margin-bottom: 2rem;">
        <h1 style="margin: 0 0 0.5rem 0;">My Attendance</h1>
        <p style="margin: 0; color: #666;">Track your class attendance across all courses</p>
    </div>

    <!-- Stats Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
        <div class="card" style="border-left: 4px solid #3498db; padding: 1.25rem;">
            <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase;">Total Classes</p>
            <h2 style="margin: 0.5rem 0 0 0; color: #3498db; font-size: 2rem;"><?php echo e($stats['total']); ?></h2>
        </div>
        <div class="card" style="border-left: 4px solid #27ae60; padding: 1.25rem;">
            <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase;">Present</p>
            <h2 style="margin: 0.5rem 0 0 0; color: #27ae60; font-size: 2rem;"><?php echo e($stats['present']); ?></h2>
        </div>
        <div class="card" style="border-left: 4px solid #e74c3c; padding: 1.25rem;">
            <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase;">Absent</p>
            <h2 style="margin: 0.5rem 0 0 0; color: #e74c3c; font-size: 2rem;"><?php echo e($stats['absent']); ?></h2>
        </div>
        <div class="card" style="border-left: 4px solid #f39c12; padding: 1.25rem;">
            <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase;">Attendance Rate</p>
            <h2 style="margin: 0.5rem 0 0 0; 
                color: <?php echo e($stats['attendance_rate'] >= 75 ? '#27ae60' : ($stats['attendance_rate'] >= 60 ? '#f39c12' : '#e74c3c')); ?>; 
                font-size: 2rem;">
                <?php echo e($stats['attendance_rate']); ?>%
            </h2>
        </div>
    </div>

    <!-- Attendance Alert -->
    <?php if($stats['attendance_rate'] < 75): ?>
    <div style="margin-bottom: 2rem; padding: 1rem; background: <?php echo e($stats['attendance_rate'] < 60 ? '#f8d7da' : '#fff3cd'); ?>; border-left: 4px solid <?php echo e($stats['attendance_rate'] < 60 ? '#e74c3c' : '#f39c12'); ?>; border-radius: 0.25rem;">
        <p style="margin: 0; color: <?php echo e($stats['attendance_rate'] < 60 ? '#721c24' : '#856404'); ?>; font-weight: 600;">
            <?php if($stats['attendance_rate'] < 60): ?>
                🚨 Critical: Your attendance is below 60%. You may be at risk of failing courses due to low attendance.
            <?php else: ?>
                ⚠️ Warning: Your attendance is below 75%. Please improve your attendance to stay on track.
            <?php endif; ?>
        </p>
    </div>
    <?php elseif($stats['attendance_rate'] >= 90): ?>
    <div style="margin-bottom: 2rem; padding: 1rem; background: #d4edda; border-left: 4px solid #27ae60; border-radius: 0.25rem;">
        <p style="margin: 0; color: #155724; font-weight: 600;">
            🎉 Excellent! You have a <?php echo e($stats['attendance_rate']); ?>% attendance rate. Keep up the great work!
        </p>
    </div>
    <?php endif; ?>

    <!-- Attendance Records -->
    <?php if($attendances->count() > 0): ?>
    <div class="card">
        <h3 style="margin: 0 0 1.5rem 0;">Attendance Record</h3>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid #ddd; background: #f8f9fa;">
                        <th style="text-align: left; padding: 1rem; font-size: 0.875rem;">Date</th>
                        <th style="text-align: left; padding: 1rem; font-size: 0.875rem;">Course Code</th>
                        <th style="text-align: left; padding: 1rem; font-size: 0.875rem;">Course Name</th>
                        <th style="text-align: center; padding: 1rem; font-size: 0.875rem;">Status</th>
                        <th style="text-align: left; padding: 1rem; font-size: 0.875rem;">Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $attendances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attendance): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 1rem;"><?php echo e(\Carbon\Carbon::parse($attendance->attendance_date)->format('M d, Y')); ?></td>
                        <td style="padding: 1rem; font-weight: 600;"><?php echo e($attendance->course->course_code); ?></td>
                        <td style="padding: 1rem;"><?php echo e($attendance->course->course_name); ?></td>
                        <td style="padding: 1rem; text-align: center;">
                            <span style="padding: 0.5rem 1rem; border-radius: 0.25rem; font-weight: 600; font-size: 0.875rem;
                                background-color: <?php echo e($attendance->status === 'present' ? '#d4edda' : ($attendance->status === 'late' ? '#fff3cd' : ($attendance->status === 'excused' ? '#d1ecf1' : '#f8d7da'))); ?>;
                                color: <?php echo e($attendance->status === 'present' ? '#155724' : ($attendance->status === 'late' ? '#856404' : ($attendance->status === 'excused' ? '#0c5460' : '#721c24'))); ?>;">
                                <?php if($attendance->status === 'present'): ?>
                                    ✓ Present
                                <?php elseif($attendance->status === 'late'): ?>
                                    ⏰ Late
                                <?php elseif($attendance->status === 'excused'): ?>
                                    ℹ️ Excused
                                <?php else: ?>
                                    ✗ Absent
                                <?php endif; ?>
                            </span>
                        </td>
                        <td style="padding: 1rem; color: #666; font-size: 0.875rem;">
                            <?php echo e($attendance->remarks ?? '-'); ?>

                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php else: ?>
    <div class="card" style="text-align: center; padding: 3rem;">
        <p style="color: #999; font-size: 1.125rem; margin-bottom: 1rem;">📅 No attendance records yet</p>
        <p style="color: #666; font-size: 0.875rem;">Your attendance will appear here once classes begin.</p>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.student', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Bensonngulube\OneDrive - zescozambia\Desktop\Laravel test\resources\views/attendance/student-view.blade.php ENDPATH**/ ?>