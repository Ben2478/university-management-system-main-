<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student - Student Management System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
        }

        .form-card {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .form-card h1 {
            color: #2c3e50;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #2c3e50;
            font-weight: 600;
        }

        input, select, textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-family: inherit;
            font-size: 14px;
        }

        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 5px rgba(102, 126, 234, 0.3);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .error {
            color: #e74c3c;
            font-size: 12px;
            margin-top: 5px;
        }

        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 30px;
        }

        .btn {
            flex: 1;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .btn-primary {
            background: #667eea;
            color: white;
        }

        .btn-primary:hover {
            background: #764ba2;
        }

        .btn-secondary {
            background: #95a5a6;
            color: white;
        }

        .btn-secondary:hover {
            background: #7f8c8d;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-card">
            <h1>Add New Student</h1>

            <?php if($errors->any()): ?>
                <div class="alert alert-error">
                    <ul>
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('students.store')); ?>">
                <?php echo csrf_field(); ?>

                <div class="form-row">
                    <div class="form-group">
                        <label for="student_id">Student ID *</label>
                        <input type="text" id="student_id" name="student_id" required value="<?php echo e(old('student_id')); ?>">
                        <?php $__errorArgs = ['student_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="error"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="form-group">
                        <label for="nrc">National Registration Card (NRC)</label>
                        <input type="text" id="nrc" name="nrc" placeholder="######/##/#" value="<?php echo e(old('nrc')); ?>">
                        <?php $__errorArgs = ['nrc'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="error"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" required value="<?php echo e(old('email')); ?>">
                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="error"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="tel" id="phone" name="phone" value="<?php echo e(old('phone')); ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="first_name">First Name *</label>
                        <input type="text" id="first_name" name="first_name" required value="<?php echo e(old('first_name')); ?>">
                        <?php $__errorArgs = ['first_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="error"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="form-group">
                        <label for="last_name">Last Name *</label>
                        <input type="text" id="last_name" name="last_name" required value="<?php echo e(old('last_name')); ?>">
                        <?php $__errorArgs = ['last_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="error"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="tel" id="phone" name="phone" value="<?php echo e(old('phone')); ?>">
                    </div>

                    <div class="form-group">
                        <label for="gender">Gender *</label>
                        <select id="gender" name="gender" required>
                            <option value="">Select Gender</option>
                            <option value="male" <?php echo e(old('gender') === 'male' ? 'selected' : ''); ?>>Male</option>
                            <option value="female" <?php echo e(old('gender') === 'female' ? 'selected' : ''); ?>>Female</option>
                            <option value="other" <?php echo e(old('gender') === 'other' ? 'selected' : ''); ?>>Other</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="date_of_birth">Date of Birth *</label>
                        <input type="date" id="date_of_birth" name="date_of_birth" required value="<?php echo e(old('date_of_birth')); ?>">
                        <?php $__errorArgs = ['date_of_birth'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="error"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="form-group">
                        <label for="enrollment_date">Enrollment Date *</label>
                        <input type="date" id="enrollment_date" name="enrollment_date" required value="<?php echo e(old('enrollment_date')); ?>">
                        <?php $__errorArgs = ['enrollment_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="error"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="program">Program of Study *</label>
                    <select id="program" name="program" required>
                        <option value="">Select Program</option>
                        <option value="Computer Science" <?php echo e(old('program') === 'Computer Science' ? 'selected' : ''); ?>>Computer Science</option>
                        <option value="Engineering" <?php echo e(old('program') === 'Engineering' ? 'selected' : ''); ?>>Engineering</option>
                        <option value="Business Administration" <?php echo e(old('program') === 'Business Administration' ? 'selected' : ''); ?>>Business Administration</option>
                        <option value="Medicine" <?php echo e(old('program') === 'Medicine' ? 'selected' : ''); ?>>Medicine</option>
                        <option value="Education" <?php echo e(old('program') === 'Education' ? 'selected' : ''); ?>>Education</option>
                        <option value="Law" <?php echo e(old('program') === 'Law' ? 'selected' : ''); ?>>Law</option>
                        <option value="Arts & Humanities" <?php echo e(old('program') === 'Arts & Humanities' ? 'selected' : ''); ?>>Arts & Humanities</option>
                        <option value="Natural Sciences" <?php echo e(old('program') === 'Natural Sciences' ? 'selected' : ''); ?>>Natural Sciences</option>
                    </select>
                    <?php $__errorArgs = ['program'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="error"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="year">Year *</label>
                        <select id="year" name="year" required>
                            <option value="">Select Year</option>
                            <option value="1" <?php echo e(old('year') == '1' ? 'selected' : ''); ?>>Year 1</option>
                            <option value="2" <?php echo e(old('year') == '2' ? 'selected' : ''); ?>>Year 2</option>
                            <option value="3" <?php echo e(old('year') == '3' ? 'selected' : ''); ?>>Year 3</option>
                            <option value="4" <?php echo e(old('year') == '4' ? 'selected' : ''); ?>>Year 4</option>
                            <option value="5" <?php echo e(old('year') == '5' ? 'selected' : ''); ?>>Year 5</option>
                        </select>
                        <?php $__errorArgs = ['year'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="error"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="form-group">
                        <label for="semester">Semester *</label>
                        <select id="semester" name="semester" required>
                            <option value="">Select Semester</option>
                            <option value="1" <?php echo e(old('semester') == '1' ? 'selected' : ''); ?>>Semester 1</option>
                            <option value="2" <?php echo e(old('semester') == '2' ? 'selected' : ''); ?>>Semester 2</option>
                        </select>
                        <?php $__errorArgs = ['semester'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="error"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" id="address" name="address" value="<?php echo e(old('address')); ?>">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="city">City</label>
                        <input type="text" id="city" name="city" value="<?php echo e(old('city')); ?>">
                    </div>

                    <div class="form-group">
                        <label for="state">State/Province</label>
                        <input type="text" id="state" name="state" value="<?php echo e(old('state')); ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="postal_code">Postal Code</label>
                    <input type="text" id="postal_code" name="postal_code" value="<?php echo e(old('postal_code')); ?>">
                </div>

                <div class="button-group">
                    <button type="submit" class="btn btn-primary">Create Student</button>
                    <a href="<?php echo e(route('students.index')); ?>" class="btn btn-secondary" style="text-align: center; text-decoration: none; display: flex; align-items: center; justify-content: center;">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\Users\Bensonngulube\OneDrive - zescozambia\Desktop\Laravel test\resources\views/students/create.blade.php ENDPATH**/ ?>