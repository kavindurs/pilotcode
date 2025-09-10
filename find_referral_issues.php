<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

echo "=== Finding Users with Potential Referral Level Issues ===\n\n";

// Find all users who have been referred but have incorrect levels
$usersWithIssues = User::with(['referrer'])
    ->whereNotNull('referred_by')
    ->get()
    ->filter(function($user) {
        if (!$user->referrer) return false;

        $expectedLevel = ($user->referrer->referral_level ?? 0) + 1;
        return $user->referral_level != $expectedLevel;
    });

echo "Users with referral level issues:\n\n";

foreach ($usersWithIssues as $user) {
    echo "User ID: {$user->id}\n";
    echo "Name: {$user->name}\n";
    echo "Current Level: " . ($user->referral_level ?? 'NULL') . "\n";
    echo "Referrer Level: " . ($user->referrer->referral_level ?? 'NULL') . "\n";
    echo "Expected Level: " . (($user->referrer->referral_level ?? 0) + 1) . "\n";
    echo "Created: {$user->created_at}\n";
    echo "---\n";
}

echo "Total users with issues: " . $usersWithIssues->count() . "\n\n";

// Also check users who are level 1 but have referrers
$suspiciousLevel1Users = User::with(['referrer'])
    ->whereNotNull('referred_by')
    ->where('referral_level', 1)
    ->get()
    ->filter(function($user) {
        return $user->referrer && $user->referrer->referral_level > 0;
    });

echo "Level 1 users who might be incorrectly leveled (have referrers at level > 0):\n\n";

foreach ($suspiciousLevel1Users as $user) {
    echo "User ID: {$user->id}\n";
    echo "Name: {$user->name}\n";
    echo "Current Level: {$user->referral_level}\n";
    echo "Referrer: {$user->referrer->name} (Level {$user->referrer->referral_level})\n";
    echo "Should be Level: " . ($user->referrer->referral_level + 1) . "\n";
    echo "Created: {$user->created_at}\n";
    echo "---\n";
}

echo "Total suspicious level 1 users: " . $suspiciousLevel1Users->count() . "\n";
