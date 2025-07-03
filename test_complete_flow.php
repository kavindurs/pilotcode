<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Create a mock request for ad creation
$request = Illuminate\Http\Request::create('/property/ads', 'POST', [
    'start_date' => '2025-07-05',
    'end_date' => '2025-07-09',
    '_token' => 'test_token'
]);

// Set property session
session(['property_id' => 2]);

use App\Http\Controllers\SimpleAdController;
use App\Models\Property;
use App\Models\AdminSetting;
use Carbon\Carbon;

echo "=== TESTING AD CREATION AND PAYMENT FLOW ===\n\n";

// Get property details
$property = Property::find(2);
echo "Property ID: {$property->id}\n";
echo "Business Name: {$property->business_name}\n";
echo "Email: " . ($property->email ?? 'No email set') . "\n\n";

// Calculate costs
$startDate = Carbon::parse('2025-07-05');
$endDate = Carbon::parse('2025-07-09');
$totalDays = $startDate->diffInDays($endDate) + 1;
$dailyCost = AdminSetting::getAdDailyCost();
$totalAmount = $totalDays * $dailyCost;

echo "Date Range: {$startDate->toDateString()} to {$endDate->toDateString()}\n";
echo "Total Days: {$totalDays}\n";
echo "Daily Cost: LKR {$dailyCost}\n";
echo "Total Amount: LKR {$totalAmount}\n";
echo "Amount for Payment Gateway (cents): " . ($totalAmount * 100) . "\n\n";

// Test payment service directly
use App\Services\GenieBusinessPaymentService;

$paymentService = new GenieBusinessPaymentService();

echo "=== TESTING PAYMENT SERVICE ===\n";

$paymentResult = $paymentService->createPayment(
    $totalAmount * 100, // Amount in cents
    "Ad Promotion for {$property->business_name} ({$totalDays} days)",
    $property->email ?: 'noemail@property' . $property->id . '.local',
    $property->business_name,
    $property->id,
    999, // Mock ad ID
    route('property.ads.payment.success', 999)
);

if ($paymentResult['success']) {
    echo "✅ Payment creation successful!\n";
    echo "Transaction ID: " . $paymentResult['data']['id'] . "\n";
    echo "Amount: " . $paymentResult['data']['amount'] . " cents\n";
    echo "Currency: " . $paymentResult['data']['currency'] . "\n";
    echo "Is Sandbox: " . ($paymentResult['data']['sandbox'] ? 'Yes' : 'No') . "\n";

    if (isset($paymentResult['data']['payment_url'])) {
        echo "Payment URL: " . $paymentResult['data']['payment_url'] . "\n";

        // Check if this is a sandbox redirect URL
        if (strpos($paymentResult['data']['payment_url'], 'sandbox=true') !== false) {
            echo "✅ This would redirect to sandbox payment success page\n";
        } else {
            echo "✅ This would redirect to actual payment gateway\n";
        }
    }
} else {
    echo "❌ Payment creation failed!\n";
    echo "Error: " . $paymentResult['error'] . "\n";
}

echo "\n=== ENVIRONMENT INFO ===\n";
echo "Genie Business Environment: " . config('genie_business.environment') . "\n";
echo "Payment Gateway URL: " . config('genie_business.api_url') . "\n";
echo "App URL: " . config('app.url') . "\n";

echo "\n=== PAYMENT FLOW SUMMARY ===\n";
echo "1. User fills form at: http://127.0.0.1:8000/property/ads/create\n";
echo "2. Form submits to: property.ads.store route\n";
echo "3. Ad created with payment_pending status\n";
echo "4. Payment service creates payment request\n";
echo "5. User redirected to payment gateway or sandbox\n";
echo "6. After payment, user returns to success page\n";
echo "7. Ad status updated to 'pending' for admin review\n";

$kernel->terminate($request, new Illuminate\Http\Response());
