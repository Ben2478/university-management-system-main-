<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Super Admin</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f7fa;
            color: #333;
        }
        .nav-toggle {
            display: none;
            border: 1px solid rgba(255,255,255,0.25);
            background: rgba(255,255,255,0.08);
            color: #fff;
            border-radius: 0.5rem;
            width: 42px;
            height: 42px;
            cursor: pointer;
            font-size: 1.25rem;
            line-height: 1;
        }
        .nav-toggle:hover { background: rgba(255,255,255,0.14); }
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.45);
            z-index: 1500;
        }
        .navbar {
            background: #2c3e50;
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            gap: 1rem;
        }
        .navbar h1 { font-size: 1.5rem; }
        .navbar-right { display: flex; gap: 2rem; align-items: center; }
        .user-info { display: flex; align-items: center; gap: 0.5rem; }
        .dropdown {
            position: relative;
            display: inline-block;
        }
        .dropdown-toggle {
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            min-height: 42px;
            padding: 0.5rem 0.75rem;
            border-radius: 0.5rem;
            transition: background 0.2s ease;
        }
        .dropdown-toggle:hover { background: rgba(255,255,255,0.1); }
        .dropdown-toggle:focus-visible, .nav-toggle:focus-visible, .sidebar a:focus-visible, .sidebar-header:focus-visible {
            outline: 3px solid #f9c74f;
            outline-offset: 2px;
        }
        .dropdown-menu {
            display: none;
            position: absolute;
            right: 0;
            top: calc(100% + 0.5rem);
            background: white;
            color: #333;
            min-width: 220px;
            padding: 0.35rem;
            box-shadow: 0 12px 32px rgba(0,0,0,0.2);
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            z-index: 1000;
        }
        .dropdown.open .dropdown-menu { display: block; }
        .dropdown-menu a {
            display: block;
            padding: 0.7rem 0.8rem;
            text-decoration: none;
            color: #333;
            border-radius: 0.35rem;
        }
        .dropdown-menu a:hover, .dropdown-menu a:focus-visible { background: #f5f7fa; }
        select:not([multiple]) { min-height: 42px; padding: 0.6rem 2.4rem 0.6rem 0.75rem; border: 1px solid #cbd5e1; border-radius: 0.4rem; background-color: #fff; color: #24313d; }
        select:focus-visible { outline: 3px solid rgba(52,152,219,0.25); border-color: #2874a6; }
        .wrapper {
            display: flex;
            min-height: calc(100vh - 70px);
        }
        .sidebar {
            width: 260px;
            flex: 0 0 260px;
            background: #34495e;
            color: white;
            padding: 1.25rem 0;
            overflow-y: auto;
            overscroll-behavior: contain;
            border-right: 1px solid rgba(255,255,255,0.08);
            box-shadow: 0 8px 30px rgba(0,0,0,0.18);
        }
        .sidebar h3 {
            padding: 0 1.5rem;
            margin: 1.5rem 0 1rem 0;
            font-size: 0.875rem;
            font-weight: 600;
            text-transform: uppercase;
            color: #bdc3c7;
        }
        .sidebar-section {
            margin-bottom: 0.5rem;
        }
        .sidebar-header {
            width: calc(100% - 1.3rem);
            display: flex;
            align-items: center;
            justify-content: space-between;
            min-height: 44px;
            margin: 0 0.65rem;
            padding: 0.7rem 0.85rem;
            color: #ecf0f1;
            background: transparent;
            border: 0;
            border-radius: 0.4rem;
            cursor: pointer;
            text-align: left;
            font: inherit;
            transition: background 0.18s ease;
            font-weight: 500;
            user-select: none;
        }
        .sidebar-header:hover {
            background: rgba(255,255,255,0.1);
        }
        .sidebar-header .icon {
            font-size: 1.1rem;
            margin-right: 0.5rem;
        }
        .sidebar-header .arrow {
            transition: transform 0.3s;
            font-size: 0.75rem;
        }
        .sidebar-header.active .arrow {
            transform: rotate(180deg);
        }
        .sidebar-items {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
        }
        .sidebar-items.open {
            max-height: 500px;
        }
        .sidebar a {
            display: block;
            min-height: 44px;
            padding: 0.7rem 1rem;
            color: #fff;
            text-decoration: none;
            transition: background 0.18s ease, color 0.18s ease;
            border-left: 3px solid transparent;
            border-radius: 0.4rem;
            margin: 0.15rem 0.65rem;
        }
        .sidebar a:hover {
            background: rgba(255,255,255,0.1);
            border-left-color: #f9c74f;
        }
        .sidebar a.active {
            background: rgba(255,255,255,0.18);
            border-left-color: #f9c74f;
            color: #fff;
            font-weight: 700;
        }
        @media (max-width: 980px) {
            .nav-toggle { display: inline-flex; align-items: center; justify-content: center; }
            .sidebar {
                position: fixed;
                top: 70px;
                left: 0;
                height: calc(100vh - 70px);
                transform: translateX(-102%);
                transition: transform 0.25s ease;
                z-index: 2000;
                width: 290px;
            }
            body.sidebar-open .sidebar { transform: translateX(0); }
            body.sidebar-open .sidebar-overlay { display: block; }
            body.sidebar-open { overflow: hidden; }
            .wrapper { min-height: auto; }
        }
        .content {
            flex: 1;
            padding: 0;
            background: white;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }
        .card {
            background: white;
            border: 1px solid #ddd;
            border-radius: 0.5rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .alert {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 0.25rem;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .btn {
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            border: none;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-size: 0.875rem;
            transition: all 0.3s;
        }
        .btn-primary { background: #3498db; color: white; }
        .btn-primary:hover { background: #2980b9; }
        .btn-success { background: #27ae60; color: white; }
        .btn-success:hover { background: #229954; }
        .btn-danger { background: #e74c3c; color: white; }
        .btn-danger:hover { background: #c0392b; }
        .btn-secondary { background: #95a5a6; color: white; }
        .btn-secondary:hover { background: #7f8c8d; }
        h1 { color: #2c3e50; }
        h2 { color: #34495e; }
        h3 { color: #34495e; }
        @media (prefers-reduced-motion: reduce) { *, *::before, *::after { transition-duration: 0.01ms !important; } }
    </style>
    @include('components.ui-foundation')
</head>
<body>
    @include('components.global-header', ['hasSidebar' => true])

    <div class="sidebar-overlay" onclick="closeSidebar()"></div>

    <div class="wrapper phoenix-app-shell">
        @include('components.sidebar')

        <main class="content phoenix-app-content">
            @if($errors->any())
                <div class="container">
                    <div class="alert alert-error">
                        <ul style="margin: 0;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @if(session('success'))
                <div class="container">
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
    <script>
        function toggleSidebar() {
            document.body.classList.toggle('sidebar-open');
            document.querySelector('.nav-toggle')?.setAttribute('aria-expanded', String(document.body.classList.contains('sidebar-open')));
        }

        function closeSidebar() {
            document.body.classList.remove('sidebar-open');
            document.querySelector('.nav-toggle')?.setAttribute('aria-expanded', 'false');
        }
    </script>
</body>
</html>
