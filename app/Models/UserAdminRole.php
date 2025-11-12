<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAdminRole extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'admin_role_id',
        'assigned_at',
        'suspended_at',
        'revoked_at',
        'suspension_reason',
    ];

    protected function casts(): array
    {
        return [
            'assigned_at' => 'datetime',
            'suspended_at' => 'datetime',
            'revoked_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(AdminRole::class, 'admin_role_id');
    }
}
