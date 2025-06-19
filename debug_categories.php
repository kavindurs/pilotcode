<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    echo "Testing categories and subcategories for edit form...\n\n";

    // Test categories
    echo "=== CATEGORIES ===\n";
    $categoriesWithActive = \App\Models\Category::where('is_active', true)->orderBy('name')->get();
    echo "Categories with is_active=true: " . count($categoriesWithActive) . "\n";

    $allCategories = \App\Models\Category::orderBy('name')->get();
    echo "All categories: " . count($allCategories) . "\n";

    if (count($categoriesWithActive) === 0 && count($allCategories) > 0) {
        echo "Issue: No categories have is_active=true, but categories exist\n";
        echo "Sample categories:\n";
        foreach ($allCategories->take(5) as $cat) {
            echo "- ID: {$cat->id}, Name: {$cat->name}, is_active: " . ($cat->is_active ?? 'NULL') . "\n";
        }
    } else {
        echo "Active categories:\n";
        foreach ($categoriesWithActive->take(5) as $cat) {
            echo "- ID: {$cat->id}, Name: {$cat->name}\n";
        }
    }

    echo "\n=== SUBCATEGORIES ===\n";
    $subcategoriesWithActive = \App\Models\Subcategory::where('is_active', true)->orderBy('name')->get();
    echo "Subcategories with is_active=true: " . count($subcategoriesWithActive) . "\n";

    $allSubcategories = \App\Models\Subcategory::orderBy('name')->get();
    echo "All subcategories: " . count($allSubcategories) . "\n";

    if (count($subcategoriesWithActive) === 0 && count($allSubcategories) > 0) {
        echo "Issue: No subcategories have is_active=true, but subcategories exist\n";
        echo "Sample subcategories:\n";
        foreach ($allSubcategories->take(5) as $sub) {
            echo "- ID: {$sub->id}, Name: {$sub->name}, is_active: " . ($sub->is_active ?? 'NULL') . ", category_id: {$sub->category_id}\n";
        }
    } else {
        echo "Active subcategories:\n";
        foreach ($subcategoriesWithActive->take(5) as $sub) {
            echo "- ID: {$sub->id}, Name: {$sub->name}, category_id: {$sub->category_id}\n";
        }
    }

    // Test a sample property
    echo "\n=== SAMPLE PROPERTY ===\n";
    $property = \App\Models\Property::first();
    if ($property) {
        echo "Property ID: {$property->id}\n";
        echo "Business Name: {$property->business_name}\n";
        echo "Category ID: " . ($property->category_id ?? 'NULL') . "\n";
        echo "Subcategory ID: " . ($property->subcategory_id ?? 'NULL') . "\n";

        if ($property->category_id) {
            $category = \App\Models\Category::find($property->category_id);
            echo "Category Name: " . ($category ? $category->name : 'NOT FOUND') . "\n";
        }

        if ($property->subcategory_id) {
            $subcategory = \App\Models\Subcategory::find($property->subcategory_id);
            echo "Subcategory Name: " . ($subcategory ? $subcategory->name : 'NOT FOUND') . "\n";
        }
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
