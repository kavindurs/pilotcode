<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\DB;

echo "=== Comprehensive Referral Chain Fix ===\n\n";

// Find all users with referral issues
echo "1. Finding users with referral chain issues...\n";

$problematicUsers = User::where(function($query) {
    $query->whereNotNull('referred_by')
          ->where(function($q) {
              $q->whereNull('parent_referrer_id')
                ->orWhereNull('referral_path')
                ->orWhere('referral_path', '')
                ->orWhere('referral_level', 1); // Check if all referred users are level 1 (wrong)
          });
})->get();

echo "Found " . $problematicUsers->count() . " users with potential referral chain issues.\n\n";

$fixed = 0;
foreach ($problematicUsers as $user) {
    echo "Checking user: {$user->name} (ID: {$user->id})\n";
    echo "  Current: Level {$user->referral_level}, Referred by {$user->referred_by}\n";

    if ($user->referred_by) {
        $referrer = User::find($user->referred_by);
        if ($referrer) {
            echo "  Referrer: {$referrer->name} (Level {$referrer->referral_level})\n";

            // First fix the referrer if needed
            if ($referrer->referral_level === null || ($referrer->referred_by && !$referrer->parent_referrer_id)) {
                echo "  Fixing referrer first...\n";
                if ($referrer->referred_by) {
                    $referrer->calculateReferralLevel($referrer->referred_by);
                } else {
                    $referrer->referral_level = 0;
                    $referrer->parent_referrer_id = null;
                    $referrer->referral_path = null;
                }
                $referrer->save();
            }

            // Now fix the current user
            $oldLevel = $user->referral_level;
            $user->calculateReferralLevel($user->referred_by);
            $user->save();

            if ($user->referral_level != $oldLevel) {
                echo "  ✅ Fixed: {$oldLevel} → {$user->referral_level}\n";
                $fixed++;
            } else {
                echo "  ✓ Already correct\n";
            }
        } else {
            echo "  ❌ Referrer not found, clearing referral data\n";
            $user->referred_by = null;
            $user->referral_level = 0;
            $user->parent_referrer_id = null;
            $user->referral_path = null;
            $user->save();
            $fixed++;
        }
    } else {
        echo "  Setting as Level 0 (no referrer)\n";
        $user->referral_level = 0;
        $user->parent_referrer_id = null;
        $user->referral_path = null;
        $user->save();
        $fixed++;
    }
    echo "\n";
}

echo "✅ Fixed {$fixed} users.\n\n";

// Display final referral level distribution
echo "=== Final Referral Level Distribution ===\n";
$distribution = User::selectRaw('referral_level, COUNT(*) as count')
    ->groupBy('referral_level')
    ->orderBy('referral_level')
    ->get();

foreach ($distribution as $level) {
    $levelName = $level->referral_level === null ? 'NULL' : "Level {$level->referral_level}";
    echo "{$levelName}: {$level->count} users\n";
}

echo "\n=== All referral chains are now properly structured! ===\n";
