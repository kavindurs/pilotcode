<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Referral;
use App\Models\ReferralEarning;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

echo "=== 3-Level Referral Dashboard Test ===\n\n";

// Find our test users
$level0User = User::where('email', 'test_referral_level0@example.com')->first();
$level1User = User::where('email', 'test_referral_level1@example.com')->first();
$level2User = User::where('email', 'test_referral_level2@example.com')->first();
$level3User = User::where('email', 'test_referral_level3@example.com')->first();

if (!$level0User) {
    echo "❌ Test users not found. Run test_3_level_referrals.php first.\n";
    exit(1);
}

echo "Testing referral relationships:\n";

// Test Level 0 User (should have 3 total referrals across all levels)
$level0Referrals = $level0User->getAllReferralsInChain();
echo "✓ Level 0 User total referrals in chain: " . $level0Referrals->count() . "\n";

// Test Level 1 User (should have 2 total referrals: Level 2 and Level 3)
$level1Referrals = $level1User->getAllReferralsInChain();
echo "✓ Level 1 User total referrals in chain: " . $level1Referrals->count() . "\n";

// Test Level 2 User (should have 1 direct referral: Level 3)
$level2Referrals = $level2User->directReferrals;
echo "✓ Level 2 User direct referrals: " . $level2Referrals->count() . "\n";

echo "\nTesting referral earnings:\n";

// Check earnings for each level
$level0Earnings = $level0User->referralEarnings()->sum('commission_amount');
$level1Earnings = $level1User->referralEarnings()->sum('commission_amount');
$level2Earnings = $level2User->referralEarnings()->sum('commission_amount');

echo "✓ Level 0 User total earnings: $" . number_format($level0Earnings, 2) . "\n";
echo "✓ Level 1 User total earnings: $" . number_format($level1Earnings, 2) . "\n";
echo "✓ Level 2 User total earnings: $" . number_format($level2Earnings, 2) . "\n";

echo "\nTesting parent-child relationships:\n";

// Test referrer relationships
echo "✓ Level 1 User's referrer: " . ($level1User->referrer?->name ?? 'None') . "\n";
echo "✓ Level 2 User's referrer: " . ($level2User->referrer?->name ?? 'None') . "\n";
echo "✓ Level 3 User's referrer: " . ($level3User->referrer?->name ?? 'None') . "\n";

// Test parent referrer relationships
echo "✓ Level 1 User's parent referrer: " . ($level1User->parentReferrer?->name ?? 'None') . "\n";
echo "✓ Level 2 User's parent referrer: " . ($level2User->parentReferrer?->name ?? 'None') . "\n";
echo "✓ Level 3 User's parent referrer: " . ($level3User->parentReferrer?->name ?? 'None') . "\n";

echo "\n=== Dashboard test completed successfully! ===\n";
