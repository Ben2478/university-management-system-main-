<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enrollments - Student Management System</title>
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
            transition: background 0.3s;
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
            padding: 15px;
            text-align: left;
            font-weight: 600;
        }

        td {
            padding: 12px 15px;
            border-bottom: 1px solid #ecf0f1;
        }

        tr:hover {
            background: #f8f9fa;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .action-buttons a, .action-buttons button {
            padding: 5px 10px;
            border-radius: 3px;
            text-decoration: none;
            cursor: pointer;
            border: none;
            font-size: 12px;
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

        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: 600;
            background: #d4edda;
            color: #155724;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #7f8c8d;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
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
        .stat-card.active { border-left-color: #27ae60; }
        .stat-card.completed { border-left-color: #3498db; }
        .stat-card.dropped { border-left-color: #e74c3c; }

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
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Enrollments Management</h1>
            <a href="<?php echo e(route('enrollments.create')); ?>" class="btn">+ New Enrollment</a>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card total">
                <h3>Total Enrollments</h3>
                <div class="number"><?php echo e($stats['total']); ?></div>
            </div>
            <div class="stat-card active">
                <h3>Active</h3>
                <div class="number"><?php echo e($stats['active']); ?></div>
            </div>
            <div class="stat-card completed">
                <h3>Completed</h3>
                <div class="number"><?php echo e($stats['completed']); ?></div>
            </div>
            <div class="stat-card dropped">
                <h3>Dropped</h3>
                <div class="number"><?php echo e($stats['dropped']); ?></div>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div class="filter-section">
            <h3>Search & Filter</h3>
            <form action="<?php echo e(route('enrollments.index')); ?>" method="GET" class="filter-form">
                <div class="filter-group">
                    <label for="search">Search</label>
                    <input type="text" id="search" name="search" 
                           placeholder="Student name, ID, or course..." 
                           value="<?php echo e(request('search')); ?>">
                </div>
                <div class="filter-group">
                    <label for="program">Program</label>
                    <select id="program" name="program">
                        <option value="">All Programs</option>
                        <option value="Computer Science" <?php echo e(request('program') == 'Computer Science' ? 'selected' : ''); ?>>Computer Science</option>
                        <option value="Information Technology" <?php echo e(request('program') == 'Information Technology' ? 'selected' : ''); ?>>Information Technology</option>
                        <option value="Software Engineering" <?php echo e(request('program') == 'Software Engineering' ? 'selected' : ''); ?>>Software Engineering</option>
                        <option value="Data Science" <?php echo e(request('program') == 'Data Science' ? 'selected' : ''); ?>>Data Science</option>
                        <option value="Cybersecurity" <?php echo e(request('program') == 'Cybersecurity' ? 'selected' : ''); ?>>Cybersecurity</option>
                        <option value="Business Administration" <?php echo e(request('program') == 'Business Administration' ? 'selected' : ''); ?>>Business Administration</option>
                        <option value="Engineering" <?php echo e(request('program') == 'Engineering' ? 'selected' : ''); ?>>Engineering</option>
                        <option value="Medicine" <?php echo e(request('program') == 'Medicine' ? 'selected' : ''); ?>>Medicine</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="">All Statuses</option>
                        <option value="active" <?php echo e(request('status') == 'active' ? 'selected' : ''); ?>>Active</option>
                        <option value="completed" <?php echo e(request('status') == 'completed' ? 'selected' : ''); ?>>Completed</option>
                        <option value="dropped" <?php echo e(request('status') == 'dropped' ? 'selected' : ''); ?>>Dropped</option>
                        <option value="suspended" <?php echo e(request('status') == 'suspended' ? 'selected' : ''); ?>>Suspended</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="date_from">From Date</label>
                    <input type="date" id="date_from" name="date_from" value="<?php echo e(request('date_from')); ?>">
                </div>
                <div class="filter-group">
                    <label for="date_to">To Date</label>
                    <input type="date" id="date_to" name="date_to" value="<?php echo e(request('date_to')); ?>">
                </div>
                <div class="filter-actions">
                    <button type="submit" class="btn-filter">Apply Filters</button>
                    <a href="<?php echo e(route('enrollments.index')); ?>" class="btn-reset">Reset</a>
                </div>
            </form>
        </div>

        <?php if($message = Session::get('success')): ?>
            <div class="alert alert-success">
                <?php echo e($message); ?>

            </div>
        <?php endif; ?>

        <div class="table-container">
            <?php if($enrollments->count() > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Student Name</th>
                            <th>Program</th>
                            <th>Year</th>
                            <th>Semester</th>
                            <th>Course Code</th>
                            <th>Course Name</th>
                            <th>Enrollment Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $enrollments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $enrollment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><strong><?php echo e($enrollment->student->student_id); ?></strong></td>
                                <td><?php echo e($enrollment->student->first_name); ?> <?php echo e($enrollment->student->last_name); ?></td>
                                <td><?php echo e($enrollment->program ?? 'N/A'); ?></td>
                                <td>Year <?php echo e($enrollment->student->year ?? 'N/A'); ?></td>
                                <td>Sem <?php echo e($enrollment->student->semester ?? 'N/A'); ?></td>
                                <td><?php echo e($enrollment->course->course_code); ?></td>
                                <td><?php echo e($enrollment->course->course_name); ?></td>
                                <td><?php echo e($enrollment->enrollment_date->format('M d, Y')); ?></td>
                                <td>
                                    <span class="status-badge">
                                        <?php echo e(ucfirst($enrollment->status)); ?>

                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="<?php echo e(route('enrollments.show', $enrollment)); ?>" class="btn-primary">View</a>
                                        <a href="<?php echo e(route('enrollments.edit', $enrollment)); ?>" class="btn-primary">Edit</a>
                                        <form method="POST" action="<?php echo e(route('enrollments.destroy', $enrollment)); ?>" style="display: inline;">
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
                    <p>No enrollments found. <a href="<?php echo e(route('enrollments.create')); ?>">Create the first one</a></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\Users\Bensonngulube\OneDrive - zescozambia\Desktop\Laravel test\resources\views/enrollments/index.blade.php ENDPATH**/ ?>