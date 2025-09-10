<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Referral;
use App\Models\ReferralEarning;
use App\Models\ReferralRate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

echo "=== 3-Level Referral System Test ===\n\n";

// Check referral rates
echo "Checking referral rates:\n";
$rates = DB::table('referral_rate')->get();
foreach ($rates as $rate) {
    echo "Level {$rate->id}: {$rate->rate}%\n";
}
echo "\n";

// Clean up existing test users
echo "Cleaning up existing test data...\n";
User::where('email', 'like', '%test_referral_%')->delete();
ReferralEarning::where('referrer_id', '>', 1000)->delete();

// Create test users
echo "Creating test user hierarchy:\n";

// Level 0 (Original referrer)
$level0User = User::create([
    'name' => 'Level 0 User',
    'email' => 'test_referral_level0@example.com',
    'password' => Hash::make('password'),
    'user_type' => 'regular user',
    'is_verified' => true,
    'referral_level' => 0
]);

$level0Referral = $level0User->getOrCreateReferral();
echo "✓ Level 0 User (ID: {$level0User->id}) - Referral Code: {$level0Referral->referral_code}\n";

// Level 1 (Direct referral)
$level1User = User::create([
    'name' => 'Level 1 User',
    'email' => 'test_referral_level1@example.com',
    'password' => Hash::make('password'),
    'user_type' => 'regular user',
    'is_verified' => true,
    'referred_by' => $level0User->id
]);

$level1User->calculateReferralLevel($level0User->id);
$level1User->save();

echo "✓ Level 1 User (ID: {$level1User->id}) - Referral Level: {$level1User->referral_level}\n";
echo "  Parent Referrer: {$level1User->parent_referrer_id}\n";
echo "  Referral Path: {$level1User->referral_path}\n";

// Level 2 (Referral of Level 1)
$level2User = User::create([
    'name' => 'Level 2 User',
    'email' => 'test_referral_level2@example.com',
    'password' => Hash::make('password'),
    'user_type' => 'regular user',
    'is_verified' => true,
    'referred_by' => $level1User->id
]);

$level2User->calculateReferralLevel($level1User->id);
$level2User->save();

echo "✓ Level 2 User (ID: {$level2User->id}) - Referral Level: {$level2User->referral_level}\n";
echo "  Parent Referrer: {$level2User->parent_referrer_id}\n";
echo "  Referral Path: {$level2User->referral_path}\n";

// Level 3 (Referral of Level 2)
$level3User = User::create([
    'name' => 'Level 3 User',
    'email' => 'test_referral_level3@example.com',
    'password' => Hash::make('password'),
    'user_type' => 'regular user',
    'is_verified' => true,
    'referred_by' => $level2User->id
]);

$level3User->calculateReferralLevel($level2User->id);
$level3User->save();

echo "✓ Level 3 User (ID: {$level3User->id}) - Referral Level: {$level3User->referral_level}\n";
echo "  Parent Referrer: {$level3User->parent_referrer_id}\n";
echo "  Referral Path: {$level3User->referral_path}\n\n";

// Test 3-level referral earnings
echo "Testing 3-level referral earnings for \$100 purchase by Level 3 user:\n";

$earnings = \App\Http\Controllers\ReferralController::process3LevelReferralEarnings(
    $level3User,
    null, // No property ID for test
    1,    // Dummy plan ID
    100   // $100 purchase
);

echo "Generated " . count($earnings) . " referral earnings:\n";
foreach ($earnings as $earning) {
    $referrer = User::find($earning->referrer_id);
    $rate = $earning->commission_rate;
    $amount = $earning->commission_amount;
    echo "  - {$referrer->name} (Level {$referrer->referral_level} referrer): {$rate}% of \$100 = \${$amount}\n";
}

echo "\n=== Test completed successfully! ===\n";
