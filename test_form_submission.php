<?php
// Simulate property login and test form submission
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Simulating Property Login and Form Submission ===\n\n";

// Create a session for property ID 2
session(['property_id' => 2]);
echo "✅ Set session property_id to 2\n";

// Test the ad creation page access
$createUrl = 'http://127.0.0.1:8000/property/ads/create';
echo "Testing access to: $createUrl\n";

// Use cURL to test form submission simulation
$postData = [
    '_token' => csrf_token(),
    'start_date' => '2025-07-08',
    'end_date' => '2025-07-10'
];

echo "Simulating form submission with data:\n";
echo "  Start Date: 2025-07-08\n";
echo "  End Date: 2025-07-10\n";
echo "  Days: 3\n";
echo "  Expected Cost: $27 USD\n\n";

// Create HTTP client with session
$client = new \GuzzleHttp\Client([
    'cookies' => true,
    'verify' => false,
    'timeout' => 30
]);

// First, get the create page to get CSRF token
try {
    echo "Step 1: Getting ad creation page...\n";
    $response = $client->get($createUrl);
    echo "Response status: " . $response->getStatusCode() . "\n";

    if ($response->getStatusCode() === 200) {
        echo "✅ Successfully accessed ad creation page\n";

        // Extract CSRF token from the page
        $html = $response->getBody()->getContents();
        preg_match('/<meta name="csrf-token" content="([^"]+)"/', $html, $matches);
        $csrfToken = $matches[1] ?? null;

        if ($csrfToken) {
            echo "✅ Found CSRF token: " . substr($csrfToken, 0, 20) . "...\n";

            // Now submit the form
            echo "\nStep 2: Submitting ad creation form...\n";
            $formResponse = $client->post('http://127.0.0.1:8000/property/ads', [
                'form_params' => [
                    '_token' => $csrfToken,
                    'start_date' => '2025-07-08',
                    'end_date' => '2025-07-10'
                ],
                'allow_redirects' => false
            ]);

            echo "Form submission status: " . $formResponse->getStatusCode() . "\n";

            if ($formResponse->getStatusCode() === 302) {
                $redirectLocation = $formResponse->getHeaderLine('Location');
                echo "✅ Form submitted successfully!\n";
                echo "Redirect location: $redirectLocation\n";

                // Check if it's a payment URL
                if (strpos($redirectLocation, 'payment/success') !== false) {
                    echo "🎉 SUCCESS! User is being redirected to payment page!\n";
                    echo "Payment URL: $redirectLocation\n";
                } else if (strpos($redirectLocation, 'payment') !== false) {
                    echo "✅ Redirected to payment page: $redirectLocation\n";
                } else {
                    echo "⚠️ Redirected to: $redirectLocation (not a payment URL)\n";
                }
            } else {
                echo "❌ Unexpected form response status: " . $formResponse->getStatusCode() . "\n";
                echo "Response body: " . $formResponse->getBody() . "\n";
            }
        } else {
            echo "❌ Could not find CSRF token in page\n";
        }
    } else {
        echo "❌ Could not access ad creation page\n";
    }

} catch (Exception $e) {
    echo "❌ Error during simulation: " . $e->getMessage() . "\n";
}

echo "\n=== Manual Testing Instructions ===\n";
echo "To test manually in browser:\n";
echo "1. Go to: http://127.0.0.1:8000/property/login\n";
echo "2. Enter email: kavindurs8@gmail.com (Property ID 2)\n";
echo "3. Go to: http://127.0.0.1:8000/property/ads/create\n";
echo "4. Select dates and click 'Pay & Submit Request'\n";
echo "5. You should be redirected to a payment page or sandbox simulation\n";
