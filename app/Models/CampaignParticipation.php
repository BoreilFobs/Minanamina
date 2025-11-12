<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CampaignParticipation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'campaign_id',
        'status',
        'started_at',
        'completed_at',
        'rejected_at',
        'rejection_reason',
        'pieces_earned',
        'validation_data',
        'time_spent_minutes',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
            'rejected_at' => 'datetime',
            'pieces_earned' => 'decimal:2',
            'validation_data' => 'json',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }
}
