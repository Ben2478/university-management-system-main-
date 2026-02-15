<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $fillable = [
        'name',
        'description',
        'department',
        'duration_years',
        'status',
    ];

    // Relationships
    public function courses()
    {
        return $this->hasMany(Course::class, 'department', 'name');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'program', 'name');
    }
}
