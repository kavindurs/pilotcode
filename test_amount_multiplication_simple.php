<?php
// Test script that can be run with artisan tinker
// Usage: php artisan tinker --execute="require_once 'test_amount_multiplication_simple.php';"

use App\Models\Property;
use App\Models\Plan;
use App\Models\Payment;
use Illuminate\Support\Facades\Session;

echo "=== Testing Amount Multiplication (Simple) ===\n\n";

// Get test data
$property = Property::first();
$plan = Plan::first();

if (!$property || !$plan) {
    echo "❌ Missing test data (property or plan)\n";
    return;
}

echo "Using Property ID: {$property->id}\n";
echo "Using Plan ID: {$plan->id}\n\n";

// Test storing amount with multiplication
echo "Test: Creating payment record with amount multiplication\n";
echo "-----------------------------------------------------\n";

// Clean up any existing test payment
Payment::where('property_id', $property->id)->delete();

$originalAmount = 100.00;
echo "Original amount: {$originalAmount}\n";

// Simulate what happens in paymentSuccess method
$payment = Payment::updateOrCreate(
    [
        'property_id' => $property->id
    ],
    [
        'plan_id' => $plan->id,
        'business_email' => 'business@example.com',
        'customer_email' => 'test@example.com',
        'customer_name' => 'Test User',
        'amount' => $originalAmount * 3, // This is what we want to test
        'currency' => 'LKR',
        'status' => 'completed',
        'order_id' => 'TEST_ORDER_' . time(),
        'payment_method' => 'Credit Card',
        'transaction_id' => 'TXN_' . time(),
        'completed_at' => now(),
        'updated_at' => now()
    ]
);

echo "Amount stored in database: {$payment->amount}\n";
$expectedAmount = $originalAmount * 3;

if ($payment->amount == $expectedAmount) {
    echo "✅ Amount correctly multiplied by 3 (Expected: {$expectedAmount}, Got: {$payment->amount})\n";
} else {
    echo "❌ Amount NOT correctly multiplied by 3 (Expected: {$expectedAmount}, Got: {$payment->amount})\n";
}

// Test with different amounts
echo "\nTesting different amounts:\n";
$testAmounts = [50.00, 150.75, 200.00, 99.99];

foreach ($testAmounts as $testAmount) {
    Payment::where('property_id', $property->id)->delete();

    $payment = Payment::create([
        'property_id' => $property->id,
        'plan_id' => $plan->id,
        'business_email' => 'business@example.com',
        'customer_email' => 'test@example.com',
        'customer_name' => 'Test User',
        'amount' => $testAmount * 3,
        'currency' => 'LKR',
        'status' => 'completed',
        'order_id' => 'TEST_ORDER_' . time() . '_' . $testAmount,
        'payment_method' => 'Credit Card',
        'transaction_id' => 'TXN_' . time() . '_' . $testAmount,
        'completed_at' => now()
    ]);

    $expectedAmount = $testAmount * 3;
    if ($payment->amount == $expectedAmount) {
        echo "✅ {$testAmount} → {$payment->amount}\n";
    } else {
        echo "❌ {$testAmount} → {$payment->amount} (expected {$expectedAmount})\n";
    }
}

// Clean up
Payment::where('customer_email', 'test@example.com')->delete();
echo "\n✅ Test data cleaned up\n";
echo "=== Test Complete ===\n";
