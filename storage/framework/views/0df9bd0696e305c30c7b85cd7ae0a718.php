

<?php $__env->startSection('content'); ?>
<div class="container" style="padding: 2rem 0;">
    <div style="margin-bottom: 2rem;">
        <h1 style="margin: 0 0 0.5rem 0;">My Grades</h1>
        <p style="margin: 0; color: #666;">View your academic performance and GPA</p>
    </div>

    <!-- Stats Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
        <div class="card" style="border-left: 4px solid #3498db; padding: 1.25rem;">
            <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase; font-weight: 600;">Total Courses</p>
            <h2 style="margin: 0.5rem 0 0 0; color: #3498db; font-size: 2rem;"><?php echo e($stats['total']); ?></h2>
        </div>
        <div class="card" style="border-left: 4px solid #9b59b6; padding: 1.25rem;">
            <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase; font-weight: 600;">GPA (4.0 Scale)</p>
            <h2 style="margin: 0.5rem 0 0 0; color: #9b59b6; font-size: 2rem;"><?php echo e($stats['gpa']); ?></h2>
        </div>
        <div class="card" style="border-left: 4px solid #27ae60; padding: 1.25rem;">
            <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase; font-weight: 600;">Average Score</p>
            <h2 style="margin: 0.5rem 0 0 0; color: #27ae60; font-size: 2rem;"><?php echo e($stats['average']); ?>%</h2>
        </div>
        <div class="card" style="border-left: 4px solid #f39c12; padding: 1.25rem;">
            <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase; font-weight: 600;">Passing Courses</p>
            <h2 style="margin: 0.5rem 0 0 0; color: #f39c12; font-size: 2rem;"><?php echo e($stats['passing']); ?>/<?php echo e($stats['total']); ?></h2>
        </div>
        <div class="card" style="border-left: 4px solid #1abc9c; padding: 1.25rem;">
            <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase; font-weight: 600;">Distinction (A/B)</p>
            <h2 style="margin: 0.5rem 0 0 0; color: #1abc9c; font-size: 2rem;"><?php echo e($stats['distinction']); ?></h2>
        </div>
        <div class="card" style="border-left: 4px solid #e74c3c; padding: 1.25rem;">
            <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase; font-weight: 600;">Need Attention</p>
            <h2 style="margin: 0.5rem 0 0 0; color: #e74c3c; font-size: 2rem;"><?php echo e($stats['need_attention']); ?></h2>
        </div>
    </div>

    <?php if($grades->count() > 0): ?>
    <!-- Performance Insights -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
        <!-- At Risk Alert -->
        <?php if($atRiskCourses->count() > 0): ?>
        <div style="padding: 1rem; background: #fff3cd; border-left: 4px solid #ffc107; border-radius: 0.25rem;">
            <h4 style="margin: 0 0 0.5rem 0; color: #856404;">⚠️ Courses Needing Attention</h4>
            <p style="margin: 0; color: #856404; font-size: 0.875rem;">You have <?php echo e($atRiskCourses->count()); ?> course(s) below 60%</p>
            <?php $__currentLoopData = $atRiskCourses->take(2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <p style="margin: 0.5rem 0 0 0; color: #856404; font-size: 0.875rem;">
                    • <?php echo e($course->course->course_code); ?>: <?php echo e($course->overall_score); ?>%
                </p>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php endif; ?>

        <!-- Best Performance -->
        <?php if($bestCourse): ?>
        <div style="padding: 1rem; background: #d4edda; border-left: 4px solid #27ae60; border-radius: 0.25rem;">
            <h4 style="margin: 0 0 0.5rem 0; color: #155724;">🏆 Top Performance</h4>
            <p style="margin: 0; color: #155724; font-weight: 600;"><?php echo e($bestCourse->course->course_code); ?></p>
            <p style="margin: 0.25rem 0 0 0; color: #155724; font-size: 0.875rem;"><?php echo e($bestCourse->overall_score); ?>% - Grade: <?php echo e($bestCourse->grade); ?></p>
        </div>
        <?php endif; ?>

        <!-- GPA Status -->
        <div style="padding: 1rem; background: <?php echo e($stats['gpa'] >= 3.0 ? '#e3f2fd' : ($stats['gpa'] >= 2.0 ? '#fff3e0' : '#ffebee')); ?>; border-left: 4px solid <?php echo e($stats['gpa'] >= 3.0 ? '#1976d2' : ($stats['gpa'] >= 2.0 ? '#f57c00' : '#c62828')); ?>; border-radius: 0.25rem;">
            <h4 style="margin: 0 0 0.5rem 0; color: <?php echo e($stats['gpa'] >= 3.0 ? '#0d47a1' : ($stats['gpa'] >= 2.0 ? '#e65100' : '#b71c1c')); ?>;">📊 GPA Status</h4>
            <p style="margin: 0; color: <?php echo e($stats['gpa'] >= 3.0 ? '#0d47a1' : ($stats['gpa'] >= 2.0 ? '#e65100' : '#b71c1c')); ?>; font-weight: 600;">
                <?php if($stats['gpa'] >= 3.5): ?>
                    Dean's List - Excellent!
                <?php elseif($stats['gpa'] >= 3.0): ?>
                    Good Standing
                <?php elseif($stats['gpa'] >= 2.0): ?>
                    Satisfactory
                <?php else: ?>
                    Academic Warning
                <?php endif; ?>
            </p>
            <p style="margin: 0.25rem 0 0 0; color: <?php echo e($stats['gpa'] >= 3.0 ? '#0d47a1' : ($stats['gpa'] >= 2.0 ? '#e65100' : '#b71c1c')); ?>; font-size: 0.875rem;">Current GPA: <?php echo e($stats['gpa']); ?></p>
        </div>
    </div>

    <!-- Grade Distribution -->
    <div class="card" style="margin-bottom: 2rem;">
        <h3 style="margin: 0 0 1rem 0;">Grade Distribution</h3>
        <div style="display: flex; gap: 1rem; align-items: end; height: 200px;">
            <?php $__currentLoopData = $gradeDistribution; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $letter => $count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $percentage = $stats['total'] > 0 ? ($count / $stats['total']) * 100 : 0;
                    $height = $percentage > 0 ? max($percentage, 10) : 0;
                    $colors = ['A' => '#27ae60', 'B' => '#3498db', 'C' => '#f39c12', 'D' => '#e67e22', 'F' => '#e74c3c'];
                ?>
                <div style="flex: 1; display: flex; flex-direction: column; align-items: center;">
                    <div style="width: 100%; background: <?php echo e($colors[$letter]); ?>; height: <?php echo e($height); ?>%; border-radius: 0.25rem 0.25rem 0 0; display: flex; align-items: start; justify-content: center; padding-top: 0.5rem; color: white; font-weight: 600; min-height: 30px;">
                        <?php if($count > 0): ?><?php echo e($count); ?><?php endif; ?>
                    </div>
                    <div style="margin-top: 0.5rem; font-weight: 600; color: #2c3e50;"><?php echo e($letter); ?></div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    <!-- Grades Table -->
    <div class="card">
        <h3 style="margin: 0 0 1.5rem 0;">Detailed Grade Report</h3>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid #ddd; background: #f8f9fa;">
                        <th style="text-align: left; padding: 1rem; font-size: 0.875rem;">Course Code</th>
                        <th style="text-align: left; padding: 1rem; font-size: 0.875rem;">Course Name</th>
                        <th style="text-align: center; padding: 1rem; font-size: 0.875rem;">Credits</th>
                        <th style="text-align: center; padding: 1rem; font-size: 0.875rem;">Midterm</th>
                        <th style="text-align: center; padding: 1rem; font-size: 0.875rem;">Final</th>
                        <th style="text-align: center; padding: 1rem; font-size: 0.875rem;">Assignments</th>
                        <th style="text-align: center; padding: 1rem; font-size: 0.875rem;">Overall</th>
                        <th style="text-align: center; padding: 1rem; font-size: 0.875rem;">Grade</th>
                        <th style="text-align: center; padding: 1rem; font-size: 0.875rem;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $grades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 1rem; font-weight: 600;"><?php echo e($grade->course->course_code); ?></td>
                        <td style="padding: 1rem;"><?php echo e($grade->course->course_name); ?></td>
                        <td style="padding: 1rem; text-align: center;"><?php echo e($grade->course->credits ?? 3); ?></td>
                        <td style="padding: 1rem; text-align: center;"><?php echo e($grade->midterm_score ?? '-'); ?></td>
                        <td style="padding: 1rem; text-align: center;"><?php echo e($grade->final_score ?? '-'); ?></td>
                        <td style="padding: 1rem; text-align: center;"><?php echo e($grade->assignment_score ?? '-'); ?></td>
                        <td style="padding: 1rem; text-align: center; font-weight: 600;"><?php echo e($grade->overall_score); ?>%</td>
                        <td style="padding: 1rem; text-align: center;">
                            <span style="padding: 0.5rem 1rem; border-radius: 0.25rem; font-weight: 600; font-size: 1.125rem;
                                background-color: <?php echo e(in_array($grade->grade, ['A', 'B']) ? '#d4edda' : (in_array($grade->grade, ['C']) ? '#fff3cd' : '#f8d7da')); ?>;
                                color: <?php echo e(in_array($grade->grade, ['A', 'B']) ? '#155724' : (in_array($grade->grade, ['C']) ? '#856404' : '#721c24')); ?>;">
                                <?php echo e($grade->grade); ?>

                            </span>
                        </td>
                        <td style="padding: 1rem; text-align: center;">
                            <?php if($grade->overall_score >= 50): ?>
                                <span style="color: #27ae60; font-weight: 600;">✓ Pass</span>
                            <?php else: ?>
                                <span style="color: #e74c3c; font-weight: 600;">✗ Fail</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php else: ?>
    <div class="card" style="text-align: center; padding: 3rem;">
        <p style="color: #999; font-size: 1.125rem; margin-bottom: 1rem;">📊 No grades recorded yet</p>
        <p style="color: #666; font-size: 0.875rem;">Your grades will appear here once they are posted by your instructors.</p>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.student', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Bensonngulube\OneDrive - zescozambia\Desktop\Laravel test\resources\views/grades/student-view.blade.php ENDPATH**/ ?>