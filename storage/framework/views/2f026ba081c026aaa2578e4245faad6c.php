

<?php $__env->startSection('content'); ?>
<div class="container" style="max-width: 600px; margin: 30px auto;">
    <h2>Add Grade</h2>

    <?php if($errors->any()): ?>
        <div class="alert alert-error" style="background: #e74c3c; color: white; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
            <ul style="margin: 0; padding-left: 20px;">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?php echo e(route('grades.store')); ?>" method="POST" style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
        <?php echo csrf_field(); ?>

        <div style="margin-bottom: 20px;">
            <label for="student_id" style="display: block; margin-bottom: 8px; font-weight: bold;">Student:</label>
            <select id="student_id" name="student_id" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                <option value="">Select Student</option>
                <?php $__currentLoopData = \App\Models\Student::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($student->id); ?>" <?php echo e(old('student_id') == $student->id ? 'selected' : ''); ?>>
                        <?php echo e($student->first_name); ?> <?php echo e($student->last_name); ?> (<?php echo e($student->student_id); ?>)
                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

        <div style="margin-bottom: 20px;">
            <label for="course_id" style="display: block; margin-bottom: 8px; font-weight: bold;">Course:</label>
            <select id="course_id" name="course_id" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                <option value="">Select Course</option>
                <?php
                    $coursesByDept = \App\Models\Course::all()->groupBy('department');
                ?>
                <?php $__currentLoopData = $coursesByDept; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department => $courses): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <optgroup label="<?php echo e($department ?? 'Other Courses'); ?>">
                        <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($course->id); ?>" <?php echo e(old('course_id') == $course->id ? 'selected' : ''); ?>>
                                <?php echo e($course->course_code); ?> - <?php echo e($course->course_name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </optgroup>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

        <div style="margin-bottom: 20px;">
            <label for="assignment_score" style="display: block; margin-bottom: 8px; font-weight: bold;">Assignment Score (0-100):</label>
            <input type="number" id="assignment_score" name="assignment_score" min="0" max="100" step="0.01" 
                   style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;" 
                   value="<?php echo e(old('assignment_score')); ?>" onchange="calculateOverall()">
        </div>

        <div style="margin-bottom: 20px;">
            <label for="midterm_score" style="display: block; margin-bottom: 8px; font-weight: bold;">Midterm Score (0-100):</label>
            <input type="number" id="midterm_score" name="midterm_score" min="0" max="100" step="0.01" 
                   style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;" 
                   value="<?php echo e(old('midterm_score')); ?>" onchange="calculateOverall()">
        </div>

        <div style="margin-bottom: 20px;">
            <label for="final_score" style="display: block; margin-bottom: 8px; font-weight: bold;">Final Score (0-100):</label>
            <input type="number" id="final_score" name="final_score" min="0" max="100" step="0.01" 
                   style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;" 
                   value="<?php echo e(old('final_score')); ?>" onchange="calculateOverall()">
        </div>

        <div style="margin-bottom: 20px; background: #f0f8ff; padding: 15px; border-radius: 4px; border: 2px solid #3498db;">
            <label for="overall_score" style="display: block; margin-bottom: 8px; font-weight: bold; color: #2c3e50;">Overall Score (Auto-calculated):</label>
            <input type="number" id="overall_score" name="overall_score" min="0" max="100" step="0.01" readonly
                   style="width: 100%; padding: 10px; border: 1px solid #3498db; border-radius: 4px; background: white; font-size: 18px; font-weight: bold;" 
                   value="<?php echo e(old('overall_score')); ?>">
            <small style="color: #666; display: block; margin-top: 5px;">Formula: (Assignment × 30%) + (Midterm × 30%) + (Final × 40%)</small>
        </div>

        <div style="margin-bottom: 20px;">
            <label for="remarks" style="display: block; margin-bottom: 8px; font-weight: bold;">Remarks:</label>
            <textarea id="remarks" name="remarks" rows="3" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;"><?php echo e(old('remarks')); ?></textarea>
        </div>

        <div style="display: flex; gap: 10px;">
            <button type="submit" class="btn-success" style="flex: 1; padding: 12px; background: #27ae60; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px;">Add Grade</button>
            <a href="<?php echo e(route('grades.index')); ?>" class="btn-secondary" style="flex: 1; padding: 12px; background: #95a5a6; color: white; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; text-align: center; font-size: 16px;">Cancel</a>
        </div>
    </form>

    <script>
        function calculateOverall() {
            const assignment = parseFloat(document.getElementById('assignment_score').value) || 0;
            const midterm = parseFloat(document.getElementById('midterm_score').value) || 0;
            const final = parseFloat(document.getElementById('final_score').value) || 0;
            
            // Weighted calculation: Assignment 30%, Midterm 30%, Final 40%
            const overall = (assignment * 0.3) + (midterm * 0.3) + (final * 0.4);
            
            document.getElementById('overall_score').value = overall.toFixed(2);
        }
    </script>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make(auth()->user()->role === 'super_admin' ? 'layouts.super-admin' : (auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.app'), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Bensonngulube\OneDrive - zescozambia\Desktop\Laravel test\resources\views/grades/create.blade.php ENDPATH**/ ?>