<?php

namespace App\Services;

use App\Models\User;
use App\Models\Referral;
use App\Models\ReferralSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReferralService
{
    /**
     * Process a referral when a new user registers
     */
    public function processReferral(User $newUser, string $referralCode): array
    {
        try {
            // Check if referral system is enabled
            if (!ReferralSetting::get('referral_enabled', true)) {
                return [
                    'success' => false,
                    'message' => 'Le système de parrainage est actuellement désactivé',
                ];
            }

            // Find the referrer
            $referrer = User::where('referral_code', $referralCode)->first();

            if (!$referrer) {
                return [
                    'success' => false,
                    'message' => 'Code de parrainage invalide',
                ];
            }

            // Can't refer yourself
            if ($referrer->id === $newUser->id) {
                return [
                    'success' => false,
                    'message' => 'Vous ne pouvez pas utiliser votre propre code de parrainage',
                ];
            }

            // Check if user was already referred
            if ($newUser->wasReferred()) {
                return [
                    'success' => false,
                    'message' => 'Vous avez déjà été parrainé',
                ];
            }

            $bonusAmount = ReferralSetting::get('referral_bonus_amount', 500);
            $newUserBonus = ReferralSetting::get('new_user_bonus_amount', 100);

            DB::beginTransaction();

            try {
                // Update new user's referred_by
                $newUser->update(['referred_by' => $referrer->id]);

                // Create referral record
                $referral = Referral::create([
                    'referrer_id' => $referrer->id,
                    'referred_id' => $newUser->id,
                    'referral_code' => $referralCode,
                    'bonus_amount' => $bonusAmount,
                    'status' => 'pending',
                ]);

                // Award bonus to referrer
                $this->awardReferralBonus($referrer, $newUser, $bonusAmount);

                // Award welcome bonus to new user
                if ($newUserBonus > 0) {
                    $newUser->addPieces(
                        $newUserBonus,
                        'welcome_bonus',
                        null,
                        "Bonus de bienvenue pour inscription avec code de parrainage"
                    );
                }

                // Mark referral as credited
                $referral->markAsCredited();

                DB::commit();

                return [
                    'success' => true,
                    'message' => "Parrainage réussi! {$referrer->name} a gagné {$bonusAmount} pièces et vous avez reçu {$newUserBonus} pièces de bienvenue",
                    'bonus_amount' => $bonusAmount,
                    'new_user_bonus' => $newUserBonus,
                    'referrer' => $referrer,
                ];

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            Log::error('Referral processing error', [
                'new_user_id' => $newUser->id,
                'referral_code' => $referralCode,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Erreur lors du traitement du parrainage',
            ];
        }
    }

    /**
     * Award referral bonus to referrer
     */
    protected function awardReferralBonus(User $referrer, User $referred, int $amount): void
    {
        // Add pieces to referrer
        $referrer->addPieces(
            $amount,
            'referral_bonus',
            null,
            "Bonus de parrainage pour {$referred->name}"
        );

        // Update referral earnings
        $referrer->addReferralEarnings($amount);
    }

    /**
     * Get referral statistics for a user
     */
    public function getUserReferralStats(User $user): array
    {
        $referredUsers = $user->referredUsers()->select('id', 'name', 'avatar', 'created_at')->get();
        $totalReferrals = $referredUsers->count();
        
        // Calculate actual referral earnings from transactions
        $actualReferralEarnings = $user->piecesTransactions()
            ->where('type', 'referral_bonus')
            ->where('amount', '>', 0)
            ->sum('amount');
        
        return [
            'referral_code' => $user->referral_code,
            'total_referrals' => $totalReferrals,
            'referral_earnings' => $actualReferralEarnings,
            'pending_referrals' => $user->referralsMade()->where('status', 'pending')->count(),
            'credited_referrals' => $user->referralsMade()->where('status', 'credited')->count(),
            'referred_users' => $referredUsers,
        ];
    }

    /**
     * Get global referral statistics
     */
    public function getGlobalStats(): array
    {
        return [
            'total_referrals' => Referral::count(),
            'pending_referrals' => Referral::where('status', 'pending')->count(),
            'credited_referrals' => Referral::where('status', 'credited')->count(),
            'total_bonus_paid' => Referral::where('status', 'credited')->sum('bonus_amount'),
            'top_referrers' => User::where('total_referrals', '>', 0)
                ->orderByDesc('total_referrals')
                ->limit(10)
                ->get(['id', 'name', 'avatar', 'total_referrals', 'referral_earnings']),
        ];
    }

    /**
     * Validate a referral code
     */
    public function validateReferralCode(string $code): bool
    {
        return User::where('referral_code', $code)->exists();
    }

    /**
     * Get referral bonus amount
     */
    public function getReferralBonusAmount(): int
    {
        return ReferralSetting::get('referral_bonus_amount', 500);
    }

    /**
     * Update referral bonus amount (admin only)
     */
    public function updateReferralBonusAmount(int $amount): void
    {
        ReferralSetting::set('referral_bonus_amount', $amount);
    }

    /**
     * Get new user bonus amount
     */
    public function getNewUserBonusAmount(): int
    {
        return ReferralSetting::get('new_user_bonus_amount', 100);
    }

    /**
     * Update new user bonus amount (admin only)
     */
    public function updateNewUserBonusAmount(int $amount): void
    {
        ReferralSetting::set('new_user_bonus_amount', $amount);
    }

    /**
     * Check if referral system is enabled
     */
    public function isEnabled(): bool
    {
        return ReferralSetting::get('referral_enabled', true);
    }

    /**
     * Toggle referral system
     */
    public function toggleSystem(bool $enabled): void
    {
        ReferralSetting::set('referral_enabled', $enabled ? 'true' : 'false');
    }
}
