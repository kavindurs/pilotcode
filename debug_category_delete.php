<?php
require_once 'vendor/autoload.php';

// Load Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    // Get a sample category
    $category = \App\Models\Category::first();

    if ($category) {
        echo "Sample Category for Testing:\n";
        echo "==========================\n";
        echo "ID: {$category->id}\n";
        echo "Name: {$category->name}\n";
        echo "Is Active: {$category->is_active}\n";
        echo "Subcategories Count: " . $category->subcategories()->count() . "\n";

        // Check if route exists
        try {
            $url = route('admin.categories.destroy', $category->id);
            echo "Delete Route URL: {$url}\n";
        } catch (Exception $e) {
            echo "Route Error: " . $e->getMessage() . "\n";
        }

    } else {
        echo "No categories found in database.\n";
    }

    // List all categories for reference
    $allCategories = \App\Models\Category::all();
    echo "\nAll Categories:\n";
    echo "===============\n";
    foreach ($allCategories as $cat) {
        echo "- ID: {$cat->id}, Name: {$cat->name}, Active: {$cat->is_active}, Subcategories: " . $cat->subcategories()->count() . "\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
