<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\ReferralRate;

echo "=== Checking and Setting Up Referral Rates ===\n\n";

// Check if referral rates exist
$rates = ReferralRate::orderBy('id')->get();

echo "Current referral rates in database: " . $rates->count() . "\n\n";

if ($rates->count() == 0) {
    echo "No referral rates found. Creating default 3-level rates...\n";

    // Create the 3 default levels
    $defaultRates = [
        ['rate' => 10.00, 'description' => 'Direct referral commission (Level 1)'],
        ['rate' => 8.00, 'description' => 'Second level referral commission (Level 2)'],
        ['rate' => 5.00, 'description' => 'Third level referral commission (Level 3)']
    ];

    foreach ($defaultRates as $index => $rateData) {
        $rate = ReferralRate::create($rateData);
        echo "✓ Created Level " . ($index + 1) . ": {$rate->rate}% - {$rate->description}\n";
    }
} else {
    echo "Existing referral rates:\n";
    foreach ($rates as $rate) {
        echo "- Level {$rate->id}: {$rate->rate}% - {$rate->description}\n";
    }

    // Ensure we have at least the 3 core levels
    if ($rates->count() < 3) {
        echo "\nMissing core levels. Adding them...\n";

        $defaultRates = [
            1 => ['rate' => 10.00, 'description' => 'Direct referral commission (Level 1)'],
            2 => ['rate' => 8.00, 'description' => 'Second level referral commission (Level 2)'],
            3 => ['rate' => 5.00, 'description' => 'Third level referral commission (Level 3)']
        ];

        for ($i = 1; $i <= 3; $i++) {
            $existing = ReferralRate::find($i);
            if (!$existing) {
                $rate = ReferralRate::create(array_merge(['id' => $i], $defaultRates[$i]));
                echo "✓ Created Level {$i}: {$rate->rate}% - {$rate->description}\n";
            }
        }
    }
}

echo "\n=== Final Status ===\n";
$finalRates = ReferralRate::orderBy('id')->get();
echo "Total referral rates: " . $finalRates->count() . "\n";

foreach ($finalRates as $rate) {
    echo "Level {$rate->id}: {$rate->rate}% - {$rate->description}\n";
}

echo "\n✅ Referral rates setup complete!\n";
echo "You can now visit: http://127.0.0.1:8000/admin/referrals\n";
