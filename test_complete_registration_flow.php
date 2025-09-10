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

echo "=== Complete Registration Flow Test ===\n\n";

// Clean up test users
User::whereIn('email', [
    'master_referrer@test.com',
    'sub_referrer@test.com',
    'new_customer@test.com'
])->delete();

echo "1. Creating master referrer (Level 0)...\n";
$masterReferrer = User::create([
    'name' => 'Master Referrer',
    'email' => 'master_referrer@test.com',
    'password' => Hash::make('password'),
    'user_type' => 'regular user',
    'is_verified' => true,
    'referral_level' => 0
]);
$masterReferral = $masterReferrer->getOrCreateReferral();
echo "✓ Master referrer created with code: {$masterReferral->referral_code}\n";

echo "\n2. Simulating sub-referrer registration using master's link...\n";
$subReferrer = User::create([
    'name' => 'Sub Referrer',
    'email' => 'sub_referrer@test.com',
    'password' => Hash::make('password'),
    'user_type' => 'regular user',
    'is_verified' => true,
]);

// Simulate AuthController logic for sub-referrer
$referrer = User::whereHas('referral', function($query) use ($masterReferral) {
    $query->where('referral_code', $masterReferral->referral_code);
})->first();

if ($referrer) {
    $subReferrer->referred_by = $referrer->id;
    $subReferrer->calculateReferralLevel($referrer->id);
    $subReferrer->save();
    echo "✓ Sub-referrer registered: Level {$subReferrer->referral_level} (expected: 1)\n";
} else {
    echo "❌ Referrer not found!\n";
}

$subReferral = $subReferrer->getOrCreateReferral();
echo "✓ Sub-referrer referral code: {$subReferral->referral_code}\n";

echo "\n3. Simulating new customer registration using sub-referrer's link...\n";
$newCustomer = User::create([
    'name' => 'New Customer',
    'email' => 'new_customer@test.com',
    'password' => Hash::make('password'),
    'user_type' => 'regular user',
    'is_verified' => true,
]);

// Simulate AuthController logic for new customer
$referrer = User::whereHas('referral', function($query) use ($subReferral) {
    $query->where('referral_code', $subReferral->referral_code);
})->first();

if ($referrer) {
    $newCustomer->referred_by = $referrer->id;
    $newCustomer->calculateReferralLevel($referrer->id);
    $newCustomer->save();
    echo "✓ New customer registered: Level {$newCustomer->referral_level} (expected: 2)\n";
} else {
    echo "❌ Sub-referrer not found!\n";
}

echo "\n=== Final Results ===\n";
echo "Master Referrer (ID: {$masterReferrer->id}): Level {$masterReferrer->referral_level}\n";
echo "Sub Referrer (ID: {$subReferrer->id}): Level {$subReferrer->referral_level}\n";
echo "New Customer (ID: {$newCustomer->id}): Level {$newCustomer->referral_level}\n";

// Validate the chain
if ($masterReferrer->referral_level == 0 &&
    $subReferrer->referral_level == 1 &&
    $newCustomer->referral_level == 2) {
    echo "\n✅ SUCCESS: Complete 3-level referral chain working correctly!\n";
} else {
    echo "\n❌ FAILURE: Referral levels are incorrect!\n";
}

echo "\n=== Test Complete ===\n";
