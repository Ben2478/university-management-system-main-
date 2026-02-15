

<?php $__env->startSection('content'); ?>
<div class="container" style="padding: 2rem 0;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 style="margin: 0 0 0.5rem 0;">Profile Settings</h1>
            <p style="margin: 0; color: #666;">Manage your account information and preferences</p>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div style="padding: 1rem; margin-bottom: 2rem; background: #d4edda; border-left: 4px solid #27ae60; border-radius: 0.25rem;">
            <span style="color: #155724; font-weight: 600;">✓ <?php echo e(session('success')); ?></span>
        </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <div style="padding: 1rem; margin-bottom: 2rem; background: #f8d7da; border-left: 4px solid #e74c3c; border-radius: 0.25rem;">
            <ul style="margin: 0; padding-left: 1.5rem; color: #721c24;">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 2rem;">
        <!-- User Info Card -->
        <div class="card">
            <div style="text-align: center; padding-bottom: 1.5rem; border-bottom: 1px solid #ecf0f1;">
                <div style="width: 100px; height: 100px; margin: 0 auto 1rem; border-radius: 50%; background: linear-gradient(135deg, 
                    <?php echo e(auth()->user()->isSuperAdmin() ? '#2c3e50, #34495e' : (auth()->user()->isAdmin() ? '#c0392b, #e74c3c' : (auth()->user()->isStudent() ? '#2980b9, #3498db' : '#27ae60, #2ecc71'))); ?>);
                    display: flex; align-items: center; justify-content: center; color: white; font-size: 2.5rem; font-weight: bold;">
                    <?php echo e(strtoupper(substr(auth()->user()->name, 0, 1))); ?>

                </div>
                <h3 style="margin: 0 0 0.5rem 0;"><?php echo e(auth()->user()->name); ?></h3>
                <span style="padding: 0.375rem 0.75rem; border-radius: 0.25rem; font-size: 0.875rem; font-weight: 600; background-color: 
                    <?php echo e(auth()->user()->isSuperAdmin() ? '#2c3e50' : (auth()->user()->isAdmin() ? '#c0392b' : (auth()->user()->isStudent() ? '#2980b9' : '#27ae60'))); ?>;
                    color: white;">
                    <?php if(auth()->user()->isSuperAdmin()): ?>
                        Super Administrator
                    <?php elseif(auth()->user()->isAdmin()): ?>
                        Accountant
                    <?php elseif(auth()->user()->isStudent()): ?>
                        Student
                    <?php else: ?>
                        Parent/Guardian
                    <?php endif; ?>
                </span>
            </div>

            <?php if(auth()->user()->isStudent() && auth()->user()->student): ?>
            <div style="padding-top: 1.5rem;">
                <h3 style="margin: 0 0 1rem 0; color: #2c3e50;">Student Information</h3>
                <div style="margin-bottom: 0.75rem;">
                    <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase;">Student ID</p>
                    <p style="margin: 0.25rem 0 0 0; font-weight: 600; color: #2c3e50;"><?php echo e(auth()->user()->student->student_id); ?></p>
                </div>
                <div style="margin-bottom: 0.75rem;">
                    <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase;">Program</p>
                    <p style="margin: 0.25rem 0 0 0; font-weight: 600; color: #2c3e50;"><?php echo e(auth()->user()->student->program ?? 'Not assigned'); ?></p>
                </div>
                <div style="margin-bottom: 0.75rem;">
                    <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase;">Year/Semester</p>
                    <p style="margin: 0.25rem 0 0 0; font-weight: 600; color: #2c3e50;">Year <?php echo e(auth()->user()->student->year ?? 'N/A'); ?> - Semester <?php echo e(auth()->user()->student->semester ?? 'N/A'); ?></p>
                </div>
                <div style="margin-bottom: 0.75rem;">
                    <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase;">Status</p>
                    <span style="padding: 0.25rem 0.5rem; background: <?php echo e(auth()->user()->student->status === 'active' ? '#d4edda' : '#f8d7da'); ?>; color: <?php echo e(auth()->user()->student->status === 'active' ? '#155724' : '#721c24'); ?>; border-radius: 0.25rem; font-size: 0.75rem; font-weight: 600; text-transform: uppercase;">
                        <?php echo e(auth()->user()->student->status ?? 'Unknown'); ?>

                    </span>
                </div>
            </div>
            <hr style="margin: 1.5rem 0; border: none; border-top: 1px solid #ecf0f1;">
            <?php endif; ?>

            <div>
                <h3 style="margin: 0 0 1rem 0; color: #2c3e50;">Account Details</h3>
                <div style="margin-bottom: 0.75rem;">
                    <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase;">Email</p>
                    <p style="margin: 0.25rem 0 0 0; font-weight: 500; color: #2c3e50; word-break: break-word;"><?php echo e(auth()->user()->email); ?></p>
                </div>
                <div style="margin-bottom: 0.75rem;">
                    <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase;">Phone</p>
                    <p style="margin: 0.25rem 0 0 0; font-weight: 500; color: #2c3e50;"><?php echo e(auth()->user()->phone ?? 'Not provided'); ?></p>
                </div>
                <div style="margin-bottom: 0.75rem;">
                    <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase;">Member Since</p>
                    <p style="margin: 0.25rem 0 0 0; font-weight: 500; color: #2c3e50;"><?php echo e(auth()->user()->created_at->format('M d, Y')); ?></p>
                </div>
                <div style="margin-bottom: 0.75rem;">
                    <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase;">Last Login</p>
                    <p style="margin: 0.25rem 0 0 0; font-weight: 500; color: #2c3e50;"><?php echo e(auth()->user()->updated_at->diffForHumans()); ?></p>
                </div>
            </div>
        </div>

        <!-- Edit Form -->
        <div class="card">
            <h3 style="margin: 0 0 1.5rem 0; color: #2c3e50; border-bottom: 2px solid #3498db; padding-bottom: 0.75rem;">✏️ Edit Profile Information</h3>

            <form action="<?php echo e(route('profile.update')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                    <div>
                        <label for="name" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50;">Full Name *</label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            value="<?php echo e(old('name', auth()->user()->name)); ?>"
                            style="width: 100%; padding: 0.75rem; border: 2px solid #ecf0f1; border-radius: 0.375rem; font-size: 1rem; transition: border-color 0.2s;"
                            onfocus="this.style.borderColor='#3498db'" 
                            onblur="this.style.borderColor='#ecf0f1'"
                            required
                        >
                        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span style="color: #e74c3c; font-size: 0.875rem; display: block; margin-top: 0.25rem;"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label for="email" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50;">Email Address *</label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="<?php echo e(old('email', auth()->user()->email)); ?>"
                            style="width: 100%; padding: 0.75rem; border: 2px solid #ecf0f1; border-radius: 0.375rem; font-size: 1rem; transition: border-color 0.2s;"
                            onfocus="this.style.borderColor='#3498db'" 
                            onblur="this.style.borderColor='#ecf0f1'"
                            required
                        >
                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span style="color: #e74c3c; font-size: 0.875rem; display: block; margin-top: 0.25rem;"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                    <div>
                        <label for="phone" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50;">Phone Number</label>
                        <input 
                            type="tel" 
                            id="phone" 
                            name="phone" 
                            value="<?php echo e(old('phone', auth()->user()->phone)); ?>"
                            style="width: 100%; padding: 0.75rem; border: 2px solid #ecf0f1; border-radius: 0.375rem; font-size: 1rem; transition: border-color 0.2s;"
                            onfocus="this.style.borderColor='#3498db'" 
                            onblur="this.style.borderColor='#ecf0f1'"
                            placeholder="+260 XXX XXX XXX"
                        >
                        <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span style="color: #e74c3c; font-size: 0.875rem; display: block; margin-top: 0.25rem;"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <?php if(auth()->user()->isStudent() && auth()->user()->student): ?>
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50;">Student ID</label>
                        <input 
                            type="text" 
                            value="<?php echo e(auth()->user()->student->student_id); ?>"
                            style="width: 100%; padding: 0.75rem; border: 2px solid #ecf0f1; border-radius: 0.375rem; font-size: 1rem; background: #f8f9fa; color: #666;"
                            readonly
                        >
                        <span style="color: #666; font-size: 0.75rem; display: block; margin-top: 0.25rem;">🔒 Read-only field</span>
                    </div>
                    <?php endif; ?>
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label for="address" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50;">Home Address</label>
                    <textarea 
                        id="address" 
                        name="address" 
                        style="width: 100%; padding: 0.75rem; border: 2px solid #ecf0f1; border-radius: 0.375rem; font-size: 1rem; resize: vertical; min-height: 100px; transition: border-color 0.2s;"
                        onfocus="this.style.borderColor='#3498db'" 
                        onblur="this.style.borderColor='#ecf0f1'"
                        placeholder="Enter your full address"
                    ><?php echo e(old('address', auth()->user()->address)); ?></textarea>
                    <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span style="color: #e74c3c; font-size: 0.875rem; display: block; margin-top: 0.25rem;"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <?php if(auth()->user()->isStudent() && auth()->user()->student): ?>
                <div style="padding: 1rem; background: #e8f4f8; border-left: 4px solid #3498db; border-radius: 0.375rem; margin-bottom: 1.5rem;">
                    <p style="margin: 0; color: #2c3e50; font-size: 0.875rem;">
                        <strong>ℹ️ Note:</strong> To update your academic information (Program, Year, Semester), please contact the registrar's office.
                    </p>
                </div>
                <?php endif; ?>

                <hr style="margin: 2rem 0; border: none; border-top: 2px solid #ecf0f1;">

                <h4 style="margin: 0 0 1.5rem 0; color: #2c3e50; border-bottom: 2px solid #e74c3c; padding-bottom: 0.75rem;">🔒 Change Password (Optional)</h4>

                <div style="padding: 1rem; background: #fff3cd; border-left: 4px solid #ffc107; border-radius: 0.375rem; margin-bottom: 1.5rem;">
                    <p style="margin: 0; color: #856404; font-size: 0.875rem;">
                        <strong>⚠️ Password Requirements:</strong> Must be at least 8 characters long. Leave blank to keep your current password.
                    </p>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                    <div>
                        <label for="password" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50;">New Password</label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            style="width: 100%; padding: 0.75rem; border: 2px solid #ecf0f1; border-radius: 0.375rem; font-size: 1rem; transition: border-color 0.2s;"
                            onfocus="this.style.borderColor='#3498db'" 
                            onblur="this.style.borderColor='#ecf0f1'"
                            placeholder="Enter new password"
                        >
                        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span style="color: #e74c3c; font-size: 0.875rem; display: block; margin-top: 0.25rem;"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label for="password_confirmation" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50;">Confirm Password</label>
                        <input 
                            type="password" 
                            id="password_confirmation" 
                            name="password_confirmation" 
                            style="width: 100%; padding: 0.75rem; border: 2px solid #ecf0f1; border-radius: 0.375rem; font-size: 1rem; transition: border-color 0.2s;"
                            onfocus="this.style.borderColor='#3498db'" 
                            onblur="this.style.borderColor='#ecf0f1'"
                            placeholder="Confirm new password"
                        >
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-top: 2rem;">
                    <button type="submit" class="btn" style="background: #27ae60; color: white; padding: 1rem; border: none; border-radius: 0.375rem; font-size: 1rem; font-weight: 600; cursor: pointer; transition: background 0.2s;" onmouseover="this.style.background='#229954'" onmouseout="this.style.background='#27ae60'">
                        💾 Save Changes
                    </button>
                    <a href="<?php echo e(route('dashboard')); ?>" class="btn" style="background: #95a5a6; color: white; padding: 1rem; text-align: center; text-decoration: none; border-radius: 0.375rem; font-size: 1rem; font-weight: 600; display: flex; align-items: center; justify-content: center; transition: background 0.2s;" onmouseover="this.style.background='#7f8c8d'" onmouseout="this.style.background='#95a5a6'">
                        ✖️ Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Security Tips Card -->
    <div class="card" style="margin-top: 2rem; background: #f8f9fa; border-left: 4px solid #9b59b6;">
        <h3 style="margin: 0 0 1rem 0; color: #2c3e50;">🛡️ Account Security Tips</h3>
        <ul style="margin: 0; padding-left: 1.5rem; color: #555; line-height: 1.8;">
            <li><strong>Strong Password:</strong> Use a mix of uppercase, lowercase, numbers, and special characters</li>
            <li><strong>Don't Share:</strong> Never share your password with anyone, including administrators</li>
            <li><strong>Regular Updates:</strong> Change your password every 3-6 months for better security</li>
            <li><strong>Logout:</strong> Always logout when using shared or public computers</li>
            <li><strong>Suspicious Activity:</strong> Report any unusual account activity immediately to IT support</li>
        </ul>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make(auth()->user()->isStudent() ? 'layouts.student' : (auth()->user()->isAdmin() ? 'layouts.admin' : (auth()->user()->isSuperAdmin() ? 'layouts.super-admin' : 'layouts.parent')), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Bensonngulube\OneDrive - zescozambia\Desktop\Laravel test\resources\views/profile/edit.blade.php ENDPATH**/ ?>