<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'course_code',
        'course_name',
        'description',
        'credits',
        'department',
        'instructor',
        'semester',
        'tuition_fee',
        'status',
    ];

    protected $casts = [
        'tuition_fee' => 'decimal:2',
    ];

    // Relationships
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function students()
    {
        return $this->hasManyThrough(Student::class, Enrollment::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }

    public function lecturers()
    {
        return $this->belongsToMany(Lecturer::class)->withTimestamps();
    }

    public function getEnrolledStudentsCountAttribute()
    {
        return $this->enrollments()->where('status', 'active')->count();
    }
}
