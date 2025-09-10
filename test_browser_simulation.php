<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Referral;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

echo "=== Simulating Real Browser Registration ===\n\n";

// Clean up test users
User::where('email', 'like', '%browser_test%')->delete();

echo "1. Setting up a referrer (Level 1 user)...\n";

// Create a Level 1 user first
$level1User = User::create([
    'name' => 'Level 1 Browser Test',
    'email' => 'level1_browser_test@example.com',
    'password' => Hash::make('password'),
    'user_type' => 'regular user',
    'is_verified' => true,
    'referral_level' => 1,
    'referred_by' => 44  // Referring to existing user
]);

$level1Referral = $level1User->getOrCreateReferral();
echo "✓ Level 1 user created with referral code: {$level1Referral->referral_code}\n";
echo "✓ User Level: {$level1User->referral_level}\n";

echo "\n2. Simulating new user registration via browser with referral link...\n";

// Create a request object similar to what would come from the browser
$requestData = [
    'name' => 'New Browser Test User',
    'email' => 'new_browser_test@example.com',
    'password' => 'password',
    'password_confirmation' => 'password',
    'user_type' => 'regular user',
    'ref' => $level1Referral->referral_code
];

echo "Request data includes ref: {$requestData['ref']}\n";

// Step 1: Create the user (as AuthController does)
$user = User::create([
    'name' => $requestData['name'],
    'email' => $requestData['email'],
    'password' => Hash::make($requestData['password']),
    'user_type' => $requestData['user_type'],
    'is_verified' => false,
    'referred_by' => null
]);

echo "✓ User created (ID: {$user->id}) with initial referral_level: " . ($user->referral_level ?? 'NULL') . "\n";

// Step 2: Process referral (as AuthController does)
echo "Processing referral...\n";

if (isset($requestData['ref']) && !empty($requestData['ref'])) {
    echo "Looking for referrer with code: {$requestData['ref']}\n";

    // Find the referrer user by referral code
    $referrer = User::whereHas('referral', function($query) use ($requestData) {
        $query->where('referral_code', $requestData['ref']);
    })->first();

    if ($referrer) {
        echo "✓ Found referrer: {$referrer->name} (ID: {$referrer->id}, Level: {$referrer->referral_level})\n";

        // Update the user's referred_by to be the referrer's ID
        $user->referred_by = $referrer->id;
        echo "✓ Set referred_by to: {$user->referred_by}\n";

        echo "Before calculateReferralLevel: Level = " . ($user->referral_level ?? 'NULL') . "\n";

        // Calculate and set referral level
        $user->calculateReferralLevel($referrer->id);

        echo "After calculateReferralLevel: Level = {$user->referral_level}\n";
        echo "Expected level: " . (($referrer->referral_level ?? 0) + 1) . "\n";

        $user->save();
        echo "✓ User saved\n";

        if ($user->referral_level == (($referrer->referral_level ?? 0) + 1)) {
            echo "✅ CORRECT: User has proper referral level!\n";
        } else {
            echo "❌ WRONG: User should be level " . (($referrer->referral_level ?? 0) + 1) . " but is level {$user->referral_level}\n";
        }
    } else {
        echo "❌ Referrer not found for code: {$requestData['ref']}\n";
    }
}

echo "\n=== Final Status ===\n";
echo "Referrer: Level {$level1User->referral_level}\n";
echo "New User: Level " . ($user->referral_level ?? 'NULL') . " (should be " . (($level1User->referral_level ?? 0) + 1) . ")\n";

echo "\n=== Test Complete ===\n";
