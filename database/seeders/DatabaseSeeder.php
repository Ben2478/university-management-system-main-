<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User, Student, and Course seeding skipped (already exist)
        // Seed enrollments
        $this->call(EnrollmentSeeder::class);
        // Seed grades
        $this->call(GradeSeeder::class);
    }
}
