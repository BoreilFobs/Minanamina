<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Leaderboard extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'rank',
        'score',
        'period',
        'period_date',
    ];

    protected function casts(): array
    {
        return [
            'score' => 'decimal:2',
            'period_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
