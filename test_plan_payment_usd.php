<?php

require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

// Create Laravel app instance
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Set up session data for property login
session(['property_id' => 2]); // Using property ID 2

echo "=== Testing Plan Payment with USD Amount ===\n";
echo "URL: http://127.0.0.1:8000/property/plans/checkout?plan_id=7&amount=16.99\n\n";

// Test the checkout logic
$planId = 7;
$amount = 16.99; // USD amount

echo "Input amount: $amount\n";

// Simulate the checkout controller logic
if ($amount < 100) {
    // Amount is likely USD
    $usdAmount = $amount;
    $lkrAmount = $amount * 300; // Convert to LKR for display
    echo "Detected as USD amount\n";
} else {
    // Amount is likely LKR
    $lkrAmount = $amount;
    $usdAmount = round($amount / 300, 2); // Convert to USD
    echo "Detected as LKR amount\n";
}

echo "USD Amount: $usdAmount\n";
echo "LKR Amount: $lkrAmount\n";

// Test payment creation logic
if ($amount < 100) {
    // Amount is likely USD
    $usdAmountForPayment = $amount;
    $lkrAmountForPayment = $amount * 300; // Convert to LKR for payment gateway
} else {
    // Amount is likely LKR
    $lkrAmountForPayment = $amount;
    $usdAmountForPayment = round($amount / 300, 2); // Convert to USD
}

// For Genie payment gateway, use LKR amount
$paymentAmount = $lkrAmountForPayment;

echo "\nFor Payment Gateway:\n";
echo "Payment Amount (LKR): $paymentAmount\n";
echo "Currency: LKR\n";

// Test if this amount meets minimum requirements
if ($paymentAmount >= 50) { // Assuming 50 LKR minimum
    echo "✅ Amount meets minimum payment requirement\n";
} else {
    echo "❌ Amount too small for payment gateway\n";
}

echo "\nTest completed!\n";
