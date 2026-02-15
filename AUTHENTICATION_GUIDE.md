# Authentication & Role-Based Access Control System

## Overview
Complete multi-role authentication system with 4 user roles, each with role-specific dashboards, sidebars, and permissions.

## User Roles

### 1. **Super Admin** 🔴
- **Color Theme**: Dark Blue (#2c3e50)
- **Access Level**: Full system access
- **Sidebar Features**:
  - Dashboard Overview
  - Management (Students, Courses, Enrollments, Grades, Attendance)
  - Financial (Payments, Receipts)
  - Users & Roles
  - Reports (Student, Payment, Enrollment)
  - Account Settings
- **Dashboard Shows**:
  - Total students count
  - Total users count
  - Total payments received
  - Pending receipts count
  - Total enrollments
  - Recent payments list
  - Recent receipts with status

### 2. **Admin (Accountant/Employee)** 🟥
- **Color Theme**: Red (#c0392b)
- **Access Level**: Limited financial management
- **Sidebar Features**:
  - Dashboard Overview
  - Receipt Management (Pending Reviews, All Receipts)
  - View Data (Students, Payments)
  - Reports (Payments, Outstanding Balances)
  - Account Settings
- **Dashboard Shows**:
  - Pending receipts count with "Review now" link
  - Approved receipts count
  - Total collected amount
  - Students with outstanding balance
  - Detailed table of pending receipts for review

### 3. **Student** 🟦
- **Color Theme**: Blue (#2980b9)
- **Access Level**: View own academic/financial data
- **Sidebar Features**:
  - Dashboard Home
  - Academic (My Courses, My Grades)
  - Financial (Upload Receipt, Receipt History, Payment History)
  - Account Settings
- **Dashboard Shows**:
  - Active courses count
  - Amount already paid
  - Balance due
  - Total enrollments
  - Active courses list with status
  - Recent grades
  - Financial summary sidebar with balance calculation
  - Recent receipts

### 4. **Parent/Guardian** 🟩
- **Color Theme**: Green (#27ae60)
- **Access Level**: View children's data
- **Sidebar Features**:
  - Dashboard Overview
  - Children (My Children)
  - Account Settings
- **Dashboard Shows**:
  - Children enrolled count
  - Total balance due across all children
  - Total paid across all children
  - Children's account cards with individual balance display

## Database Structure

### Users Table
```sql
- id
- name
- email (unique)
- password (hashed)
- role (enum: 'super_admin', 'admin', 'student', 'parent')
- phone (nullable)
- address (nullable)
- email_verified_at (nullable)
- remember_token (nullable)
- deleted_at (soft deletes)
- timestamps
```

### Students Table
```sql
- ... existing fields ...
- user_id (FK to users)
- parent_id (FK to users - optional)
- parent_email (nullable)
- year
- semester
- tuition_fee
```

### Additional Tables (for future permissions)
- roles
- permissions
- role_permission (pivot)

## Authentication Flow

### Login Process
1. User visits `/login`
2. Enters email and password
3. System authenticates credentials
4. On success: redirects to `/` (dashboard)
5. Dashboard controller routes to role-specific view
6. On failure: returns with validation errors

### Registration Process
1. User visits `/register`
2. Creates account with:
   - Full Name
   - Email
   - Password (confirmed)
   - Account Type (Student or Parent)
   - Phone (optional)
3. Account created with selected role
4. Auto-login and redirect to dashboard

### Logout
1. User clicks logout
2. Session invalidated
3. Redirect to `/login`

## Route Structure

### Public Routes
```
GET  /login                    - Show login form
POST /login                    - Handle login
GET  /register                 - Show registration form
POST /register                 - Handle registration
```

### Protected Routes (All require auth middleware)
```
GET  /                         - Dashboard (role-specific)
GET  /home                     - Home page
POST /logout                   - Logout user
GET  /profile/edit             - Edit profile page
PUT  /profile/update           - Update profile
GET  /receipts/create          - Upload receipt form (Student only)
POST /receipts/store           - Store receipt (Student only)
GET  /receipts/student-history - View own receipt history (Student only)
GET  /receipts                 - View all receipts (Admin/Super Admin)
GET  /receipts/pending         - View pending receipts (Admin/Super Admin)
... (all other resources)
```

## Controller Classes

### AuthController (Authentication)
- `showLogin()` - Display login form
- `login(Request $request)` - Process login
- `showRegister()` - Display registration form
- `register(Request $request)` - Process registration
- `logout(Request $request)` - Handle logout

### DashboardController (Role-based dashboards)
- `index()` - Main dashboard router
  - Checks user role
  - Calls appropriate dashboard method
  - Returns role-specific view
- `superAdminDashboard()` - Super admin stats and data
- `adminDashboard()` - Accountant financial overview
- `studentDashboard()` - Student academic/financial info
- `parentDashboard()` - Parent children overview

### ProfileController (User profile management)
- `edit()` - Show edit profile form
- `update(Request $request)` - Update profile and password

## Layouts & Views

### Layout Files
1. **layouts/super-admin.blade.php**
   - Dark blue navbar (#2c3e50)
   - Comprehensive sidebar
   - Full system access indicator

2. **layouts/admin.blade.php**
   - Red navbar (#c0392b)
   - Limited sidebar (Financial focus)
   - Accountant-specific information

3. **layouts/student.blade.php**
   - Blue navbar (#2980b9)
   - Compact sidebar (Academic/Financial)
   - Student-centric features

4. **layouts/parent.blade.php**
   - Green navbar (#27ae60)
   - Simple sidebar (Children-focused)
   - Parent portal features

### Dashboard Views
- `dashboards/super-admin.blade.php`
- `dashboards/admin.blade.php`
- `dashboards/student.blade.php`
- `dashboards/parent.blade.php`

### Authentication Views
- `auth/login.blade.php` - Login form with demo credentials
- `auth/register.blade.php` - Registration form with role selection

### Profile View
- `profile/edit.blade.php` - Edit profile, email, phone, address, password

## User Model Methods

```php
// Role checking methods
$user->isSuperAdmin()  // bool
$user->isAdmin()       // bool
$user->isStudent()     // bool
$user->isParent()      // bool

// Relationships
$user->student()       // hasOne Student
$user->childrenAsParent() // hasMany Student (for parents)
```

## Student Model Methods

```php
// Relationships
$student->user()       // belongsTo User
$student->parent()     // belongsTo User (parent_id FK)
$student->receipts()   // hasMany Receipt
$student->payments()   // hasMany Payment

// Financial methods
$student->getPaidAmount()  // Sum of approved receipts
$student->getBalance()     // tuition_fee - paid_amount
$student->isFullyPaid()    // bool
```

## Demo Accounts

All passwords: `password123`

### Super Admin
- **Email**: superadmin@school.edu
- **Password**: password123

### Accountant (Admin)
- **Email**: accountant@school.edu
- **Password**: password123

### Students (5 accounts)
- **Email**: student1@school.edu through student5@school.edu
- **Password**: password123 (all)

### Parents (3 accounts)
- **Email**: parent1@school.edu through parent3@school.edu
- **Password**: password123 (all)

## Middleware

### CheckRole Middleware
```php
// Usage: Route::middleware('role:super_admin,admin')->group(...)
// Checks if user has specified role
// Returns 403 if unauthorized
```

### Built-in Middlewares Used
- `auth` - Requires authenticated user
- `guest` - Only for non-authenticated users

## Feature Highlights

1. **Secure Authentication**
   - Password hashing (bcrypt)
   - Session management
   - CSRF protection
   - Remember me functionality

2. **Role-Based Access Control**
   - 4 distinct roles with different permissions
   - Role-specific dashboards
   - Role-specific sidebars
   - Role-specific color themes

3. **Financial Tracking**
   - Balance calculation per student
   - Receipt approval workflow
   - Payment history tracking
   - Outstanding balance reports

4. **User-Friendly**
   - Beautiful login/register pages
   - Responsive design
   - Color-coded user types
   - Demo credentials for testing

5. **Soft Deletes**
   - Users can be soft deleted
   - Data preservation
   - Account recovery possible

## Future Enhancements

1. Email verification
2. Two-factor authentication
3. Password reset via email
4. Advanced permission system with granular controls
5. Audit logging of admin actions
6. Session timeout security
7. IP whitelist for admin accounts
8. Role-based API authentication
