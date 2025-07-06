<?php
// Create a test payment for LKR 100
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Services\GenieBusinessPaymentService;
use App\Models\Property;

echo "=== Creating LKR 100 Payment ===\n\n";

// Initialize payment service
$paymentService = new GenieBusinessPaymentService();

// Get a test property
$property = Property::first();
if (!$property) {
    echo "❌ No properties found!\n";
    exit(1);
}

echo "Using property: {$property->business_name} (ID: {$property->id})\n";
echo "Email: " . ($property->business_email ?: 'No email') . "\n\n";

// Create payment for LKR 100
echo "Creating payment for LKR 100...\n";

$amount = 100.00; // LKR 100
$description = "Test Payment - LKR 100";
$adId = time(); // Use timestamp as unique ad ID

try {
    $paymentResult = $paymentService->createPayment(
        $amount * 100, // Convert to cents (10000 cents = LKR 100)
        $description,
        $property->business_email ?: 'test@example.com',
        $property->business_name ?: 'Test Property',
        $property->id,
        $adId,
        "http://127.0.0.1:8000/property/ads/{$adId}/payment/success"
    );

    if ($paymentResult['success']) {
        echo "✅ Payment created successfully!\n\n";
        echo "=== PAYMENT DETAILS ===\n";
        echo "Amount: LKR " . number_format($amount, 2) . "\n";
        echo "Payment ID: " . ($paymentResult['data']['id'] ?? 'N/A') . "\n";
        echo "Status: " . ($paymentResult['data']['status'] ?? 'N/A') . "\n";
        echo "Currency: " . ($paymentResult['data']['currency'] ?? 'N/A') . "\n";

        if (isset($paymentResult['data']['payment_url'])) {
            echo "\n=== PAYMENT URL ===\n";
            echo $paymentResult['data']['payment_url'] . "\n\n";

            echo "=== INSTRUCTIONS ===\n";
            echo "1. Copy the payment URL above\n";
            echo "2. Open it in your browser\n";
            echo "3. Complete the payment process\n";
            echo "4. You'll be redirected back to the success page\n\n";

            if (isset($paymentResult['data']['sandbox']) && $paymentResult['data']['sandbox']) {
                echo "🏖️  NOTE: This is a sandbox payment (development mode)\n";
                echo "    The payment will be simulated automatically\n";
            }
        } else {
            echo "⚠️  No payment URL generated\n";
        }

        echo "\n=== RAW RESPONSE ===\n";
        echo json_encode($paymentResult['data'], JSON_PRETTY_PRINT) . "\n";

    } else {
        echo "❌ Payment creation failed!\n";
        echo "Error: " . ($paymentResult['error'] ?? 'Unknown error') . "\n";
    }

} catch (Exception $e) {
    echo "❌ Exception occurred: " . $e->getMessage() . "\n";
}

echo "\n=== Test Complete ===\n";
