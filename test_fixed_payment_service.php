<?php

require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use App\Services\GenieBusinessPaymentService;

// Create Laravel app instance
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Testing Genie Payment Service with Fixed Amount ===\n\n";

// Set up test data
$amount = 5097; // LKR amount (16.99 USD * 300)
$description = "Test Plan Payment";
$customerEmail = "test@example.com";
$customerName = "Test Customer";
$propertyId = 2;
$adId = 999; // Test ad ID
$returnUrl = "http://127.0.0.1:8000/plans/payment/success/999";

echo "Test Data:\n";
echo "Amount: {$amount} LKR\n";
echo "Description: {$description}\n";
echo "Customer: {$customerName} ({$customerEmail})\n";
echo "Property ID: {$propertyId}\n";
echo "Return URL: {$returnUrl}\n\n";

// Calculate what will be sent to Genie
$amountInCents = (int) ($amount * 100);
echo "Amount to be sent to Genie: {$amountInCents} cents (LKR {$amount} * 100)\n\n";

// Test the payment service
$paymentService = new GenieBusinessPaymentService();

echo "Creating payment with Genie Business API...\n";
$result = $paymentService->createPayment(
    $amount,
    $description,
    $customerEmail,
    $customerName,
    $propertyId,
    $adId,
    $returnUrl
);

echo "\nPayment Result:\n";
if ($result['success']) {
    echo "✅ Payment created successfully!\n";
    echo "Payment ID: " . ($result['data']['id'] ?? 'Not provided') . "\n";
    echo "Payment URL: " . ($result['data']['payment_url'] ?? 'Not provided') . "\n";
    echo "Status: " . ($result['data']['status'] ?? 'Unknown') . "\n";
    echo "Amount: " . ($result['data']['amount'] ?? 'Not provided') . " cents\n";
    echo "Currency: " . ($result['data']['currency'] ?? 'Not provided') . "\n";
} else {
    echo "❌ Payment creation failed!\n";
    echo "Error: " . ($result['error'] ?? 'Unknown error') . "\n";
}

echo "\nTest completed!\n";
