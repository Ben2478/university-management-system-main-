<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class InitialUsersSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'superadmin@school.edu'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('welcome123'),
                'role' => 'super_admin',
                'phone' => '+260123456789',
                'address' => '123 Admin Street',
                'must_change_password' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'accountant@school.edu'],
            [
                'name' => 'Accountant John',
                'password' => Hash::make('welcome123'),
                'role' => 'admin',
                'phone' => '+260123456790',
                'address' => '456 Finance Avenue',
                'must_change_password' => true,
            ]
        );
    }
}
