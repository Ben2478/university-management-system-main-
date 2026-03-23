<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create User - Phoenix Open University</title>
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
        .form-group { margin-top: 1rem; }
        label { display:block; margin-bottom: .4rem; font-weight: 700; color:#333; }
        input, select, textarea {
            width: 100%;
            padding: .75rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            font-family: inherit;
        }
        .row { display:grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        @media (max-width: 640px) { .row { grid-template-columns: 1fr; } }
        .alert {
            padding: .85rem 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            font-size: .95rem;
        }
        .alert-success { background:#d4edda; color:#155724; border:1px solid #c3e6cb; }
        .alert-error { background:#f8d7da; color:#721c24; border:1px solid #f5c6cb; }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <h1>Create User (Admin Only)</h1>
            <p>Phoenix Open University</p>
        </div>

        <div class="body">
            <div class="note">
                Default password for new users is <code>welcome123</code>. They will be forced to change it at first login.
            </div>


            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-error">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            @if(session('created_user'))
                <div class="alert alert-success">
                    <div><strong>Created:</strong> {{ session('created_user.name') }} ({{ session('created_user.role') }})</div>
                    <div><strong>Email:</strong> {{ session('created_user.email') }}</div>
                    <div><strong>Temporary Password:</strong> <code>{{ session('created_user.password') }}</code></div>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required>
                </div>

                <div class="row">
                    <div class="form-group">
                        <label>Role</label>
                        <input type="text" value="Admin" disabled>
                        <div class="small">This page only creates Admin accounts.</div>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone (optional)</label>
                        <input id="phone" name="phone" type="text" value="{{ old('phone') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="address">Address (optional)</label>
                    <textarea id="address" name="address" rows="2">{{ old('address') }}</textarea>
                </div>

                <button type="submit" class="btn">Create User</button>
            </form>

            <a class="btn" style="margin-top: .75rem; background: #6c757d;" href="{{ route('dashboard') }}">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
