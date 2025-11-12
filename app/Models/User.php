<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'phone',
        'password',
        'avatar',
        'bio',
        'status',
        'pieces_balance',
        'privacy_settings',
        'notification_preferences',
        'referral_code',
        'total_referrals',
        'referral_earnings',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'phone_verified_at' => 'datetime',
            'password' => 'hashed',
            'privacy_settings' => 'json',
            'notification_preferences' => 'json',
            'pieces_balance' => 'decimal:2',
            'referral_earnings' => 'decimal:2',
        ];
    }

    // Relations
    public function campaigns(): HasMany
    {
        return $this->hasMany(Campaign::class, 'created_by');
    }

    public function participations(): HasMany
    {
        return $this->hasMany(CampaignParticipation::class);
    }

    public function piecesTransactions(): HasMany
    {
        return $this->hasMany(UserPiecesTransaction::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function referralCode(): HasOne
    {
        return $this->hasOne(ReferralCode::class);
    }

    public function referrals(): HasMany
    {
        return $this->hasMany(UserReferral::class, 'referrer_id');
    }

    public function referrer(): HasOne
    {
        return $this->hasOne(UserReferral::class, 'referral_user_id');
    }

    public function adminRole(): HasOne
    {
        return $this->hasOne(UserAdminRole::class);
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AdminAuditLog::class, 'admin_id');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(UserNotification::class);
    }

    public function supportTickets(): HasMany
    {
        return $this->hasMany(SupportTicket::class);
    }

    public function badges(): HasMany
    {
        return $this->hasMany(UserBadge::class);
    }

    public function leaderboards(): HasMany
    {
        return $this->hasMany(Leaderboard::class);
    }
}

