<?php

namespace Database\Seeders;

use App\Models\Badge;
use Illuminate\Database\Seeder;

class BadgeSeeder extends Seeder
{
    public function run(): void
    {
        $badges = [
            // Campaign Completion Badges
            [
                'name' => 'Nouveau Venu',
                'description' => 'Complétez votre première campagne',
                'icon' => '🌱',
                'criteria' => ['campaigns_completed' => 1],
                'points_reward' => 50,
                'is_active' => true,
            ],
            [
                'name' => 'Explorateur',
                'description' => 'Complétez 5 campagnes',
                'icon' => '🔍',
                'criteria' => ['campaigns_completed' => 5],
                'points_reward' => 100,
                'is_active' => true,
            ],
            [
                'name' => 'Professionnel',
                'description' => 'Complétez 25 campagnes',
                'icon' => '⭐',
                'criteria' => ['campaigns_completed' => 25],
                'points_reward' => 250,
                'is_active' => true,
            ],
            [
                'name' => 'Expert',
                'description' => 'Complétez 50 campagnes',
                'icon' => '💎',
                'criteria' => ['campaigns_completed' => 50],
                'points_reward' => 500,
                'is_active' => true,
            ],
            [
                'name' => 'Maître',
                'description' => 'Complétez 100 campagnes',
                'icon' => '👑',
                'criteria' => ['campaigns_completed' => 100],
                'points_reward' => 1000,
                'is_active' => true,
            ],

            // Consecutive Completion Badges
            [
                'name' => 'En Série',
                'description' => '5 complétions consécutives',
                'icon' => '🔥',
                'criteria' => ['consecutive_completions' => 5],
                'points_reward' => 150,
                'is_active' => true,
            ],
            [
                'name' => 'Inarrêtable',
                'description' => '10 complétions consécutives',
                'icon' => '⚡',
                'criteria' => ['consecutive_completions' => 10],
                'points_reward' => 300,
                'is_active' => true,
            ],
            [
                'name' => 'Légende',
                'description' => '20 complétions consécutives',
                'icon' => '🏆',
                'criteria' => ['consecutive_completions' => 20],
                'points_reward' => 750,
                'is_active' => true,
            ],

            // Earnings Badges
            [
                'name' => 'Première Fortune',
                'description' => 'Gagnez 1,000 pièces au total',
                'icon' => '💰',
                'criteria' => ['lifetime_earnings' => 1000],
                'points_reward' => 100,
                'is_active' => true,
            ],
            [
                'name' => 'Collectionneur',
                'description' => 'Gagnez 5,000 pièces au total',
                'icon' => '💵',
                'criteria' => ['lifetime_earnings' => 5000],
                'points_reward' => 200,
                'is_active' => true,
            ],
            [
                'name' => 'Fortuné',
                'description' => 'Gagnez 10,000 pièces au total',
                'icon' => '💸',
                'criteria' => ['lifetime_earnings' => 10000],
                'points_reward' => 400,
                'is_active' => true,
            ],
            [
                'name' => 'Millionnaire',
                'description' => 'Gagnez 50,000 pièces au total',
                'icon' => '🤑',
                'criteria' => ['lifetime_earnings' => 50000],
                'points_reward' => 1000,
                'is_active' => true,
            ],

            // Referral Badges
            [
                'name' => 'Ambassadeur',
                'description' => 'Parrainez 5 utilisateurs',
                'icon' => '👥',
                'criteria' => ['referrals_count' => 5],
                'points_reward' => 200,
                'is_active' => true,
            ],
            [
                'name' => 'Influenceur',
                'description' => 'Parrainez 10 utilisateurs',
                'icon' => '📢',
                'criteria' => ['referrals_count' => 10],
                'points_reward' => 500,
                'is_active' => true,
            ],
            [
                'name' => 'Leader',
                'description' => 'Parrainez 25 utilisateurs',
                'icon' => '🎯',
                'criteria' => ['referrals_count' => 25],
                'points_reward' => 1000,
                'is_active' => true,
            ],

            // Conversion Badges
            [
                'name' => 'Premier Retrait',
                'description' => 'Effectuez votre première conversion',
                'icon' => '🎁',
                'criteria' => ['conversions_completed' => 1],
                'points_reward' => 100,
                'is_active' => true,
            ],
            [
                'name' => 'Habitué',
                'description' => 'Effectuez 5 conversions',
                'icon' => '🎊',
                'criteria' => ['conversions_completed' => 5],
                'points_reward' => 300,
                'is_active' => true,
            ],
            [
                'name' => 'Expert Financier',
                'description' => 'Effectuez 10 conversions',
                'icon' => '💳',
                'criteria' => ['conversions_completed' => 10],
                'points_reward' => 600,
                'is_active' => true,
            ],

            // Loyalty Badges
            [
                'name' => 'Fidèle',
                'description' => 'Actif depuis 30 jours',
                'icon' => '📅',
                'criteria' => ['days_active' => 30],
                'points_reward' => 150,
                'is_active' => true,
            ],
            [
                'name' => 'Vétéran',
                'description' => 'Actif depuis 90 jours',
                'icon' => '🎖️',
                'criteria' => ['days_active' => 90],
                'points_reward' => 400,
                'is_active' => true,
            ],
            [
                'name' => 'Pilier',
                'description' => 'Actif depuis 180 jours',
                'icon' => '🏛️',
                'criteria' => ['days_active' => 180],
                'points_reward' => 800,
                'is_active' => true,
            ],
            [
                'name' => 'Fondateur',
                'description' => 'Actif depuis 365 jours',
                'icon' => '🌟',
                'criteria' => ['days_active' => 365],
                'points_reward' => 1500,
                'is_active' => true,
            ],
        ];

        foreach ($badges as $badge) {
            Badge::updateOrCreate(
                ['name' => $badge['name']],
                $badge
            );
        }

        $this->command->info('✅ ' . count($badges) . ' badges créés avec succès!');
    }
}
