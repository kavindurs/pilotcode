<?php
// Test that amount is stored as original value * 3

// Basic Laravel bootstrap for testing
require_once __DIR__ . '/bootstrap/app.php';

use App\Http\Controllers\PlanPaymentController;
use App\Models\Property;
use App\Models\Plan;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

echo "=== Testing Amount Multiplication ===\n\n";

// Clean up any previous test data
Payment::where('customer_email', 'test@example.com')->delete();

// Get or create test property
$property = Property::first();
if (!$property) {
    echo "❌ No properties found. Please ensure you have at least one property in the database.\n";
    exit;
}

// Get or create test plan
$plan = Plan::first();
if (!$plan) {
    echo "❌ No plans found. Please ensure you have at least one plan in the database.\n";
    exit;
}

echo "Using Property ID: {$property->id}\n";
echo "Using Plan ID: {$plan->id}\n\n";

// Test 1: Check paymentSuccess method stores amount * 3
echo "Test 1: Checking paymentSuccess method amount multiplication\n";
echo "----------------------------------------\n";

// Create session data for payment
Session::put('payment_data', [
    'plan_id' => $plan->id,
    'business_email' => 'business@example.com',
    'customer_email' => 'test@example.com',
    'customer_name' => 'Test User',
    'amount' => 100.00, // Original amount
    'currency' => 'LKR',
    'order_id' => 'TEST_ORDER_' . time(),
    'payment_method' => 'Credit Card',
    'gateway_transaction_id' => 'TXN_' . time()
]);

Session::put('property_id', $property->id);

// Create mock request
$request = new Request([
    'order_id' => Session::get('payment_data.order_id'),
    'transaction_id' => 'TXN_SUCCESS_' . time(),
    'status' => 'success'
]);

// Call paymentSuccess method
$controller = new PlanPaymentController();
try {
    $response = $controller->paymentSuccess($request);

    // Check if payment was created/updated with correct amount
    $payment = Payment::where('property_id', $property->id)->first();

    if ($payment) {
        echo "✅ Payment record found\n";
        echo "Original amount in session: " . Session::get('payment_data.amount') . "\n";
        echo "Amount stored in database: {$payment->amount}\n";

        $expectedAmount = Session::get('payment_data.amount') * 3;
        if ($payment->amount == $expectedAmount) {
            echo "✅ Amount correctly multiplied by 3 (Expected: {$expectedAmount}, Got: {$payment->amount})\n";
        } else {
            echo "❌ Amount NOT correctly multiplied by 3 (Expected: {$expectedAmount}, Got: {$payment->amount})\n";
        }
    } else {
        echo "❌ No payment record found\n";
    }
} catch (Exception $e) {
    echo "❌ Error in paymentSuccess: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 2: Check verifyPayment method doesn't change amount
echo "Test 2: Checking verifyPayment method doesn't change amount\n";
echo "--------------------------------------------------------\n";

if (isset($payment) && $payment) {
    $originalAmount = $payment->amount;
    echo "Amount before verification: {$originalAmount}\n";

    // Create verification request
    $verifyRequest = new Request([
        'transaction_id' => 'VERIFY_TXN_' . time()
    ]);

    try {
        $response = $controller->verifyPayment($verifyRequest, $payment);

        // Refresh payment from database
        $payment->refresh();

        echo "Amount after verification: {$payment->amount}\n";

        if ($payment->amount == $originalAmount) {
            echo "✅ Amount unchanged during verification (as expected)\n";
        } else {
            echo "❌ Amount was changed during verification (unexpected)\n";
        }
    } catch (Exception $e) {
        echo "❌ Error in verifyPayment: " . $e->getMessage() . "\n";
    }
} else {
    echo "❌ No payment record to verify\n";
}

echo "\n";

// Test 3: Test with different amounts
echo "Test 3: Testing with different amount values\n";
echo "-------------------------------------------\n";

$testAmounts = [50.00, 150.75, 200.00, 99.99];

foreach ($testAmounts as $testAmount) {
    echo "Testing with amount: {$testAmount}\n";

    // Clean up previous test payment
    Payment::where('property_id', $property->id)->delete();

    // Update session with new amount
    Session::put('payment_data.amount', $testAmount);
    Session::put('payment_data.order_id', 'TEST_ORDER_' . time() . '_' . $testAmount);

    // Create new request
    $request = new Request([
        'order_id' => Session::get('payment_data.order_id'),
        'transaction_id' => 'TXN_' . time() . '_' . $testAmount,
        'status' => 'success'
    ]);

    try {
        $response = $controller->paymentSuccess($request);

        $payment = Payment::where('property_id', $property->id)->first();
        if ($payment) {
            $expectedAmount = $testAmount * 3;
            if ($payment->amount == $expectedAmount) {
                echo "  ✅ {$testAmount} → {$payment->amount} (correctly multiplied by 3)\n";
            } else {
                echo "  ❌ {$testAmount} → {$payment->amount} (expected {$expectedAmount})\n";
            }
        } else {
            echo "  ❌ No payment record created\n";
        }
    } catch (Exception $e) {
        echo "  ❌ Error: " . $e->getMessage() . "\n";
    }
}

echo "\n=== Test Complete ===\n";

// Clean up test data
Payment::where('customer_email', 'test@example.com')->delete();
echo "Test data cleaned up.\n";
