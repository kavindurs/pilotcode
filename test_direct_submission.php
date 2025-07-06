<?php
// Test direct form submission to debug redirection
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Property;
use App\Models\Ad;
use Illuminate\Support\Facades\DB;

echo "=== Testing Direct Form Submission ===\n\n";

// Clear any existing ads for testing
$deletedAds = Ad::where('property_id', 2)->delete();
echo "Cleared {$deletedAds} existing ads for testing\n\n";

// Test form submission using HTTP client
$baseUrl = 'http://127.0.0.1:8000';

// Step 1: Login first
echo "1. Attempting login...\n";
$loginData = [
    'business_email' => 'kavindurs8@gmail.com',
    'password' => 'password',
    '_token' => 'test-token'
];

try {
    $client = new \GuzzleHttp\Client(['cookies' => true]);

    // Get login page to get CSRF token
    $loginPage = $client->get($baseUrl . '/property/login');
    $loginPageContent = $loginPage->getBody()->getContents();

    // Extract CSRF token
    preg_match('/_token.*?value="([^"]*)"/', $loginPageContent, $tokenMatch);
    $csrfToken = $tokenMatch[1] ?? 'test-token';

    echo "CSRF Token: " . substr($csrfToken, 0, 20) . "...\n";

    // Submit login
    $loginResponse = $client->post($baseUrl . '/property/login', [
        'form_params' => [
            'business_email' => 'kavindurs8@gmail.com',
            'password' => 'password',
            '_token' => $csrfToken
        ],
        'allow_redirects' => false
    ]);

    echo "Login response status: " . $loginResponse->getStatusCode() . "\n";
    echo "Login response headers: " . json_encode($loginResponse->getHeaders()) . "\n";

    if ($loginResponse->getStatusCode() == 302) {
        $location = $loginResponse->getHeaderLine('Location');
        echo "Login redirect to: " . $location . "\n";

        if (strpos($location, 'dashboard') !== false || strpos($location, 'ads') !== false) {
            echo "✅ Login successful!\n\n";

            // Step 2: Get create page
            echo "2. Getting create page...\n";
            $createPage = $client->get($baseUrl . '/property/ads/create');
            $createPageContent = $createPage->getBody()->getContents();

            // Extract CSRF token from create page
            preg_match('/_token.*?value="([^"]*)"/', $createPageContent, $createTokenMatch);
            $createCsrfToken = $createTokenMatch[1] ?? 'test-token';

            echo "Create page CSRF Token: " . substr($createCsrfToken, 0, 20) . "...\n";

            // Step 3: Submit form
            echo "3. Submitting form...\n";
            $formData = [
                'start_date' => date('Y-m-d'),
                'end_date' => date('Y-m-d', strtotime('+1 day')),
                '_token' => $createCsrfToken
            ];

            echo "Form data: " . json_encode($formData) . "\n";

            $formResponse = $client->post($baseUrl . '/property/ads', [
                'form_params' => $formData,
                'allow_redirects' => false
            ]);

            echo "Form response status: " . $formResponse->getStatusCode() . "\n";

            if ($formResponse->getStatusCode() == 302) {
                $redirectLocation = $formResponse->getHeaderLine('Location');
                echo "✅ Form submitted successfully!\n";
                echo "Redirect to: " . $redirectLocation . "\n";

                if (strpos($redirectLocation, 'payment') !== false) {
                    echo "🎉 Payment redirection working!\n";
                } else {
                    echo "⚠️  Redirected but not to payment page\n";
                }
            } else {
                echo "❌ Form submission failed\n";
                echo "Response body: " . $formResponse->getBody()->getContents() . "\n";
            }
        } else {
            echo "❌ Login failed - redirected to: " . $location . "\n";
        }
    } else {
        echo "❌ Login failed - status: " . $loginResponse->getStatusCode() . "\n";
        echo "Response: " . $loginResponse->getBody()->getContents() . "\n";
    }

} catch (Exception $e) {
    echo "❌ Error during testing: " . $e->getMessage() . "\n";
}

// Check if ad was created
echo "\n4. Checking database...\n";
$recentAds = Ad::where('property_id', 2)->orderBy('created_at', 'desc')->take(3)->get();
echo "Recent ads for property 2: " . $recentAds->count() . "\n";
foreach ($recentAds as $ad) {
    echo "  Ad ID: {$ad->id}, Status: {$ad->status}, Payment Status: {$ad->payment_status}\n";
}

echo "\n=== Test Complete ===\n";
