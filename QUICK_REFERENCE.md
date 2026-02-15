# 🚀 Quick Reference Card - Student Management System

## 🔓 Login URLs

| Role | Email | Password |
|------|-------|----------|
| Super Admin | superadmin@school.edu | password123 |
| Accountant | accountant@school.edu | password123 |
| Student | student1@school.edu | password123 |
| Parent | parent1@school.edu | password123 |

**Login at:** `http://127.0.0.1:8000/login`

---

## 📱 Main Routes

```
/login              →  Login page
/register           →  Registration page
/                   →  Dashboard (role-specific)
/logout             →  Logout
/profile/edit       →  Edit profile
```

---

## 🎨 Themes

| Role | Color | Hex |
|------|-------|-----|
| Super Admin | Dark Blue | #2c3e50 |
| Accountant | Red | #c0392b |
| Student | Blue | #2980b9 |
| Parent | Green | #27ae60 |

---

## 🔐 Role Methods (User Model)

```php
// Check role
auth()->user()->isSuperAdmin()   // bool
auth()->user()->isAdmin()        // bool
auth()->user()->isStudent()      // bool
auth()->user()->isParent()       // bool

// Get data
auth()->user()->student          // Student object
auth()->user()->childrenAsParent // Student[] (parents)
```

---

## 💰 Student Balance Methods

```php
$student->getBalance()    // Remaining balance
$student->getPaidAmount() // Total approved receipts
$student->isFullyPaid()   // Boolean check
```

---

## 📊 Database Roles

```sql
-- Add role to user
UPDATE users SET role = 'super_admin' WHERE id = 1;
UPDATE users SET role = 'admin' WHERE id = 2;
UPDATE users SET role = 'student' WHERE id = 3;
UPDATE users SET role = 'parent' WHERE id = 4;

-- Check roles
SELECT email, role FROM users;
```

---

## 🛠️ Common Commands

```bash
# Start server
php artisan serve

# Run migrations
php artisan migrate

# Seed demo data
php artisan db:seed --class=UserSeeder

# Clear caches
php artisan cache:clear
php artisan config:cache

# View routes
php artisan route:list
```

---

## 📁 Key Files to Know

| File | Purpose |
|------|---------|
| `AuthController.php` | Login/Register logic |
| `DashboardController.php` | Route to role dashboard |
| `User.php` | User model with roles |
| `Student.php` | Student with balance calc |
| `layouts/super-admin.blade.php` | Super admin layout |
| `layouts/admin.blade.php` | Accountant layout |
| `layouts/student.blade.php` | Student layout |
| `layouts/parent.blade.php` | Parent layout |

---

## 🔍 Debugging Tips

```php
// Check current user role
dd(auth()->user()->role);

// Check if user has role
if (auth()->user()->isSuperAdmin()) { ... }

// Get student from user
$student = auth()->user()->student;

// Check student balance
$balance = $student->getBalance();
```

---

## 🎯 Dashboard URLs (After Login)

| Role | Dashboard View |
|------|----------------|
| Super Admin | `dashboards/super-admin.blade.php` |
| Accountant | `dashboards/admin.blade.php` |
| Student | `dashboards/student.blade.php` |
| Parent | `dashboards/parent.blade.php` |

---

## 📧 Email Fields

```
Auth: email, password
Profile: name, email, phone, address
Student: email, parent_email
User: email (unique)
```

---

## 💾 Tables with Role Data

```
users
├── role (enum)           ← Core role field
├── phone
└── address

students
├── user_id               ← Links to user
├── parent_id             ← Links to parent user
├── parent_email
├── tuition_fee           ← Financial
└── year, semester
```

---

## 🔗 Key Relationships

```
User (1) ──→ (1) Student
User (1) ──→ (many) Student as parent
Student (1) ──→ (many) Receipt
Student (1) ──→ (many) Enrollment
Enrollment (1) ──→ (1) Course
```

---

## 🧪 Testing Role Access

### Super Admin Test
- ✅ Can access `/` (super-admin dashboard)
- ✅ Can see all students
- ✅ Can access all receipts
- ✅ Dark blue theme

### Accountant Test
- ✅ Can access `/` (admin dashboard)
- ✅ Can access `/receipts/pending`
- ✅ Can approve/reject receipts
- ✅ Red theme

### Student Test
- ✅ Can access `/` (student dashboard)
- ✅ Can access `/receipts/create`
- ✅ Can upload receipts
- ✅ Blue theme

### Parent Test
- ✅ Can access `/` (parent dashboard)
- ✅ Can see children
- ✅ Can see balances
- ✅ Green theme

---

## ⚠️ Important Notes

1. **Password Hashing**: All passwords are hashed with bcrypt
2. **Session**: Lasts per browser session (remember me option)
3. **Role-Based**: Every route checks auth() && role
4. **Soft Deletes**: Users can be deleted without data loss
5. **Balance Calc**: Auto-updates on receipt approval

---

## 🚨 Troubleshooting

| Issue | Solution |
|-------|----------|
| Login fails | Check credentials match UserSeeder |
| Wrong dashboard | Check user role in database |
| Routes not found | Run `php artisan cache:clear` |
| Styles missing | Ensure middleware registered in app.php |
| Session not saving | Check browser cookies enabled |

---

## 📖 Documentation Files

- `AUTHENTICATION_GUIDE.md` - Detailed authentication docs
- `AUTHENTICATION_IMPLEMENTATION.md` - Implementation details
- `SYSTEM_OVERVIEW.md` - System-wide overview
- `DOCUMENTATION_INDEX.md` - All documentation

---

## 🎓 Quick Start

1. **Start server:** `php artisan serve`
2. **Go to:** `http://127.0.0.1:8000/login`
3. **Login:** `superadmin@school.edu` / `password123`
4. **Explore:** Click through the super admin dashboard
5. **Test roles:** Logout and test other demo accounts

---

*For detailed info, see full documentation files*
**Status: ✅ Ready for Testing**
