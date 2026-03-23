<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::getDriverName();

        // MySQL/MariaDB: users.role is an ENUM in this project, so we must extend it.
        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('super_admin','admin','student','parent','lecturer') NOT NULL DEFAULT 'student'");
        }

        // SQLite/Postgres typically store enum as string or use different types;
        // no action required for local SQLite.
    }

    public function down(): void
    {
        $driver = DB::getDriverName();

        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('super_admin','admin','student','parent') NOT NULL DEFAULT 'student'");
        }
    }
};
