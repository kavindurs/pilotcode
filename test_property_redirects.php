<?php
require_once 'vendor/autoload.php';

// Check that all our PropertyController methods have the correct redirect
$file = file_get_contents('app/Http/Controllers/Admin/PropertyController.php');

echo "=== Checking PropertyController redirects ===\n";

// Check approve method
if (strpos($file, "return redirect()->route('admin.properties.index')") !== false) {
    echo "✓ approve() method has correct redirect\n";
} else {
    echo "✗ approve() method missing correct redirect\n";
}

// Check reject method
if (preg_match('/public function reject.*?return redirect\(\)->route\(\'admin\.properties\.index\'\)/', $file, $matches)) {
    echo "✓ reject() method has correct redirect\n";
} else {
    echo "✗ reject() method missing correct redirect\n";
}

// Check update method
if (preg_match('/public function update.*?return redirect\(\)->route\(\'admin\.properties\.index\'\)/', $file, $matches)) {
    echo "✓ update() method has correct redirect\n";
} else {
    echo "✗ update() method missing correct redirect\n";
}

// Check destroy method
if (preg_match('/public function destroy.*?return redirect\(\)->route\(\'admin\.properties\.index\'\)/', $file, $matches)) {
    echo "✓ destroy() method has correct redirect\n";
} else {
    echo "✗ destroy() method missing correct redirect\n";
}

echo "\n=== Checking routes file for conflicts ===\n";

$routes = file_get_contents('routes/web.php');

// Check for duplicate resource routes
$resourceCount = substr_count($routes, "Route::resource('properties'");
echo "Number of resource routes for properties: $resourceCount\n";

if ($resourceCount > 1) {
    echo "⚠️  WARNING: Multiple resource routes detected - may cause conflicts\n";
} else {
    echo "✓ Single resource route detected\n";
}

// Check for admin prefix group
if (strpos($routes, "Route::prefix('admin')") !== false) {
    echo "✓ Admin prefix group found\n";
} else {
    echo "✗ Admin prefix group not found\n";
}

echo "\n=== Complete ===\n";
?>
