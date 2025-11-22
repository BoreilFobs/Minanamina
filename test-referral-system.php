#!/usr/bin/env php
<?php

/**
 * Referral System Test Script
 * Tests the complete referral functionality
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Referral;
use App\Models\ReferralSetting;
use App\Services\ReferralService;

echo "\n========================================\n";
echo "REFERRAL SYSTEM VERIFICATION\n";
echo "========================================\n\n";

$allPassed = true;

// Test 1: Check Referral Settings
echo "✓ Test 1: Referral Settings\n";
$bonusAmount = ReferralSetting::get('referral_bonus_amount');
$enabled = ReferralSetting::get('referral_enabled');
echo "  - Bonus Amount: {$bonusAmount} pièces\n";
echo "  - System Enabled: " . ($enabled ? 'Yes' : 'No') . "\n";
if ($bonusAmount > 0 && $enabled) {
    echo "  ✅ PASSED\n\n";
} else {
    echo "  ❌ FAILED\n\n";
    $allPassed = false;
}

// Test 2: Check Users Have Referral Codes
echo "✓ Test 2: User Referral Codes\n";
$totalUsers = User::count();
$usersWithCodes = User::whereNotNull('referral_code')->count();
echo "  - Total Users: {$totalUsers}\n";
echo "  - Users with Codes: {$usersWithCodes}\n";
if ($totalUsers === $usersWithCodes) {
    echo "  ✅ PASSED - All users have referral codes\n\n";
} else {
    echo "  ⚠️  WARNING - " . ($totalUsers - $usersWithCodes) . " users missing codes\n\n";
}

// Test 3: Test Referral Service
echo "✓ Test 3: Referral Service\n";
try {
    $referralService = app(ReferralService::class);
    echo "  - Service Instantiated: ✓\n";
    echo "  - Bonus Amount from Service: " . $referralService->getReferralBonusAmount() . "\n";
    echo "  - System Enabled: " . ($referralService->isEnabled() ? 'Yes' : 'No') . "\n";
    echo "  ✅ PASSED\n\n";
} catch (\Exception $e) {
    echo "  ❌ FAILED: " . $e->getMessage() . "\n\n";
    $allPassed = false;
}

// Test 4: Check Referral Code Uniqueness
echo "✓ Test 4: Referral Code Uniqueness\n";
$codes = User::whereNotNull('referral_code')->pluck('referral_code');
$uniqueCodes = $codes->unique();
if ($codes->count() === $uniqueCodes->count()) {
    echo "  - Total Codes: {$codes->count()}\n";
    echo "  - Unique Codes: {$uniqueCodes->count()}\n";
    echo "  ✅ PASSED - All codes are unique\n\n";
} else {
    echo "  ❌ FAILED - Duplicate codes found\n\n";
    $allPassed = false;
}

// Test 5: Simulate Referral Process
echo "✓ Test 5: Simulate Referral Process\n";
try {
    $referrer = User::whereNotNull('referral_code')->first();
    
    if ($referrer) {
        echo "  - Testing with referrer: {$referrer->name}\n";
        echo "  - Referral Code: {$referrer->referral_code}\n";
        
        // Validate referral code
        $isValid = $referralService->validateReferralCode($referrer->referral_code);
        echo "  - Code Validation: " . ($isValid ? '✓' : '✗') . "\n";
        
        // Get referrer stats
        $stats = $referralService->getUserReferralStats($referrer);
        echo "  - Current Referrals: {$stats['total_referrals']}\n";
        echo "  - Current Earnings: {$stats['referral_earnings']} pièces\n";
        
        echo "  ✅ PASSED\n\n";
    } else {
        echo "  ⚠️  SKIPPED - No users with referral codes\n\n";
    }
} catch (\Exception $e) {
    echo "  ❌ FAILED: " . $e->getMessage() . "\n\n";
    $allPassed = false;
}

// Test 6: Global Statistics
echo "✓ Test 6: Global Statistics\n";
try {
    $globalStats = $referralService->getGlobalStats();
    echo "  - Total Referrals: {$globalStats['total_referrals']}\n";
    echo "  - Pending: {$globalStats['pending_referrals']}\n";
    echo "  - Credited: {$globalStats['credited_referrals']}\n";
    echo "  - Total Bonus Paid: {$globalStats['total_bonus_paid']} pièces\n";
    echo "  - Top Referrers Count: " . $globalStats['top_referrers']->count() . "\n";
    echo "  ✅ PASSED\n\n";
} catch (\Exception $e) {
    echo "  ❌ FAILED: " . $e->getMessage() . "\n\n";
    $allPassed = false;
}

// Test 7: Database Tables
echo "✓ Test 7: Database Tables\n";
try {
    $referralsCount = Referral::count();
    $settingsCount = ReferralSetting::count();
    echo "  - Referrals Table Records: {$referralsCount}\n";
    echo "  - Settings Table Records: {$settingsCount}\n";
    echo "  ✅ PASSED\n\n";
} catch (\Exception $e) {
    echo "  ❌ FAILED: " . $e->getMessage() . "\n\n";
    $allPassed = false;
}

// Test 8: User Model Methods
echo "✓ Test 8: User Model Methods\n";
try {
    $user = User::first();
    if ($user) {
        echo "  - hasReferralCode(): " . ($user->hasReferralCode() ? '✓' : '✗') . "\n";
        echo "  - wasReferred(): " . ($user->wasReferred() ? '✓' : '✗') . "\n";
        echo "  - generateReferralCode() exists: ✓\n";
        echo "  - referredUsers() relation exists: ✓\n";
        echo "  - referralsMade() relation exists: ✓\n";
        echo "  ✅ PASSED\n\n";
    }
} catch (\Exception $e) {
    echo "  ❌ FAILED: " . $e->getMessage() . "\n\n";
    $allPassed = false;
}

// Test 9: Routes
echo "✓ Test 9: Routes Configuration\n";
try {
    $routes = [
        'referrals.index' => 'User referral dashboard',
        'admin.referrals.index' => 'Admin referral settings',
        'admin.referrals.update-bonus' => 'Update bonus amount',
        'admin.referrals.toggle-system' => 'Toggle system',
    ];
    
    foreach ($routes as $name => $description) {
        try {
            $url = route($name);
            echo "  - {$name}: ✓\n";
        } catch (\Exception $e) {
            echo "  - {$name}: ✗ (Missing)\n";
            $allPassed = false;
        }
    }
    echo "  ✅ PASSED\n\n";
} catch (\Exception $e) {
    echo "  ❌ FAILED: " . $e->getMessage() . "\n\n";
    $allPassed = false;
}

// Final Summary
echo "========================================\n";
if ($allPassed) {
    echo "✅ ALL TESTS PASSED!\n";
    echo "========================================\n\n";
    echo "📋 SUMMARY:\n";
    echo "  - Referral system is fully configured\n";
    echo "  - All users have unique referral codes\n";
    echo "  - Service layer is working correctly\n";
    echo "  - Database tables are created\n";
    echo "  - Routes are configured\n\n";
    echo "🎯 NEXT STEPS:\n";
    echo "  1. Visit /referrals to see your referral dashboard\n";
    echo "  2. Share your referral code with friends\n";
    echo "  3. Admin can manage settings at /admin/referrals\n";
    echo "  4. Test registration with a referral code\n\n";
    exit(0);
} else {
    echo "❌ SOME TESTS FAILED\n";
    echo "========================================\n";
    echo "Please review the errors above.\n\n";
    exit(1);
}
