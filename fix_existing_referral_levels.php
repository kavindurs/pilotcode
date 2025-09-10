<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\DB;

echo "=== Fixing Existing Users' Referral Levels ===\n\n";

// Find users with referred_by but incorrect referral levels
$usersToFix = User::where('referred_by', '!=', null)
    ->whereRaw('(referral_level IS NULL OR referral_level = 1 OR parent_referrer_id IS NULL)')
    ->get();

echo "Found " . $usersToFix->count() . " users that need referral level correction.\n\n";

if ($usersToFix->count() > 0) {
    $fixed = 0;
    foreach ($usersToFix as $user) {
        echo "Fixing user: {$user->name} (ID: {$user->id})\n";
        echo "  Current - Level: {$user->referral_level}, Referred by: {$user->referred_by}, Parent: {$user->parent_referrer_id}\n";

        // Recalculate referral level
        $user->calculateReferralLevel($user->referred_by);
        $user->save();

        echo "  Updated - Level: {$user->referral_level}, Parent: {$user->parent_referrer_id}, Path: {$user->referral_path}\n\n";
        $fixed++;
    }

    echo "✅ Fixed {$fixed} users' referral levels.\n";
} else {
    echo "✅ No users need fixing - all referral levels are correct.\n";
}

// Display summary of current referral level distribution
echo "\n=== Current Referral Level Distribution ===\n";
$distribution = User::selectRaw('referral_level, COUNT(*) as count')
    ->groupBy('referral_level')
    ->orderBy('referral_level')
    ->get();

foreach ($distribution as $level) {
    $levelName = $level->referral_level === null ? 'NULL' : $level->referral_level;
    echo "Level {$levelName}: {$level->count} users\n";
}

echo "\n=== Fix completed! ===\n";
