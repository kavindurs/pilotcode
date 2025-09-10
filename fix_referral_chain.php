<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

echo "=== Fixing User 44's Referral Chain ===\n\n";

$user44 = User::find(44);

echo "User 44 current state:\n";
echo "Name: {$user44->name}\n";
echo "Level: {$user44->referral_level}\n";
echo "Referred by: {$user44->referred_by}\n";
echo "Parent Referrer: {$user44->parent_referrer_id}\n";
echo "Referral Path: {$user44->referral_path}\n\n";

// Check if user 44 has a referrer
if ($user44->referred_by) {
    echo "User 44 has a referrer (ID: {$user44->referred_by}), recalculating...\n";
    $user44->calculateReferralLevel($user44->referred_by);
    $user44->save();
} else {
    echo "User 44 has no referrer, so should be Level 0 (original referrer)\n";
    $user44->referral_level = 0;
    $user44->parent_referrer_id = null;
    $user44->referral_path = null;
    $user44->save();
}

echo "\nUser 44 after fix:\n";
echo "Level: {$user44->referral_level}\n";
echo "Parent Referrer: {$user44->parent_referrer_id}\n";
echo "Referral Path: {$user44->referral_path}\n";

// Now fix user 69 based on the corrected user 44
echo "\n=== Fixing User 69 Based on Corrected User 44 ===\n";

$user69 = User::find(69);
$user69->calculateReferralLevel(44);
$user69->save();

echo "User 69 after fix:\n";
echo "Level: {$user69->referral_level}\n";
echo "Parent Referrer: {$user69->parent_referrer_id}\n";
echo "Referral Path: {$user69->referral_path}\n";

echo "\n✅ Referral chain fixed!\n";
