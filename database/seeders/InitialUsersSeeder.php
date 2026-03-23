<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class InitialUsersSeeder extends Seeder
{
    public function run(): void
    {
        $superAdminEmail = env('INITIAL_SUPERADMIN_EMAIL', 'superadmin@school.edu');
        $superAdminName = env('INITIAL_SUPERADMIN_NAME', 'Super Admin');
        $adminEmail = env('INITIAL_ADMIN_EMAIL', 'accountant@school.edu');
        $adminName = env('INITIAL_ADMIN_NAME', 'Accountant John');

        User::updateOrCreate(
            ['email' => $superAdminEmail],
            [
                'name' => $superAdminName,
                'password' => Hash::make('welcome123'),
                'role' => 'super_admin',
                'phone' => '+260123456789',
                'address' => '123 Admin Street',
                'must_change_password' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => $adminEmail],
            [
                'name' => $adminName,
                'password' => Hash::make('welcome123'),
                'role' => 'admin',
                'phone' => '+260123456790',
                'address' => '456 Finance Avenue',
                'must_change_password' => true,
            ]
        );
    }
}
