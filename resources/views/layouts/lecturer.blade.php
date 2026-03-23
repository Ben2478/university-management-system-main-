<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Lecturer</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f7fa; color: #333; }
        .nav-toggle { display:none; border:1px solid rgba(255,255,255,0.25); background: rgba(255,255,255,0.08); color:#fff; border-radius:.5rem; width:42px; height:42px; cursor:pointer; font-size:1.25rem; }
        .nav-toggle:hover { background: rgba(255,255,255,0.14); }
        .sidebar-overlay { display:none; position:fixed; inset:0; background: rgba(0,0,0,0.45); z-index:1500; }
        .navbar { background:#6d28d9; color:#fff; padding:1rem 2rem; display:flex; justify-content:space-between; align-items:center; box-shadow:0 2px 4px rgba(0,0,0,0.1); gap:1rem; }
        .navbar h1 { font-size:1.4rem; }
        .navbar-right { display:flex; gap:2rem; align-items:center; }
        .user-info { display:flex; align-items:center; gap:.5rem; }
        .dropdown { position:relative; display:inline-block; }
        .dropdown-toggle { background:none; border:none; color:#fff; cursor:pointer; padding:.5rem 1rem; border-radius:.25rem; transition: background .3s; }
        .dropdown-toggle:hover { background: rgba(255,255,255,0.1); }
        .dropdown-menu { display:none; position:absolute; right:0; top:100%; background:#fff; color:#333; min-width:200px; box-shadow:0 4px 6px rgba(0,0,0,0.1); border-radius:.25rem; z-index:1000; }
        .dropdown:hover .dropdown-menu { display:block; }
        .dropdown-menu a { display:block; padding:.75rem 1rem; text-decoration:none; color:#333; border-bottom:1px solid #eee; }
        .dropdown-menu a:hover { background:#f5f7fa; }
        .wrapper { display:flex; min-height: calc(100vh - 70px); }
        .sidebar { width:260px; background:#5b21b6; color:#fff; padding:2rem 0; overflow-y:auto; border-right:1px solid rgba(255,255,255,0.08); box-shadow:0 8px 30px rgba(0,0,0,0.18); }
        .sidebar h3 { padding:0 1.5rem; margin: 1.5rem 0 1rem 0; font-size:.875rem; font-weight:700; text-transform:uppercase; color:#ddd6fe; }
        .sidebar a { display:block; padding:.75rem 1.5rem; color:#ede9fe; text-decoration:none; transition: all .3s; border-left:3px solid transparent; border-radius:.5rem; margin: .125rem .75rem; }
        .sidebar a:hover { background: rgba(255,255,255,0.1); border-left-color:#a78bfa; }
        .sidebar a.active { background: rgba(167, 139, 250, 0.22); border-left-color:#a78bfa; color:#a78bfa; }
        .content { flex:1; padding:0; background:#fff; }
        .container { max-width: 1200px; margin: 0 auto; padding: 2rem; }
        .card { background:#fff; border:1px solid #ddd; border-radius:.5rem; padding:1.5rem; margin-bottom:1.5rem; box-shadow:0 1px 3px rgba(0,0,0,0.1); }
        .alert { padding:1rem; margin-bottom:1rem; border-radius:.25rem; }
        .alert-success { background:#d4edda; color:#155724; border:1px solid #c3e6cb; }
        .alert-error { background:#f8d7da; color:#721c24; border:1px solid #f5c6cb; }
        @media (max-width: 980px) {
            .nav-toggle { display:inline-flex; align-items:center; justify-content:center; }
            .sidebar { position:fixed; top:70px; left:0; height: calc(100vh - 70px); transform: translateX(-102%); transition: transform .25s ease; z-index:2000; width:290px; }
            body.sidebar-open .sidebar { transform: translateX(0); }
            body.sidebar-open .sidebar-overlay { display:block; }
            body.sidebar-open { overflow:hidden; }
            .wrapper { min-height:auto; }
        }
    </style>
</head>
<body>
    <div class="navbar">
        <button class="nav-toggle" type="button" onclick="toggleSidebar()" aria-label="Toggle sidebar">☰</button>
        <h1>👨‍🏫 Lecturer Portal</h1>
        <div class="navbar-right">
            <div class="user-info">
                <span>{{ auth()->user()->name }}</span>
                <div class="dropdown">
                    <button class="dropdown-toggle">▼</button>
                    <div class="dropdown-menu">
                        <a href="{{ route('profile.edit') }}">Profile</a>
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                    </div>
                </div>
            </div>
        </div>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
    </div>

    <div class="sidebar-overlay" onclick="closeSidebar()"></div>

    <div class="wrapper">
        <aside class="sidebar" id="sidebar">
            <h3>Dashboard</h3>
            <a href="{{ route('dashboard') }}">Overview</a>

            <h3>Teaching</h3>
            <a href="{{ route('dashboard') }}">My Courses</a>
            <a href="{{ route('grades.index') }}">Grades</a>
            <a href="{{ route('attendance.index') }}">Attendance</a>

            <h3>Account</h3>
            <a href="{{ route('profile.edit') }}">Profile Settings</a>
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
        </aside>

        <main class="content">
            @if($errors->any())
                <div class="container">
                    <div class="alert alert-error">
                        <ul style="margin:0; padding-left: 1.25rem;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @if(session('success'))
                <div class="container">
                    <div class="alert alert-success">{{ session('success') }}</div>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script>
        function toggleSidebar(){ document.body.classList.toggle('sidebar-open'); }
        function closeSidebar(){ document.body.classList.remove('sidebar-open'); }

        document.addEventListener('DOMContentLoaded', function(){
            const currentPath = window.location.pathname.replace(/\/+$/, '');
            const links = document.querySelectorAll('#sidebar a[href]');
            let bestMatch = null; let bestLength = -1;
            links.forEach((link) => {
                try {
                    const linkPath = new URL(link.href, window.location.origin).pathname.replace(/\/+$/, '');
                    if (linkPath === '' || linkPath === '/') {
                        if (currentPath === '/' && linkPath.length > bestLength) { bestMatch = link; bestLength = linkPath.length; }
                        return;
                    }
                    if (currentPath === linkPath || currentPath.startsWith(linkPath + '/')) {
                        if (linkPath.length > bestLength) { bestMatch = link; bestLength = linkPath.length; }
                    }
                } catch (e) {}
            });
            if (bestMatch) bestMatch.classList.add('active');
        });
    </script>
</body>
</html>
