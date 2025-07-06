<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$request = Illuminate\Http\Request::capture();

$response = $kernel->handle($request);

use App\Services\GenieBusinessPaymentService;
use App\Models\Property;
use App\Models\AdminSetting;

echo "=== TESTING COMPLETE GENIE BUSINESS INTEGRATION ===\n\n";

// Get property for testing
$property = Property::find(2);

if (!$property) {
    echo "❌ Property not found\n";
    exit;
}

echo "✅ Property Details:\n";
echo "   ID: {$property->id}\n";
echo "   Business Name: {$property->business_name}\n";
echo "   Email: {$property->business_email}\n\n";

// Calculate costs
$totalDays = 5;
$dailyCost = AdminSetting::getAdDailyCost();
$totalAmount = $totalDays * $dailyCost;

echo "✅ Payment Calculation:\n";
echo "   Daily Cost: LKR {$dailyCost}\n";
echo "   Total Days: {$totalDays}\n";
echo "   Total Amount: LKR {$totalAmount}\n";
echo "   Payment Gateway Amount (cents): " . ($totalAmount * 100) . "\n\n";

// Test the new Genie Business service
echo "=== TESTING NEW GENIE BUSINESS SERVICE ===\n";

$paymentService = new GenieBusinessPaymentService();

$paymentResult = $paymentService->createPayment(
    $totalAmount * 100, // Convert to cents
    "Ad Promotion for {$property->business_name} ({$totalDays} days)",
    $property->business_email ?: 'noemail@example.com',
    $property->business_name,
    $property->id,
    999, // Mock ad ID
    route('property.ads.payment.success', 999)
);

if ($paymentResult['success']) {
    echo "✅ PAYMENT CREATION: SUCCESS\n";
    echo "   Transaction ID: " . $paymentResult['data']['id'] . "\n";
    echo "   Payment URL: " . $paymentResult['data']['payment_url'] . "\n";
    echo "   Amount: " . $paymentResult['data']['amount'] . " cents\n";
    echo "   Currency: " . $paymentResult['data']['currency'] . "\n";

    if (isset($paymentResult['data']['sandbox']) && $paymentResult['data']['sandbox']) {
        echo "   Environment: SANDBOX\n\n";
        echo "✅ EXPECTED FLOW:\n";
        echo "   1. User clicks 'Pay & Submit Request'\n";
        echo "   2. Ad created with payment_pending status\n";
        echo "   3. Redirects to: " . $paymentResult['data']['payment_url'] . "\n";
        echo "   4. Sandbox payment automatically completes\n";
        echo "   5. Ad status updates to 'pending' for admin review\n";
    } else {
        echo "   Environment: PRODUCTION\n";
        echo "   Would redirect to real Genie Business payment gateway\n";
    }
} else {
    echo "❌ PAYMENT CREATION: FAILED\n";
    echo "   Error: " . $paymentResult['error'] . "\n";
}

echo "\n=== CONFIGURATION CHECK ===\n";
echo "API URL: " . config('genie_business.api_url') . "\n";
echo "App ID: " . config('genie_business.app_id') . "\n";
echo "Environment: " . config('genie_business.environment') . "\n";
echo "Base URL: " . config('app.url') . "\n";

echo "\n=== USER INSTRUCTIONS ===\n";
echo "1. Login at: http://127.0.0.1:8000/property/login\n";
echo "   Email: kavindurs8@gmail.com\n";
echo "   Password: password\n\n";
echo "2. Visit: http://127.0.0.1:8000/property/ads/create\n\n";
echo "3. Fill the form with any start/end dates\n\n";
echo "4. Click 'Pay & Submit Request'\n\n";
echo "5. You should be redirected to the payment gateway!\n";

$kernel->terminate($request, $response);
