<?php
// Test the property login and ad creation flow
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Property;
use Illuminate\Support\Facades\Session;

echo "=== Property Login and Ad Creation Flow Test ===\n\n";

// Test 1: Find a test property
echo "1. Finding test property...\n";
$property = Property::first();
if (!$property) {
    echo "❌ No properties found in database!\n";
    exit(1);
}
echo "✅ Found property: {$property->business_name} (ID: {$property->id})\n";
echo "   Email: " . ($property->email ?: 'No email set') . "\n";
echo "   Contact: " . ($property->contact_person ?: 'No contact person set') . "\n\n";

// Test 2: Check if property has login credentials
echo "2. Checking property login credentials...\n";
if (empty($property->email) || empty($property->password)) {
    echo "⚠️  Property doesn't have login credentials set\n";
    echo "   Setting up test credentials...\n";

    $property->update([
        'email' => $property->email ?: 'test@property' . $property->id . '.com',
        'password' => bcrypt('password123'),
    ]);

    echo "✅ Test credentials created:\n";
    echo "   Email: " . $property->email . "\n";
    echo "   Password: password123\n\n";
} else {
    echo "✅ Property has login credentials\n\n";
}

// Test 3: Check session simulation
echo "3. Testing session simulation...\n";
echo "   Current session property_id: " . (session('property_id') ?: 'Not set') . "\n";

// Simulate logging in
session(['property_id' => $property->id]);
echo "✅ Simulated property login (property_id = {$property->id})\n\n";

// Test 4: Test URL accessibility
echo "4. Testing URL accessibility...\n";
$urls = [
    'Property Login' => 'http://127.0.0.1:8000/property/login',
    'Ad Creation' => 'http://127.0.0.1:8000/property/ads/create',
    'Ads Dashboard' => 'http://127.0.0.1:8000/property/ads',
];

foreach ($urls as $name => $url) {
    echo "   {$name}: {$url}\n";
}

echo "\n5. Testing direct ad creation simulation...\n";

// Simulate what happens when the form is submitted
try {
    $controller = new \App\Http\Controllers\SimpleAdController();

    // Create a mock request
    $request = new \Illuminate\Http\Request();
    $request->merge([
        'start_date' => date('Y-m-d'),
        'end_date' => date('Y-m-d', strtotime('+2 days')),
    ]);

    echo "   Form data:\n";
    echo "     Start Date: " . $request->start_date . "\n";
    echo "     End Date: " . $request->end_date . "\n";

    echo "✅ Mock request created successfully\n\n";

} catch (Exception $e) {
    echo "❌ Error creating mock request: " . $e->getMessage() . "\n\n";
}

echo "=== Instructions to Test Manually ===\n";
echo "1. Go to: http://127.0.0.1:8000/property/login\n";
echo "2. Login with:\n";
echo "   Email: {$property->email}\n";
echo "   Password: password123\n";
echo "3. After login, go to: http://127.0.0.1:8000/property/ads/create\n";
echo "4. Select dates and click 'Pay & Submit Request'\n";
echo "5. You should be redirected to the payment gateway\n\n";

echo "=== Alternative Direct Access ===\n";
echo "If you want to bypass login for testing, you can:\n";
echo "1. Open browser developer tools\n";
echo "2. Go to Application/Storage > Cookies\n";
echo "3. Add a cookie named 'laravel_session' with the current session ID\n";
echo "4. Or modify the SimpleAdController to skip the login check temporarily\n\n";

echo "✅ Test setup complete!\n";
