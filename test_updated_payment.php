<?php
// Test the updated payment gateway integration for ads manager
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Services\GenieBusinessPaymentService;
use App\Models\Property;

echo "=== Testing Updated Payment Gateway Integration ===\n\n";

// Test 1: Check Environment Variables
echo "1. Checking environment variables...\n";
$config = [
    'GENIE_BUSINESS_APP_ID' => env('GENIE_BUSINESS_APP_ID'),
    'GENIE_BUSINESS_APP_KEY' => env('GENIE_BUSINESS_APP_KEY'),
    'GENIE_BUSINESS_API_URL' => env('GENIE_BUSINESS_API_URL'),
    'GENIE_BUSINESS_ENVIRONMENT' => env('GENIE_BUSINESS_ENVIRONMENT'),
];

foreach ($config as $key => $value) {
    echo "   $key: " . ($value ? substr($value, 0, 50) . (strlen($value) > 50 ? '...' : '') : 'NOT SET') . "\n";
}

if (empty($config['GENIE_BUSINESS_APP_ID']) || empty($config['GENIE_BUSINESS_APP_KEY'])) {
    echo "❌ Environment variables not set correctly!\n";
    exit(1);
}

echo "✅ Environment variables configured\n\n";

// Test 2: Initialize Payment Service
echo "2. Testing payment service initialization...\n";
try {
    $paymentService = new GenieBusinessPaymentService();
    echo "✅ Payment service initialized successfully\n\n";
} catch (Exception $e) {
    echo "❌ Payment service initialization failed: " . $e->getMessage() . "\n";
    exit(1);
}

// Test 3: Find a property for testing
echo "3. Finding test property...\n";
$property = Property::first();
if (!$property) {
    echo "❌ No properties found!\n";
    exit(1);
}

echo "✅ Found property: {$property->business_name} (ID: {$property->id})\n";
echo "   Email: " . ($property->business_email ?: 'No email set') . "\n\n";

// Test 4: Test payment creation
echo "4. Testing payment creation...\n";
$testAmount = 15.00; // $15 test amount
$testDescription = "Test Ad Promotion - Updated Integration";

try {
    $paymentResult = $paymentService->createPayment(
        $testAmount * 100, // Convert to cents
        $testDescription,
        $property->business_email ?: 'test@example.com',
        $property->business_name,
        $property->id,
        999, // Test ad ID
        'http://127.0.0.1:8000/property/ads/999/payment/success'
    );

    echo "Payment creation result:\n";
    echo "   Success: " . ($paymentResult['success'] ? 'YES' : 'NO') . "\n";

    if ($paymentResult['success']) {
        echo "   Payment ID: " . ($paymentResult['data']['id'] ?? 'N/A') . "\n";
        echo "   Payment URL: " . ($paymentResult['data']['payment_url'] ?? 'N/A') . "\n";
        echo "   Amount: " . ($paymentResult['data']['amount'] ?? 'N/A') . " cents\n";
        echo "   Currency: " . ($paymentResult['data']['currency'] ?? 'N/A') . "\n";

        if (isset($paymentResult['data']['sandbox']) && $paymentResult['data']['sandbox']) {
            echo "   🏖️  Sandbox mode: Payment simulation will be used\n";
        } else {
            echo "   🔴 Real API: Payment gateway should work\n";
        }

        echo "✅ Payment creation successful!\n";
    } else {
        echo "   Error: " . ($paymentResult['error'] ?? 'Unknown error') . "\n";
        echo "❌ Payment creation failed\n";
    }
} catch (Exception $e) {
    echo "❌ Payment creation exception: " . $e->getMessage() . "\n";
}

echo "\n=== Test Summary ===\n";
echo "The updated payment gateway integration is now configured.\n";
echo "Key changes made:\n";
echo "- Removed 'Bearer' prefix from Authorization header\n";
echo "- Updated API endpoint URL format\n";
echo "- Fixed environment variable loading\n";
echo "- Updated email field to use business_email\n\n";

echo "Next steps:\n";
echo "1. Test the browser flow: http://127.0.0.1:8000/property/ads/create\n";
echo "2. Login with: kavindurs8@gmail.com / password\n";
echo "3. Submit promotion form and check for payment redirection\n";
echo "4. Check logs for any remaining issues\n\n";

echo "🚀 Integration test complete!\n";
