<?php
require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\Http;

class QuickPaymentSessionTest {
    private $baseUrl = 'http://127.0.0.1:8000';
    private $sessionCookies = [];

    public function __construct() {
        echo "=== QUICK PAYMENT SESSION TEST ===\n";
        echo "Testing session-based payment flow\n\n";
    }

    /**
     * Test payment process with session
     */
    public function testPaymentFlow() {
        echo "Step 1: Getting payment process page...\n";

        // Get login page first to establish session
        $loginResponse = Http::get("{$this->baseUrl}/property/login");
        $this->extractCookies($loginResponse);

        // Get CSRF token
        preg_match('/<meta name="csrf-token" content="([^"]+)"/', $loginResponse->body(), $matches);
        $csrfToken = $matches[1] ?? null;

        if (!$csrfToken) {
            throw new Exception("Could not extract CSRF token");
        }

        // Login as property
        $loginSubmitResponse = Http::withCookies($this->sessionCookies, $this->baseUrl)
            ->withHeaders(['X-CSRF-TOKEN' => $csrfToken])
            ->post("{$this->baseUrl}/property/login", [
                'property_id' => 1,
                'password' => 'password123',
                '_token' => $csrfToken
            ]);

        $this->extractCookies($loginSubmitResponse);

        if ($loginSubmitResponse->status() !== 302) {
            throw new Exception("Login failed");
        }

        echo "✓ Logged in successfully\n";

        // Get checkout page
        $checkoutResponse = Http::withCookies($this->sessionCookies, $this->baseUrl)
            ->get("{$this->baseUrl}/plans/checkout?plan_id=2&amount=2500");

        if (!$checkoutResponse->successful()) {
            throw new Exception("Failed to load checkout page");
        }

        $this->extractCookies($checkoutResponse);

        // Extract CSRF token from checkout page
        preg_match('/<meta name="csrf-token" content="([^"]+)"/', $checkoutResponse->body(), $matches);
        $csrfToken = $matches[1] ?? null;

        if (!$csrfToken) {
            throw new Exception("Could not extract CSRF token from checkout page");
        }

        echo "✓ Checkout page loaded successfully\n";

        // Check payment record before payment process
        $this->checkPaymentRecord("before payment");

        // Submit payment form
        echo "Step 2: Submitting payment form...\n";
        $paymentResponse = Http::withCookies($this->sessionCookies, $this->baseUrl)
            ->withHeaders(['X-CSRF-TOKEN' => $csrfToken])
            ->post("{$this->baseUrl}/plans/payment/process", [
                'plan_id' => 2,
                'amount' => 2500,
                'payment_method' => 'genie_business',
                '_token' => $csrfToken
            ]);

        $this->extractCookies($paymentResponse);

        echo "Payment process response status: " . $paymentResponse->status() . "\n";

        if ($paymentResponse->status() === 302) {
            $location = $paymentResponse->header('Location');
            echo "Redirected to: $location\n";

            if (str_contains($location, 'sandbox') || str_contains($location, 'genie') || str_contains($location, 'payment')) {
                echo "✓ Successfully redirected to payment gateway\n";

                // Check payment record after payment process
                $this->checkPaymentRecord("after payment initiation");

                // Now simulate payment success
                echo "\nStep 3: Simulating payment success...\n";

                $successResponse = Http::withCookies($this->sessionCookies, $this->baseUrl)
                    ->get("{$this->baseUrl}/plans/payment/success?transaction_id=TEST_SUCCESS_123");

                $this->extractCookies($successResponse);

                echo "Success response status: " . $successResponse->status() . "\n";

                if ($successResponse->status() === 302) {
                    $successLocation = $successResponse->header('Location');
                    echo "Success redirected to: $successLocation\n";

                    if (str_contains($successLocation, '/plans/activated')) {
                        echo "✓ Successfully redirected to plans activated page\n";

                        // Check payment record after success
                        $this->checkPaymentRecord("after payment success");

                        echo "\n✓ PAYMENT FLOW TEST COMPLETED SUCCESSFULLY\n";
                    } else {
                        echo "✗ Not redirected to activated page\n";
                    }
                } else {
                    echo "✗ Payment success failed\n";
                    echo "Response: " . $successResponse->body() . "\n";
                }
            } else {
                echo "✗ Not redirected to payment gateway\n";
                echo "Response body: " . $paymentResponse->body() . "\n";
            }
        } else {
            echo "✗ Payment process failed\n";
            echo "Response: " . $paymentResponse->body() . "\n";
        }
    }

    /**
     * Check payment record
     */
    private function checkPaymentRecord($step) {
        echo "\nPayment Record Check ($step):\n";

        $response = Http::get("{$this->baseUrl}/debug_payment_table.php");

        if ($response->successful()) {
            $body = $response->body();

            // Look for property 1's payment record
            $propertyPattern = "/Property ID: 1.*?(?=Property ID: |\$)/s";

            if (preg_match($propertyPattern, $body, $matches)) {
                echo "✓ Payment record found for property 1:\n";
                $record = trim($matches[0]);
                echo $record . "\n";

                // Extract status
                if (preg_match('/Status: (\w+)/', $record, $statusMatch)) {
                    $status = $statusMatch[1];
                    echo "Status: $status\n";
                }
            } else {
                echo "✗ No payment record found for property 1\n";
            }
        } else {
            echo "✗ Failed to check payment table\n";
        }

        echo "\n";
    }

    /**
     * Extract cookies from response
     */
    private function extractCookies($response) {
        $cookies = $response->cookies();
        foreach ($cookies as $cookie) {
            $this->sessionCookies[$cookie->getName()] = $cookie->getValue();
        }
    }

    /**
     * Run the test
     */
    public function runTest() {
        try {
            $this->testPaymentFlow();
        } catch (Exception $e) {
            echo "\n✗ TEST FAILED: " . $e->getMessage() . "\n";
        }
    }
}

// Run the test
$test = new QuickPaymentSessionTest();
$test->runTest();
?>
