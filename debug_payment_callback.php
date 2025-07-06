<?php
// Test script to debug payment callback handling

require_once 'vendor/autoload.php';
require_once 'bootstrap/app.php';

use App\Models\Ad;
use App\Services\GenieBusinessPaymentService;

echo "=== Payment Callback Debug Test ===\n\n";

// Find a recent ad with payment_pending status
$ad = Ad::where('status', 'payment_pending')
         ->whereNotNull('payment_intent_id')
         ->orderBy('created_at', 'desc')
         ->first();

if (!$ad) {
    echo "❌ No ads found with payment_pending status\n";
    echo "Creating a test ad...\n";

    // Create a test ad for debugging
    $property = \App\Models\Property::first();
    if (!$property) {
        echo "❌ No properties found in database\n";
        exit(1);
    }

    $ad = Ad::create([
        'property_id' => $property->id,
        'title' => 'Test Ad for Payment Debug',
        'description' => 'Test ad for debugging payment callback',
        'promotion_days' => 7,
        'total_amount' => 700,
        'status' => 'payment_pending',
        'payment_status' => 'pending',
        'payment_intent_id' => '686a9d99a73596000951a521' // Use the transaction ID from your example
    ]);

    echo "✅ Created test ad with ID: {$ad->id}\n\n";
}

echo "=== Ad Information ===\n";
echo "Ad ID: {$ad->id}\n";
echo "Status: {$ad->status}\n";
echo "Payment Status: {$ad->payment_status}\n";
echo "Payment Intent ID: {$ad->payment_intent_id}\n";
echo "Total Amount: {$ad->total_amount}\n\n";

// Test payment verification
echo "=== Testing Payment Verification ===\n";
$paymentService = new GenieBusinessPaymentService();

if ($ad->payment_intent_id) {
    echo "Verifying payment with transaction ID: {$ad->payment_intent_id}\n";

    $paymentResult = $paymentService->verifyPayment($ad->payment_intent_id);

    echo "Verification Success: " . ($paymentResult['success'] ? 'YES' : 'NO') . "\n";

    if ($paymentResult['success']) {
        echo "Payment Data:\n";
        print_r($paymentResult['data']);

        $paymentStatus = $paymentResult['data']['status'] ?? 'unknown';
        echo "\nPayment Status: {$paymentStatus}\n";

        if (in_array($paymentStatus, ['completed', 'success', 'confirmed', 'paid'])) {
            echo "✅ Payment status indicates successful payment\n";

            // Simulate updating the ad
            echo "\nSimulating ad status update...\n";
            $ad->update([
                'payment_status' => 'paid',
                'paid_at' => now(),
                'status' => 'pending',
                'payment_notes' => json_encode($paymentResult['data'])
            ]);

            echo "✅ Ad status updated successfully!\n";
            echo "New Status: {$ad->fresh()->status}\n";
            echo "New Payment Status: {$ad->fresh()->payment_status}\n";
        } else {
            echo "⚠️ Payment status '{$paymentStatus}' does not indicate successful payment\n";
        }
    } else {
        echo "❌ Payment verification failed\n";
        echo "Error: " . ($paymentResult['error'] ?? 'Unknown error') . "\n";
    }
} else {
    echo "❌ No payment intent ID found\n";
}

echo "\n=== Test Complete ===\n";
