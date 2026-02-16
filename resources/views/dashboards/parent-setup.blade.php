<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent Portal - Student Management System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
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
            background: #f0fdf4;
            border-left: 4px solid #10b981;
            padding: 1.5rem;
            margin: 2rem 0;
            text-align: left;
            border-radius: 5px;
        }
        .info-box h3 {
            color: #10b981;
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
            color: #10b981;
            font-weight: bold;
            margin-right: 0.5rem;
        }
        .btn {
            display: inline-block;
            padding: 1rem 2rem;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
            margin: 0.5rem;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(16, 185, 129, 0.4);
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
        <div class="icon">👨‍👩‍👧‍👦</div>
        <h1>Welcome to the Parent Portal!</h1>
        <p>{{ $message ?? 'Your parent account has been created successfully.' }}</p>

        <div class="info-box">
            <h3>What you can do once linked to a student:</h3>
            <ul>
                <li>View your child's academic progress</li>
                <li>Monitor attendance records</li>
                <li>Track payment history</li>
                <li>Receive updates on grades</li>
            </ul>
        </div>

        <div class="note">
            <strong>📧 Contact Administration:</strong> To link your account to a student, please contact the administration office with:
            <ul style="text-align: left; margin-top: 1rem; padding-left: 1.5rem;">
                <li>Your registered email: <strong>{{ auth()->user()->email }}</strong></li>
                <li>Your student's ID or name</li>
            </ul>
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
