<?php
// Test Payment Creation API Endpoint
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Services\GenieBusinessPaymentService;
use App\Models\Property;

// Set headers for JSON response
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit();
}

try {
    // Get JSON input
    $input = json_decode(file_get_contents('php://input'), true);

    if (!$input) {
        throw new Exception('Invalid JSON input');
    }

    // Validate required fields
    $amount = floatval($input['amount'] ?? 0);
    $currency = $input['currency'] ?? 'LKR';
    $description = $input['description'] ?? 'Test Payment';
    $customerName = $input['customer_name'] ?? 'Test Customer';
    $customerEmail = $input['customer_email'] ?? 'test@example.com';

    if ($amount <= 0) {
        throw new Exception('Invalid amount');
    }

    // Get a test property
    $property = Property::first();
    if (!$property) {
        throw new Exception('No properties found in database');
    }

    // Initialize payment service
    $paymentService = new GenieBusinessPaymentService();

    // Generate unique ad ID for testing
    $adId = time() . '_' . rand(1000, 9999);

    // Create payment
    $paymentResult = $paymentService->createPayment(
        $amount * 100, // Convert to cents
        $description,
        $customerEmail,
        $customerName,
        $property->id,
        $adId,
        "http://127.0.0.1:8000/property/ads/{$adId}/payment/success"
    );

    if ($paymentResult['success']) {
        // Log the successful payment creation
        error_log("Test payment created: " . json_encode($paymentResult['data']));

        echo json_encode([
            'success' => true,
            'data' => $paymentResult['data'],
            'message' => 'Payment created successfully'
        ]);
    } else {
        throw new Exception($paymentResult['error'] ?? 'Payment creation failed');
    }

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
