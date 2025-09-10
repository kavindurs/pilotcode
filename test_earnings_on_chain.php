<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Http\Controllers\ReferralController;

echo "=== Testing 3-Level Referral Earnings ===\n\n";

// Test with the chain we just created:
// User 75 (Level 0) -> User 76 (Level 1) -> User 77 (Level 2)

use App\Models\User;

// Simulate a payment/purchase by user 77 (Level 2)
$payerId = 77;
$user = User::find($payerId);
$propertyId = 89; // Valid property ID
$planId = 1; // Sample plan ID
$amount = 100.00;

echo "Processing referral earnings for:\n";
echo "- Payer: User {$payerId} (Level 2)\n";
echo "- Property: {$propertyId}\n";
echo "- Plan: {$planId}\n";
echo "- Amount: \${$amount}\n\n";

// Call the 3-level referral processing
$earnings = ReferralController::process3LevelReferralEarnings($user, $propertyId, $planId, $amount);

if (!empty($earnings)) {
    echo "✅ Referral earnings processed successfully!\n\n";

    echo "Earnings Distribution:\n";
    foreach ($earnings as $earning) {
        echo "- Level: User {$earning->referrer_id} earned \${$earning->commission_amount} ({$earning->commission_rate}%)\n";
    }

    $totalDistributed = collect($earnings)->sum('commission_amount');
    echo "\nTotal distributed: \${$totalDistributed}\n";
} else {
    echo "❌ No referral earnings were processed (user may not have referrers)\n";
}

echo "\n=== Test Complete ===\n";
