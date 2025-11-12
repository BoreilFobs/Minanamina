<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Campaign extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'image',
        'affiliate_link',
        'pieces_reward',
        'start_date',
        'end_date',
        'geographic_restrictions',
        'validation_conditions',
        'status',
        'created_by',
        'approved_by',
        'approved_at',
        'max_participants',
        'current_participants',
        'total_rewards_distributed',
        'conversion_rate',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'datetime',
            'end_date' => 'datetime',
            'approved_at' => 'datetime',
            'pieces_reward' => 'decimal:2',
            'total_rewards_distributed' => 'decimal:2',
            'conversion_rate' => 'decimal:2',
            'geographic_restrictions' => 'json',
            'validation_conditions' => 'json',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function participations(): HasMany
    {
        return $this->hasMany(CampaignParticipation::class);
    }

    public function piecesTransactions(): HasMany
    {
        return $this->hasMany(UserPiecesTransaction::class);
    }
}
