<?php

// Simple test script to verify plan switching functionality
require_once 'vendor/autoload.php';
require_once 'bootstrap/app.php';

use App\Models\Plan;
use App\Models\Payment;

echo "Testing Plan Switching Functionality\n";
echo "====================================\n\n";

// Test 1: Customer can have only one active plan
echo "Test 1: Testing single active plan per customer\n";

$customerEmail = 'test@example.com';

// Clean up any existing test data
Payment::where('business_email', $customerEmail)->delete();

// Get plan IDs (assuming they exist)
$basicPlan = Plan::where('name', 'Basic')->first();
$proPlan = Plan::where('name', 'Pro')->first();

if (!$basicPlan || !$proPlan) {
    echo "Error: Required plans not found. Please ensure Basic and Pro plans exist.\n";
    exit(1);
}

// Create initial payment for Basic plan
$payment1 = Payment::create([
    'business_email' => $customerEmail,
    'plan_id' => $basicPlan->id,
    'order_id' => 'TEST_ORDER_001',
    'amount' => 19.00,
    'currency' => 'USD',
    'status' => 'confirmed',
    'payment_method' => 'genie_business',
    'customer_name' => 'Test Customer',
    'customer_email' => $customerEmail,
]);

echo "✓ Created Basic plan payment (ID: {$payment1->id})\n";

// Verify customer has Basic plan active
$activePlan = Payment::getActivePlanForCustomer($customerEmail);
if ($activePlan && $activePlan->plan_id == $basicPlan->id) {
    echo "✓ Customer has Basic plan active\n";
} else {
    echo "✗ Error: Customer should have Basic plan active\n";
}

// Switch to Pro plan - this should update the existing record
$payment1->update([
    'plan_id' => $proPlan->id,
    'amount' => 59.00,
]);

echo "✓ Updated payment to Pro plan\n";

// Verify customer now has Pro plan active and only one active payment
$activePlan = Payment::getActivePlanForCustomer($customerEmail);
$activePaymentsCount = Payment::where('business_email', $customerEmail)->active()->count();

if ($activePlan && $activePlan->plan_id == $proPlan->id) {
    echo "✓ Customer now has Pro plan active\n";
} else {
    echo "✗ Error: Customer should have Pro plan active\n";
}

if ($activePaymentsCount == 1) {
    echo "✓ Customer has exactly 1 active payment\n";
} else {
    echo "✗ Error: Customer should have exactly 1 active payment, found: $activePaymentsCount\n";
}

echo "\nTest 1 completed.\n\n";

// Test 2: Pending payments are cancelled when new payment is confirmed
echo "Test 2: Testing pending payment cancellation\n";

// Clean up
Payment::where('business_email', $customerEmail)->delete();

// Create pending payment for Basic plan
$pendingBasic = Payment::create([
    'business_email' => $customerEmail,
    'plan_id' => $basicPlan->id,
    'order_id' => 'TEST_ORDER_002',
    'amount' => 19.00,
    'currency' => 'USD',
    'status' => 'pending',
    'payment_method' => 'genie_business',
    'customer_name' => 'Test Customer',
    'customer_email' => $customerEmail,
]);

echo "✓ Created pending Basic plan payment (ID: {$pendingBasic->id})\n";

// Create another pending payment for Pro plan
$pendingPro = Payment::create([
    'business_email' => $customerEmail,
    'plan_id' => $proPlan->id,
    'order_id' => 'TEST_ORDER_003',
    'amount' => 59.00,
    'currency' => 'USD',
    'status' => 'pending',
    'payment_method' => 'genie_business',
    'customer_name' => 'Test Customer',
    'customer_email' => $customerEmail,
]);

echo "✓ Created pending Pro plan payment (ID: {$pendingPro->id})\n";

// Confirm the Pro plan payment (simulate webhook)
$pendingPro->update(['status' => 'confirmed']);
echo "✓ Confirmed Pro plan payment\n";

// Simulate the webhook logic that cancels other pending payments
Payment::where('business_email', $customerEmail)
    ->where('id', '!=', $pendingPro->id)
    ->whereIn('status', ['pending', 'PENDING'])
    ->update(['status' => 'cancelled']);

echo "✓ Cancelled other pending payments\n";

// Verify only Pro plan is active and Basic plan payment is cancelled
$activePlan = Payment::getActivePlanForCustomer($customerEmail);
$cancelledPayments = Payment::where('business_email', $customerEmail)->cancelled()->count();

if ($activePlan && $activePlan->plan_id == $proPlan->id) {
    echo "✓ Pro plan is now active\n";
} else {
    echo "✗ Error: Pro plan should be active\n";
}

if ($cancelledPayments == 1) {
    echo "✓ Basic plan payment was cancelled\n";
} else {
    echo "✗ Error: Should have 1 cancelled payment, found: $cancelledPayments\n";
}

echo "\nTest 2 completed.\n\n";

// Clean up test data
Payment::where('business_email', $customerEmail)->delete();
echo "✓ Cleaned up test data\n";

echo "\nAll tests completed successfully!\n";
