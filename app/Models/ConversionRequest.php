<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConversionRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'pieces_amount',
        'cash_amount',
        'conversion_rate',
        'status',
        'payment_method',
        'payment_phone',
        'payment_email',
        'payment_account',
        'payment_details',
        'approved_by',
        'approved_at',
        'processed_at',
        'completed_at',
        'rejection_reason',
        'admin_notes',
        'transaction_reference',
        'payment_proof',
    ];

    protected function casts(): array
    {
        return [
            'pieces_amount' => 'decimal:2',
            'cash_amount' => 'decimal:2',
            'conversion_rate' => 'decimal:4',
            'payment_details' => 'json',
            'approved_at' => 'datetime',
            'processed_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Helper methods
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

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function canBeProcessed(): bool
    {
        return in_array($this->status, ['pending', 'approved']);
    }
}
