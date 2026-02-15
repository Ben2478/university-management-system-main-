<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grades - Student Management System</title>
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
            max-width: 1400px;
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
            font-size: 14px;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #ecf0f1;
        }

        tr:hover {
            background: #f8f9fa;
        }

        .grade-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 14px;
            text-align: center;
            min-width: 35px;
        }

        .grade-a { background: #d4edda; color: #155724; }
        .grade-b { background: #cfe2ff; color: #084298; }
        .grade-c { background: #fff3cd; color: #997404; }
        .grade-d { background: #f8d7da; color: #842029; }
        .grade-f { background: #f5c6cb; color: #842029; }

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

        .btn-primary:hover {
            background: #2980b9;
        }

        .btn-danger {
            background: #e74c3c;
            color: white;
        }

        .btn-danger:hover {
            background: #c0392b;
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
        .stat-card.average { border-left-color: #3498db; }
        .stat-card.distinction { border-left-color: #27ae60; }
        .stat-card.attention { border-left-color: #e74c3c; }

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
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Grades Management</h1>
            <div class="header-actions">
                <a href="<?php echo e(route('grades.export', request()->query())); ?>" class="btn-export">📥 Export to Excel</a>
                <a href="<?php echo e(route('grades.create')); ?>" class="btn">+ Record Grade</a>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card total">
                <h3>Total Grades</h3>
                <div class="number"><?php echo e($stats['total']); ?></div>
            </div>
            <div class="stat-card average">
                <h3>Average Score</h3>
                <div class="number"><?php echo e($stats['average']); ?>%</div>
            </div>
            <div class="stat-card distinction">
                <h3>A & B Grades</h3>
                <div class="number"><?php echo e($stats['distinction']); ?></div>
            </div>
            <div class="stat-card attention">
                <h3>D & F Grades</h3>
                <div class="number"><?php echo e($stats['need_attention']); ?></div>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div class="filter-section">
            <h3>Search & Filter</h3>
            <form action="<?php echo e(route('grades.index')); ?>" method="GET" class="filter-form">
                <div class="filter-group">
                    <label for="search">Search</label>
                    <input type="text" id="search" name="search" 
                           placeholder="Student name, ID, or course..." 
                           value="<?php echo e(request('search')); ?>">
                </div>
                <div class="filter-group">
                    <label for="course_id">Course</label>
                    <select id="course_id" name="course_id">
                        <option value="">All Courses</option>
                        <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($course->id); ?>" <?php echo e(request('course_id') == $course->id ? 'selected' : ''); ?>>
                                <?php echo e($course->course_code); ?> - <?php echo e($course->course_name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="grade">Grade</label>
                    <select id="grade" name="grade">
                        <option value="">All Grades</option>
                        <option value="A" <?php echo e(request('grade') == 'A' ? 'selected' : ''); ?>>A (90-100)</option>
                        <option value="B" <?php echo e(request('grade') == 'B' ? 'selected' : ''); ?>>B (80-89)</option>
                        <option value="C" <?php echo e(request('grade') == 'C' ? 'selected' : ''); ?>>C (70-79)</option>
                        <option value="D" <?php echo e(request('grade') == 'D' ? 'selected' : ''); ?>>D (60-69)</option>
                        <option value="F" <?php echo e(request('grade') == 'F' ? 'selected' : ''); ?>>F (0-59)</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="score_min">Min Score</label>
                    <input type="number" id="score_min" name="score_min" 
                           placeholder="0" min="0" max="100" 
                           value="<?php echo e(request('score_min')); ?>">
                </div>
                <div class="filter-group">
                    <label for="score_max">Max Score</label>
                    <input type="number" id="score_max" name="score_max" 
                           placeholder="100" min="0" max="100" 
                           value="<?php echo e(request('score_max')); ?>">
                </div>
                <div class="filter-actions">
                    <button type="submit" class="btn-filter">Apply Filters</button>
                    <a href="<?php echo e(route('grades.index')); ?>" class="btn-reset">Reset</a>
                </div>
            </form>
        </div>

        <!-- Department/Program Tabs -->
        <div class="dept-tabs">
            <h3>Browse Students by Program</h3>
            <div class="dept-buttons">
                <?php $__empty_1 = true; $__currentLoopData = $programs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $program): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <button type="button" class="dept-tab-btn" data-program="<?php echo e($program); ?>"><?php echo e($program); ?></button>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p style="color: #999;">No programs available</p>
                <?php endif; ?>
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

        <?php if($message = Session::get('success')): ?>
            <div class="alert-success"><?php echo e($message); ?></div>
        <?php endif; ?>

        <div class="table-container">
            <?php if($grades->count() > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Student Name</th>
                            <th>Program</th>
                            <th>Year/Sem</th>
                            <th>Course Code</th>
                            <th>Course Name</th>
                            <th>Assignment</th>
                            <th>Midterm</th>
                            <th>Final</th>
                            <th>Overall</th>
                            <th>Grade</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $grades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><strong><?php echo e($grade->student->student_id); ?></strong></td>
                                <td><?php echo e($grade->student->first_name); ?> <?php echo e($grade->student->last_name); ?></td>
                                <td><?php echo e($grade->student->program ?? 'N/A'); ?></td>
                                <td>Y<?php echo e($grade->student->year ?? '-'); ?>/S<?php echo e($grade->student->semester ?? '-'); ?></td>
                                <td><?php echo e($grade->course->course_code); ?></td>
                                <td><?php echo e($grade->course->course_name); ?></td>
                                <td><?php echo e($grade->assignment_score ?? '-'); ?></td>
                                <td><?php echo e($grade->midterm_score ?? '-'); ?></td>
                                <td><?php echo e($grade->final_score ?? '-'); ?></td>
                                <td><strong><?php echo e($grade->overall_score ?? '-'); ?></strong></td>
                                <td>
                                    <?php if($grade->grade): ?>
                                        <span class="grade-badge grade-<?php echo e(strtolower($grade->grade)); ?>">
                                            <?php echo e($grade->grade); ?>

                                        </span>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="<?php echo e(route('grades.edit', $grade)); ?>" class="btn-primary">Edit</a>
                                        <form method="POST" action="<?php echo e(route('grades.destroy', $grade)); ?>" style="display: inline;">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="no-data">
                    <p>No grades recorded yet. <a href="<?php echo e(route('grades.create')); ?>">Record the first grade</a></p>
                </div>
            <?php endif; ?>
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
                            <div class="student-card" onclick="window.location.href='/grades/create?student_id=${student.id}'">
                                <div class="name">${student.first_name} ${student.last_name}</div>
                                <div class="details">
                                    <div>📋 ${student.student_id}</div>
                                    <div>📚 Year ${student.year || 'N/A'}, Semester ${student.semester || 'N/A'}</div>
                                    <div style="margin-top: 5px; color: #667eea; font-weight: 500;">Click to add grade →</div>
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
<?php /**PATH C:\Users\Bensonngulube\OneDrive - zescozambia\Desktop\Laravel test\resources\views/grades/index.blade.php ENDPATH**/ ?>