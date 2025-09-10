<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

echo "=== Debugging Referral Level Calculation ===\n\n";

$user69 = User::find(69);
$user44 = User::find(44);

echo "User 44 (Referrer) Details:\n";
echo "Level: {$user44->referral_level}\n";
echo "Parent Referrer: {$user44->parent_referrer_id}\n";
echo "Referral Path: {$user44->referral_path}\n\n";

echo "User 69 (Before recalculation):\n";
echo "Level: {$user69->referral_level}\n";
echo "Referred by: {$user69->referred_by}\n";
echo "Parent Referrer: {$user69->parent_referrer_id}\n";
echo "Referral Path: {$user69->referral_path}\n\n";

echo "Recalculating user 69's referral level...\n";
$user69->calculateReferralLevel(44);
$user69->save();

echo "\nUser 69 (After recalculation):\n";
echo "Level: {$user69->referral_level}\n";
echo "Referred by: {$user69->referred_by}\n";
echo "Parent Referrer: {$user69->parent_referrer_id}\n";
echo "Referral Path: {$user69->referral_path}\n";

// Let's check what happens in the calculation step by step
echo "\n=== Step-by-step calculation ===\n";
$referrer = User::find(44);
$referrerLevel = $referrer->referral_level ?? 0;
echo "Referrer level: {$referrerLevel}\n";

if ($referrerLevel == 0) {
    echo "Branch: Direct referral of original user (Level 1)\n";
} elseif ($referrerLevel == 1) {
    echo "Branch: Referral of Level 1 user (Level 2)\n";
    echo "Expected parent referrer ID: {$referrer->parent_referrer_id}\n";
    echo "Expected referral path: {$referrer->referral_path},44\n";
} else {
    echo "Branch: Other level\n";
}

echo "\n✅ Debug completed\n";
