<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Disabled</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2rem;
        }
        .card {
            width: 100%;
            max-width: 560px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        .header h1 { font-size: 1.8rem; margin-bottom: .5rem; }
        .header p { opacity: .95; font-size: .98rem; }
        .body { padding: 2rem; }
        .note {
            padding: 1rem;
            background: #fff3cd;
            border: 1px solid #ffeeba;
            color: #856404;
            border-radius: 8px;
            margin-bottom: 1.25rem;
        }
        .list { margin: 0; padding-left: 1.2rem; color: #333; }
        .list li { margin: .4rem 0; }
        a.btn {
            display: inline-block;
            margin-top: 1.25rem;
            width: 100%;
            text-align: center;
            padding: .85rem 1rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            font-weight: 700;
            border-radius: 8px;
        }
        .small { margin-top: .75rem; color: #666; font-size: .9rem; text-align: center; }
        code { background: #f6f7ff; padding: .15rem .35rem; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <h1>Registration Disabled</h1>
            <p>Accounts are created by the administration.</p>
        </div>

        <div class="body">
            <div class="note">
                If you need an account, please contact the school administration.
            </div>

            <ul class="list">
                <li><strong>Students</strong> log in using their <strong>Student ID</strong>.</li>
                <li><strong>Lecturers / Admin / Management</strong> log in using <strong>Email</strong>.</li>
                <li>Default first-time password is <code>welcome123</code> and you’ll be required to change it after login.</li>
            </ul>

            <a class="btn" href="{{ route('login') }}">Go to Login</a>
            <div class="small">If you already have credentials, you can sign in now.</div>
        </div>
    </div>
</body>
</html>
