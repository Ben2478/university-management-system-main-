<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'student_id',
        'first_name',
        'last_name',
        'email',
        'nrc',
        'phone',
        'date_of_birth',
        'gender',
        'address',
        'city',
        'state',
        'postal_code',
        'enrollment_date',
        'program',
        'status',
        'user_id',
        'parent_id',
        'parent_email',
        'year',
        'semester',
        'tuition_fee',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'enrollment_date' => 'date',
    ];

    // Relationships
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function courses()
    {
        return $this->hasManyThrough(Course::class, Enrollment::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function receipts()
    {
        return $this->hasMany(Receipt::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    // Accessors
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getAgeAttribute()
    {
        return \Carbon\Carbon::parse($this->date_of_birth)->age;
    }

    // Calculate paid amount from approved receipts
    public function getPaidAmount()
    {
        return $this->receipts()
            ->where('status', 'approved')
            ->sum('amount');
    }

    // Calculate balance
    public function getBalance()
    {
        return $this->tuition_fee - $this->getPaidAmount();
    }

    // Check if fully paid
    public function isFullyPaid(): bool
    {
        return $this->getBalance() <= 0;
    }
}
