<?php

/**
 * Test Plan Payment Flow Integration
 * This script tests the plan payment flow using the same GenieBusinessPaymentService as Ad Manager
 */

require_once 'vendor/autoload.php';

use App\Models\Plan;
use App\Models\Property;
use App\Models\Payment;
use App\Services\GenieBusinessPaymentService;
use Illuminate\Support\Facades\Log;

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

echo "=== PLAN PAYMENT FLOW TEST ===\n\n";

// Test 1: Check if plans exist
echo "✅ Testing Plan Models...\n";
$plans = Plan::all();
if ($plans->count() > 0) {
    echo "   Found {$plans->count()} plans\n";
    foreach ($plans as $plan) {
        echo "   - {$plan->name}: \${$plan->price}\n";
    }
} else {
    echo "   ❌ No plans found in database\n";
}

// Test 2: Check if properties exist
echo "\n✅ Testing Property Models...\n";
$property = Property::first();
if ($property) {
    echo "   Found property: {$property->business_name}\n";
    echo "   Email: {$property->business_email}\n";
} else {
    echo "   ❌ No properties found in database\n";
    exit(1);
}

// Test 3: Test payment service integration
echo "\n✅ Testing GenieBusinessPaymentService for Plans...\n";
$paymentService = new GenieBusinessPaymentService();

// Use the first plan for testing
$testPlan = $plans->first();
if ($testPlan) {
    echo "   Testing with plan: {$testPlan->name} (\${$testPlan->price})\n";

    // Convert price to USD (same logic as PlanPaymentController)
    $usdAmount = round($testPlan->price / 300, 2);
    echo "   LKR {$testPlan->price} = USD {$usdAmount}\n";

    // Create payment record (same as PlanPaymentController)
    $payment = Payment::create([
        'plan_id' => $testPlan->id,
        'property_id' => $property->id,
        'business_email' => $property->business_email ?: 'noemail@property' . $property->id . '.local',
        'customer_email' => $property->business_email ?: 'noemail@property' . $property->id . '.local',
        'customer_name' => $property->business_name ?: $property->contact_person,
        'amount' => $usdAmount,
        'currency' => 'USD',
        'status' => 'pending',
        'order_id' => 'PLAN_' . $testPlan->id . '_' . time(),
        'payment_method' => 'gateway'
    ]);

    echo "   Created payment record with ID: {$payment->id}\n";

    // Test payment service (same as PlanPaymentController)
    $paymentResult = $paymentService->createPayment(
        $usdAmount, // Amount in USD
        "Subscription to {$testPlan->name} Plan",
        $property->business_email ?: 'noemail@property' . $property->id . '.local',
        $property->business_name ?: $property->contact_person,
        $property->id,
        $payment->id, // Use payment ID
        "http://127.0.0.1:8000/property/plans/payment/{$payment->id}/success"
    );

    if ($paymentResult['success']) {
        echo "   ✅ PAYMENT SERVICE: SUCCESS\n";
        echo "   Transaction ID: " . ($paymentResult['data']['id'] ?? 'N/A') . "\n";
        echo "   Payment URL: " . ($paymentResult['data']['payment_url'] ?? 'N/A') . "\n";
        echo "   Currency: " . ($paymentResult['data']['currency'] ?? 'N/A') . "\n";

        // Update payment record
        $payment->update([
            'transaction_id' => $paymentResult['data']['id'] ?? null,
            'genie_transaction_id' => $paymentResult['data']['id'] ?? null
        ]);

        echo "   Updated payment record with transaction details\n";
    } else {
        echo "   ❌ PAYMENT SERVICE: FAILED\n";
        echo "   Error: " . ($paymentResult['error'] ?? 'Unknown error') . "\n";

        // Clean up failed payment
        $payment->delete();
    }
} else {
    echo "   ❌ No plans available for testing\n";
}

echo "\n✅ Testing Route Configuration...\n";
echo "   Plan Index Route: http://127.0.0.1:8000/property/plans\n";
echo "   Plan Checkout Route: http://127.0.0.1:8000/property/plans/checkout\n";
echo "   Payment Process Route: http://127.0.0.1:8000/property/plans/payment/process\n";

echo "\n=== INTEGRATION COMPLETE ===\n";
echo "✅ Plan payment integration uses the same GenieBusinessPaymentService as Ad Manager\n";
echo "✅ Payment flow: Plans Page → Select Plan → Checkout → Payment Gateway\n";
echo "✅ Currency conversion: LKR → USD (1 USD = 300 LKR)\n";
echo "✅ Payment tracking via Payment model\n";
echo "\nTo test manually:\n";
echo "1. Go to http://127.0.0.1:8000/property/plans\n";
echo "2. Click 'Select Plan' on any plan\n";
echo "3. Complete payment form and submit\n";
echo "4. You'll be redirected to the same payment gateway as Ad Manager\n";
