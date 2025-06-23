<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\Subcategory;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');

        if (!$query) {
            return redirect()->back()->with('error', 'Please enter a search term');
        }

        // Search in properties table
        $properties = Property::where('business_name', 'LIKE', "%{$query}%")
            ->orWhere('category', 'LIKE', "%{$query}%")
            ->orWhere('subcategory', 'LIKE', "%{$query}%") // Use subcategory field instead of subcategory_id
            ->orWhere('city', 'LIKE', "%{$query}%")
            ->orWhere('country', 'LIKE', "%{$query}%")
            ->orWhere('domain', 'LIKE', "%{$query}%")
            ->where('status', 'Approved') // Only include approved businesses
            ->get();

        // Search in subcategories table
        $subcategories = Subcategory::where('name', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->where('is_active', 1) // Only include active subcategories
            ->get();

        // Get businesses in matching subcategories using subcategory name, not ID
        $subcategoryNames = $subcategories->pluck('name')->toArray();

        $relatedProperties = [];
        if (!empty($subcategoryNames)) {
            $relatedProperties = Property::whereIn('subcategory', $subcategoryNames)
                ->where('status', 'Approved')
                ->get();
        }

        // Merge and remove duplicates
        $allProperties = $properties->concat($relatedProperties)->unique('id');

        return view('search.results', [
            'query' => $query,
            'properties' => $allProperties,
            'subcategories' => $subcategories,
            'total' => $allProperties->count() + $subcategories->count()
        ]);
    }

    /**
     * Search for claimable properties only
     * Returns only properties with claim-related statuses for AJAX requests
     */
    public function claimSearch(Request $request)
    {
        $query = $request->input('query');

        if (!$query || strlen($query) < 2) {
            return response()->json([
                'properties' => [],
                'subcategories' => []
            ]);
        }

        // Search in properties table - only claim-related statuses
        $properties = Property::where(function($q) use ($query) {
                $q->where('business_name', 'LIKE', "%{$query}%")
                  ->orWhere('category', 'LIKE', "%{$query}%")
                  ->orWhere('subcategory', 'LIKE', "%{$query}%")
                  ->orWhere('city', 'LIKE', "%{$query}%")
                  ->orWhere('country', 'LIKE', "%{$query}%")
                  ->orWhere('domain', 'LIKE', "%{$query}%")
                  ->orWhere('business_email', 'LIKE', "%{$query}%")
                  ->orWhere('first_name', 'LIKE', "%{$query}%")
                  ->orWhere('last_name', 'LIKE', "%{$query}%");
            })
            ->whereIn('status', ['Not Approved & Not Claimed', 'Not Claimed & Rejected', 'Not Claimed'])
            ->select('id', 'business_name', 'category', 'subcategory', 'city', 'country', 'profile_picture', 'status')
            ->limit(10)
            ->get();

        // Search in subcategories table for claimable business categories
        $subcategories = Subcategory::where(function($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('description', 'LIKE', "%{$query}%");
            })
            ->where('is_active', 1)
            ->select('id', 'name', 'description', 'slug')
            ->limit(5)
            ->get();

        return response()->json([
            'properties' => $properties,
            'subcategories' => $subcategories
        ]);
    }
}
