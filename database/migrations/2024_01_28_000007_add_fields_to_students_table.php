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
        Schema::table('students', function (Blueprint $table) {
            if (!Schema::hasColumn('students', 'year')) {
                $table->integer('year')->default(1)->after('status');
            }
            if (!Schema::hasColumn('students', 'semester')) {
                $table->integer('semester')->default(1)->after('year');
            }
            if (!Schema::hasColumn('students', 'tuition_fee')) {
                $table->decimal('tuition_fee', 10, 2)->default(10000)->after('semester');
            }
            if (!Schema::hasColumn('students', 'user_id')) {
                $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade')->after('id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            if (Schema::hasColumn('students', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
            if (Schema::hasColumn('students', 'year')) {
                $table->dropColumn('year');
            }
            if (Schema::hasColumn('students', 'semester')) {
                $table->dropColumn('semester');
            }
            if (Schema::hasColumn('students', 'tuition_fee')) {
                $table->dropColumn('tuition_fee');
            }
        });
    }
};
