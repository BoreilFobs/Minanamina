<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Referral extends Model
{
    protected $fillable = [
        'referrer_id',
        'referred_id',
        'referral_code',
        'bonus_amount',
        'status',
        'credited_at',
    ];

    protected $casts = [
        'credited_at' => 'datetime',
    ];

    /**
     * Get the user who made the referral
     */
    public function referrer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referrer_id');
    }

    /**
     * Get the user who was referred
     */
    public function referred(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referred_id');
    }

    /**
     * Mark referral as credited
     */
    public function markAsCredited(): void
    {
        $this->update([
            'status' => 'credited',
            'credited_at' => now(),
        ]);
    }

    /**
     * Check if referral is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if referral is credited
     */
    public function isCredited(): bool
    {
        return $this->status === 'credited';
    }
}
