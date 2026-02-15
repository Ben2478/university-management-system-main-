<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'student_id',
        'amount',
        'description',
        'status',
        'transaction_id',
        'reference_number',
        'payment_method',
        'paid_at',
        'gateway_response',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'gateway_response' => 'array',
        'paid_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function receipt()
    {
        return $this->hasOne(Receipt::class);
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    public function markAsCompleted(string $transactionId, array $gatewayResponse = []): void
    {
        $this->update([
            'status' => 'completed',
            'transaction_id' => $transactionId,
            'gateway_response' => $gatewayResponse,
            'paid_at' => now(),
        ]);
    }

    public function markAsFailed(string $reason, array $gatewayResponse = []): void
    {
        $this->update([
            'status' => 'failed',
            'gateway_response' => array_merge($gatewayResponse, ['error' => $reason]),
        ]);
    }
}
