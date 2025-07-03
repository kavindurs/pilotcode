<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$request = Illuminate\Http\Request::capture();

$response = $kernel->handle($request);

use App\Services\GenieBusinessPaymentService;
use App\Models\Property;
use App\Models\AdminSetting;
use Carbon\Carbon;

echo "=== TESTING PAYMENT FLOW FOR AD CREATION ===\n\n";

// Get property for testing
$property = Property::where('status', 'Approved')->first();

if (!$property) {
    echo "❌ No approved properties found for testing.\n";
    exit;
}

echo "✅ Test Property Found:\n";
echo "   ID: {$property->id}\n";
echo "   Business Name: {$property->business_name}\n";
echo "   Email: " . ($property->email ?? 'No email') . "\n\n";

// Calculate costs for a 5-day ad
$totalDays = 5;
$dailyCost = AdminSetting::getAdDailyCost();
$totalAmount = $totalDays * $dailyCost;

echo "✅ Cost Calculation:\n";
echo "   Daily Cost: LKR {$dailyCost}\n";
echo "   Total Days: {$totalDays}\n";
echo "   Total Amount: LKR {$totalAmount}\n";
echo "   Payment Gateway Amount (cents): " . ($totalAmount * 100) . "\n\n";

// Test payment service
echo "=== TESTING PAYMENT SERVICE ===\n";

$paymentService = new GenieBusinessPaymentService();

$paymentResult = $paymentService->createPayment(
    $totalAmount * 100, // Convert to cents
    "Ad Promotion for {$property->business_name} ({$totalDays} days)",
    $property->email ?: 'noemail@property' . $property->id . '.local',
    $property->business_name,
    $property->id,
    999, // Mock ad ID for testing
    route('property.ads.payment.success', 999)
);

if ($paymentResult['success']) {
    echo "✅ Payment Creation: SUCCESS\n";
    echo "   Transaction ID: " . $paymentResult['data']['id'] . "\n";
    echo "   Amount: " . $paymentResult['data']['amount'] . " cents\n";
    echo "   Currency: " . $paymentResult['data']['currency'] . "\n";
    echo "   Environment: " . ($paymentResult['data']['sandbox'] ? 'Sandbox' : 'Production') . "\n";

    if (isset($paymentResult['data']['payment_url'])) {
        echo "   Payment URL: " . $paymentResult['data']['payment_url'] . "\n\n";

        // Analyze the payment URL
        if (strpos($paymentResult['data']['payment_url'], 'sandbox=true') !== false) {
            echo "✅ SANDBOX MODE: Payment would redirect to success page immediately\n";
            echo "   This simulates successful payment completion\n";
        } else {
            echo "✅ PRODUCTION MODE: Payment would redirect to actual gateway\n";
        }
    }
} else {
    echo "❌ Payment Creation: FAILED\n";
    echo "   Error: " . $paymentResult['error'] . "\n";
}

echo "\n=== CONFIGURATION INFO ===\n";
echo "Environment: " . config('genie_business.environment') . "\n";
echo "API URL: " . config('genie_business.api_url') . "\n";
echo "App URL: " . config('app.url') . "\n";
echo "Currency: " . config('genie_business.currency') . "\n";

echo "\n=== EXPECTED USER FLOW ===\n";
echo "1. User visits: http://127.0.0.1:8000/property/ads/create\n";
echo "2. User fills form with start/end dates\n";
echo "3. User clicks 'Pay & Submit Request'\n";
echo "4. Form submits to SimpleAdController@store\n";
echo "5. Ad created with payment_pending status\n";
echo "6. Payment service creates transaction\n";
echo "7. User redirected to payment gateway/sandbox\n";
echo "8. After payment completion, user returns to success page\n";
echo "9. Ad status updated to 'pending' for admin approval\n";

$kernel->terminate($request, $response);
