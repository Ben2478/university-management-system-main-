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
        // Seed programs first (required for courses and students)
        $this->call(ProgramSeeder::class);
        
        // User, Student, and Course seeding skipped (already exist)
        // Seed enrollments
        $this->call(EnrollmentSeeder::class);
        // Seed grades
        $this->call(GradeSeeder::class);
    }
}
