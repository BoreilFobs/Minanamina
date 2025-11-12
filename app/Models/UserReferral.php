<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserReferral extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'referrer_id',
        'referral_user_id',
        'status',
        'referral_level',
        'commission_percentage',
        'total_earned',
        'pending_earnings',
        'referred_at',
        'activated_at',
    ];

    protected function casts(): array
    {
        return [
            'referred_at' => 'datetime',
            'activated_at' => 'datetime',
            'commission_percentage' => 'decimal:2',
            'total_earned' => 'decimal:2',
            'pending_earnings' => 'decimal:2',
        ];
    }

    public function referrer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referrer_id');
    }

    public function referral(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referral_user_id');
    }
}
