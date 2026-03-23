<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Phoenix Open University</title>
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
        }
        .login-container {
            width: 100%;
            max-width: 400px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }
        .login-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        .login-logo {
            width: 72px;
            height: 72px;
            display: inline-block;
            margin-bottom: .75rem;
        }
        .login-logo img {
            width: 72px;
            height: 72px;
            display: block;
            margin: 0 auto;
        }
        .login-header h1 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
        .login-header p {
            opacity: 0.9;
            font-size: 0.95rem;
        }
        .login-form {
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
        input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 5px rgba(102, 126, 234, 0.2);
        }
        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
        }
        .remember-forgot label {
            margin: 0;
            display: flex;
            align-items: center;
        }
        .remember-forgot input[type="checkbox"] {
            width: auto;
            margin-right: 0.5rem;
        }
        .remember-forgot a {
            color: #667eea;
            text-decoration: none;
        }
        .remember-forgot a:hover {
            text-decoration: underline;
        }
        .btn-login {
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
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }
        .signup-link {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #eee;
            font-size: 0.9rem;
            color: #666;
        }
        .signup-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }
        .signup-link a:hover {
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
        .demo-credentials {
            margin-top: 2rem;
            padding: 1rem;
            background: #f0f4ff;
            border-radius: 5px;
            border-left: 4px solid #667eea;
            font-size: 0.85rem;
        }
        .demo-credentials h4 {
            margin: 0 0 0.5rem 0;
            color: #333;
        }
        .demo-credentials p {
            margin: 0.25rem 0;
            color: #666;
        }
        .demo-credentials code {
            background: white;
            padding: 0.2rem 0.4rem;
            border-radius: 3px;
            font-family: monospace;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <div class="login-logo">
                <img src="{{ asset('images/phoenix-open-university.jpeg') }}" alt="Phoenix Open University" />
            </div>
            <h1>Phoenix Open University</h1>
            <p>University Management System</p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="login-form">
            @csrf

            @if ($errors->any())
                <div class="alert alert-error">
                    @foreach ($errors->all() as $error)
                        <p style="margin: 0.25rem 0;">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div class="form-group">
                <label for="identifier">Student ID or Email</label>
                <input 
                    type="text" 
                    id="identifier" 
                    name="identifier" 
                    value="{{ old('identifier') }}" 
                    required 
                    autofocus
                    placeholder="e.g., 202501123 or staff@school.edu"
                >
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    required
                    placeholder="Enter your password"
                >
            </div>

            <div class="remember-forgot">
                <label>
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    Remember me
                </label>
            </div>

            <button type="submit" class="btn-login">Login</button>

        </form>
    </div>
</body>
</html>
