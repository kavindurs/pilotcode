<?php

require_once 'vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
if (file_exists(__DIR__ . '/.env')) {
    $dotenv->load();
}

// Create a minimal Laravel application for database access
$app = new Illuminate\Foundation\Application(__DIR__);

$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    App\Http\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

// Bootstrap the application
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Testing Payment Creation Debug ===\n";

try {
    // Test data
    $testData = [
        'plan_id' => 1,
        'property_id' => 1,
        'business_email' => 'test@example.com',
        'customer_email' => 'test@example.com',
        'customer_name' => 'Test Customer',
        'amount' => 10.00,
        'currency' => 'USD',
        'status' => 'pending',
        'order_id' => 'TEST_' . time(),
        'payment_method' => 'genie_business'
    ];

    echo "Attempting to create payment with data:\n";
    print_r($testData);

    // Try to create the payment
    $payment = App\Models\Payment::create($testData);

    echo "Payment created successfully with ID: " . $payment->id . "\n";
    echo "Business Email: " . $payment->business_email . "\n";

} catch (Exception $e) {
    echo "Error creating payment:\n";
    echo "Message: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";

    // Also check SQL state if it's a database error
    if ($e instanceof PDOException) {
        echo "SQL State: " . $e->errorInfo[0] . "\n";
        echo "Driver Code: " . $e->errorInfo[1] . "\n";
        echo "Driver Message: " . $e->errorInfo[2] . "\n";
    }
}

echo "\n=== Debug Complete ===\n";
