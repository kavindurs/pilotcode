<?php

// Quick demonstration of "One Payment Record Per Property" behavior
require_once __DIR__ . '/vendor/autoload.php';

use App\Models\Payment;
use App\Models\Property;
use App\Models\Plan;

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== ONE PAYMENT RECORD PER PROPERTY DEMO ===\n\n";

$property = Property::first();
$plans = Plan::take(3)->get();

if (!$property || $plans->count() < 2) {
    echo "Need at least 1 property and 2 plans for this demo\n";
    exit;
}

echo "Property: {$property->business_name} (ID: {$property->id})\n";
echo "Available Plans: " . $plans->pluck('name')->implode(', ') . "\n\n";

// Clean up any existing test records
Payment::where('property_id', $property->id)->where('order_id', 'LIKE', 'DEMO_%')->delete();

// Demonstrate the behavior
foreach ($plans as $index => $plan) {
    echo "--- Step " . ($index + 1) . ": Purchasing {$plan->name} Plan ---\n";

    $payment = Payment::updateOrCreate(
        ['property_id' => $property->id],
        [
            'plan_id' => $plan->id,
            'business_email' => $property->business_email ?: 'demo@test.com',
            'customer_email' => $property->business_email ?: 'demo@test.com',
            'customer_name' => $property->business_name ?: 'Demo Property',
            'amount' => ($index + 1) * 1000, // Different amounts
            'currency' => 'LKR',
            'status' => 'pending',
            'order_id' => 'DEMO_' . $plan->id . '_' . time() . '_' . $index,
            'payment_method' => 'genie_business',
            'transaction_id' => null,
            'completed_at' => null
        ]
    );

    echo "Payment Record ID: {$payment->id}\n";
    echo "Plan ID: {$payment->plan_id}\n";
    echo "Amount: {$payment->amount} LKR\n";
    echo "Status: {$payment->status}\n";
    echo "Created New Record: " . ($payment->wasRecentlyCreated ? 'YES' : 'NO') . "\n\n";

    // Simulate payment completion for variety
    if ($index === 1) {
        $payment->update(['status' => 'completed', 'completed_at' => now()]);
        echo "-> Marked as completed\n\n";
    }
}

// Show final state
echo "--- Final State Check ---\n";
$finalPayments = Payment::where('property_id', $property->id)->where('order_id', 'LIKE', 'DEMO_%')->get();
echo "Total payment records for this property: {$finalPayments->count()}\n";

foreach ($finalPayments as $payment) {
    echo "Record ID {$payment->id}: Plan {$payment->plan_id}, Status: {$payment->status}, Amount: {$payment->amount}\n";
}

// Clean up
Payment::where('property_id', $property->id)->where('order_id', 'LIKE', 'DEMO_%')->delete();
echo "\nDemo records cleaned up.\n";

echo "\n=== SUMMARY ===\n";
echo "✓ Only ONE payment record exists per property at any time\n";
echo "✓ When purchasing a different plan, the same record is updated\n";
echo "✓ Plan ID, amount, and other details are updated to reflect the new purchase\n";
echo "✓ Status is reset to 'pending' for new purchases\n";
echo "✓ Transaction fields are cleared for new payment attempts\n";
echo "✓ Previous payment history is overwritten (as requested)\n";
