<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Your Profile - Student Management System</title>
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
            padding: 2rem;
        }
        .setup-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 600px;
            width: 100%;
            padding: 3rem;
            text-align: center;
        }
        .icon {
            font-size: 4rem;
            margin-bottom: 1rem;
        }
        h1 {
            color: #333;
            margin-bottom: 1rem;
            font-size: 2rem;
        }
        p {
            color: #666;
            margin-bottom: 2rem;
            line-height: 1.6;
            font-size: 1.1rem;
        }
        .info-box {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 1.5rem;
            margin: 2rem 0;
            text-align: left;
            border-radius: 5px;
        }
        .info-box h3 {
            color: #667eea;
            margin-bottom: 1rem;
            font-size: 1.1rem;
        }
        .info-box ul {
            list-style: none;
            padding-left: 0;
        }
        .info-box li {
            padding: 0.5rem 0;
            color: #555;
        }
        .info-box li::before {
            content: "✓ ";
            color: #667eea;
            font-weight: bold;
            margin-right: 0.5rem;
        }
        .btn {
            display: inline-block;
            padding: 1rem 2rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
            margin: 0.5rem;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }
        .btn-secondary {
            background: #6c757d;
        }
        .actions {
            margin-top: 2rem;
        }
        .note {
            background: #fff3cd;
            border: 1px solid #ffc107;
            padding: 1rem;
            border-radius: 5px;
            margin-top: 1.5rem;
            color: #856404;
        }
    </style>
</head>
<body>
    <div class="setup-container">
        <div class="icon">👤</div>
        <h1>Welcome to the Student Portal!</h1>
        <p>{{ $message ?? 'Your account has been created successfully, but your student profile needs to be set up by an administrator.' }}</p>

        <div class="info-box">
            <h3>What happens next?</h3>
            <ul>
                <li>An administrator will create your student profile</li>
                <li>You'll receive access to courses and enrollment</li>
                <li>You can track your grades and attendance</li>
                <li>You can manage payments and receipts</li>
            </ul>
        </div>

        <div class="note">
            <strong>📧 Note:</strong> Please contact the administration office to complete your student registration. Once your profile is created, you'll have full access to the student portal.
        </div>

        <div class="actions">
            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-secondary" style="border: none; cursor: pointer;">Logout</button>
            </form>
        </div>
    </div>
</body>
</html>
