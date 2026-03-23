<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('lecturers')) {
            return;
        }

        Schema::create('lecturers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('lecturer_code', 50)->unique();
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            // 191 to avoid older MySQL utf8mb4 index-length issues
            $table->string('email', 191)->unique();
            $table->string('phone', 30)->nullable();
            $table->string('school_code', 10)->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();

            $table->index('user_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lecturers');
    }
};
