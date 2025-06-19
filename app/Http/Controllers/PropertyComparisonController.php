<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Rate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PropertyComparisonController extends Controller
{
    /**
     * Get properties for comparison dropdown
     */
    public function getProperties(Request $request)
    {
        $query = Property::select('id', 'business_name', 'category', 'subcategory', 'city', 'country')
            ->where('status', 'Approved');

        // If search term is provided, filter by business name
        if ($request->has('search') && !empty($request->search)) {
            $query->where('business_name', 'LIKE', '%' . $request->search . '%');
        }

        // If category is provided, filter by category name
        if ($request->has('category') && !empty($request->category)) {
            // Get category ID from name
            $categoryId = \App\Models\Category::where('name', $request->category)->value('id');
            if ($categoryId) {
                $query->where('category', $categoryId);
            }
        }

        // If subcategory is provided, filter by subcategory ID
        if ($request->has('subcategory') && !empty($request->subcategory)) {
            $query->where('subcategory', $request->subcategory);
        }

        $properties = $query->orderBy('business_name')->take(20)->get();

        // Add category names to the response by looking up categories
        $categoryIds = $properties->pluck('category')->unique()->filter();
        $categories = \App\Models\Category::whereIn('id', $categoryIds)->pluck('name', 'id');

        // Add subcategory names
        $subcategoryIds = $properties->pluck('subcategory')->unique()->filter();
        $subcategories = \App\Models\Subcategory::whereIn('id', $subcategoryIds)->pluck('name', 'id');

        $properties->each(function($property) use ($categories, $subcategories) {
            $property->category_name = $categories[$property->category] ?? 'Unknown';
            $property->subcategory_name = $subcategories[$property->subcategory] ?? null;
        });

        return response()->json($properties);
    }

    /**
     * Compare two properties
     */
    public function compare(Request $request)
    {
        $request->validate([
            'property1_id' => 'required|exists:properties,id',
            'property2_id' => 'required|exists:properties,id|different:property1_id'
        ]);

        $property1 = $this->getPropertyWithStats($request->property1_id);
        $property2 = $this->getPropertyWithStats($request->property2_id);

        if (!$property1 || !$property2) {
            return response()->json(['error' => 'One or both properties not found'], 404);
        }

        return response()->json([
            'property1' => $property1,
            'property2' => $property2
        ]);
    }

    /**
     * Get property details with review statistics
     */
    private function getPropertyWithStats($propertyId)
    {
        $property = Property::select(
            'id',
            'business_name',
            'city',
            'country',
            'category',
            'subcategory',
            'domain',
            'profile_picture',
            'business_email',
            'property_type',
            'annual_revenue',
            'employee_count'
        )->where('id', $propertyId)
         ->where('status', 'Approved')
         ->first();

        if (!$property) {
            return null;
        }

        // Add category and subcategory names by looking up the records
        if ($property->category) {
            $category = \App\Models\Category::find($property->category);
            $property->category_name = $category ? $category->name : 'Unknown';
        } else {
            $property->category_name = 'Unknown';
        }

        if ($property->subcategory) {
            $subcategory = \App\Models\Subcategory::find($property->subcategory);
            $property->subcategory_name = $subcategory ? $subcategory->name : null;
        } else {
            $property->subcategory_name = null;
        }

        // Get review statistics with enhanced analysis
        $reviewStats = Rate::where('property_id', $propertyId)
            ->where('status', 'Approved')
            ->selectRaw('
                COUNT(*) as total_reviews,
                AVG(rate) as average_rating,
                SUM(CASE WHEN rate = 5 THEN 1 ELSE 0 END) as five_star,
                SUM(CASE WHEN rate = 4 THEN 1 ELSE 0 END) as four_star,
                SUM(CASE WHEN rate = 3 THEN 1 ELSE 0 END) as three_star,
                SUM(CASE WHEN rate = 2 THEN 1 ELSE 0 END) as two_star,
                SUM(CASE WHEN rate = 1 THEN 1 ELSE 0 END) as one_star,
                SUM(CASE WHEN rate >= 4 THEN 1 ELSE 0 END) as positive_reviews,
                SUM(CASE WHEN rate = 3 THEN 1 ELSE 0 END) as neutral_reviews,
                SUM(CASE WHEN rate <= 2 THEN 1 ELSE 0 END) as negative_reviews
            ')
            ->first();

        // Get latest reviews
        $latestReviews = Rate::with('user')
            ->where('property_id', $propertyId)
            ->where('status', 'Approved')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        // Get products for this property
        $products = \App\Models\Product::where('property_id', $propertyId)
            ->select('id', 'name', 'category', 'price', 'stock_quantity')
            ->orderBy('created_at', 'desc')
            ->take(10) // Limit to 10 products for comparison
            ->get();

        $property->review_stats = $reviewStats;
        $property->latest_reviews = $latestReviews;
        $property->products = $products;

        return $property;
    }

    /**
     * Get all categories for filter
     */
    public function getCategories()
    {
        $categories = \App\Models\Category::where('is_active', 1)
            ->orderBy('name')
            ->pluck('name', 'id');

        return response()->json($categories);
    }
}
