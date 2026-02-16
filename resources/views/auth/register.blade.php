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
            <p>Create Your Test Account - All Roles Available</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="register-form">
            @csrf

            @if ($errors->any())
                <div class="alert alert-error">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="form-group">
                <label for="name">Full Name</label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    value="{{ old('name') }}" 
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
                    value="{{ old('email') }}" 
                    required
                    placeholder="Enter your email"
                >
            </div>

            <div class="form-group">
                <label for="role">Account Type</label>
                <select id="role" name="role" required>
                    <option value="">-- Select Account Type --</option>
                    <option value="super_admin" {{ old('role') === 'super_admin' ? 'selected' : '' }}>Super Admin (Full Access)</option>
                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin/Accountant (Financial Access)</option>
                    <option value="student" {{ old('role') === 'student' ? 'selected' : '' }}>Student</option>
                    <option value="parent" {{ old('role') === 'parent' ? 'selected' : '' }}>Parent/Guardian</option>
                </select>
                <small style="color: #666; font-size: 0.85rem; display: block; margin-top: 0.25rem;">
                    ℹ️ For testing purposes - Choose your role to explore different features
                </small>
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
                    value="{{ old('phone') }}"
                    placeholder="Enter your phone number"
                >
            </div>

            <button type="submit" class="btn-register">Create Account</button>

            <div class="login-link">
                Already have an account? <a href="{{ route('login') }}">Login</a>
            </div>
        </form>
    </div>
</body>
</html>
