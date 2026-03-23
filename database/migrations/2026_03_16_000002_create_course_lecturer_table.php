<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_lecturer', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->foreignId('lecturer_id')->constrained('lecturers')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['course_id', 'lecturer_id']);
            $table->index('course_id');
            $table->index('lecturer_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_lecturer');
    }
};
