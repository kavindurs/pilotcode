<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$request = Illuminate\Http\Request::capture();

$response = $kernel->handle($request);

use App\Services\GenieBusinessPaymentService;
use App\Models\Property;
use App\Models\AdminSetting;

echo "=== DEBUGGING PAYMENT FLOW ===\n\n";

// Get property
$property = Property::find(2); // Your test property

if (!$property) {
    echo "❌ Property not found\n";
    exit;
}

echo "✅ Property Found:\n";
echo "   ID: {$property->id}\n";
echo "   Business Name: {$property->business_name}\n";
echo "   Email: {$property->business_email}\n\n";

// Calculate costs (same as in store method)
$totalDays = 5;
$dailyCost = AdminSetting::getAdDailyCost();
$totalAmount = $totalDays * $dailyCost;

echo "✅ Cost Calculation:\n";
echo "   Daily Cost: LKR {$dailyCost}\n";
echo "   Total Days: {$totalDays}\n";
echo "   Total Amount: LKR {$totalAmount}\n";
echo "   Payment Amount (cents): " . ($totalAmount * 100) . "\n\n";

// Test payment service (exact same call as in store method)
$paymentService = new GenieBusinessPaymentService();

echo "=== TESTING PAYMENT SERVICE (SAME AS STORE METHOD) ===\n";

$paymentResult = $paymentService->createPayment(
    $totalAmount * 100, // Convert to cents for payment gateway
    "Ad Promotion for {$property->business_name} ({$totalDays} days)",
    $property->business_email ?: 'noemail@example.com',
    $property->business_name,
    $property->id,
    999, // Mock ad ID
    route('property.ads.payment.success', 999)
);

echo "Payment Result:\n";
var_dump($paymentResult);

if ($paymentResult['success']) {
    echo "\n✅ Payment creation successful!\n";

    if (isset($paymentResult['data']['payment_url'])) {
        echo "✅ Payment URL exists: " . $paymentResult['data']['payment_url'] . "\n";

        // Check if URL is valid
        if (filter_var($paymentResult['data']['payment_url'], FILTER_VALIDATE_URL)) {
            echo "✅ Payment URL is valid\n";
            echo "🔄 WOULD REDIRECT TO: " . $paymentResult['data']['payment_url'] . "\n";
        } else {
            echo "❌ Payment URL is MALFORMED: " . $paymentResult['data']['payment_url'] . "\n";
            echo "This is why the redirect isn't working!\n";
        }
    } else {
        echo "❌ No payment_url in response\n";
        echo "Would redirect to manual payment page instead\n";
    }
} else {
    echo "❌ Payment creation failed: " . $paymentResult['error'] . "\n";
}

echo "\n=== ANALYZING THE ISSUE ===\n";
echo "Expected flow in store method:\n";
echo "1. Create ad with payment_pending status ✅\n";
echo "2. Call payment service ✅\n";
echo "3. Check if payment_url exists in response\n";
echo "4. Redirect to payment_url\n";
echo "5. If URL is malformed, redirect fails and falls through\n";

$kernel->terminate($request, $response);
