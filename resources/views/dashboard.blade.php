<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management System - Dashboard</title>
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
        }

        .sidebar {
            width: 250px;
            background: #2c3e50;
            padding: 20px;
            color: white;
            overflow-y: auto;
        }

        .sidebar h2 {
            margin-bottom: 30px;
            font-size: 24px;
        }

        .sidebar ul {
            list-style: none;
        }

        .sidebar li {
            margin-bottom: 10px;
        }

        .sidebar a {
            color: #ecf0f1;
            text-decoration: none;
            display: block;
            padding: 12px 15px;
            border-radius: 5px;
            transition: background 0.3s;
        }

        .sidebar a:hover {
            background: #34495e;
        }

        .main-content {
            flex: 1;
            padding: 40px;
        }

        .header {
            background: white;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .header h1 {
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .header p {
            color: #7f8c8d;
        }

        .cards-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 12px rgba(0, 0, 0, 0.15);
        }

        .card-icon {
            font-size: 40px;
            margin-bottom: 15px;
        }

        .card h3 {
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .card p {
            color: #7f8c8d;
            font-size: 14px;
            margin-bottom: 15px;
        }

        .card a {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            transition: background 0.3s;
        }

        .card a:hover {
            background: #764ba2;
        }

        .number {
            font-size: 32px;
            font-weight: bold;
            color: #667eea;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>📚 SMS</h2>
        <ul>
            <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li><a href="{{ route('students.index') }}">Students</a></li>
            <li><a href="{{ route('courses.index') }}">Courses</a></li>
            <li><a href="{{ route('enrollments.index') }}">Enrollments</a></li>
            <li><a href="{{ route('grades.index') }}">Grades</a></li>
            <li><a href="{{ route('attendance.index') }}">Attendance</a></li>
            <li><a href="{{ route('payments.index') }}">Payments</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>Welcome to Student Management System</h1>
            <p>Manage students, courses, enrollments, grades, and attendance efficiently.</p>
        </div>

        <div class="cards-container">
            <div class="card">
                <div class="card-icon">👨‍🎓</div>
                <h3>Students</h3>
                <p>Manage student profiles and information</p>
                <a href="{{ route('students.index') }}">View Students</a>
            </div>

            <div class="card">
                <div class="card-icon">📖</div>
                <h3>Courses</h3>
                <p>Create and manage course offerings</p>
                <a href="{{ route('courses.index') }}">View Courses</a>
            </div>

            <div class="card">
                <div class="card-icon">✅</div>
                <h3>Enrollments</h3>
                <p>Manage student course enrollments</p>
                <a href="{{ route('enrollments.index') }}">View Enrollments</a>
            </div>

            <div class="card">
                <div class="card-icon">📊</div>
                <h3>Grades</h3>
                <p>Track and manage student grades</p>
                <a href="{{ route('grades.index') }}">View Grades</a>
            </div>

            <div class="card">
                <div class="card-icon">📅</div>
                <h3>Attendance</h3>
                <p>Record student attendance</p>
                <a href="{{ route('attendance.index') }}">View Attendance</a>
            </div>

            <div class="card">
                <div class="card-icon">💰</div>
                <h3>Payments</h3>
                <p>Manage student payments and tuition</p>
                <a href="{{ route('payments.index') }}">View Payments</a>
            </div>
        </div>
    </div>
</body>
</html>
