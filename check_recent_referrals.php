<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

echo "=== Checking Recent Users with Referral Information ===\n\n";

// Get the 10 most recent users with their referral info
$recentUsers = User::with(['referrer', 'referral'])
    ->whereNotNull('referred_by')
    ->orderBy('created_at', 'desc')
    ->limit(10)
    ->get();

foreach ($recentUsers as $user) {
    echo "User ID: {$user->id}\n";
    echo "Name: {$user->name}\n";
    echo "Email: {$user->email}\n";
    echo "Referral Level: " . ($user->referral_level ?? 'NULL') . "\n";
    echo "Referred By: " . ($user->referred_by ?? 'NULL') . "\n";

    if ($user->referrer) {
        echo "Referrer Name: {$user->referrer->name}\n";
        echo "Referrer Level: " . ($user->referrer->referral_level ?? 'NULL') . "\n";
        echo "Expected Level: " . (($user->referrer->referral_level ?? 0) + 1) . "\n";

        if ($user->referral_level == (($user->referrer->referral_level ?? 0) + 1)) {
            echo "Status: ✅ CORRECT\n";
        } else {
            echo "Status: ❌ INCORRECT (should be " . (($user->referrer->referral_level ?? 0) + 1) . ")\n";
        }
    } else {
        echo "Referrer: Not found\n";
        echo "Status: ❌ MISSING REFERRER\n";
    }

    echo "Created: {$user->created_at}\n";
    echo "---\n";
}

echo "\nTotal recent users with referrals: " . $recentUsers->count() . "\n";
