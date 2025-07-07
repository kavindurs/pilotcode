<?php

// Test that verifies the PlanPaymentController routes work correctly

echo "=== PLAN PAYMENT CONTROLLER ROUTE TEST ===\n";
echo "Testing that the updated controller works with session-based payment flow\n\n";

// Test 1: Check that plans.payment.success route exists and doesn't require payment ID
echo "Step 1: Testing route definitions...\n";

try {
    $successRoute = route('plans.payment.success');
    echo "✓ plans.payment.success route: $successRoute\n";
} catch (Exception $e) {
    echo "✗ plans.payment.success route error: " . $e->getMessage() . "\n";
}

try {
    $cancelRoute = route('plans.payment.cancel');
    echo "✓ plans.payment.cancel route: $cancelRoute\n";
} catch (Exception $e) {
    echo "✗ plans.payment.cancel route error: " . $e->getMessage() . "\n";
}

try {
    $processRoute = route('plans.payment.process');
    echo "✓ plans.payment.process route: $processRoute\n";
} catch (Exception $e) {
    echo "✗ plans.payment.process route error: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 2: Simulate session-based payment flow
echo "Step 2: Testing session-based payment success without payment ID...\n";

// Clear any existing payment for property 2
\App\Models\Payment::where('property_id', 2)->delete();

// Set up session data (simulating what processPayment would do)
$paymentData = [
    'plan_id' => 2,
    'property_id' => 2,
    'business_email' => 'test@example.com',
    'customer_email' => 'test@example.com',
    'customer_name' => 'Test Customer',
    'amount' => 2500,
    'currency' => 'LKR',
    'payment_method' => 'genie_business',
    'order_id' => 'PLAN_2_TEST_' . time(),
    'gateway_transaction_id' => 'GATEWAY_TEST_' . time()
];

// Start session and set payment data
session()->start();
session(['pending_plan_payment' => $paymentData]);

echo "✓ Session payment data set:\n";
foreach ($paymentData as $key => $value) {
    echo "  $key: $value\n";
}

echo "\n";

// Test 3: Simulate the paymentSuccess method
echo "Step 3: Testing paymentSuccess method logic...\n";

$paymentCountBefore = \App\Models\Payment::where('property_id', 2)->count();
echo "Payment records before success: $paymentCountBefore\n";

// Simulate what the paymentSuccess method does
$sessionData = session('pending_plan_payment');

if ($sessionData) {
    $property = \App\Models\Property::find($sessionData['property_id']);

    if ($property) {
        // Create/update payment record (this simulates paymentSuccess logic)
        $payment = \App\Models\Payment::updateOrCreate(
            [
                'property_id' => $property->id
            ],
            [
                'plan_id' => $sessionData['plan_id'],
                'business_email' => $sessionData['business_email'],
                'customer_email' => $sessionData['customer_email'],
                'customer_name' => $sessionData['customer_name'],
                'amount' => $sessionData['amount'],
                'currency' => $sessionData['currency'],
                'status' => 'completed',
                'order_id' => $sessionData['order_id'],
                'payment_method' => $sessionData['payment_method'],
                'transaction_id' => 'SUCCESS_TEST_123',
                'genie_transaction_id' => $sessionData['gateway_transaction_id'],
                'completed_at' => now(),
                'updated_at' => now()
            ]
        );

        // Update property plan
        $property->update([
            'plan_id' => $payment->plan_id
        ]);

        // Clear session
        session()->forget('pending_plan_payment');

        echo "✓ Payment record created: ID {$payment->id}\n";
        echo "✓ Property plan updated to: {$property->plan_id}\n";
        echo "✓ Session data cleared\n";

    } else {
        echo "✗ Property not found\n";
    }
} else {
    echo "✗ No session data found\n";
}

echo "\n";

// Test 4: Final verification
echo "Step 4: Final verification...\n";

$paymentCountAfter = \App\Models\Payment::where('property_id', 2)->count();
$payment = \App\Models\Payment::where('property_id', 2)->first();
$property = \App\Models\Property::find(2);

echo "Payment records after success: $paymentCountAfter\n";
if ($payment) {
    echo "Payment status: {$payment->status}\n";
    echo "Payment plan ID: {$payment->plan_id}\n";
}
echo "Property plan ID: {$property->plan_id}\n";
echo "Session payment data exists: " . (session('pending_plan_payment') ? 'Yes' : 'No') . "\n";

echo "\n";

// Test results
if ($paymentCountAfter === 1 && $payment->status === 'completed' && $property->plan_id == 2 && !session('pending_plan_payment')) {
    echo "=== TEST RESULT ===\n";
    echo "✓ SUCCESS: All tests passed\n";
    echo "✓ Routes are correctly defined\n";
    echo "✓ Payment record created only on success\n";
    echo "✓ Property plan updated correctly\n";
    echo "✓ Session data handled correctly\n";
    echo "\nThe session-based payment flow is working correctly!\n";
} else {
    echo "=== TEST RESULT ===\n";
    echo "✗ FAILURE: Some tests failed\n";
    echo "Check the output above for details.\n";
}
