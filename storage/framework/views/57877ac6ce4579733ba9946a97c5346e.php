

<?php $__env->startSection('content'); ?>
<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <a href="<?php echo e(route('students.index')); ?>" style="color: #3498db; text-decoration: none;">← Back to Students</a>
            <h1 style="margin-top: 0.5rem;"><?php echo e($student->full_name); ?></h1>
            <p style="color: #666;">ID: <?php echo e($student->student_id); ?></p>
        </div>
        <div style="display: flex; gap: 1rem;">
            <a href="<?php echo e(route('students.edit', $student)); ?>" class="btn">Edit</a>
            <form action="<?php echo e(route('students.destroy', $student)); ?>" method="POST" style="display: inline;" onsubmit="return confirm('Delete this student?');">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
        </div>
    </div>

    <!-- Login Credentials Alert (shown only when student is first created) -->
    <?php if(session('login_credentials')): ?>
    <div style="background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 1.5rem; border-radius: 8px; margin-bottom: 2rem;">
        <h3 style="margin-top: 0; color: #155724;">✓ Student Account Created Successfully!</h3>
        <p style="margin-bottom: 1rem;"><strong>Login credentials have been generated. Please save these details and share them with the student:</strong></p>
        
        <div style="background: white; padding: 1rem; border-radius: 5px; border: 1px solid #c3e6cb;">
            <p style="margin: 0.5rem 0;"><strong>Email/Username:</strong> <code style="background: #f8f9fa; padding: 0.25rem 0.5rem; border-radius: 3px;"><?php echo e(session('login_credentials')['email']); ?></code></p>
            <p style="margin: 0.5rem 0;"><strong>Student ID:</strong> <code style="background: #f8f9fa; padding: 0.25rem 0.5rem; border-radius: 3px;"><?php echo e(session('login_credentials')['student_id']); ?></code></p>
            <p style="margin: 0.5rem 0;"><strong>Temporary Password:</strong> <code style="background: #fff3cd; padding: 0.25rem 0.5rem; border-radius: 3px; color: #856404;"><?php echo e(session('login_credentials')['password']); ?></code></p>
        </div>
        
        <p style="margin-top: 1rem; margin-bottom: 0; font-size: 0.9rem;">
            <strong>⚠️ Important:</strong> These credentials will only be shown once. Make sure to copy them or print this page. 
            The student can use these credentials to log in at <strong><?php echo e(url('/login')); ?></strong>
        </p>
    </div>
    <?php endif; ?>

    <!-- Balance & Payment Section -->
    <div class="card" style="margin-bottom: 2rem;">
        <h2>Financial Summary</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
            <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 1.5rem; border-radius: 8px;">
                <p style="opacity: 0.9;">Year & Semester</p>
                <p style="font-size: 1.5rem; font-weight: bold;">Year <?php echo e($student->year); ?>, Semester <?php echo e($student->semester); ?></p>
            </div>
            <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 1.5rem; border-radius: 8px;">
                <p style="opacity: 0.9;">Tuition Fee</p>
                <p style="font-size: 1.5rem; font-weight: bold;">K<?php echo e(number_format($student->tuition_fee, 2)); ?></p>
            </div>
            <div style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; padding: 1.5rem; border-radius: 8px;">
                <p style="opacity: 0.9;">Amount Paid</p>
                <p style="font-size: 1.5rem; font-weight: bold;">K<?php echo e(number_format($student->getPaidAmount(), 2)); ?></p>
            </div>
            <div style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: white; padding: 1.5rem; border-radius: 8px;">
                <p style="opacity: 0.9;">Balance Due</p>
                <p style="font-size: 1.5rem; font-weight: bold;">
                    <?php if($student->getBalance() <= 0): ?>
                        Fully Paid ✓
                    <?php else: ?>
                        K<?php echo e(number_format($student->getBalance(), 2)); ?>

                    <?php endif; ?>
                </p>
            </div>
        </div>

        <div style="display: flex; gap: 1rem;">
            <a href="<?php echo e(route('receipts.create', ['student_id' => $student->id])); ?>" class="btn btn-success">+ Upload Receipt</a>
            <a href="<?php echo e(route('receipts.student-receipts', $student)); ?>" class="btn">View Payment History</a>
        </div>
    </div>

    <!-- Personal Information -->
    <div class="card" style="margin-bottom: 2rem;">
        <h2>Personal Information</h2>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
            <div>
                <p><strong>Email:</strong> <?php echo e($student->email); ?></p>
                <p><strong>NRC:</strong> <?php echo e($student->nrc ?? 'N/A'); ?></p>
                <p><strong>Phone:</strong> <?php echo e($student->phone ?? 'N/A'); ?></p>
                <p><strong>Date of Birth:</strong> <?php echo e($student->date_of_birth?->format('M d, Y') ?? 'N/A'); ?></p>
                <p><strong>Gender:</strong> <?php echo e(ucfirst($student->gender)); ?></p>
                <p><strong>Age:</strong> <?php echo e($student->age ?? 'N/A'); ?> years old</p>
            </div>
            <div>
                <p><strong>Address:</strong> <?php echo e($student->address ?? 'N/A'); ?></p>
                <p><strong>City:</strong> <?php echo e($student->city ?? 'N/A'); ?></p>
                <p><strong>State:</strong> <?php echo e($student->state ?? 'N/A'); ?></p>
                <p><strong>Postal Code:</strong> <?php echo e($student->postal_code ?? 'N/A'); ?></p>
                <p><strong>Enrollment Date:</strong> <?php echo e($student->enrollment_date?->format('M d, Y') ?? 'N/A'); ?></p>
                <p><strong>Status:</strong> <span class="badge badge-success"><?php echo e(ucfirst($student->status)); ?></span></p>
            </div>
        </div>
    </div>

    <!-- Enrolled Courses -->
    <div class="card" style="margin-bottom: 2rem;">
        <h2>Enrolled Courses</h2>
        <?php if($student->enrollments->count() > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Course Code</th>
                        <th>Course Name</th>
                        <th>Credits</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $student->enrollments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $enrollment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($enrollment->course->course_code); ?></td>
                            <td><?php echo e($enrollment->course->name); ?></td>
                            <td><?php echo e($enrollment->course->credits ?? 'N/A'); ?></td>
                            <td><span class="badge badge-info"><?php echo e(ucfirst($enrollment->status)); ?></span></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="color: #999; text-align: center; padding: 2rem;">No courses enrolled</p>
        <?php endif; ?>
    </div>

    <!-- Recent Payment Receipts -->
    <div class="card">
        <h2>Recent Payment Receipts</h2>
        <?php if($student->receipts->count() > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>File</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $student->receipts()->latest()->take(5)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $receipt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($receipt->uploaded_at->format('M d, Y')); ?></td>
                            <td>K<?php echo e(number_format($receipt->amount, 2)); ?></td>
                            <td>
                                <span class="badge 
                                    <?php if($receipt->status === 'pending'): ?> badge-warning
                                    <?php elseif($receipt->status === 'approved'): ?> badge-success
                                    <?php else: ?> badge-danger
                                    <?php endif; ?>">
                                    <?php echo e(ucfirst($receipt->status)); ?>

                                </span>
                            </td>
                            <td><?php echo e(Str::limit($receipt->original_filename, 25)); ?></td>
                            <td><a href="<?php echo e(route('receipts.show', $receipt)); ?>" style="color: #3498db; text-decoration: none;">View</a></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
            <div style="margin-top: 1rem; text-align: center;">
                <a href="<?php echo e(route('receipts.student-receipts', $student)); ?>" style="color: #3498db; text-decoration: none;">View All Receipts →</a>
            </div>
        <?php else: ?>
            <p style="color: #999; text-align: center; padding: 2rem;">No payment receipts uploaded yet</p>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Bensonngulube\OneDrive - zescozambia\Desktop\Laravel test\resources\views/students/show.blade.php ENDPATH**/ ?>