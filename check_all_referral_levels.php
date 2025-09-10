<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

echo "=== Checking All Users for Referral Level Issues ===\n\n";

// Get all users with referrals
$usersWithReferrals = User::with(['referrer'])
    ->whereNotNull('referred_by')
    ->get();

echo "Total users with referrals: " . $usersWithReferrals->count() . "\n\n";

$problemUsers = [];

foreach ($usersWithReferrals as $user) {
    if (!$user->referrer) {
        echo "❌ User {$user->id} ({$user->name}) has referred_by={$user->referred_by} but referrer not found\n";
        $problemUsers[] = $user;
        continue;
    }

    $expectedLevel = ($user->referrer->referral_level ?? 0) + 1;
    $actualLevel = $user->referral_level ?? 0;

    if ($actualLevel != $expectedLevel) {
        echo "❌ User {$user->id} ({$user->name}):\n";
        echo "   Current Level: {$actualLevel}\n";
        echo "   Expected Level: {$expectedLevel}\n";
        echo "   Referrer: {$user->referrer->name} (Level {$user->referrer->referral_level})\n";
        echo "   Created: {$user->created_at}\n";
        $problemUsers[] = $user;
    }
}

if (empty($problemUsers)) {
    echo "✅ All users have correct referral levels!\n";
} else {
    echo "\n" . count($problemUsers) . " users found with incorrect referral levels.\n\n";

    echo "Would you like me to fix these users? Here's what I would do:\n\n";

    foreach ($problemUsers as $user) {
        if ($user->referrer) {
            $correctLevel = ($user->referrer->referral_level ?? 0) + 1;
            echo "Fix User {$user->id}: Set referral_level to {$correctLevel}\n";
        } else {
            echo "Fix User {$user->id}: Remove referred_by (orphaned referral)\n";
        }
    }
}

echo "\n=== Check Complete ===\n";
