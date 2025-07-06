<?php
// Test login and payment redirection flow
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Property;
use Illuminate\Support\Facades\Hash;

echo "=== Testing Login and Payment Flow ===\n\n";

// Test 1: Find property with the given email
echo "1. Finding property with email kavindurs8@gmail.com...\n";
$property = Property::where('business_email', 'kavindurs8@gmail.com')->first();

if (!$property) {
    echo "❌ Property not found with that email!\n";
    echo "Let me check what properties exist...\n";

    $properties = Property::select('id', 'business_name', 'business_email')->take(5)->get();
    foreach ($properties as $prop) {
        echo "   ID: {$prop->id}, Name: {$prop->business_name}, Email: " . ($prop->business_email ?: 'No email') . "\n";
    }

    // Let's use the first property we can find
    $property = Property::first();
    if ($property) {
        echo "Using first available property: {$property->business_name} (ID: {$property->id})\n";

        // Update it with the test credentials
        $property->update([
            'business_email' => 'kavindurs8@gmail.com',
            'password' => Hash::make('password')
        ]);
        echo "✅ Updated property with test credentials\n";
    } else {
        echo "❌ No properties found at all!\n";
        exit(1);
    }
} else {
    echo "✅ Found property: {$property->business_name} (ID: {$property->id})\n";
}

// Test 2: Check/set password
echo "\n2. Checking password...\n";
if (empty($property->password)) {
    echo "Setting password...\n";
    $property->update(['password' => Hash::make('password')]);
    echo "✅ Password set\n";
} else {
    // Test if current password works
    if (Hash::check('password', $property->password)) {
        echo "✅ Password verification successful\n";
    } else {
        echo "⚠️  Password doesn't match, updating...\n";
        $property->update(['password' => Hash::make('password')]);
        echo "✅ Password updated\n";
    }
}

// Test 3: Simulate login session
echo "\n3. Setting up session simulation...\n";
// We'll simulate the session in the controller test
echo "✅ Session simulation prepared for property_id: {$property->id}\n";

// Test 4: Test the controller methods directly
echo "\n4. Testing controller methods...\n";

try {
    // Test create method
    $controller = new \App\Http\Controllers\SimpleAdController();
    echo "✅ Controller instantiated\n";

    // Create a test request for store method
    $request = \Illuminate\Http\Request::create('/property/ads', 'POST', [
        'start_date' => date('Y-m-d'),
        'end_date' => date('Y-m-d', strtotime('+1 day')),
        '_token' => 'test-token'
    ]);

    echo "📋 Test form data:\n";
    echo "   Start Date: " . $request->start_date . "\n";
    echo "   End Date: " . $request->end_date . "\n";

    echo "\n5. Checking if property can access create page...\n";

    // Simulate being logged in for the test
    app('session')->put('property_id', $property->id);

    try {
        $response = $controller->create();
        echo "✅ Create method executed successfully\n";
    } catch (Exception $e) {
        echo "❌ Create method failed: " . $e->getMessage() . "\n";
    }

} catch (Exception $e) {
    echo "❌ Controller test failed: " . $e->getMessage() . "\n";
}

echo "\n=== Manual Testing Instructions ===\n";
echo "1. Go to: http://127.0.0.1:8000/property/login\n";
echo "2. Login with:\n";
echo "   Email: kavindurs8@gmail.com\n";
echo "   Password: password\n";
echo "3. After successful login, go to: http://127.0.0.1:8000/property/ads/create\n";
echo "4. Select dates and click 'Pay & Submit Request'\n";
echo "5. Watch the browser console and network tab for any errors\n\n";

echo "=== Debug Information ===\n";
echo "Property Details:\n";
echo "   ID: {$property->id}\n";
echo "   Name: {$property->business_name}\n";
echo "   Email: {$property->business_email}\n";
echo "   Has Password: " . (empty($property->password) ? 'No' : 'Yes') . "\n";
echo "\nURLs to test:\n";
echo "   Login: http://127.0.0.1:8000/property/login\n";
echo "   Create Ad: http://127.0.0.1:8000/property/ads/create\n";
echo "   Dashboard: http://127.0.0.1:8000/property/ads\n";

echo "\n✅ Setup complete! Ready for manual testing.\n";
