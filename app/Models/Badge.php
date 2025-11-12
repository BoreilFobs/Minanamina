<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Badge extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'icon',
        'criteria',
        'points_reward',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'criteria' => 'json',
            'is_active' => 'boolean',
        ];
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_badges');
    }

    public function userBadges(): HasMany
    {
        return $this->hasMany(UserBadge::class);
    }
}
