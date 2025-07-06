<?php
// Test property login and redirect to ad creation page
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Property;

echo "=== Testing Property Login Flow ===\n\n";

// Find a property to test with
$property = Property::find(2);
if (!$property) {
    echo "❌ Property not found!\n";
    exit(1);
}

echo "Found property: {$property->business_name} (ID: {$property->id})\n";
echo "Email: " . ($property->email ?: 'NOT SET') . "\n";
echo "Phone: " . ($property->phone ?: 'NOT SET') . "\n\n";

// Test property login URL
$loginUrl = route('property.login');
echo "Property login URL: $loginUrl\n";

// Test ad creation URL
$createAdUrl = route('property.ads.create');
echo "Ad creation URL: $createAdUrl\n\n";

// Test property login with cURL to see if it's working
echo "Testing property login page...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $loginUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Login page HTTP status: $httpCode\n";
if ($httpCode == 200) {
    echo "✅ Property login page is accessible\n";
} else {
    echo "❌ Property login page returned status: $httpCode\n";
}

// Test ad creation page (should redirect to login if not logged in)
echo "\nTesting ad creation page (without login)...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $createAdUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false); // Don't follow redirects
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$redirectUrl = curl_getinfo($ch, CURLINFO_REDIRECT_URL);
curl_close($ch);

echo "Ad creation page HTTP status: $httpCode\n";
if ($httpCode == 302) {
    echo "✅ Properly redirects to login (expected behavior)\n";
    echo "Redirect URL: $redirectUrl\n";
} else {
    echo "❌ Unexpected status: $httpCode\n";
}

echo "\n=== Property Login Credentials ===\n";
echo "To test the flow manually:\n";
echo "1. Go to: $loginUrl\n";
echo "2. Enter property credentials (phone/email)\n";
echo "3. Go to: $createAdUrl\n";
echo "4. Fill out the form and submit\n\n";

// Let's also check if there are any properties with email addresses
$propertiesWithEmail = Property::whereNotNull('email')->where('email', '!=', '')->limit(5)->get(['id', 'business_name', 'email', 'phone']);
echo "Properties with email addresses:\n";
foreach ($propertiesWithEmail as $prop) {
    echo "  ID: {$prop->id}, Name: {$prop->business_name}, Email: {$prop->email}, Phone: {$prop->phone}\n";
}
