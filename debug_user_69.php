<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Referral;

echo "=== Investigating User ID 69 ===\n\n";

$user = User::find(69);

if ($user) {
    echo "User 69 Details:\n";
    echo "Name: {$user->name}\n";
    echo "Email: {$user->email}\n";
    echo "Referral Level: {$user->referral_level}\n";
    echo "Referred By: {$user->referred_by}\n";
    echo "Parent Referrer ID: {$user->parent_referrer_id}\n";
    echo "Referral Path: {$user->referral_path}\n\n";

    // Check who referred this user
    if ($user->referred_by) {
        $referrer = User::find($user->referred_by);
        if ($referrer) {
            echo "Referrer Details:\n";
            echo "Referrer Name: {$referrer->name}\n";
            echo "Referrer Level: {$referrer->referral_level}\n";
            echo "Referrer Parent: {$referrer->parent_referrer_id}\n";
            echo "Referrer Path: {$referrer->referral_path}\n\n";
        }
    }

    // Check the referral code REFWLCTK6OM
    echo "Checking referral code REFWLCTK6OM:\n";
    $referral = Referral::where('referral_code', 'REFWLCTK6OM')->first();
    if ($referral) {
        $referralOwner = User::find($referral->user_id);
        if ($referralOwner) {
            echo "Referral Code Owner: {$referralOwner->name} (ID: {$referralOwner->id})\n";
            echo "Owner Referral Level: {$referralOwner->referral_level}\n";
            echo "Owner Parent Referrer: {$referralOwner->parent_referrer_id}\n";
            echo "Owner Referral Path: {$referralOwner->referral_path}\n";

            // Calculate what the correct level should be
            echo "\nWhat should User 69's level be?\n";
            $expectedLevel = ($referralOwner->referral_level ?? 0) + 1;
            echo "Expected Level: {$expectedLevel}\n";

            if ($user->referral_level != $expectedLevel) {
                echo "❌ MISMATCH! User 69 should be level {$expectedLevel} but is level {$user->referral_level}\n";

                // Fix it
                echo "Fixing user 69's referral level...\n";
                $user->calculateReferralLevel($referralOwner->id);
                $user->save();

                echo "✅ Fixed! New level: {$user->referral_level}\n";
            } else {
                echo "✅ Correct level\n";
            }
        }
    } else {
        echo "❌ Referral code REFWLCTK6OM not found!\n";
    }

} else {
    echo "User 69 not found!\n";
}

echo "\n=== Investigation Complete ===\n";
