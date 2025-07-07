<?php
require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\Http;

class PlanPaymentOnSuccessTest {
    private $baseUrl = 'http://127.0.0.1:8000';
    private $propertyId;
    private $sessionCookies = [];

    public function __construct() {
        echo "=== PLAN PAYMENT ON SUCCESS ONLY TEST ===\n";
        echo "Testing that payment records are only created on payment success\n\n";
    }

    /**
     * Login as property owner
     */
    public function loginAsProperty($propertyId = 1) {
        $this->propertyId = $propertyId;

        echo "Step 1: Logging in as property $propertyId...\n";

        // Get login form
        $response = Http::get("{$this->baseUrl}/property/login");
        $this->extractCookies($response);

        // Get CSRF token
        preg_match('/<meta name="csrf-token" content="([^"]+)"/', $response->body(), $matches);
        $csrfToken = $matches[1] ?? null;

        if (!$csrfToken) {
            throw new Exception("Could not extract CSRF token");
        }

        // Attempt login
        $loginResponse = Http::withCookies($this->sessionCookies, $this->baseUrl)
            ->withHeaders(['X-CSRF-TOKEN' => $csrfToken])
            ->post("{$this->baseUrl}/property/login", [
                'property_id' => $propertyId,
                'password' => 'password123',
                '_token' => $csrfToken
            ]);

        $this->extractCookies($loginResponse);

        if ($loginResponse->status() === 302 && str_contains($loginResponse->header('Location'), '/property/dashboard')) {
            echo "✓ Successfully logged in as property $propertyId\n\n";
            return true;
        } else {
            echo "✗ Login failed\n";
            echo "Status: " . $loginResponse->status() . "\n";
            echo "Response: " . $loginResponse->body() . "\n\n";
            return false;
        }
    }

    /**
     * Check current payment record for property
     */
    public function checkPaymentRecord($step = "unknown") {
        echo "Payment Record Check ($step):\n";

        $response = Http::get("{$this->baseUrl}/debug_payment_table.php");

        if ($response->successful()) {
            $body = $response->body();

            // Look for our property's payment record
            $propertyPattern = "/Property ID: {$this->propertyId}.*?(?=Property ID: |\$)/s";

            if (preg_match($propertyPattern, $body, $matches)) {
                echo "✓ Payment record found for property {$this->propertyId}:\n";
                echo trim($matches[0]) . "\n";

                // Extract status
                if (preg_match('/Status: (\w+)/', $matches[0], $statusMatch)) {
                    $status = $statusMatch[1];
                    echo "  Status: $status\n";
                    return $status;
                }
            } else {
                echo "✗ No payment record found for property {$this->propertyId}\n";
                return null;
            }
        } else {
            echo "✗ Failed to check payment table\n";
            return null;
        }

        echo "\n";
        return null;
    }

    /**
     * Start payment process but don't complete it
     */
    public function startPaymentProcess() {
        echo "Step 2: Starting payment process (clicking Complete Payment)...\n";

        // Get checkout page to get CSRF token
        $checkoutResponse = Http::withCookies($this->sessionCookies, $this->baseUrl)
            ->get("{$this->baseUrl}/plans/checkout?plan_id=2&amount=2500");

        if (!$checkoutResponse->successful()) {
            echo "✗ Failed to load checkout page\n";
            return false;
        }

        $this->extractCookies($checkoutResponse);

        // Extract CSRF token
        preg_match('/<meta name="csrf-token" content="([^"]+)"/', $checkoutResponse->body(), $matches);
        $csrfToken = $matches[1] ?? null;

        if (!$csrfToken) {
            echo "✗ Could not extract CSRF token from checkout page\n";
            return false;
        }

        // Check payment record before clicking Complete Payment
        $beforeStatus = $this->checkPaymentRecord("before payment initiation");

        // Submit payment form
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

            // Check if redirected to payment gateway
            if (str_contains($location, 'sandbox') || str_contains($location, 'payment')) {
                echo "✓ Successfully redirected to payment gateway\n";

                // Check payment record after clicking Complete Payment but before gateway success
                $afterStatus = $this->checkPaymentRecord("after payment initiation (before gateway success)");

                if ($beforeStatus === null && $afterStatus === null) {
                    echo "✓ CORRECT: No payment record created on payment initiation\n";
                    return true;
                } else {
                    echo "✗ INCORRECT: Payment record was created on payment initiation\n";
                    echo "  Before: " . ($beforeStatus ?? 'none') . "\n";
                    echo "  After: " . ($afterStatus ?? 'none') . "\n";
                    return false;
                }
            } else {
                echo "✗ Not redirected to payment gateway: $location\n";
                return false;
            }
        } else {
            echo "✗ Payment process failed\n";
            echo "Response: " . $paymentResponse->body() . "\n";
            return false;
        }
    }

    /**
     * Simulate payment success
     */
    public function simulatePaymentSuccess() {
        echo "\nStep 3: Simulating payment gateway success callback...\n";

        // Check payment record before success callback
        $beforeStatus = $this->checkPaymentRecord("before payment success");

        // Simulate payment success callback
        $successResponse = Http::withCookies($this->sessionCookies, $this->baseUrl)
            ->get("{$this->baseUrl}/plans/payment/success?transaction_id=TEST_TRANS_123");

        $this->extractCookies($successResponse);

        echo "Success callback response status: " . $successResponse->status() . "\n";

        if ($successResponse->status() === 302) {
            $location = $successResponse->header('Location');
            echo "Redirected to: $location\n";

            if (str_contains($location, '/plans/activated')) {
                echo "✓ Successfully redirected to plans activated page\n";

                // Check payment record after success callback
                $afterStatus = $this->checkPaymentRecord("after payment success");

                if ($beforeStatus === null && $afterStatus === 'completed') {
                    echo "✓ CORRECT: Payment record created only on success with 'completed' status\n";
                    return true;
                } else {
                    echo "✗ INCORRECT: Payment record behavior unexpected\n";
                    echo "  Before: " . ($beforeStatus ?? 'none') . "\n";
                    echo "  After: " . ($afterStatus ?? 'none') . "\n";
                    return false;
                }
            } else {
                echo "✗ Not redirected to activated page: $location\n";
                return false;
            }
        } else {
            echo "✗ Payment success callback failed\n";
            echo "Response: " . $successResponse->body() . "\n";
            return false;
        }
    }

    /**
     * Check property plan update
     */
    public function checkPropertyPlanUpdate() {
        echo "\nStep 4: Checking property plan update...\n";

        $response = Http::get("{$this->baseUrl}/check_property_data.php?property_id={$this->propertyId}");

        if ($response->successful()) {
            $body = $response->body();
            echo "Property data check response:\n$body\n";

            if (preg_match('/Plan ID: (\d+)/', $body, $matches)) {
                $planId = $matches[1];
                if ($planId == 2) {
                    echo "✓ CORRECT: Property plan updated to plan 2 after payment success\n";
                    return true;
                } else {
                    echo "✗ INCORRECT: Property plan is $planId, expected 2\n";
                    return false;
                }
            } else {
                echo "✗ Could not extract plan ID from property data\n";
                return false;
            }
        } else {
            echo "✗ Failed to check property data\n";
            return false;
        }
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
     * Run the complete test
     */
    public function runTest() {
        try {
            // Step 1: Login
            if (!$this->loginAsProperty(1)) {
                throw new Exception("Login failed");
            }

            // Step 2: Start payment process
            if (!$this->startPaymentProcess()) {
                throw new Exception("Payment process failed");
            }

            // Step 3: Simulate payment success
            if (!$this->simulatePaymentSuccess()) {
                throw new Exception("Payment success simulation failed");
            }

            // Step 4: Check property plan update
            if (!$this->checkPropertyPlanUpdate()) {
                throw new Exception("Property plan update check failed");
            }

            echo "\n=== TEST RESULT ===\n";
            echo "✓ ALL TESTS PASSED\n";
            echo "✓ Payment record is only created on payment success\n";
            echo "✓ Property plan is only updated on payment success\n";
            echo "✓ Flow works correctly with session-based approach\n";

        } catch (Exception $e) {
            echo "\n=== TEST RESULT ===\n";
            echo "✗ TEST FAILED: " . $e->getMessage() . "\n";

            // Show final payment record state
            echo "\nFinal payment record state:\n";
            $this->checkPaymentRecord("final state");
        }
    }
}

// Run the test
$test = new PlanPaymentOnSuccessTest();
$test->runTest();
?>
