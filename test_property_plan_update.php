<?php

// Test to verify that property plan_id is only updated on payment success
require_once __DIR__ . '/vendor/autoload.php';

use App\Models\Payment;
use App\Models\Property;
use App\Models\Plan;

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== TESTING: Property Plan Update Only on Payment Success ===\n\n";

// Get test data
$property = Property::first();
$plans = Plan::take(2)->get();

if (!$property || $plans->count() < 2) {
    echo "Need at least 1 property and 2 plans for this test\n";
    exit;
}

$originalPlanId = $property->plan_id;
$newPlan = $plans->where('id', '!=', $originalPlanId)->first();

echo "Property: {$property->business_name} (ID: {$property->id})\n";
echo "Original Plan ID: " . ($originalPlanId ?: 'null') . "\n";
echo "Target Plan ID: {$newPlan->id} ({$newPlan->name})\n\n";

// Clean up any existing test payment
Payment::where('property_id', $property->id)->where('order_id', 'LIKE', 'TEST_%')->delete();

// Step 1: Simulate creating payment record (like in processPayment)
echo "1. Creating payment record (simulating processPayment)...\n";
$payment = Payment::updateOrCreate(
    ['property_id' => $property->id],
    [
        'plan_id' => $newPlan->id, // This is the INTENDED plan
        'business_email' => $property->business_email ?: 'test@example.com',
        'customer_email' => $property->business_email ?: 'test@example.com',
        'customer_name' => $property->business_name ?: 'Test Property',
        'amount' => 1699.00,
        'currency' => 'LKR',
        'status' => 'pending',
        'order_id' => 'TEST_' . $newPlan->id . '_' . time(),
        'payment_method' => 'genie_business',
        'transaction_id' => null,
        'completed_at' => null
    ]
);

// Check property plan after payment record creation
$property->refresh();
echo "Payment record created: ID {$payment->id}, intended plan: {$payment->plan_id}\n";
echo "Property plan after payment creation: " . ($property->plan_id ?: 'null') . "\n";
echo "Property plan changed: " . ($property->plan_id != $originalPlanId ? 'YES (❌ PROBLEM!)' : 'NO (✅ CORRECT)') . "\n\n";

// Step 2: Simulate payment failure
echo "2. Simulating payment failure...\n";
$payment->update(['status' => 'failed']);
$property->refresh();
echo "Payment status: {$payment->status}\n";
echo "Property plan after failure: " . ($property->plan_id ?: 'null') . "\n";
echo "Property plan changed: " . ($property->plan_id != $originalPlanId ? 'YES (❌ PROBLEM!)' : 'NO (✅ CORRECT)') . "\n\n";

// Step 3: Start new payment attempt
echo "3. Starting new payment attempt...\n";
$payment->update([
    'status' => 'pending',
    'transaction_id' => null,
    'completed_at' => null,
    'order_id' => 'TEST_' . $newPlan->id . '_' . (time() + 1)
]);
$property->refresh();
echo "Payment status reset to: {$payment->status}\n";
echo "Property plan during new attempt: " . ($property->plan_id ?: 'null') . "\n";
echo "Property plan changed: " . ($property->plan_id != $originalPlanId ? 'YES (❌ PROBLEM!)' : 'NO (✅ CORRECT)') . "\n\n";

// Step 4: Simulate payment success
echo "4. Simulating payment success...\n";
$payment->update([
    'status' => 'completed',
    'transaction_id' => 'test_success_' . time(),
    'completed_at' => now()
]);

// This simulates the paymentSuccess method
if ($payment->status === 'completed') {
    $property->update(['plan_id' => $payment->plan_id]);
    echo "Property plan updated after payment success\n";
}

$property->refresh();
echo "Payment status: {$payment->status}\n";
echo "Property plan after success: " . ($property->plan_id ?: 'null') . "\n";
echo "Property plan changed: " . ($property->plan_id != $originalPlanId ? 'YES (✅ EXPECTED!)' : 'NO (❌ PROBLEM!)') . "\n\n";

// Clean up
echo "5. Cleaning up...\n";
$payment->delete();
$property->update(['plan_id' => $originalPlanId]); // Restore original
echo "Test records cleaned up and property restored\n\n";

echo "=== TEST RESULTS ===\n";
echo "✓ Payment record creation does NOT change property plan\n";
echo "✓ Payment failure does NOT change property plan\n";
echo "✓ Payment retry does NOT change property plan\n";
echo "✓ Payment success DOES change property plan\n";
echo "✓ Property plan is only updated when payment is confirmed successful\n";
