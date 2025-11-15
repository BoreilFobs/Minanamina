<?php

namespace App\Services;

use App\Models\Badge;
use App\Models\User;
use App\Models\UserBadge;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class BadgeService
{
    /**
     * Check all badge criteria for a user and award any earned badges
     */
    public function checkAndAwardBadges(User $user): Collection
    {
        $awardedBadges = collect();
        $activeBadges = Badge::where('is_active', true)->get();

        foreach ($activeBadges as $badge) {
            if ($this->checkBadgeCriteria($user, $badge)) {
                $awarded = $this->awardBadge($user, $badge);
                if ($awarded) {
                    $awardedBadges->push($badge);
                }
            }
        }

        return $awardedBadges;
    }

    /**
     * Check if a user meets the criteria for a specific badge
     */
    public function checkBadgeCriteria(User $user, Badge $badge): bool
    {
        // Skip if user already has this badge
        if ($user->badges()->where('badge_id', $badge->id)->exists()) {
            return false;
        }

        $criteria = $badge->criteria;
        if (!$criteria) {
            return false;
        }

        // Campaign completion badges
        if (isset($criteria['campaigns_completed'])) {
            if ($user->total_campaigns_completed < $criteria['campaigns_completed']) {
                return false;
            }
        }

        // Consecutive completion badges
        if (isset($criteria['consecutive_completions'])) {
            if ($user->consecutive_completions < $criteria['consecutive_completions']) {
                return false;
            }
        }

        // Earnings badges
        if (isset($criteria['lifetime_earnings'])) {
            if ($user->lifetime_earnings < $criteria['lifetime_earnings']) {
                return false;
            }
        }

        // Referral badges
        if (isset($criteria['referrals_count'])) {
            $referralsCount = $user->referrals()->count();
            if ($referralsCount < $criteria['referrals_count']) {
                return false;
            }
        }

        // Conversion badges
        if (isset($criteria['conversions_completed'])) {
            $conversionsCount = $user->conversionRequests()
                ->where('status', 'completed')
                ->count();
            if ($conversionsCount < $criteria['conversions_completed']) {
                return false;
            }
        }

        // Days active badge
        if (isset($criteria['days_active'])) {
            $daysActive = $user->created_at->diffInDays(now());
            if ($daysActive < $criteria['days_active']) {
                return false;
            }
        }

        return true;
    }

    /**
     * Award a badge to a user
     */
    public function awardBadge(User $user, Badge $badge): ?UserBadge
    {
        // Check if already awarded
        $existing = UserBadge::where('user_id', $user->id)
            ->where('badge_id', $badge->id)
            ->first();

        if ($existing) {
            return null;
        }

        return UserBadge::create([
            'user_id' => $user->id,
            'badge_id' => $badge->id,
            'awarded_at' => now(),
        ]);
    }

    /**
     * Get all badges for a user with awarded status
     */
    public function getUserBadgesWithProgress(User $user): Collection
    {
        $badges = Badge::where('is_active', true)->get();
        $earnedBadgeIds = $user->badges()->pluck('badges.id')->toArray();

        return $badges->map(function ($badge) use ($user, $earnedBadgeIds) {
            $isEarned = in_array($badge->id, $earnedBadgeIds);
            $progress = $this->getBadgeProgress($user, $badge);

            return [
                'badge' => $badge,
                'is_earned' => $isEarned,
                'awarded_at' => $isEarned ? $user->badges()->where('badges.id', $badge->id)->first()->pivot->awarded_at : null,
                'progress' => $progress,
            ];
        });
    }

    /**
     * Get progress toward earning a badge
     */
    public function getBadgeProgress(User $user, Badge $badge): array
    {
        $criteria = $badge->criteria;
        if (!$criteria) {
            return ['percentage' => 0, 'current' => 0, 'required' => 0, 'label' => ''];
        }

        // Campaign completion progress
        if (isset($criteria['campaigns_completed'])) {
            $current = $user->total_campaigns_completed;
            $required = $criteria['campaigns_completed'];
            return [
                'percentage' => min(100, round(($current / $required) * 100)),
                'current' => $current,
                'required' => $required,
                'label' => 'Campagnes complétées',
            ];
        }

        // Consecutive completions progress
        if (isset($criteria['consecutive_completions'])) {
            $current = $user->consecutive_completions;
            $required = $criteria['consecutive_completions'];
            return [
                'percentage' => min(100, round(($current / $required) * 100)),
                'current' => $current,
                'required' => $required,
                'label' => 'Complétions consécutives',
            ];
        }

        // Earnings progress
        if (isset($criteria['lifetime_earnings'])) {
            $current = $user->lifetime_earnings;
            $required = $criteria['lifetime_earnings'];
            return [
                'percentage' => min(100, round(($current / $required) * 100)),
                'current' => $current,
                'required' => $required,
                'label' => 'Pièces gagnées',
            ];
        }

        // Referrals progress
        if (isset($criteria['referrals_count'])) {
            $current = $user->referrals()->count();
            $required = $criteria['referrals_count'];
            return [
                'percentage' => min(100, round(($current / $required) * 100)),
                'current' => $current,
                'required' => $required,
                'label' => 'Parrainages',
            ];
        }

        // Conversions progress
        if (isset($criteria['conversions_completed'])) {
            $current = $user->conversionRequests()->where('status', 'completed')->count();
            $required = $criteria['conversions_completed'];
            return [
                'percentage' => min(100, round(($current / $required) * 100)),
                'current' => $current,
                'required' => $required,
                'label' => 'Conversions réussies',
            ];
        }

        // Days active progress
        if (isset($criteria['days_active'])) {
            $current = $user->created_at->diffInDays(now());
            $required = $criteria['days_active'];
            return [
                'percentage' => min(100, round(($current / $required) * 100)),
                'current' => $current,
                'required' => $required,
                'label' => 'Jours actif',
            ];
        }

        return ['percentage' => 0, 'current' => 0, 'required' => 0, 'label' => ''];
    }

    /**
     * Get earned badges for a user
     */
    public function getEarnedBadges(User $user): Collection
    {
        return $user->badges()->get();
    }

    /**
     * Get available (not yet earned) badges for a user
     */
    public function getAvailableBadges(User $user): Collection
    {
        $earnedBadgeIds = $user->badges()->pluck('badges.id')->toArray();
        
        return Badge::where('is_active', true)
            ->whereNotIn('id', $earnedBadgeIds)
            ->get();
    }

    /**
     * Get recently awarded badges for a user
     */
    public function getRecentBadges(User $user, int $limit = 5): Collection
    {
        return $user->badges()
            ->orderByDesc('user_badges.awarded_at')
            ->limit($limit)
            ->get();
    }

    /**
     * Get badge statistics for a user
     */
    public function getUserBadgeStats(User $user): array
    {
        $totalBadges = Badge::where('is_active', true)->count();
        $earnedBadges = $user->badges()->count();
        
        return [
            'total_badges' => $totalBadges,
            'earned_badges' => $earnedBadges,
            'remaining_badges' => $totalBadges - $earnedBadges,
            'completion_percentage' => $totalBadges > 0 ? round(($earnedBadges / $totalBadges) * 100) : 0,
        ];
    }
}
