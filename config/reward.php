<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Conversion Rate
    |--------------------------------------------------------------------------
    |
    | The rate at which pieces are converted to cash (local currency).
    | Example: 0.001 means 1000 pieces = 1 unit of currency
    |
    */
    'conversion_rate' => env('REWARD_CONVERSION_RATE', 0.001),

    /*
    |--------------------------------------------------------------------------
    | Minimum Conversion Amount
    |--------------------------------------------------------------------------
    |
    | The minimum number of pieces required to request a conversion.
    |
    */
    'minimum_conversion' => env('REWARD_MINIMUM_CONVERSION', 10000),

    /*
    |--------------------------------------------------------------------------
    | Consecutive Completion Bonus
    |--------------------------------------------------------------------------
    |
    | Settings for consecutive completion bonuses
    |
    */
    'consecutive_bonus' => [
        'enabled' => true,
        'threshold' => 5, // Number of consecutive completions needed
        'multiplier' => 1.1, // 10% bonus
    ],

    /*
    |--------------------------------------------------------------------------
    | Loyalty Bonus
    |--------------------------------------------------------------------------
    |
    | Settings for loyalty bonuses (daily active users)
    |
    */
    'loyalty_bonus' => [
        'enabled' => true,
        'days_threshold' => 30, // Days of consecutive activity
        'percentage' => 15, // 15% bonus
    ],

    /*
    |--------------------------------------------------------------------------
    | Referral Rewards
    |--------------------------------------------------------------------------
    |
    | Settings for referral program rewards
    |
    */
    'referral_rewards' => [
        'signup_bonus' => 500, // Pieces for referrer when someone signs up
        'completion_percentage' => 10, // Percentage of referred user's earnings
        'max_tier_levels' => 3, // Maximum referral tiers
    ],

    /*
    |--------------------------------------------------------------------------
    | Fraud Detection
    |--------------------------------------------------------------------------
    |
    | Settings for fraud detection and prevention
    |
    */
    'fraud_detection' => [
        'min_completion_time_seconds' => 30,
        'suspicious_completion_time_seconds' => 15,
        'max_daily_participations' => 20,
        'max_daily_earnings_multiplier' => 10,
    ],

    /*
    |--------------------------------------------------------------------------
    | Badge Rewards
    |--------------------------------------------------------------------------
    |
    | Pieces awarded for earning badges
    |
    */
    'badge_rewards' => [
        'bronze' => 100,
        'silver' => 250,
        'gold' => 500,
        'platinum' => 1000,
        'diamond' => 2500,
    ],

    /*
    |--------------------------------------------------------------------------
    | Payment Methods
    |--------------------------------------------------------------------------
    |
    | Available payment methods for conversions
    |
    */
    'payment_methods' => [
        'orange_money' => [
            'name' => 'Orange Money',
            'enabled' => true,
            'min_amount' => 500, // Minimum cash amount
            'max_amount' => 100000,
            'fee_percentage' => 2, // 2% fee
        ],
        'mtn_mobile_money' => [
            'name' => 'MTN Mobile Money',
            'enabled' => true,
            'min_amount' => 500,
            'max_amount' => 100000,
            'fee_percentage' => 2,
        ],
        'wave' => [
            'name' => 'Wave',
            'enabled' => true,
            'min_amount' => 100,
            'max_amount' => 150000,
            'fee_percentage' => 1,
        ],
        'bank_transfer' => [
            'name' => 'Virement Bancaire',
            'enabled' => true,
            'min_amount' => 5000,
            'max_amount' => 500000,
            'fee_percentage' => 0,
        ],
        'paypal' => [
            'name' => 'PayPal',
            'enabled' => false,
            'min_amount' => 1000,
            'max_amount' => 200000,
            'fee_percentage' => 3.4,
        ],
    ],
];
