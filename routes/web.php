<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\LecturerController;
use App\Http\Controllers\Auth\PasswordChangeController;
use App\Http\Controllers\Auth\AdminRegisterController;

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
// Temporary public bootstrap registration (use an access code; disable later)
Route::get('/register', [AdminRegisterController::class, 'create'])
    ->name('register')
    ->middleware(['guest', 'throttle:30,1']);
Route::post('/register', [AdminRegisterController::class, 'store'])
    ->middleware(['guest', 'throttle:30,1']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Force password change for first-time logins
Route::get('/password/change', [PasswordChangeController::class, 'edit'])
    ->name('password.change')
    ->middleware('auth');
Route::post('/password/change', [PasswordChangeController::class, 'update'])
    ->name('password.change.update')
    ->middleware('auth');

// Root route with authentication
Route::get('/', [DashboardController::class, 'index'])
    ->name('dashboard')
    ->middleware(['auth']);

// Redirect login to dashboard if already authenticated
Route::middleware(['auth', 'password.changed'])->group(function () {
    Route::get('/home', [DashboardController::class, 'index'])->name('home');
    
    // Profile Routes
    Route::get('profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile/update', [ProfileController::class, 'update'])->name('profile.update');

    // Student Routes (Protected by auth)
    Route::get('students/by-program', [GradeController::class, 'getStudentsByProgram'])->name('students.by-program');
    Route::get('students-export', [StudentController::class, 'export'])->name('students.export');
    Route::resource('students', StudentController::class);

    // Program Routes
    Route::get('programs/{program}/add-courses', [ProgramController::class, 'addCourses'])->name('programs.add-courses');
    Route::post('programs/{program}/store-courses', [ProgramController::class, 'storeCourses'])->name('programs.store-courses');
    Route::resource('programs', ProgramController::class);

    // Lecturer Routes (Super Admin & Admin)
    Route::resource('lecturers', LecturerController::class)->middleware('role:super_admin,admin');

    // Course Routes
    Route::resource('courses', CourseController::class);

    // Enrollment Routes
    Route::get('enrollments/available', [EnrollmentController::class, 'availableCourses'])->name('enrollments.available');
    Route::post('enrollments/enroll/{course}', [EnrollmentController::class, 'enrollInCourse'])->name('enrollments.enroll');
    Route::resource('enrollments', EnrollmentController::class);

    // Grade Routes
    Route::resource('grades', GradeController::class);
    Route::get('grades-export', [GradeController::class, 'export'])->name('grades.export');

    // Attendance Routes
    Route::resource('attendance', AttendanceController::class);
    Route::get('attendance-export', [AttendanceController::class, 'export'])->name('attendance.export');
    Route::get('attendance/student/{student}', [AttendanceController::class, 'studentReport'])->name('attendance.student-report');

    // Payment Routes
    Route::get('payments/export', [PaymentController::class, 'export'])->name('payments.export');
    Route::get('payments/reports', [PaymentController::class, 'reports'])->name('payments.reports');
    Route::get('payments/my-history', [PaymentController::class, 'myPayments'])->name('payments.my-history');
    Route::resource('payments', PaymentController::class);
    Route::get('students/{student}/payments', [PaymentController::class, 'studentPayments'])->name('payments.student-payments');
    Route::post('payments/{payment}/verify', [PaymentController::class, 'verify'])->name('payments.verify');
    Route::post('payments/callback', [PaymentController::class, 'callback'])->name('payments.callback');

    // Receipt Routes
    Route::get('receipts/export', [ReceiptController::class, 'export'])->name('receipts.export');
    Route::get('receipts', [ReceiptController::class, 'index'])->name('receipts.index');
    Route::get('receipts/reports', [ReceiptController::class, 'reports'])->name('receipts.reports');
    Route::get('receipts/pending', [ReceiptController::class, 'pendingReceipts'])->name('receipts.pending');
    Route::get('receipts/create', [ReceiptController::class, 'create'])->name('receipts.create');
    Route::post('receipts/store', [ReceiptController::class, 'store'])->name('receipts.store');
    Route::get('receipts/student-history', [ReceiptController::class, 'studentReceipts'])->name('receipts.student-history');
    Route::get('receipts/student/{student}', [ReceiptController::class, 'studentReceipts'])->name('receipts.student-receipts');
    Route::get('receipts/{receipt}/download', [ReceiptController::class, 'download'])->name('receipts.download');
    Route::get('receipts/{receipt}', [ReceiptController::class, 'show'])->name('receipts.show');
    Route::get('receipts/{receipt}/edit', [ReceiptController::class, 'show'])->name('receipts.edit');
    Route::post('receipts/{receipt}/approve', [ReceiptController::class, 'approve'])->name('receipts.approve');
    Route::post('receipts/{receipt}/reject', [ReceiptController::class, 'reject'])->name('receipts.reject');

    // User Management Routes (Super Admin Only)
    Route::get('users/export', [UserController::class, 'export'])->name('users.export');
    Route::resource('users', UserController::class);
    Route::post('users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');
    Route::post('users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
});
