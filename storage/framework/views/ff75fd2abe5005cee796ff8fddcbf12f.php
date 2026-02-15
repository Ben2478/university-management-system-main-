<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Student Management System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2rem 0;
        }
        .register-container {
            width: 100%;
            max-width: 500px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }
        .register-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        .register-header h1 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
        .register-header p {
            opacity: 0.9;
            font-size: 0.95rem;
        }
        .register-form {
            padding: 2rem;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #333;
            font-weight: 500;
        }
        input, select {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            transition: border-color 0.3s;
            font-family: inherit;
        }
        input:focus, select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 5px rgba(102, 126, 234, 0.2);
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
        .btn-register {
            width: 100%;
            padding: 0.75rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }
        .login-link {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #eee;
            font-size: 0.9rem;
            color: #666;
        }
        .login-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
        .alert {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 5px;
        }
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .alert-error ul {
            list-style: none;
            margin: 0;
        }
        .alert-error li {
            margin: 0.25rem 0;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-header">
            <h1>🎓</h1>
            <h1>SMS</h1>
            <p>Create Your Account</p>
        </div>

        <form method="POST" action="<?php echo e(route('register')); ?>" class="register-form">
            <?php echo csrf_field(); ?>

            <?php if($errors->any()): ?>
                <div class="alert alert-error">
                    <ul>
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="form-group">
                <label for="name">Full Name</label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    value="<?php echo e(old('name')); ?>" 
                    required 
                    autofocus
                    placeholder="Enter your full name"
                >
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="<?php echo e(old('email')); ?>" 
                    required
                    placeholder="Enter your email"
                >
            </div>

            <div class="form-group">
                <label for="role">Account Type</label>
                <select id="role" name="role" required>
                    <option value="">-- Select Account Type --</option>
                    <option value="student" <?php echo e(old('role') === 'student' ? 'selected' : ''); ?>>Student</option>
                    <option value="parent" <?php echo e(old('role') === 'parent' ? 'selected' : ''); ?>>Parent/Guardian</option>
                </select>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="password">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        required
                        placeholder="Create a password"
                    >
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirm Password</label>
                    <input 
                        type="password" 
                        id="password_confirmation" 
                        name="password_confirmation" 
                        required
                        placeholder="Confirm password"
                    >
                </div>
            </div>

            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input 
                    type="tel" 
                    id="phone" 
                    name="phone" 
                    value="<?php echo e(old('phone')); ?>"
                    placeholder="Enter your phone number"
                >
            </div>

            <button type="submit" class="btn-register">Create Account</button>

            <div class="login-link">
                Already have an account? <a href="<?php echo e(route('login')); ?>">Login</a>
            </div>
        </form>
    </div>
</body>
</html>
<?php /**PATH C:\Users\Bensonngulube\OneDrive - zescozambia\Desktop\Laravel test\resources\views/auth/register.blade.php ENDPATH**/ ?>