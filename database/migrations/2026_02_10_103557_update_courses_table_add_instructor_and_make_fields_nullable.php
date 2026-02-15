<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->string('instructor')->nullable()->after('department');
            $table->integer('credits')->nullable()->change();
            $table->integer('semester')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('instructor');
            $table->integer('credits')->nullable(false)->change();
            $table->integer('semester')->nullable(false)->change();
        });
    }
};
