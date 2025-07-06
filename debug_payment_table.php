<?php

// Simple debug script for payment creation issue
session_start();

echo "=== Payment Creation Debug ===\n\n";

try {
    // Use direct database path
    $dbPath = 'database/database.sqlite';

    echo "Database file exists: " . (file_exists($dbPath) ? 'YES' : 'NO') . "\n";
    echo "Database path: " . realpath($dbPath) . "\n\n";

    // Create PDO connection directly
    $pdo = new PDO('sqlite:' . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "=== Checking payments table structure ===\n";
    $stmt = $pdo->query("PRAGMA table_info(payments)");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "Columns in payments table:\n";
    foreach($columns as $column) {
        echo "- {$column['name']} | {$column['type']} | " .
             ($column['notnull'] ? 'NOT NULL' : 'NULL') . " | " .
             "Default: " . ($column['dflt_value'] ?: 'None') . "\n";
    }

    echo "\n=== Testing payment creation with business_email ===\n";

    // Test payment data
    $testData = [
        'plan_id' => 1,
        'property_id' => 1,
        'business_email' => 'test@example.com',
        'customer_email' => 'test@example.com',
        'customer_name' => 'Test Customer',
        'amount' => 10.00,
        'currency' => 'USD',
        'status' => 'pending',
        'order_id' => 'DEBUG_' . time(),
        'payment_method' => 'genie_business',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
    ];

    echo "Test data:\n";
    foreach($testData as $key => $value) {
        echo "- $key: $value\n";
    }

    // Create SQL insert statement
    $columns = array_keys($testData);
    $placeholders = ':' . implode(', :', $columns);
    $sql = "INSERT INTO payments (" . implode(', ', $columns) . ") VALUES ($placeholders)";

    echo "\nSQL: $sql\n";

    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute($testData);

    if ($result) {
        $paymentId = $pdo->lastInsertId();
        echo "\nPayment created successfully with ID: $paymentId\n";

        // Verify the record was created
        $stmt = $pdo->prepare("SELECT * FROM payments WHERE id = ?");
        $stmt->execute([$paymentId]);
        $payment = $stmt->fetch(PDO::FETCH_ASSOC);

        echo "Retrieved payment record:\n";
        foreach($payment as $key => $value) {
            echo "- $key: $value\n";
        }
    } else {
        echo "Failed to create payment\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";

    if ($e instanceof PDOException) {
        echo "SQL State: " . $e->getCode() . "\n";
    }
}

echo "\n=== Debug Complete ===\n";
