<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $fillable = [
        'student_id',
        'course_id',
        'assignment_score',
        'midterm_score',
        'final_score',
        'overall_score',
        'grade',
        'remarks',
        'graded_date',
    ];

    protected $casts = [
        'graded_date' => 'date',
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // Mutators
    public function setOverallScoreAttribute($value)
    {
        $this->attributes['overall_score'] = $value;
        $this->attributes['grade'] = $this->calculateGrade($value);
    }

    private function calculateGrade($score)
    {
        if ($score >= 90) return 'A';
        if ($score >= 80) return 'B';
        if ($score >= 70) return 'C';
        if ($score >= 60) return 'D';
        return 'F';
    }
}
