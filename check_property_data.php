<?php
require_once 'vendor/autoload.php';

// Load Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    // Get a sample property
    $property = \App\Models\Property::first();

    if ($property) {
        echo "Sample Property Data:\n";
        echo "====================\n";
        echo "ID: {$property->id}\n";
        echo "Business Name: {$property->business_name}\n";
        echo "Category: {$property->category}\n";
        echo "Subcategory: {$property->subcategory}\n";

        // Try to find matching category and subcategory IDs
        $categoryModel = \App\Models\Category::where('name', $property->category)->first();
        $subcategoryModel = \App\Models\Subcategory::where('name', $property->subcategory)->first();

        echo "\nMatching IDs:\n";
        echo "Category ID: " . ($categoryModel ? $categoryModel->id : 'Not found') . "\n";
        echo "Subcategory ID: " . ($subcategoryModel ? $subcategoryModel->id : 'Not found') . "\n";

    } else {
        echo "No properties found.\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
