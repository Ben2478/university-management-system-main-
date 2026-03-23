<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Super Admin
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@school.edu',
            'password' => Hash::make('welcome123'),
            'role' => 'super_admin',
            'phone' => '+260123456789',
            'address' => '123 Admin Street',
            'must_change_password' => true,
        ]);

        // Create Admin (Accountant)
        User::create([
            'name' => 'Accountant John',
            'email' => 'accountant@school.edu',
            'password' => Hash::make('welcome123'),
            'role' => 'admin',
            'phone' => '+260123456790',
            'address' => '456 Finance Avenue',
            'must_change_password' => true,
        ]);

        // Create Student Users
        for ($i = 1; $i <= 5; $i++) {
            $user = User::create([
                'name' => "Student {$i}",
                'email' => "student{$i}@school.edu",
                'password' => Hash::make('welcome123'),
                'role' => 'student',
                'phone' => '+2601234567' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'address' => "{$i}00 Student Street",
                'must_change_password' => true,
            ]);
            
            // Create corresponding Student record
            Student::create([
                'user_id' => $user->id,
                'student_id' => 'STU' . str_pad($i, 5, '0', STR_PAD_LEFT),
                'first_name' => "Student",
                'last_name' => "{$i}",
                'email' => $user->email,
                'phone' => $user->phone,
                'date_of_birth' => now()->subYears(20),
                'gender' => 'Male',
                'enrollment_date' => now(),
                'year' => 1,
                'semester' => 1,
                'tuition_fee' => 5000,
                'status' => 'active'
            ]);
        }

        // Create Parent Users
        for ($i = 1; $i <= 3; $i++) {
            User::create([
                'name' => "Parent {$i}",
                'email' => "parent{$i}@school.edu",
                'password' => Hash::make('welcome123'),
                'role' => 'parent',
                'phone' => '+2601234567' . str_pad($i + 10, 2, '0', STR_PAD_LEFT),
                'address' => "{$i}00 Parent Street",
                'must_change_password' => true,
            ]);
        }
    }
}
