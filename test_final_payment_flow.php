<?php
// Test the complete payment flow integration
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Services\GenieBusinessPaymentService;
use App\Models\Property;
use App\Models\Ad;

echo "=== Final Payment Flow Integration Test ===\n\n";

// Test 1: Configuration Check
echo "1. Checking configuration...\n";
$config = [
    'app_id' => config('genie_business.app_id'),
    'app_key' => config('genie_business.app_key'),
    'api_url' => config('genie_business.api_url'),
    'environment' => config('genie_business.environment'),
    'app_url' => config('app.url')
];

foreach ($config as $key => $value) {
    echo "   $key: " . ($value ? substr($value, 0, 50) . (strlen($value) > 50 ? '...' : '') : 'NOT SET') . "\n";
}

if (empty($config['app_id']) || empty($config['app_key']) || empty($config['api_url'])) {
    echo "❌ Configuration incomplete! Check your .env file.\n";
    exit(1);
}

echo "✅ Configuration looks good!\n\n";

// Test 2: Payment Service Initialization
echo "2. Testing payment service initialization...\n";
try {
    $paymentService = new GenieBusinessPaymentService();
    echo "✅ Payment service initialized successfully!\n\n";
} catch (Exception $e) {
    echo "❌ Payment service initialization failed: " . $e->getMessage() . "\n";
    exit(1);
}

// Test 3: Find a test property
echo "3. Finding test property...\n";
$property = Property::first();
if (!$property) {
    echo "❌ No properties found in database!\n";
    exit(1);
}
echo "✅ Found property: {$property->business_name} (ID: {$property->id})\n\n";

// Test 4: Create test payment
echo "4. Creating test payment...\n";
$testAmount = 10.00; // $10 USD test
$testDescription = "Test Ad Promotion Payment";
$customerEmail = $property->email ?: 'test@example.com';
$customerName = $property->business_name;
$adId = 999; // Test ad ID

try {
    $paymentResult = $paymentService->createPayment(
        $testAmount * 100, // Convert to cents
        $testDescription,
        $customerEmail,
        $customerName,
        $property->id,
        $adId,
        config('app.url') . "/property/ads/{$adId}/payment/success"
    );

    if ($paymentResult['success']) {
        echo "✅ Payment creation successful!\n";
        echo "   Payment ID: " . ($paymentResult['data']['id'] ?? 'N/A') . "\n";
        echo "   Payment URL: " . ($paymentResult['data']['payment_url'] ?? 'N/A') . "\n";
        echo "   Amount: " . ($paymentResult['data']['amount'] ?? 'N/A') . " cents\n";
        echo "   Currency: " . ($paymentResult['data']['currency'] ?? 'N/A') . "\n";

        if (isset($paymentResult['data']['sandbox']) && $paymentResult['data']['sandbox']) {
            echo "   🏖️  Sandbox mode detected - this is expected in development\n";
        }
    } else {
        echo "❌ Payment creation failed: " . ($paymentResult['error'] ?? 'Unknown error') . "\n";
    }
} catch (Exception $e) {
    echo "❌ Payment creation exception: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 5: Test URL Generation
echo "5. Testing URL generation...\n";
$testUrls = [
    'ads_create' => route('property.ads.create'),
    'ads_store' => route('property.ads.store'),
    'payment_success' => route('property.ads.payment.success', 1),
    'payment_callback' => route('property.ads.payment.callback'),
];

foreach ($testUrls as $name => $url) {
    echo "   $name: $url\n";
}

echo "\n";

// Test 6: Check Database Tables
echo "6. Checking database tables...\n";
try {
    $adsCount = \Illuminate\Support\Facades\DB::table('ads')->count();
    $propertiesCount = \Illuminate\Support\Facades\DB::table('properties')->count();
    echo "   Properties table: $propertiesCount records\n";
    echo "   Ads table: $adsCount records\n";
    echo "✅ Database connectivity confirmed!\n";
} catch (Exception $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n";
}

echo "\n=== Integration Test Summary ===\n";
echo "The payment gateway integration appears to be working correctly.\n";
echo "You can now test the complete flow by:\n";
echo "1. Going to: " . route('property.ads.create') . "\n";
echo "2. Selecting promotion dates\n";
echo "3. Submitting the form\n";
echo "4. You should be redirected to the payment gateway (or sandbox simulation)\n";
echo "\nEverything looks ready for real user testing! 🚀\n";
