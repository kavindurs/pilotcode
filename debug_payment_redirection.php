<?php
// Debug the ad creation and payment redirection issue
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Services\GenieBusinessPaymentService;
use App\Models\Property;
use App\Models\Ad;

echo "=== Debugging Ad Creation and Payment Redirection ===\n\n";

// First, let's create a test property login session
session(['property_id' => 2]); // Using property ID 2 (COS)
echo "✅ Set property session to ID: 2\n\n";

// Get the property
$property = Property::find(2);
if (!$property) {
    echo "❌ Property not found!\n";
    exit(1);
}
echo "✅ Found property: {$property->business_name}\n\n";

// Test the payment service directly
echo "=== Testing Payment Service Directly ===\n";
$paymentService = new GenieBusinessPaymentService();
$testAmount = 27.00; // $27 test
$testAdId = 999;

$paymentResult = $paymentService->createPayment(
    $testAmount * 100, // Convert to cents
    "Test Ad Promotion Payment",
    $property->email ?: 'test@example.com',
    $property->business_name,
    $property->id,
    $testAdId,
    route('property.ads.payment.success', $testAdId)
);

echo "Payment Result:\n";
echo "Success: " . ($paymentResult['success'] ? 'YES' : 'NO') . "\n";
if ($paymentResult['success']) {
    echo "Payment ID: " . ($paymentResult['data']['id'] ?? 'N/A') . "\n";
    echo "Payment URL: " . ($paymentResult['data']['payment_url'] ?? 'N/A') . "\n";
    echo "Sandbox: " . (isset($paymentResult['data']['sandbox']) && $paymentResult['data']['sandbox'] ? 'YES' : 'NO') . "\n";
} else {
    echo "Error: " . ($paymentResult['error'] ?? 'Unknown') . "\n";
}

echo "\n=== Testing Ad Creation Flow ===\n";

// Simulate the ad creation process
$validated = [
    'start_date' => '2025-07-07',
    'end_date' => '2025-07-09'
];

$startDate = \Carbon\Carbon::parse($validated['start_date']);
$endDate = \Carbon\Carbon::parse($validated['end_date']);
$totalDays = $startDate->diffInDays($endDate) + 1;
$dailyCost = 9.00; // From admin settings
$totalAmount = $totalDays * $dailyCost;

echo "Calculated costs:\n";
echo "  Start Date: {$validated['start_date']}\n";
echo "  End Date: {$validated['end_date']}\n";
echo "  Total Days: $totalDays\n";
echo "  Daily Cost: \$$dailyCost\n";
echo "  Total Amount: \$$totalAmount\n\n";

// Check for overlapping ads (should be none since table is empty)
$overlappingAds = Ad::where('property_id', $property->id)
    ->whereIn('status', ['active', 'approved'])
    ->where(function ($query) use ($validated) {
        $query->whereBetween('start_date', [$validated['start_date'], $validated['end_date']])
              ->orWhereBetween('end_date', [$validated['start_date'], $validated['end_date']])
              ->orWhere(function ($q) use ($validated) {
                  $q->where('start_date', '<=', $validated['start_date'])
                    ->where('end_date', '>=', $validated['end_date']);
              });
    })->exists();

echo "Overlapping ads check: " . ($overlappingAds ? 'CONFLICT FOUND' : 'NO CONFLICTS') . "\n\n";

// Create the ad record
echo "Creating ad record...\n";
$ad = Ad::create([
    'property_id' => $property->id,
    'start_date' => $validated['start_date'],
    'end_date' => $validated['end_date'],
    'amount' => $totalAmount,
    'days' => $totalDays,
    'total_amount' => $totalAmount,
    'total_days' => $totalDays,
    'daily_rate' => $dailyCost,
    'payment_status' => 'pending',
    'status' => 'payment_pending'
]);

echo "✅ Ad created with ID: {$ad->id}\n\n";

// Create payment request
echo "Creating payment request...\n";
$paymentResult = $paymentService->createPayment(
    $totalAmount * 100, // Convert to cents
    "Ad Promotion for {$property->business_name} ({$totalDays} days)",
    $property->email ?: 'noemail@example.com',
    $property->business_name,
    $property->id,
    $ad->id,
    route('property.ads.payment.success', $ad->id)
);

if ($paymentResult['success']) {
    echo "✅ Payment creation successful!\n";

    // Update ad with payment details
    $ad->update([
        'payment_intent_id' => $paymentResult['data']['id'] ?? null,
        'payment_notes' => json_encode($paymentResult['data'])
    ]);

    echo "✅ Ad updated with payment details\n";

    // Check if payment URL exists
    if (isset($paymentResult['data']['payment_url'])) {
        $paymentUrl = $paymentResult['data']['payment_url'];
        echo "✅ Payment URL found: $paymentUrl\n";

        // Test if the URL is accessible
        echo "\nTesting payment URL accessibility...\n";
        try {
            $response = \Illuminate\Support\Facades\Http::get($paymentUrl);
            echo "URL Response Status: " . $response->status() . "\n";
            if ($response->successful()) {
                echo "✅ Payment URL is accessible!\n";
            } else {
                echo "⚠️ Payment URL returned status: " . $response->status() . "\n";
            }
        } catch (Exception $e) {
            echo "❌ Error accessing payment URL: " . $e->getMessage() . "\n";
        }

        echo "\n=== ISSUE DIAGNOSIS ===\n";
        echo "The payment system is working correctly.\n";
        echo "The payment URL is being generated: $paymentUrl\n";
        echo "The issue might be in the controller's redirect logic.\n";
        echo "\nRecommendation: Check if there's a JavaScript error preventing form submission,\n";
        echo "or if the redirect is being intercepted by browser security policies.\n";

    } else {
        echo "❌ No payment URL in response!\n";
        echo "Response data: " . json_encode($paymentResult['data'], JSON_PRETTY_PRINT) . "\n";
    }
} else {
    echo "❌ Payment creation failed: " . ($paymentResult['error'] ?? 'Unknown error') . "\n";
    echo "This would cause the ad to be deleted in the controller.\n";
}

echo "\n=== Final Ad Status ===\n";
$finalAd = Ad::find($ad->id);
if ($finalAd) {
    echo "Ad ID: {$finalAd->id}\n";
    echo "Status: {$finalAd->status}\n";
    echo "Payment Status: {$finalAd->payment_status}\n";
    echo "Payment Intent ID: " . ($finalAd->payment_intent_id ?? 'N/A') . "\n";
} else {
    echo "❌ Ad was deleted!\n";
}
