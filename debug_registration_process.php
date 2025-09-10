<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Referral;
use Illuminate\Support\Facades\Hash;

echo "=== Debug Registration Process ===\n\n";

// Clean up any previous test users
User::where('email', 'like', '%debug_test%')->delete();

echo "1. Creating referrer chain...\n";

// Create Level 0 user (original referrer)
$level0User = User::create([
    'name' => 'Level 0 Debug Test',
    'email' => 'level0_debug_test@example.com',
    'password' => Hash::make('password'),
    'user_type' => 'regular user',
    'is_verified' => true,
    'referral_level' => 0
]);
$level0Referral = $level0User->getOrCreateReferral();
echo "✓ Level 0 user created (ID: {$level0User->id}) with referral code: {$level0Referral->referral_code}\n";

// Create Level 1 user using Level 0's referral code
echo "\n2. Registering Level 1 user...\n";
$level1User = User::create([
    'name' => 'Level 1 Debug Test',
    'email' => 'level1_debug_test@example.com',
    'password' => Hash::make('password'),
    'user_type' => 'regular user',
    'is_verified' => true,
]);

// Simulate AuthController referral processing for Level 1
echo "Simulating AuthController processing for Level 1...\n";
echo "Looking for referrer with code: {$level0Referral->referral_code}\n";

$referrer = User::whereHas('referral', function($query) use ($level0Referral) {
    $query->where('referral_code', $level0Referral->referral_code);
})->first();

if ($referrer) {
    echo "✓ Found referrer: {$referrer->name} (ID: {$referrer->id}, Level: {$referrer->referral_level})\n";

    // Set referred_by
    $level1User->referred_by = $referrer->id;
    echo "✓ Set referred_by to: {$level1User->referred_by}\n";

    // Calculate referral level
    echo "Calling calculateReferralLevel({$referrer->id})...\n";
    $level1User->calculateReferralLevel($referrer->id);
    echo "✓ After calculation, referral_level is: {$level1User->referral_level}\n";

    $level1User->save();
    echo "✓ User saved\n";
} else {
    echo "❌ Referrer not found!\n";
}

$level1Referral = $level1User->getOrCreateReferral();
echo "Level 1 user final state: Level {$level1User->referral_level}, Referral code: {$level1Referral->referral_code}\n";

// Now test Level 2 user registration
echo "\n3. Registering Level 2 user using Level 1's referral...\n";
$level2User = User::create([
    'name' => 'Level 2 Debug Test',
    'email' => 'level2_debug_test@example.com',
    'password' => Hash::make('password'),
    'user_type' => 'regular user',
    'is_verified' => true,
]);

echo "Simulating AuthController processing for Level 2...\n";
echo "Looking for referrer with code: {$level1Referral->referral_code}\n";

$referrer = User::whereHas('referral', function($query) use ($level1Referral) {
    $query->where('referral_code', $level1Referral->referral_code);
})->first();

if ($referrer) {
    echo "✓ Found referrer: {$referrer->name} (ID: {$referrer->id}, Level: {$referrer->referral_level})\n";

    // Set referred_by
    $level2User->referred_by = $referrer->id;
    echo "✓ Set referred_by to: {$level2User->referred_by}\n";

    // Calculate referral level
    echo "Calling calculateReferralLevel({$referrer->id})...\n";
    $level2User->calculateReferralLevel($referrer->id);
    echo "✓ After calculation, referral_level is: {$level2User->referral_level}\n";

    $level2User->save();
    echo "✓ User saved\n";
} else {
    echo "❌ Referrer not found!\n";
}

echo "\n=== Final Results ===\n";
echo "Level 0 User: Level {$level0User->referral_level}\n";
echo "Level 1 User: Level {$level1User->referral_level} (should be 1)\n";
echo "Level 2 User: Level {$level2User->referral_level} (should be 2)\n";

if ($level1User->referral_level == 1 && $level2User->referral_level == 2) {
    echo "\n✅ SUCCESS: Registration process working correctly!\n";
} else {
    echo "\n❌ PROBLEM: Registration not assigning correct levels!\n";

    // Debug the calculateReferralLevel method
    echo "\nDEBUG: Let me check the calculateReferralLevel method...\n";

    // Test the method directly
    $testUser = new User();
    $testUser->referred_by = $level1User->id;
    echo "Test user referred_by: {$testUser->referred_by}\n";

    $testUser->calculateReferralLevel($level1User->id);
    echo "Test user calculated level: {$testUser->referral_level}\n";
}

echo "\n=== Debug Complete ===\n";
