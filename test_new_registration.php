<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Referral;
use Illuminate\Support\Facades\Hash;

echo "=== Testing New User Registration Flow ===\n\n";

// Clean up previous test users
User::where('email', 'test_new_registration@example.com')->delete();

// First, let's create a proper referral chain
echo "1. Setting up test referral chain...\n";

// Level 0 user
$level0User = User::create([
    'name' => 'Level 0 Original',
    'email' => 'level0_test@example.com',
    'password' => Hash::make('password'),
    'user_type' => 'regular user',
    'is_verified' => true,
    'referral_level' => 0
]);
$level0Referral = $level0User->getOrCreateReferral();
echo "✓ Level 0 user created with referral code: {$level0Referral->referral_code}\n";

// Level 1 user
$level1User = User::create([
    'name' => 'Level 1 User',
    'email' => 'level1_test@example.com',
    'password' => Hash::make('password'),
    'user_type' => 'regular user',
    'is_verified' => true,
    'referred_by' => null
]);

// Simulate registration process for Level 1
$referrer = User::whereHas('referral', function($query) use ($level0Referral) {
    $query->where('referral_code', $level0Referral->referral_code);
})->first();

if ($referrer) {
    $level1User->referred_by = $referrer->id;
    $level1User->calculateReferralLevel($referrer->id);
    $level1User->save();
}

$level1Referral = $level1User->getOrCreateReferral();
echo "✓ Level 1 user created - Level: {$level1User->referral_level}, Referral code: {$level1Referral->referral_code}\n";

echo "\n2. Now testing new user registration using Level 1's referral link...\n";

// Simulate new user registration using Level 1's referral code
$newUser = User::create([
    'name' => 'Test New Registration',
    'email' => 'test_new_registration@example.com',
    'password' => Hash::make('password'),
    'user_type' => 'regular user',
    'is_verified' => true,
    'referred_by' => null
]);

echo "Created new user (ID: {$newUser->id})\n";
echo "Using referral code: {$level1Referral->referral_code}\n";

// Simulate the AuthController referral processing
$referrer = User::whereHas('referral', function($query) use ($level1Referral) {
    $query->where('referral_code', $level1Referral->referral_code);
})->first();

echo "Found referrer: " . ($referrer ? $referrer->name . " (Level {$referrer->referral_level})" : "None") . "\n";

if ($referrer) {
    echo "Before calculation:\n";
    echo "  New user level: {$newUser->referral_level}\n";
    echo "  Referrer level: {$referrer->referral_level}\n";

    $newUser->referred_by = $referrer->id;
    $newUser->calculateReferralLevel($referrer->id);
    $newUser->save();

    echo "After calculation:\n";
    echo "  New user level: {$newUser->referral_level}\n";
    echo "  Expected level: " . (($referrer->referral_level ?? 0) + 1) . "\n";

    if ($newUser->referral_level == (($referrer->referral_level ?? 0) + 1)) {
        echo "✅ CORRECT: New user has proper referral level!\n";
    } else {
        echo "❌ WRONG: New user should be level " . (($referrer->referral_level ?? 0) + 1) . " but is level {$newUser->referral_level}\n";
    }
}

echo "\n=== Final Status ===\n";
echo "Level 0 User: Level {$level0User->referral_level}\n";
echo "Level 1 User: Level {$level1User->referral_level}\n";
echo "New User: Level {$newUser->referral_level} (should be 2)\n";

echo "\n=== Test Complete ===\n";
