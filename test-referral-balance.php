#!/usr/bin/env php
<?php

/**
 * Test Referral Bonus Balance Updates
 * Simulates registration with referral code and verifies database updates
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Referral;
use App\Models\UserPiecesTransaction;
use App\Services\ReferralService;
use Illuminate\Support\Facades\Hash;

echo "\n";
echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║     REFERRAL BONUS - DATABASE BALANCE VERIFICATION           ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n";
echo "\n";

// Get referral service
$referralService = app(ReferralService::class);
$referrerBonus = $referralService->getReferralBonusAmount();
$newUserBonus = $referralService->getNewUserBonusAmount();

echo "📋 CONFIGURED BONUSES\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "  Referrer will receive: {$referrerBonus} pieces\n";
echo "  New user will receive: {$newUserBonus} pieces\n";
echo "\n";

// Get an existing user to be the referrer
$referrer = User::where('role', 'superadmin')->first();

if (!$referrer) {
    echo "❌ No users found to test with\n";
    exit(1);
}

echo "👤 REFERRER (BEFORE)\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "  Name: {$referrer->name}\n";
echo "  Referral Code: {$referrer->referral_code}\n";
echo "  Pieces Balance: " . number_format($referrer->pieces_balance, 2) . " pieces\n";
echo "  Total Referrals: {$referrer->total_referrals}\n";
echo "  Referral Earnings: " . number_format($referrer->referral_earnings, 2) . " pieces\n";
echo "\n";

// Store before values
$referrerBalanceBefore = $referrer->pieces_balance;
$referrerReferralsBefore = $referrer->total_referrals;
$referrerEarningsBefore = $referrer->referral_earnings;

// Create a test new user
echo "🆕 CREATING NEW TEST USER...\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

$testPhone = '+221' . rand(700000000, 799999999);

$newUser = User::create([
    'name' => 'Test Referral User ' . rand(1000, 9999),
    'phone' => $testPhone,
    'password' => Hash::make('password'),
    'phone_verified_at' => now(),
    'status' => 'active',
    'pieces_balance' => 0,
    'role' => 'user',
]);

// Generate referral code for new user
$newUser->generateReferralCode();
$newUser->refresh();

echo "  ✓ Created user: {$newUser->name}\n";
echo "  ✓ Phone: {$newUser->phone}\n";
echo "  ✓ Initial Balance: " . number_format($newUser->pieces_balance, 2) . " pieces\n";
echo "\n";

// Process the referral
echo "⚡ PROCESSING REFERRAL...\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

$result = $referralService->processReferral($newUser, $referrer->referral_code);

if ($result['success']) {
    echo "  ✅ {$result['message']}\n";
} else {
    echo "  ❌ {$result['message']}\n";
    exit(1);
}
echo "\n";

// Refresh both users from database
$referrer->refresh();
$newUser->refresh();

echo "👤 REFERRER (AFTER)\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "  Pieces Balance: " . number_format($referrer->pieces_balance, 2) . " pieces\n";
echo "  Change: +" . number_format($referrer->pieces_balance - $referrerBalanceBefore, 2) . " pieces\n";
echo "  Total Referrals: {$referrer->total_referrals}\n";
echo "  Change: +" . ($referrer->total_referrals - $referrerReferralsBefore) . "\n";
echo "  Referral Earnings: " . number_format($referrer->referral_earnings, 2) . " pieces\n";
echo "  Change: +" . number_format($referrer->referral_earnings - $referrerEarningsBefore, 2) . " pieces\n";
echo "\n";

echo "🆕 NEW USER (AFTER)\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "  Pieces Balance: " . number_format($newUser->pieces_balance, 2) . " pieces\n";
echo "  Referred By: User ID #{$newUser->referred_by}\n";
echo "\n";

// Verify transactions were created
echo "💳 TRANSACTION RECORDS\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

$referrerTransaction = UserPiecesTransaction::where('user_id', $referrer->id)
    ->where('type', 'referral_bonus')
    ->latest()
    ->first();

if ($referrerTransaction) {
    echo "  ✓ Referrer transaction found:\n";
    echo "    Type: {$referrerTransaction->type}\n";
    echo "    Amount: {$referrerTransaction->amount} pieces\n";
    echo "    Balance Before: {$referrerTransaction->balance_before}\n";
    echo "    Balance After: {$referrerTransaction->balance_after}\n";
    echo "    Description: {$referrerTransaction->description}\n";
} else {
    echo "  ❌ No referrer transaction found\n";
}

echo "\n";

$newUserTransaction = UserPiecesTransaction::where('user_id', $newUser->id)
    ->where('type', 'welcome_bonus')
    ->latest()
    ->first();

if ($newUserTransaction) {
    echo "  ✓ New user transaction found:\n";
    echo "    Type: {$newUserTransaction->type}\n";
    echo "    Amount: {$newUserTransaction->amount} pieces\n";
    echo "    Balance Before: {$newUserTransaction->balance_before}\n";
    echo "    Balance After: {$newUserTransaction->balance_after}\n";
    echo "    Description: {$newUserTransaction->description}\n";
} else {
    echo "  ❌ No new user transaction found\n";
}

echo "\n";

// Verify referral record
echo "📝 REFERRAL RECORD\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

$referralRecord = Referral::where('referrer_id', $referrer->id)
    ->where('referred_id', $newUser->id)
    ->latest()
    ->first();

if ($referralRecord) {
    echo "  ✓ Referral record created:\n";
    echo "    Referrer: User #{$referralRecord->referrer_id}\n";
    echo "    Referred: User #{$referralRecord->referred_id}\n";
    echo "    Code Used: {$referralRecord->referral_code}\n";
    echo "    Bonus Amount: {$referralRecord->bonus_amount} pieces\n";
    echo "    Status: {$referralRecord->status}\n";
    echo "    Credited At: {$referralRecord->credited_at}\n";
} else {
    echo "  ❌ No referral record found\n";
}

echo "\n";

// Validation
echo "✅ VALIDATION\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

$allGood = true;

// Check referrer balance increased by correct amount
if (abs($referrer->pieces_balance - ($referrerBalanceBefore + $referrerBonus)) < 0.01) {
    echo "  ✓ Referrer balance increased correctly (+{$referrerBonus})\n";
} else {
    echo "  ❌ Referrer balance incorrect\n";
    $allGood = false;
}

// Check new user balance is correct
if (abs($newUser->pieces_balance - $newUserBonus) < 0.01) {
    echo "  ✓ New user balance is correct ({$newUserBonus})\n";
} else {
    echo "  ❌ New user balance incorrect\n";
    $allGood = false;
}

// Check referral count increased
if ($referrer->total_referrals == $referrerReferralsBefore + 1) {
    echo "  ✓ Referral count increased\n";
} else {
    echo "  ❌ Referral count incorrect\n";
    $allGood = false;
}

// Check referral earnings increased
if (abs($referrer->referral_earnings - ($referrerEarningsBefore + $referrerBonus)) < 0.01) {
    echo "  ✓ Referral earnings increased correctly\n";
} else {
    echo "  ❌ Referral earnings incorrect\n";
    $allGood = false;
}

// Check transactions exist
if ($referrerTransaction && $newUserTransaction) {
    echo "  ✓ Both transaction records created\n";
} else {
    echo "  ❌ Missing transaction records\n";
    $allGood = false;
}

// Check referral record
if ($referralRecord && $referralRecord->status === 'credited') {
    echo "  ✓ Referral record created and credited\n";
} else {
    echo "  ❌ Referral record issue\n";
    $allGood = false;
}

echo "\n";

// Cleanup
echo "🧹 CLEANUP\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "  Deleting test user... ";
$newUser->delete();
echo "✓\n";

// Restore referrer stats
$referrer->update([
    'pieces_balance' => $referrerBalanceBefore,
    'total_referrals' => $referrerReferralsBefore,
    'referral_earnings' => $referrerEarningsBefore,
]);
echo "  Restoring referrer stats... ✓\n";

// Delete test transactions and referral
UserPiecesTransaction::where('user_id', $referrer->id)
    ->where('description', 'like', '%Test Referral User%')
    ->delete();
    
Referral::where('referrer_id', $referrer->id)
    ->where('referral_code', $referrer->referral_code)
    ->where('referred_id', $newUser->id)
    ->delete();

echo "  Cleaning up test records... ✓\n";
echo "\n";

if ($allGood) {
    echo "╔══════════════════════════════════════════════════════════════╗\n";
    echo "║            ✅ ALL VALIDATIONS PASSED! ✅                      ║\n";
    echo "║                                                              ║\n";
    echo "║  Referral bonuses are correctly added to database:          ║\n";
    echo "║  • Referrer pieces_balance updated ✓                        ║\n";
    echo "║  • New user pieces_balance updated ✓                        ║\n";
    echo "║  • Referral earnings tracked ✓                              ║\n";
    echo "║  • Transaction records created ✓                            ║\n";
    echo "║  • Referral records created ✓                               ║\n";
    echo "║                                                              ║\n";
    echo "║  The system is fully functional! 🎉                          ║\n";
    echo "╚══════════════════════════════════════════════════════════════╝\n";
    exit(0);
} else {
    echo "╔══════════════════════════════════════════════════════════════╗\n";
    echo "║                  ❌ VALIDATION FAILED ❌                      ║\n";
    echo "╚══════════════════════════════════════════════════════════════╝\n";
    exit(1);
}
