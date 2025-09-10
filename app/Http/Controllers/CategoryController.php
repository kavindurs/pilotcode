<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Property;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $selectedCategoryId = $request->input('category_id');
        $selectedSubcategoryId = $request->input('subcategory_id');

        // New: Handle text inputs for categories and subcategories
        $categoryText = $request->input('category_text');
        $subcategoryText = $request->input('subcategory_text');

        // Get all categories for dropdown
        $allCategories = Category::where('is_active', 1)->orderBy('name')->get();

        // Get subcategories for dropdown (filtered by selected category if any)
        $allSubcategories = collect();
        if ($selectedCategoryId) {
            $allSubcategories = Category::find($selectedCategoryId)
                ?->subcategories()
                ->where('is_active', 1)
                ->orderBy('name')
                ->get() ?? collect();
        }

        // Get businesses/properties when subcategory is selected OR when text inputs are provided
        $businesses = collect();

        if ($selectedSubcategoryId || $subcategoryText || $categoryText) {
            $businesses = Property::query()
                ->where('status', 'approved') // Only show approved businesses
                ->when($selectedSubcategoryId, function($query) use ($selectedSubcategoryId) {
                    // Filter by selected subcategory ID
                    $query->where('subcategory', $selectedSubcategoryId);
                })
                ->when($subcategoryText && !$selectedSubcategoryId, function($query) use ($subcategoryText) {
                    // Filter by subcategory name if typed and no dropdown selection
                    $query->where('subcategory', 'like', "%{$subcategoryText}%");
                })
                ->when($categoryText && !$selectedCategoryId && !$subcategoryText, function($query) use ($categoryText) {
                    // Filter by category name if only category text is provided
                    $query->where('category', 'like', "%{$categoryText}%");
                })
                ->when($search, function($query) use ($search) {
                    $query->where(function($q) use ($search) {
                        $q->where('business_name', 'like', "%{$search}%")
                          ->orWhere('city', 'like', "%{$search}%")
                          ->orWhere('country', 'like', "%{$search}%");
                    });
                })
                ->orderBy('business_name')
                ->paginate(12);
        }

        $categories = Category::with(['subcategories' => function($query) use ($search, $selectedSubcategoryId, $subcategoryText) {
            // Only load active subcategories
            $query->where('is_active', 1);
            if ($search) {
                $query->where('name', 'like', "%{$search}%");
            }
            if ($selectedSubcategoryId) {
                $query->where('id', $selectedSubcategoryId);
            }
            if ($subcategoryText && !$selectedSubcategoryId) {
                $query->where('name', 'like', "%{$subcategoryText}%");
            }
        }])
        ->where('is_active', 1) // Only load active categories
        ->when($selectedCategoryId, function($query) use ($selectedCategoryId) {
            $query->where('id', $selectedCategoryId);
        })
        ->when($categoryText && !$selectedCategoryId, function($query) use ($categoryText) {
            $query->where('name', 'like', "%{$categoryText}%");
        })
        ->when($search, function($query) use ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhereHas('subcategories', function($query) use ($search) {
                      $query->where('name', 'like', "%{$search}%")
                            ->where('is_active', 1); // Ensure subcategories are active in search
                  });
        })
        ->get();

        return view('categories.index', compact(
            'categories',
            'search',
            'allCategories',
            'allSubcategories',
            'selectedCategoryId',
            'selectedSubcategoryId',
            'businesses',
            'categoryText',
            'subcategoryText'
        ));
    }

    /**
     * Get subcategories for a specific category (AJAX endpoint)
     */
    public function getSubcategories(Request $request)
    {
        $categoryId = $request->input('category_id');

        if (!$categoryId) {
            return response()->json([]);
        }

        $subcategories = Category::find($categoryId)
            ?->subcategories()
            ->where('is_active', 1)
            ->orderBy('name')
            ->get(['id', 'name']) ?? collect();

        return response()->json($subcategories);
    }
}
