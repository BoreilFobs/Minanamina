<?php

namespace App\Services;

use App\Models\User;
use App\Models\Campaign;
use App\Models\CampaignParticipation;
use App\Models\UserPiecesTransaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RewardService
{
    protected BadgeService $badgeService;

    public function __construct(BadgeService $badgeService)
    {
        $this->badgeService = $badgeService;
    }

    // Configuration constants
    const MIN_COMPLETION_TIME_SECONDS = 30; // Minimum time to prevent gaming
    const CONSECUTIVE_BONUS_MULTIPLIER = 1.1; // 10% bonus
    const CONSECUTIVE_THRESHOLD = 5; // Number of consecutive completions for bonus
    const LOYALTY_BONUS_DAYS = 30; // Days of consecutive activity for loyalty bonus
    const LOYALTY_BONUS_PERCENTAGE = 15; // 15% bonus for loyal users
    const MAX_DAILY_EARNINGS_MULTIPLIER = 10; // Max earnings per day = avg campaign reward * 10
    const SUSPICIOUS_COMPLETION_TIME = 15; // Seconds - too fast is suspicious
    const SUSPICIOUS_DAILY_PARTICIPATIONS = 20; // Too many participations per day

    /**
     * Award pieces to user for completing a campaign
     */
    public function awardCampaignCompletion(CampaignParticipation $participation): array
    {
        DB::beginTransaction();
        try {
            $user = $participation->user;
            $campaign = $participation->campaign;

            // Fraud detection
            $fraudCheck = $this->detectFraud($user, $participation);
            if ($fraudCheck['is_suspicious']) {
                DB::rollBack();
                return [
                    'success' => false,
                    'message' => 'Activité suspecte détectée. Votre compte a été signalé pour révision.',
                    'flagged' => true,
                    'reason' => $fraudCheck['reason'],
                ];
            }

            // Calculate reward amount
            $baseAmount = $campaign->pieces_reward;
            $bonusAmount = 0;
            $totalAmount = $baseAmount;
            $bonuses = [];

            // Apply consecutive completion bonus
            if ($user->consecutive_completions >= self::CONSECUTIVE_THRESHOLD) {
                $consecutiveBonus = $baseAmount * (self::CONSECUTIVE_BONUS_MULTIPLIER - 1);
                $bonusAmount += $consecutiveBonus;
                $bonuses[] = "Bonus de complétion consécutive: +{$consecutiveBonus} pièces";
            }

            // Apply loyalty bonus (based on last_completion_at)
            if ($user->last_completion_at && 
                $user->last_completion_at->diffInDays(now()) <= 1) {
                $loyaltyBonus = $baseAmount * (self::LOYALTY_BONUS_PERCENTAGE / 100);
                $bonusAmount += $loyaltyBonus;
                $bonuses[] = "Bonus de fidélité: +{$loyaltyBonus} pièces";
            }

            $totalAmount += $bonusAmount;

            // Award pieces
            $transaction = $user->addPieces(
                $totalAmount,
                'earned',
                $campaign->id,
                "Campagne complétée: {$campaign->title}" . 
                ($bonusAmount > 0 ? " (Bonus: +{$bonusAmount})" : "")
            );

            // Update participation
            $participation->update([
                'status' => 'completed',
                'completed_at' => now(),
                'pieces_earned' => $totalAmount,
            ]);

            // Update user statistics
            $user->increment('consecutive_completions');
            $user->increment('total_campaigns_completed');
            $user->increment('lifetime_earnings', $totalAmount);
            $user->update(['last_completion_at' => now()]);

            // Update campaign statistics
            $campaign->increment('total_rewards_distributed', $totalAmount);
            $campaign->updateConversionRate();

            DB::commit();

            // Check and award badges after successful completion
            $newBadges = $this->badgeService->checkAndAwardBadges($user->fresh());

            return [
                'success' => true,
                'message' => 'Félicitations! Pièces attribuées avec succès.',
                'amount' => $totalAmount,
                'base_amount' => $baseAmount,
                'bonus_amount' => $bonusAmount,
                'bonuses' => $bonuses,
                'new_balance' => $user->fresh()->pieces_balance,
                'transaction_id' => $transaction->id,
                'new_badges' => $newBadges->count() > 0 ? $newBadges->pluck('name')->toArray() : [],
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Reward attribution failed', [
                'participation_id' => $participation->id,
                'error' => $e->getMessage(),
            ]);
            
            return [
                'success' => false,
                'message' => 'Erreur lors de l\'attribution des pièces: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Detect potentially fraudulent activity
     */
    protected function detectFraud(User $user, CampaignParticipation $participation): array
    {
        $reasons = [];

        // Check completion time (too fast)
        if ($participation->started_at) {
            $completionTimeSeconds = $participation->started_at->diffInSeconds(now());
            if ($completionTimeSeconds < self::SUSPICIOUS_COMPLETION_TIME) {
                $reasons[] = "Temps de complétion trop rapide ({$completionTimeSeconds}s)";
            }
        }

        // Check daily participation count
        $todayParticipations = CampaignParticipation::where('user_id', $user->id)
            ->whereDate('started_at', today())
            ->count();

        if ($todayParticipations > self::SUSPICIOUS_DAILY_PARTICIPATIONS) {
            $reasons[] = "Trop de participations aujourd'hui ({$todayParticipations})";
        }

        // Check if already flagged
        if ($user->is_flagged_suspicious) {
            $reasons[] = "Utilisateur déjà signalé comme suspect";
        }

        // Check for same IP/device pattern (would need additional tracking)
        // This is a placeholder for future implementation

        if (!empty($reasons)) {
            // Flag the user
            $user->update([
                'is_flagged_suspicious' => true,
                'fraud_notes' => implode('; ', $reasons),
            ]);

            return [
                'is_suspicious' => true,
                'reason' => implode('; ', $reasons),
            ];
        }

        return ['is_suspicious' => false];
    }

    /**
     * Award referral bonus
     */
    public function awardReferralBonus(User $referrer, User $referred, float $amount): UserPiecesTransaction
    {
        return DB::transaction(function() use ($referrer, $referred, $amount) {
            $transaction = $referrer->addPieces(
                $amount,
                'referral_bonus',
                null,
                "Bonus de parrainage: {$referred->name} ({$referred->phone})"
            );

            $referrer->increment('referral_earnings', $amount);

            // Check and award badges for referrals
            $this->badgeService->checkAndAwardBadges($referrer->fresh());

            return $transaction;
        });
    }

    /**
     * Manual adjustment by admin
     */
    public function manualAdjustment(User $user, float $amount, string $reason, User $admin): UserPiecesTransaction
    {
        return DB::transaction(function() use ($user, $amount, $reason, $admin) {
            if ($amount > 0) {
                $transaction = $user->addPieces(
                    $amount,
                    'manual_adjustment',
                    null,
                    "Ajustement manuel par {$admin->name}: {$reason}"
                );
            } else {
                $transaction = $user->deductPieces(
                    abs($amount),
                    'manual_adjustment',
                    "Ajustement manuel par {$admin->name}: {$reason}"
                );
            }

            // Log admin action
            Log::info('Manual pieces adjustment', [
                'admin_id' => $admin->id,
                'user_id' => $user->id,
                'amount' => $amount,
                'reason' => $reason,
            ]);

            return $transaction;
        });
    }

    /**
     * Reverse a transaction
     */
    public function reverseTransaction(UserPiecesTransaction $transaction, string $reason, User $admin): UserPiecesTransaction
    {
        return DB::transaction(function() use ($transaction, $reason, $admin) {
            $user = $transaction->user;
            $reversalAmount = -$transaction->amount; // Opposite of original

            if ($reversalAmount > 0) {
                $reversal = $user->addPieces(
                    $reversalAmount,
                    'reversal',
                    null,
                    "Annulation de transaction #{$transaction->id}: {$reason}"
                );
            } else {
                $reversal = $user->deductPieces(
                    abs($reversalAmount),
                    'reversal',
                    "Annulation de transaction #{$transaction->id}: {$reason}"
                );
            }

            // Log reversal
            Log::warning('Transaction reversed', [
                'original_transaction_id' => $transaction->id,
                'reversal_transaction_id' => $reversal->id,
                'admin_id' => $admin->id,
                'reason' => $reason,
            ]);

            return $reversal;
        });
    }

    /**
     * Get conversion rate (pieces to cash)
     */
    public function getConversionRate(): float
    {
        return \App\Models\Setting::getConversionRate();
    }

    /**
     * Calculate cash equivalent for pieces
     */
    public function calculateCashAmount(float $pieces): float
    {
        return round($pieces * $this->getConversionRate(), 2);
    }

    /**
     * Get minimum conversion threshold
     */
    public function getMinimumConversionAmount(): float
    {
        return \App\Models\Setting::getMinimumConversionPieces();
    }

    /**
     * Check if user can convert
     */
    public function canConvert(User $user, float $pieces): array
    {
        // Check if conversion system is enabled
        if (!\App\Models\Setting::isConversionEnabled()) {
            return [
                'can_convert' => false,
                'reason' => 'Le système de conversion est temporairement désactivé',
            ];
        }

        $minimum = $this->getMinimumConversionAmount();

        if ($pieces < $minimum) {
            return [
                'can_convert' => false,
                'reason' => "Montant minimum requis: {$minimum} pièces",
            ];
        }

        if (!$user->hasEnoughPieces($pieces)) {
            return [
                'can_convert' => false,
                'reason' => 'Solde insuffisant',
            ];
        }

        if ($user->is_flagged_suspicious) {
            return [
                'can_convert' => false,
                'reason' => 'Votre compte est en révision. Contactez le support.',
            ];
        }

        // Check for pending conversion requests
        $pendingConversions = $user->conversionRequests()
            ->whereIn('status', ['pending', 'processing'])
            ->count();

        if ($pendingConversions > 0) {
            return [
                'can_convert' => false,
                'reason' => 'Vous avez déjà une demande de conversion en cours',
            ];
        }

        return ['can_convert' => true];
    }
}
