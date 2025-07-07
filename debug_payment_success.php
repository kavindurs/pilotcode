<?php

// Debug script to test if payment success is working correctly

echo "=== PAYMENT SUCCESS DEBUG TEST ===\n";
echo "Testing if payment success callback is working properly\n\n";

// Clear any existing payments for testing
\App\Models\Payment::where('property_id', 2)->delete();

// Set up property
$property = \App\Models\Property::find(2);
if (!$property) {
    echo "✗ Property 2 not found\n";
    exit(1);
}

echo "✓ Property found: {$property->business_name}\n";

// Test 1: Check if session works
echo "\nTest 1: Session functionality...\n";

$testSessionData = [
    'plan_id' => 2,
    'property_id' => 2,
    'business_email' => 'test@example.com',
    'customer_email' => 'test@example.com',
    'customer_name' => 'Test Customer',
    'amount' => 2500,
    'currency' => 'LKR',
    'payment_method' => 'genie_business',
    'order_id' => 'TEST_ORDER_' . time(),
    'gateway_transaction_id' => 'TEST_GATEWAY_' . time()
];

session(['pending_plan_payment' => $testSessionData]);

$retrievedData = session('pending_plan_payment');
if ($retrievedData) {
    echo "✓ Session storage and retrieval works\n";
    echo "  Stored plan_id: {$retrievedData['plan_id']}\n";
    echo "  Stored amount: {$retrievedData['amount']}\n";
} else {
    echo "✗ Session storage failed\n";
    exit(1);
}

// Test 2: Check if Payment::updateOrCreate works
echo "\nTest 2: Payment::updateOrCreate functionality...\n";

$paymentCount = \App\Models\Payment::where('property_id', 2)->count();
echo "Payments before creation: $paymentCount\n";

try {
    $payment = \App\Models\Payment::updateOrCreate(
        [
            'property_id' => $property->id
        ],
        [
            'plan_id' => $testSessionData['plan_id'],
            'business_email' => $testSessionData['business_email'],
            'customer_email' => $testSessionData['customer_email'],
            'customer_name' => $testSessionData['customer_name'],
            'amount' => $testSessionData['amount'],
            'currency' => $testSessionData['currency'],
            'status' => 'completed',
            'order_id' => $testSessionData['order_id'],
            'payment_method' => $testSessionData['payment_method'],
            'transaction_id' => 'TEST_TRANSACTION_123',
            'genie_transaction_id' => $testSessionData['gateway_transaction_id'],
            'completed_at' => now(),
            'updated_at' => now()
        ]
    );

    echo "✓ Payment record created successfully\n";
    echo "  Payment ID: {$payment->id}\n";
    echo "  Payment status: {$payment->status}\n";
    echo "  Payment amount: {$payment->amount}\n";
    echo "  Was recently created: " . ($payment->wasRecentlyCreated ? 'Yes' : 'No') . "\n";

} catch (Exception $e) {
    echo "✗ Payment creation failed: " . $e->getMessage() . "\n";
    exit(1);
}

$paymentCountAfter = \App\Models\Payment::where('property_id', 2)->count();
echo "Payments after creation: $paymentCountAfter\n";

// Test 3: Check if property update works
echo "\nTest 3: Property plan update...\n";

$originalPlanId = $property->plan_id;
echo "Original property plan ID: " . ($originalPlanId ?? 'null') . "\n";

try {
    $property->update([
        'plan_id' => $payment->plan_id
    ]);

    $property->refresh();
    echo "✓ Property plan updated successfully\n";
    echo "  New plan ID: {$property->plan_id}\n";

} catch (Exception $e) {
    echo "✗ Property update failed: " . $e->getMessage() . "\n";
}

// Test 4: Check database persistence
echo "\nTest 4: Database persistence check...\n";

$savedPayment = \App\Models\Payment::where('property_id', 2)->first();
if ($savedPayment) {
    echo "✓ Payment record persisted in database\n";
    echo "  ID: {$savedPayment->id}\n";
    echo "  Status: {$savedPayment->status}\n";
    echo "  Amount: {$savedPayment->amount}\n";
    echo "  Plan ID: {$savedPayment->plan_id}\n";
    echo "  Created at: {$savedPayment->created_at}\n";
    echo "  Updated at: {$savedPayment->updated_at}\n";
} else {
    echo "✗ Payment record not found in database\n";
}

$savedProperty = \App\Models\Property::find(2);
if ($savedProperty) {
    echo "✓ Property record persisted in database\n";
    echo "  Plan ID: {$savedProperty->plan_id}\n";
} else {
    echo "✗ Property record not found in database\n";
}

// Test 5: Simulate the exact paymentSuccess flow
echo "\nTest 5: Simulating exact paymentSuccess flow...\n";

// Clean up first
\App\Models\Payment::where('property_id', 2)->delete();
$property->update(['plan_id' => 1]);

// Set up session again
session(['pending_plan_payment' => $testSessionData]);

// Now simulate the exact paymentSuccess method logic
$paymentData = session('pending_plan_payment');

if (!$paymentData) {
    echo "✗ Session data not found\n";
    exit(1);
}

echo "✓ Session data retrieved\n";

$property = \App\Models\Property::find($paymentData['property_id']);
if (!$property) {
    echo "✗ Property not found\n";
    exit(1);
}

echo "✓ Property found\n";

$payment = \App\Models\Payment::updateOrCreate(
    [
        'property_id' => $property->id
    ],
    [
        'plan_id' => $paymentData['plan_id'],
        'business_email' => $paymentData['business_email'],
        'customer_email' => $paymentData['customer_email'],
        'customer_name' => $paymentData['customer_name'],
        'amount' => $paymentData['amount'],
        'currency' => $paymentData['currency'],
        'status' => 'completed',
        'order_id' => $paymentData['order_id'],
        'payment_method' => $paymentData['payment_method'],
        'transaction_id' => 'FINAL_TEST_TRANSACTION',
        'genie_transaction_id' => $paymentData['gateway_transaction_id'],
        'completed_at' => now(),
        'updated_at' => now()
    ]
);

echo "✓ Payment record created in success flow\n";

if ($property && $payment->status === 'completed') {
    $property->update([
        'plan_id' => $payment->plan_id
    ]);
    echo "✓ Property plan updated in success flow\n";
}

session()->forget('pending_plan_payment');
echo "✓ Session cleared\n";

// Final verification
$finalPayment = \App\Models\Payment::where('property_id', 2)->first();
$finalProperty = \App\Models\Property::find(2);

echo "\nFinal verification:\n";
echo "  Payment exists: " . ($finalPayment ? 'Yes' : 'No') . "\n";
if ($finalPayment) {
    echo "  Payment status: {$finalPayment->status}\n";
    echo "  Payment amount: {$finalPayment->amount}\n";
}
echo "  Property plan: {$finalProperty->plan_id}\n";
echo "  Session cleared: " . (session('pending_plan_payment') ? 'No' : 'Yes') . "\n";

echo "\n=== CONCLUSION ===\n";
if ($finalPayment && $finalPayment->status === 'completed' && $finalProperty->plan_id == 2) {
    echo "✓ All tests passed - payment success flow is working correctly\n";
    echo "If payments are not being saved in real usage, the issue might be:\n";
    echo "1. Session not persisting between requests\n";
    echo "2. paymentSuccess route not being called\n";
    echo "3. Database transaction rollback\n";
    echo "4. Different database connection\n";
} else {
    echo "✗ Some tests failed - there are issues with the payment flow\n";
}
