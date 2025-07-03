<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$request = Illuminate\Http\Request::capture();

$response = $kernel->handle($request);

// Test the payment service
use App\Services\GenieBusinessPaymentService;
use App\Models\Ad;

// Get the test ad
$ad = Ad::find(28);
if (!$ad) {
    echo "Test ad not found\n";
    exit;
}

$property = $ad->property;

echo "Testing payment flow for Ad ID: {$ad->id}\n";
echo "Property ID: {$property->id}\n";
echo "Business Name: " . ($property->business_name ?? 'N/A') . "\n";
echo "Current Status: {$ad->status}\n";
echo "Payment Status: {$ad->payment_status}\n\n";

// Initialize payment service
$paymentService = new GenieBusinessPaymentService();

// Test payment creation
echo "Creating payment...\n";
$paymentResult = $paymentService->createPayment(
    1000, // Amount in cents (LKR 10.00)
    'Property Ad Promotion - ' . ($property->business_name ?? 'Property #' . $property->id),
    $property->email ?? 'noemail@property' . $property->id . '.local',
    $property->business_name ?? $property->contact_person ?? 'Property Owner #' . $property->id,
    $ad->property_id,
    $ad->id,
    route('property.ads.payment.success', $ad->id)
);

echo "Payment Result:\n";
print_r($paymentResult);

if ($paymentResult['success']) {
    echo "\nPayment created successfully!\n";
    echo "Transaction ID: " . ($paymentResult['data']['id'] ?? 'N/A') . "\n";
    echo "Is Sandbox: " . (($paymentResult['data']['sandbox'] ?? false) ? 'Yes' : 'No') . "\n";

    if (isset($paymentResult['data']['payment_url'])) {
        echo "Payment URL: " . $paymentResult['data']['payment_url'] . "\n";
    }
} else {
    echo "\nPayment creation failed!\n";
    echo "Error: " . ($paymentResult['error'] ?? 'Unknown error') . "\n";
}

$kernel->terminate($request, $response);
