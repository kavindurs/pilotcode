<?php

require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use App\Http\Controllers\PlanPaymentController;
use Illuminate\Http\Request;

// Create Laravel app instance
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Set up session data for property login
session(['property_id' => 2]); // Using property ID 2

echo "=== Testing Complete Plan Payment Flow ===\n\n";

// Simulate the request from the checkout page
$request = new Request([
    'plan_id' => 7,
    'amount' => 5097, // LKR amount (16.99 USD * 300)
    'payment_method' => 'card'
]);

echo "Simulating payment process request:\n";
echo "Plan ID: {$request->plan_id}\n";
echo "Amount: {$request->amount} (should be interpreted as LKR)\n";
echo "Payment Method: {$request->payment_method}\n\n";

try {
    // Test the controller logic manually
    $controller = new PlanPaymentController();

    echo "Testing processPayment method...\n";

    // This would normally be called by the controller
    $validated = [
        'plan_id' => $request->plan_id,
        'amount' => $request->amount,
        'payment_method' => $request->payment_method
    ];

    // Test currency detection logic
    if ($validated['amount'] < 100) {
        echo "Amount detected as USD\n";
        $usdAmount = $validated['amount'];
        $lkrAmount = $validated['amount'] * 300;
    } else {
        echo "Amount detected as LKR\n";
        $lkrAmount = $validated['amount'];
        $usdAmount = round($validated['amount'] / 300, 2);
    }

    echo "USD Amount: $usdAmount\n";
    echo "LKR Amount: $lkrAmount\n";
    echo "Payment Gateway Amount: $lkrAmount LKR\n\n";

    // Check if we have required data
    $plan = \App\Models\Plan::find($validated['plan_id']);
    $property = \App\Models\Property::find(session('property_id'));

    if (!$plan) {
        echo "❌ Plan not found!\n";
        exit;
    }

    if (!$property) {
        echo "❌ Property not found!\n";
        exit;
    }

    echo "✅ Plan found: {$plan->name}\n";
    echo "✅ Property found: {$property->business_name}\n\n";

    echo "Payment creation would use:\n";
    echo "- Amount: $lkrAmount LKR\n";
    echo "- Currency: LKR\n";
    echo "- Business Email: " . ($property->business_email ?: 'noemail@property' . $property->id . '.local') . "\n";
    echo "- Customer Name: " . ($property->business_name ?: $property->contact_person) . "\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

echo "\nFlow test completed!\n";
