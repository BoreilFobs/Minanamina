<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
        'country',
        'status',
        'is_admin',
        'pieces_balance',
        'consecutive_completions',
        'total_campaigns_completed',
        'lifetime_earnings',
        'last_completion_at',
        'is_flagged_suspicious',
        'fraud_notes',
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
            'is_admin' => 'boolean',
            'is_flagged_suspicious' => 'boolean',
            'privacy_settings' => 'json',
            'notification_preferences' => 'json',
            'pieces_balance' => 'decimal:2',
            'lifetime_earnings' => 'decimal:2',
            'referral_earnings' => 'decimal:2',
            'last_completion_at' => 'datetime',
        ];
    }

    // Helper methods
    public function isAdmin(): bool
    {
        return $this->is_admin === true;
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

    public function userBadges(): HasMany
    {
        return $this->hasMany(UserBadge::class);
    }

    public function badges(): BelongsToMany
    {
        return $this->belongsToMany(Badge::class, 'user_badges')
            ->withPivot('awarded_at')
            ->withTimestamps()
            ->orderByDesc('user_badges.awarded_at');
    }

    public function leaderboards(): HasMany
    {
        return $this->hasMany(Leaderboard::class);
    }

    public function conversionRequests(): HasMany
    {
        return $this->hasMany(ConversionRequest::class);
    }

    // Reward system helper methods
    public function addPieces(float $amount, string $type, ?int $campaignId = null, ?string $description = null): UserPiecesTransaction
    {
        $balanceBefore = $this->pieces_balance;
        $balanceAfter = $balanceBefore + $amount;

        $this->update(['pieces_balance' => $balanceAfter]);

        return UserPiecesTransaction::create([
            'user_id' => $this->id,
            'campaign_id' => $campaignId,
            'type' => $type,
            'amount' => $amount,
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceAfter,
            'description' => $description,
            'reference_id' => 'TXN-' . strtoupper(uniqid()),
        ]);
    }

    public function deductPieces(float $amount, string $type, ?string $description = null): UserPiecesTransaction
    {
        $balanceBefore = $this->pieces_balance;
        $balanceAfter = $balanceBefore - $amount;

        if ($balanceAfter < 0) {
            throw new \Exception('Solde insuffisant pour cette opération');
        }

        $this->update(['pieces_balance' => $balanceAfter]);

        return UserPiecesTransaction::create([
            'user_id' => $this->id,
            'type' => $type,
            'amount' => -$amount,
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceAfter,
            'description' => $description,
            'reference_id' => 'TXN-' . strtoupper(uniqid()),
        ]);
    }

    public function hasEnoughPieces(float $amount): bool
    {
        return $this->pieces_balance >= $amount;
    }

    public function isSuspicious(): bool
    {
        return $this->is_flagged_suspicious === true;
    }
}


