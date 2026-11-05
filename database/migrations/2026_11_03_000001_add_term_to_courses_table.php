<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->unsignedTinyInteger('term')->default(1);
        });

        DB::table('courses')
            ->select(['id', 'semester'])
            ->orderBy('id')
            ->chunkById(100, function ($courses) {
                foreach ($courses as $course) {
                    $legacySemester = (int) ($course->semester ?? 1);
                    $term = (($legacySemester - 1) % 3) + 1;

                    DB::table('courses')
                        ->where('id', $course->id)
                        ->update(['term' => $term]);
                }
            });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('term');
        });
    }
};