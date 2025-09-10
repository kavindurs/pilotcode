<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Referral;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

echo "=== Testing Referral Level Calculation Fix ===\n\n";

// Clean up existing test users
echo "Cleaning up existing test data...\n";
User::where('email', 'like', '%test_level_%')->delete();

// Create Level 0 User (original referrer)
echo "1. Creating Level 0 User (original referrer)...\n";
$level0User = User::create([
    'name' => 'Level 0 Test User',
    'email' => 'test_level_0@example.com',
    'password' => Hash::make('password'),
    'user_type' => 'regular user',
    'is_verified' => true,
    'referral_level' => 0
]);

$level0Referral = $level0User->getOrCreateReferral();
echo "✓ Level 0 User created (ID: {$level0User->id}) - Referral Code: {$level0Referral->referral_code}\n";

// Test Level 1 Registration (simulate registration with referral code)
echo "\n2. Testing Level 1 registration using referral code...\n";
$level1User = User::create([
    'name' => 'Level 1 Test User',
    'email' => 'test_level_1@example.com',
    'password' => Hash::make('password'),
    'user_type' => 'regular user',
    'is_verified' => true,
    'referred_by' => $level0Referral->referral_code // This mimics what would come from the form
]);

// Simulate the referral processing from AuthController
$referrer = User::whereHas('referral', function($query) use ($level0Referral) {
    $query->where('referral_code', $level0Referral->referral_code);
})->first();

if ($referrer) {
    $level1User->referred_by = $referrer->id;
    $level1User->calculateReferralLevel($referrer->id);
    $level1User->save();
    echo "✓ Level 1 User created (ID: {$level1User->id}) - Referral Level: {$level1User->referral_level}\n";
    echo "  Referred by: {$level1User->referred_by} | Parent Referrer: {$level1User->parent_referrer_id}\n";
    echo "  Path: {$level1User->referral_path}\n";
} else {
    echo "❌ Failed to find referrer by code: {$level0Referral->referral_code}\n";
}

// Test Level 2 Registration
echo "\n3. Testing Level 2 registration...\n";
$level1Referral = $level1User->getOrCreateReferral();
echo "Level 1 User's referral code: {$level1Referral->referral_code}\n";

$level2User = User::create([
    'name' => 'Level 2 Test User',
    'email' => 'test_level_2@example.com',
    'password' => Hash::make('password'),
    'user_type' => 'regular user',
    'is_verified' => true,
    'referred_by' => $level1Referral->referral_code
]);

// Simulate referral processing
$level2Referrer = User::whereHas('referral', function($query) use ($level1Referral) {
    $query->where('referral_code', $level1Referral->referral_code);
})->first();

if ($level2Referrer) {
    $level2User->referred_by = $level2Referrer->id;
    $level2User->calculateReferralLevel($level2Referrer->id);
    $level2User->save();
    echo "✓ Level 2 User created (ID: {$level2User->id}) - Referral Level: {$level2User->referral_level}\n";
    echo "  Referred by: {$level2User->referred_by} | Parent Referrer: {$level2User->parent_referrer_id}\n";
    echo "  Path: {$level2User->referral_path}\n";
}

// Test Level 3 Registration
echo "\n4. Testing Level 3 registration...\n";
$level2Referral = $level2User->getOrCreateReferral();
echo "Level 2 User's referral code: {$level2Referral->referral_code}\n";

$level3User = User::create([
    'name' => 'Level 3 Test User',
    'email' => 'test_level_3@example.com',
    'password' => Hash::make('password'),
    'user_type' => 'regular user',
    'is_verified' => true,
    'referred_by' => $level2Referral->referral_code
]);

// Simulate referral processing
$level3Referrer = User::whereHas('referral', function($query) use ($level2Referral) {
    $query->where('referral_code', $level2Referral->referral_code);
})->first();

if ($level3Referrer) {
    $level3User->referred_by = $level3Referrer->id;
    $level3User->calculateReferralLevel($level3Referrer->id);
    $level3User->save();
    echo "✓ Level 3 User created (ID: {$level3User->id}) - Referral Level: {$level3User->referral_level}\n";
    echo "  Referred by: {$level3User->referred_by} | Parent Referrer: {$level3User->parent_referrer_id}\n";
    echo "  Path: {$level3User->referral_path}\n";
}

echo "\n=== Summary ===\n";
echo "Level 0 User: Referral Level = {$level0User->referral_level}\n";
echo "Level 1 User: Referral Level = {$level1User->referral_level}\n";
echo "Level 2 User: Referral Level = {$level2User->referral_level}\n";
echo "Level 3 User: Referral Level = {$level3User->referral_level}\n";

echo "\n✅ Referral level calculation test completed!\n";
