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
}
