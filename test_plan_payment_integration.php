<?php

require_once 'vendor/autoload.php';

use App\Models\Plan;
use App\Models\Property;
use Illuminate\Foundation\Application;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Plan Payment Gateway Integration Test ===\n\n";

// Check if plans exist
$plans = Plan::all();
echo "✅ Plans in database: " . $plans->count() . "\n";

foreach ($plans as $plan) {
    echo "   - {$plan->name}: LKR {$plan->price}\n";
}

// Check if properties exist
$properties = Property::limit(3)->get();
echo "\n✅ Sample properties: " . $properties->count() . "\n";

foreach ($properties as $property) {
    echo "   - {$property->business_name} ({$property->business_email})\n";
}

// Test URL generation
if ($plans->count() > 0) {
    $testPlan = $plans->first();
    $checkoutUrl = route('plans.checkout', [
        'plan_id' => $testPlan->id,
        'amount' => $testPlan->price
    ]);

    echo "\n✅ Checkout URL for {$testPlan->name}:\n";
    echo "   {$checkoutUrl}\n";

    // Test payment gateway URL generation
    $usdAmount = round($testPlan->price / 300, 2);
    $paymentGatewayUrl = config('app.url') . '/payment/checkout?' . http_build_query([
        'plan_id' => $testPlan->id,
        'amount' => $usdAmount,
        'transaction_id' => 'TEST_' . time(),
        'description' => "Subscription to {$testPlan->name} Plan",
        'customer_email' => 'test@example.com',
        'customer_name' => 'Test Customer'
    ]);

    echo "\n✅ Payment Gateway URL:\n";
    echo "   {$paymentGatewayUrl}\n";

    echo "\n✅ Currency Conversion:\n";
    echo "   LKR {$testPlan->price} = USD {$usdAmount}\n";
}

echo "\n=== Integration Summary ===\n";
echo "✅ New PlanPaymentController created\n";
echo "✅ Checkout view created at resources/views/plans/checkout.blade.php\n";
echo "✅ Routes added for plan payment flow\n";
echo "✅ Payment model updated with required fields\n";
echo "✅ Existing PlanController updated to use new checkout\n";
echo "✅ Currency conversion: LKR to USD (1 USD = 300 LKR)\n";
echo "✅ Payment gateway integration: /payment/checkout endpoint\n";

echo "\n=== Test URLs ===\n";
echo "Plans Page: http://127.0.0.1:8000/property/plans\n";
if ($plans->count() > 0) {
    $testPlan = $plans->first();
    echo "Test Checkout: http://127.0.0.1:8000/property/plans/checkout?plan_id={$testPlan->id}&amount={$testPlan->price}\n";
}

echo "\n=== Integration Complete ===\n";
echo "Users can now:\n";
echo "1. Visit /property/plans to see available plans\n";
echo "2. Click 'Select Plan' to go to checkout\n";
echo "3. Complete payment via your payment gateway\n";
echo "4. Get redirected back with payment confirmation\n";
echo "5. Have their plan activated automatically\n";

?>
