<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academic_calendars', function (Blueprint $table) {
            $table->id();
            $table->string('academic_year', 20);
            $table->unsignedTinyInteger('semester');
            $table->date('semester_start_date');
            $table->date('semester_end_date');
            $table->date('term_1_start_date');
            $table->date('term_1_end_date');
            $table->date('term_2_start_date');
            $table->date('term_2_end_date');
            $table->date('term_3_start_date');
            $table->date('term_3_end_date');
            $table->timestamps();

            $table->unique(['academic_year', 'semester']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_calendars');
    }
};