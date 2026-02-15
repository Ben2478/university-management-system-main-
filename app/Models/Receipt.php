<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Receipt extends Model
{
    protected $fillable = [
        'student_id',
        'payment_id',
        'file_path',
        'original_filename',
        'amount',
        'payment_method',
        'status',
        'accountant_notes',
        'reviewed_by',
        'reviewed_at',
        'uploaded_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'reviewed_at' => 'datetime',
        'uploaded_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function approve(int $reviewedBy, string $notes = ''): void
    {
        $this->update([
            'status' => 'approved',
            'reviewed_by' => $reviewedBy,
            'accountant_notes' => $notes,
            'reviewed_at' => now(),
        ]);
    }

    public function reject(int $reviewedBy, string $notes = ''): void
    {
        $this->update([
            'status' => 'rejected',
            'reviewed_by' => $reviewedBy,
            'accountant_notes' => $notes,
            'reviewed_at' => now(),
        ]);
    }
}
