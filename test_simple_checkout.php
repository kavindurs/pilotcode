<?php

// Simple test to check the checkout page response
echo "Checking Plan Checkout Page Response...\n";

$baseUrl = 'http://127.0.0.1:8000';
$planId = 7;
$amount = 16.99;
$checkoutUrl = "{$baseUrl}/property/plans/checkout?plan_id={$planId}&amount={$amount}";

echo "URL: {$checkoutUrl}\n\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $checkoutUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: {$httpCode}\n";
echo "Response Length: " . strlen($response) . "\n";

// Check for login redirect
if (strpos($response, 'login') !== false || strpos($response, 'Login') !== false) {
    echo "✗ Redirected to login page\n";
    echo "Need to login first to test the payment flow\n";
} else if (strpos($response, 'Complete Payment') !== false) {
    echo "✓ Checkout page loaded successfully\n";
} else if (strpos($response, 'Error') !== false || strpos($response, 'error') !== false) {
    echo "✗ Error on page\n";
    // Show first 500 chars to debug
    echo "Response preview:\n" . substr($response, 0, 500) . "...\n";
} else {
    echo "Response preview (first 200 chars):\n";
    echo substr($response, 0, 200) . "...\n";
}

echo "\nTesting completed.\n";
