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
        .dropdown-toggle { background:none; border:none; color:#fff; cursor:pointer; min-height:42px; padding:.5rem .75rem; border-radius:.5rem; transition: background .2s ease; }
        .dropdown-toggle:hover { background: rgba(255,255,255,0.1); }
        .dropdown-toggle:focus-visible, .nav-toggle:focus-visible, .sidebar a:focus-visible { outline:3px solid #f9c74f; outline-offset:2px; }
        .dropdown-menu { display:none; position:absolute; right:0; top:calc(100% + .5rem); background:#fff; color:#333; min-width:220px; padding:.35rem; box-shadow:0 12px 32px rgba(0,0,0,.2); border:1px solid #e5e7eb; border-radius:.5rem; z-index:1000; }
        .dropdown.open .dropdown-menu { display:block; }
        .dropdown-menu a { display:block; padding:.7rem .8rem; text-decoration:none; color:#333; border-radius:.35rem; }
        .dropdown-menu a:hover, .dropdown-menu a:focus-visible { background:#f5f7fa; }
        select:not([multiple]) { min-height:42px; padding:.6rem 2.4rem .6rem .75rem; border:1px solid #cbd5e1; border-radius:.4rem; background-color:#fff; color:#24313d; }
        select:focus-visible { outline:3px solid rgba(52,152,219,.25); border-color:#2874a6; }
        .wrapper { display:flex; min-height: calc(100vh - 70px); }
        .sidebar { width:260px; flex:0 0 260px; background:#5b21b6; color:#fff; padding:1.25rem 0; overflow-y:auto; overscroll-behavior:contain; border-right:1px solid rgba(255,255,255,0.08); box-shadow:4px 0 18px rgba(0,0,0,0.12); }
        .sidebar h3 { padding:0 1.5rem; margin:1.5rem 0 .5rem; font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.08em; color:#ddd6fe; }
        .sidebar a { display:block; min-height:44px; padding:.7rem 1rem; color:#fff; text-decoration:none; transition:background .18s ease, color .18s ease; border-left:3px solid transparent; border-radius:.4rem; margin:.15rem .65rem; }
        .sidebar a:hover { background:rgba(255,255,255,.12); }
        .sidebar a.active { background:rgba(255,255,255,.18); border-left-color:#f9c74f; color:#fff; font-weight:700; }
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
        @media (prefers-reduced-motion: reduce) { *, *::before, *::after { transition-duration:.01ms !important; } }
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
        function toggleSidebar(){ document.body.classList.toggle('sidebar-open'); document.querySelector('.nav-toggle')?.setAttribute('aria-expanded', document.body.classList.contains('sidebar-open')); }
        function closeSidebar(){ document.body.classList.remove('sidebar-open'); document.querySelector('.nav-toggle')?.setAttribute('aria-expanded', 'false'); }
    </script>
</body>
</html>
