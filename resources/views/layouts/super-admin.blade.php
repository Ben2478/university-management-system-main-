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
        .navbar {
            background: #2c3e50;
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
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
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            transition: background 0.3s;
        }
        .dropdown-toggle:hover { background: rgba(255,255,255,0.1); }
        .dropdown-menu {
            display: none;
            position: absolute;
            right: 0;
            top: 100%;
            background: white;
            color: #333;
            min-width: 200px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            border-radius: 0.25rem;
            z-index: 1000;
        }
        .dropdown:hover .dropdown-menu { display: block; }
        .dropdown-menu a {
            display: block;
            padding: 0.75rem 1rem;
            text-decoration: none;
            color: #333;
            border-bottom: 1px solid #eee;
        }
        .dropdown-menu a:hover { background: #f5f7fa; }
        .wrapper {
            display: flex;
            min-height: calc(100vh - 70px);
        }
        .sidebar {
            width: 260px;
            background: #34495e;
            color: white;
            padding: 2rem 0;
            overflow-y: auto;
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
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.875rem 1.5rem;
            color: #ecf0f1;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: 500;
            user-select: none;
        }
        .sidebar-header:hover {
            background: rgba(52, 152, 219, 0.1);
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
            padding: 0.75rem 1.5rem;
            color: #ecf0f1;
            text-decoration: none;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }
        .sidebar a:hover {
            background: rgba(255,255,255,0.1);
            border-left-color: #3498db;
        }
        .sidebar a.active {
            background: rgba(52, 152, 219, 0.2);
            border-left-color: #3498db;
            color: #3498db;
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
    </style>
</head>
<body>
    <div class="navbar">
        <h1>🎓 Student Management System - Super Admin</h1>
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
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>

    <div class="wrapper">
        <aside class="sidebar">
            <!-- Dashboard -->
            <div class="sidebar-section">
                <a href="{{ route('dashboard') }}" style="padding-left: 1.5rem;">
                    <span style="margin-right: 0.5rem;">📊</span> Dashboard
                </a>
            </div>

            <!-- Academic Management -->
            <div class="sidebar-section">
                <div class="sidebar-header" onclick="toggleSection('academic')">
                    <span><span class="icon">👥</span> Academic Management</span>
                    <span class="arrow">▼</span>
                </div>
                <div id="academic" class="sidebar-items">
                    <a href="{{ route('programs.index') }}" style="padding-left: 3rem;">Programs</a>
                    <a href="{{ route('students.index') }}" style="padding-left: 3rem;">Students</a>
                    <a href="{{ route('courses.index') }}" style="padding-left: 3rem;">Courses</a>
                    <a href="{{ route('enrollments.index') }}" style="padding-left: 3rem;">Enrollments</a>
                </div>
            </div>

            <!-- Academic Records -->
            <div class="sidebar-section">
                <div class="sidebar-header" onclick="toggleSection('records')">
                    <span><span class="icon">📚</span> Academic Records</span>
                    <span class="arrow">▼</span>
                </div>
                <div id="records" class="sidebar-items">
                    <a href="{{ route('grades.index') }}" style="padding-left: 3rem;">Grades</a>
                    <a href="{{ route('attendance.index') }}" style="padding-left: 3rem;">Attendance</a>
                </div>
            </div>

            <!-- Financial -->
            <div class="sidebar-section">
                <div class="sidebar-header" onclick="toggleSection('financial')">
                    <span><span class="icon">💰</span> Financial</span>
                    <span class="arrow">▼</span>
                </div>
                <div id="financial" class="sidebar-items">
                    <a href="{{ route('payments.index') }}" style="padding-left: 3rem;">Payments</a>
                    <a href="{{ route('receipts.index') }}" style="padding-left: 3rem;">Receipts</a>
                </div>
            </div>

            <!-- Administration -->
            <div class="sidebar-section">
                <div class="sidebar-header" onclick="toggleSection('admin')">
                    <span><span class="icon">🔐</span> Administration</span>
                    <span class="arrow">▼</span>
                </div>
                <div id="admin" class="sidebar-items">
                    <a href="{{ route('users.index') }}" style="padding-left: 3rem;">User Management</a>
                </div>
            </div>

            <!-- Account -->
            <div class="sidebar-section">
                <div class="sidebar-header" onclick="toggleSection('account')">
                    <span><span class="icon">👤</span> Account</span>
                    <span class="arrow">▼</span>
                </div>
                <div id="account" class="sidebar-items">
                    <a href="{{ route('profile.edit') }}" style="padding-left: 3rem;">Profile Settings</a>
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="padding-left: 3rem;">Logout</a>
                </div>
            </div>
        </aside>

        <main class="content">
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
        function toggleSection(sectionId) {
            const section = document.getElementById(sectionId);
            const header = section.previousElementSibling;
            
            // Toggle open state
            section.classList.toggle('open');
            header.classList.toggle('active');
            
            // Save state to localStorage
            const isOpen = section.classList.contains('open');
            localStorage.setItem('sidebar_' + sectionId, isOpen ? 'open' : 'closed');
        }
        
        // Restore saved states on page load
        document.addEventListener('DOMContentLoaded', function() {
            const sections = ['academic', 'records', 'financial', 'admin', 'account'];
            
            sections.forEach(sectionId => {
                const savedState = localStorage.getItem('sidebar_' + sectionId);
                const section = document.getElementById(sectionId);
                const header = section.previousElementSibling;
                
                // Default: open all sections if no saved state
                if (savedState === 'open' || savedState === null) {
                    section.classList.add('open');
                    header.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>
