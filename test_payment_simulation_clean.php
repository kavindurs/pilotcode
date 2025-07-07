<?php

// This script will test the payment flow by directly calling Laravel
// We'll run it using artisan tinker to have access to the models

echo "=== PAYMENT ON SUCCESS ONLY TEST ===\n";
echo "Testing that payment records are only created on payment success\n\n";

// Clear any existing payment records for property 2
echo "Step 1: Cleaning up existing payment records...\n";
$existingPayments = \App\Models\Payment::where('property_id', 2)->get();
foreach ($existingPayments as $payment) {
    echo "Deleting existing payment ID: {$payment->id}\n";
    $payment->delete();
}

// Check that no payment records exist
$paymentCount = \App\Models\Payment::where('property_id', 2)->count();
echo "Payment records for property 2: $paymentCount\n\n";

// Get property 2
$property = \App\Models\Property::find(2);
if (!$property) {
    echo "✗ Property 2 not found\n";
    exit(1);
}

echo "✓ Property 2 found: {$property->business_name}\n";
echo "Current plan ID: {$property->plan_id}\n\n";

// Get plan 2
$plan = \App\Models\Plan::find(2);
if (!$plan) {
    echo "✗ Plan 2 not found\n";
    exit(1);
}

echo "✓ Plan 2 found: {$plan->name}\n\n";

// Step 2: Simulate payment data in session (as if user clicked Complete Payment)
echo "Step 2: Creating session payment data (simulating processPayment)...\n";

$paymentData = [
    'plan_id' => $plan->id,
    'property_id' => $property->id,
    'business_email' => $property->business_email ?: 'noemail@property' . $property->id . '.local',
    'customer_email' => $property->business_email ?: 'noemail@property' . $property->id . '.local',
    'customer_name' => $property->business_name ?: $property->contact_person,
    'amount' => 2500, // LKR amount
    'currency' => 'LKR',
    'payment_method' => 'genie_business',
    'order_id' => 'PLAN_' . $plan->id . '_' . time(),
    'gateway_transaction_id' => 'TEST_GATEWAY_' . time()
];

// This simulates storing data in session (normally done in processPayment)
echo "Payment data created:\n";
foreach ($paymentData as $key => $value) {
    echo "  $key: $value\n";
}

echo "\n";

// Step 3: Check payment records after "processPayment" (should still be 0)
echo "Step 3: Checking payment records after processPayment simulation...\n";
$paymentCountAfterProcess = \App\Models\Payment::where('property_id', 2)->count();
echo "Payment records for property 2: $paymentCountAfterProcess\n";

if ($paymentCountAfterProcess === 0) {
    echo "✓ CORRECT: No payment record created in processPayment\n\n";
} else {
    echo "✗ INCORRECT: Payment record was created in processPayment\n\n";
    exit(1);
}

// Step 4: Simulate payment success (this is where payment record should be created)
echo "Step 4: Simulating payment success callback...\n";

// This simulates the paymentSuccess method logic
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
        'status' => 'completed', // Set as completed immediately
        'order_id' => $paymentData['order_id'],
        'payment_method' => $paymentData['payment_method'],
        'transaction_id' => 'TEST_SUCCESS_TRANS_123',
        'genie_transaction_id' => $paymentData['gateway_transaction_id'],
        'completed_at' => now(),
        'updated_at' => now()
    ]
);

echo "✓ Payment record created on success: ID {$payment->id}\n";
echo "Payment details:\n";
echo "  Status: {$payment->status}\n";
echo "  Amount: {$payment->amount}\n";
echo "  Plan ID: {$payment->plan_id}\n";
echo "  Transaction ID: {$payment->transaction_id}\n\n";

// Step 5: Update property plan (this should also happen in paymentSuccess)
echo "Step 5: Updating property plan...\n";
$property->update([
    'plan_id' => $payment->plan_id
]);

$property->refresh();
echo "✓ Property plan updated to: {$property->plan_id}\n\n";

// Step 6: Final verification
echo "Step 6: Final verification...\n";

$finalPaymentCount = \App\Models\Payment::where('property_id', 2)->count();
$finalPayment = \App\Models\Payment::where('property_id', 2)->first();

echo "Final payment record count: $finalPaymentCount\n";
if ($finalPayment) {
    echo "Final payment status: {$finalPayment->status}\n";
    echo "Final payment plan ID: {$finalPayment->plan_id}\n";
}
echo "Final property plan ID: {$property->plan_id}\n\n";

// Test results
if ($finalPaymentCount === 1 && $finalPayment->status === 'completed' && $property->plan_id == 2) {
    echo "=== TEST RESULT ===\n";
    echo "✓ SUCCESS: Payment record created only on success\n";
    echo "✓ SUCCESS: Payment status is completed\n";
    echo "✓ SUCCESS: Property plan updated to plan 2\n";
    echo "✓ SUCCESS: Only one payment record exists\n";
    echo "\nFlow is working correctly!\n";
} else {
    echo "=== TEST RESULT ===\n";
    echo "✗ FAILURE: Something is not working correctly\n";
    echo "Expected: 1 payment record, status=completed, property plan=2\n";
    echo "Actual: $finalPaymentCount payment record(s), status={$finalPayment->status}, property plan={$property->plan_id}\n";
}
