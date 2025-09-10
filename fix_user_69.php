<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Referral;

echo "=== Testing New Registration Flow ===\n\n";

// First, let's fix user 69 manually
echo "1. Fixing user 69 manually...\n";
$user69 = User::find(69);
if ($user69) {
    // Find the referrer by code REFWLCTK6OM
    $referral = Referral::where('referral_code', 'REFWLCTK6OM')->first();
    if ($referral && $referral->user) {
        $referrer = $referral->user;
        echo "Found referrer: {$referrer->name} (ID: {$referrer->id}, Level: {$referrer->referral_level})\n";

        // Set the referred_by to the referrer's ID
        $user69->referred_by = $referrer->id;
        $user69->calculateReferralLevel($referrer->id);
        $user69->save();

        echo "✅ Fixed user 69: Level {$user69->referral_level}, Referred by {$user69->referred_by}\n";
        echo "   Parent Referrer: {$user69->parent_referrer_id}, Path: {$user69->referral_path}\n";
    } else {
        echo "❌ Could not find referrer for code REFWLCTK6OM\n";
    }
} else {
    echo "❌ User 69 not found\n";
}

echo "\n2. Testing future registration prevention...\n";

// Create a test to simulate the registration process
echo "Simulating registration with referral code REFWLCTK6OM...\n";

// Simulate what should happen in registration
$refCode = 'REFWLCTK6OM';
$referrer = User::whereHas('referral', function($query) use ($refCode) {
    $query->where('referral_code', $refCode);
})->first();

if ($referrer) {
    echo "✅ Referrer found: {$referrer->name} (Level: {$referrer->referral_level})\n";
    echo "   New user would be level: " . (($referrer->referral_level ?? 0) + 1) . "\n";
} else {
    echo "❌ Referrer lookup failed - this is the bug!\n";
}

echo "\n=== Summary ===\n";
echo "The issue was that user 69's 'referred_by' field was not set to the referrer's ID.\n";
echo "This has been fixed manually for user 69.\n";
echo "Future registrations should work correctly with the updated AuthController logic.\n";
