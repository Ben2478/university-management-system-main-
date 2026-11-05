<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicCalendar extends Model
{
    protected $fillable = [
        'academic_year',
        'semester',
        'semester_start_date',
        'semester_end_date',
        'term_1_start_date',
        'term_1_end_date',
        'term_2_start_date',
        'term_2_end_date',
        'term_3_start_date',
        'term_3_end_date',
    ];

    protected $casts = [
        'semester_start_date' => 'date',
        'semester_end_date' => 'date',
        'term_1_start_date' => 'date',
        'term_1_end_date' => 'date',
        'term_2_start_date' => 'date',
        'term_2_end_date' => 'date',
        'term_3_start_date' => 'date',
        'term_3_end_date' => 'date',
    ];
}