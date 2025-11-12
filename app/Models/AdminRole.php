<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AdminRole extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'role_type',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(AdminPermission::class, 'admin_role_permissions');
    }

    public function users(): HasMany
    {
        return $this->hasMany(UserAdminRole::class);
    }
}
