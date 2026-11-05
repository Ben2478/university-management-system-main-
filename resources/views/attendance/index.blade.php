<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance - Student Management System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .header h1 {
            color: #2c3e50;
        }

        .btn {
            background: #667eea;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            border: none;
            cursor: pointer;
        }

        .btn:hover {
            background: #764ba2;
        }

        .table-container {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #34495e;
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: 600;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #ecf0f1;
        }

        tr:hover {
            background: #f8f9fa;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 3px;
            font-weight: 600;
            font-size: 12px;
        }

        .status-present { background: #d4edda; color: #155724; }
        .status-absent { background: #f8d7da; color: #721c24; }
        .status-late { background: #fff3cd; color: #856404; }
        .status-excused { background: #d1ecf1; color: #0c5460; }

        .action-buttons {
            display: flex;
            gap: 5px;
        }

        .action-buttons a, .action-buttons button {
            padding: 5px 10px;
            border-radius: 3px;
            text-decoration: none;
            cursor: pointer;
            border: none;
            font-size: 11px;
        }

        .btn-primary {
            background: #3498db;
            color: white;
        }

        .btn-danger {
            background: #e74c3c;
            color: white;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #7f8c8d;
        }

        .alert-success {
            padding: 15px;
            margin-bottom: 20px;
            background: #d4edda;
            color: #155724;
            border-radius: 5px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-left: 4px solid;
        }

        .stat-card.total { border-left-color: #667eea; }
        .stat-card.present { border-left-color: #27ae60; }
        .stat-card.absent { border-left-color: #e74c3c; }
        .stat-card.late { border-left-color: #f39c12; }

        .stat-card h3 {
            color: #7f8c8d;
            font-size: 14px;
            margin-bottom: 10px;
            font-weight: 500;
        }

        .stat-card .number {
            color: #2c3e50;
            font-size: 32px;
            font-weight: bold;
        }

        .filter-section {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .filter-section h3 {
            margin-bottom: 15px;
            color: #2c3e50;
        }

        .filter-form {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            align-items: end;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
        }

        .filter-group label {
            margin-bottom: 5px;
            color: #555;
            font-size: 14px;
            font-weight: 500;
        }

        .filter-group input,
        .filter-group select {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        .filter-actions {
            display: flex;
            gap: 10px;
            align-items: flex-end;
        }

        .btn-filter {
            background: #667eea;
            color: white;
            padding: 8px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-filter:hover {
            background: #764ba2;
        }

        .btn-reset {
            background: #95a5a6;
            color: white;
            padding: 8px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
        }

        .btn-reset:hover {
            background: #7f8c8d;
        }

        .btn-export {
            background: #27ae60;
            color: white;
            padding: 8px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
        }

        .btn-export:hover {
            background: #229954;
        }

        .header-actions {
            display: flex;
            gap: 10px;
        }

        .dept-tabs {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .dept-tabs h3 {
            margin-bottom: 15px;
            color: #2c3e50;
        }

        .dept-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .dept-tab-btn {
            padding: 10px 20px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.2s;
        }

        .dept-tab-btn:hover {
            background: #764ba2;
            transform: translateY(-2px);
        }

        .dept-tab-btn.active {
            background: #764ba2;
            box-shadow: 0 4px 8px rgba(102, 126, 234, 0.3);
        }

        .students-display {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            display: none;
        }

        .students-display h4 {
            margin-bottom: 15px;
            color: #2c3e50;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 10px;
            border-bottom: 2px solid #667eea;
        }

        .students-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }

        .student-card {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #667eea;
            transition: all 0.2s;
            cursor: pointer;
        }

        .student-card:hover {
            transform: translateX(5px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .student-card .name {
            font-weight: bold;
            font-size: 16px;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .student-card .details {
            font-size: 13px;
            color: #666;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('css/academic-records.css') }}?v={{ filemtime(public_path('css/academic-records.css')) }}">
</head>
<body class="records-standalone">
    <div class="container academic-records">
        <div class="header">
            <h1>Attendance Management</h1>
            <div class="header-actions">
                <a href="{{ route('attendance.export', request()->query()) }}" class="btn-export">📥 Export to Excel</a>
                <a href="{{ route('attendance.create') }}" class="btn">+ Record Attendance</a>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card total">
                <h3>Total Records</h3>
                <div class="number">{{ $stats['total'] }}</div>
            </div>
            <div class="stat-card present">
                <h3>Present Rate</h3>
                <div class="number">{{ $stats['present_rate'] }}%</div>
            </div>
            <div class="stat-card absent">
                <h3>Absent</h3>
                <div class="number">{{ $stats['absent'] }}</div>
            </div>
            <div class="stat-card late">
                <h3>Late</h3>
                <div class="number">{{ $stats['late'] }}</div>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div class="filter-section">
            <h3>Search & Filter</h3>
            <form action="{{ route('attendance.index') }}" method="GET" class="filter-form">
                <div class="filter-group">
                    <label for="search">Search</label>
                    <input type="text" id="search" name="search" 
                           placeholder="Student name, ID, or course..." 
                           value="{{ request('search') }}">
                </div>
                <div class="filter-group">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="">All Statuses</option>
                        <option value="present" {{ request('status') == 'present' ? 'selected' : '' }}>Present</option>
                        <option value="absent" {{ request('status') == 'absent' ? 'selected' : '' }}>Absent</option>
                        <option value="late" {{ request('status') == 'late' ? 'selected' : '' }}>Late</option>
                        <option value="excused" {{ request('status') == 'excused' ? 'selected' : '' }}>Excused</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="course_id">Course</label>
                    <select id="course_id" name="course_id">
                        <option value="">All Courses</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                                {{ $course->course_code }} - {{ $course->course_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group">
                    <label for="program">Program</label>
                    <select id="program" name="program">
                        <option value="">All Programs</option>
                        @foreach($programs as $program)
                            <option value="{{ $program }}" {{ request('program') == $program ? 'selected' : '' }}>{{ $program }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group">
                    <label for="date_from">From Date</label>
                    <input type="date" id="date_from" name="date_from" value="{{ request('date_from') }}">
                </div>
                <div class="filter-group">
                    <label for="date_to">To Date</label>
                    <input type="date" id="date_to" name="date_to" value="{{ request('date_to') }}">
                </div>
                <div class="filter-actions">
                    <button type="submit" class="btn-filter">Apply Filters</button>
                    <a href="{{ route('attendance.index') }}" class="btn-reset">Reset</a>
                </div>
            </form>
        </div>

        <!-- Department/Program Tabs -->
        <div class="dept-tabs">
            <h3>Browse Students by Program</h3>
            <div class="dept-buttons">
                @forelse($programs as $program)
                    <button type="button" class="dept-tab-btn" data-program="{{ $program }}">{{ $program }}</button>
                @empty
                    <p style="color: #999;">No programs available</p>
                @endforelse
            </div>
        </div>

        <!-- Students Display Area -->
        <div id="students-display" class="students-display">
            <h4>
                <span>Students in <span id="current-program"></span></span>
                <button type="button" onclick="closeStudents()" style="background: #95a5a6; color: white; border: none; padding: 5px 15px; border-radius: 3px; cursor: pointer;">Close</button>
            </h4>
            <div id="students-grid" class="students-grid"></div>
        </div>

        @if ($message = Session::get('success'))
            <div class="alert-success">{{ $message }}</div>
        @endif

        <div class="table-container" tabindex="0" role="region" aria-label="Attendance records">
            @if ($attendance->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Student Name</th>
                            <th>Program</th>
                            <th>Year/Sem</th>
                            <th>Course Code</th>
                            <th>Course Name</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Remarks</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($attendance as $record)
                            <tr>
                                <td><strong>{{ $record->student->student_id }}</strong></td>
                                <td>{{ $record->student->first_name }} {{ $record->student->last_name }}</td>
                                <td>{{ $record->student->program ?? 'N/A' }}</td>
                                <td>Y{{ $record->student->year ?? '-' }}/S{{ $record->student->semester ?? '-' }}</td>
                                <td>{{ $record->course->course_code }}</td>
                                <td>{{ $record->course->course_name }}</td>
                                <td>{{ $record->attendance_date->format('M d, Y') }}</td>
                                <td>
                                    <span class="status-badge status-{{ $record->status }}">
                                        {{ ucfirst($record->status) }}
                                    </span>
                                </td>
                                <td>{{ $record->remarks ?? '-' }}</td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('attendance.edit', $record) }}" class="btn-primary">Edit</a>
                                        <form method="POST" action="{{ route('attendance.destroy', $record) }}" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="no-data">
                    <p>No attendance records yet. <a href="{{ route('attendance.create') }}">Record the first attendance</a></p>
                </div>
            @endif
        </div>
    </div>

    <script>
        // Department tab functionality
        const deptButtons = document.querySelectorAll('.dept-tab-btn');
        const studentsDisplay = document.getElementById('students-display');
        const studentsGrid = document.getElementById('students-grid');
        const currentProgram = document.getElementById('current-program');

        deptButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const program = this.dataset.program;
                
                // Update active button
                deptButtons.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                
                // Load students
                loadStudents(program);
            });
        });

        function loadStudents(program) {
            currentProgram.textContent = program;
            studentsGrid.innerHTML = '<div style="text-align: center; padding: 40px; color: #666; grid-column: 1 / -1;">Loading students...</div>';
            studentsDisplay.style.display = 'block';

            fetch(`/students/by-program?program=${encodeURIComponent(program)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.length === 0) {
                        studentsGrid.innerHTML = '<div style="text-align: center; padding: 40px; color: #999; grid-column: 1 / -1;">No students found in this program</div>';
                    } else {
                        studentsGrid.innerHTML = data.map(student => `
                            <div class="student-card" onclick="window.location.href='/attendance/create?student_id=${student.id}'">
                                <div class="name">${student.first_name} ${student.last_name}</div>
                                <div class="details">
                                    <div>📋 ${student.student_id}</div>
                                    <div>📚 Year ${student.year || 'N/A'}, Semester ${student.semester || 'N/A'}</div>
                                    <div style="margin-top: 5px; color: #667eea; font-weight: 500;">Click to mark attendance →</div>
                                </div>
                            </div>
                        `).join('');
                    }
                })
                .catch(error => {
                    console.error('Error loading students:', error);
                    studentsGrid.innerHTML = '<div style="text-align: center; padding: 40px; color: #e74c3c; grid-column: 1 / -1;">Error loading students</div>';
                });
        }

        function closeStudents() {
            studentsDisplay.style.display = 'none';
            deptButtons.forEach(b => b.classList.remove('active'));
        }
    </script>
</body>
</html>
