<?php

namespace App\Http\Controllers;

use App\Models\BusinessClaim;
use App\Models\Property;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BusinessClaimController extends Controller
{
    /**
     * Show the business claim form for a specific property
     */
    public function create(Request $request, $propertyId)
    {
        $property = Property::findOrFail($propertyId);

        // Check if property is claimable
        if (!in_array($property->status, ['Not Approved & Not Claimed', 'Not Claimed & Rejected', 'Not Claimed'])) {
            return redirect()->back()->with('error', 'This business is not available for claiming.');
        }

        // Get categories and subcategories for the form
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        $subcategories = Subcategory::where('is_active', true)->orderBy('name')->get();

        return view('business-claim.create', compact('property', 'categories', 'subcategories'));
    }

    /**
     * Store a new business claim
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'business_name' => 'required|string|max:255',
            'business_email' => 'required|email|max:255',
            'property_type' => 'required|in:web,physical',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'zip_code' => 'nullable|string|max:20',
            'country' => 'required|string|max:255',
            'annual_revenue' => 'nullable|string|max:255',
            'employee_count' => 'nullable|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'nullable|exists:subcategories,id',
            'domain' => 'nullable|url|max:255',
            'business_document' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
        ]);

        // Check if property is still claimable
        $property = Property::findOrFail($validatedData['property_id']);
        if (!in_array($property->status, ['Not Approved & Not Claimed', 'Not Claimed & Rejected', 'Not Claimed'])) {
            return redirect()->back()->with('error', 'This business is no longer available for claiming.');
        }

        // Handle file upload for physical businesses
        if ($request->hasFile('business_document')) {
            $documentPath = $request->file('business_document')->store('business_documents', 'public');
            $validatedData['business_document'] = $documentPath;
        }

        // Create the business claim
        BusinessClaim::create($validatedData);

        return redirect()->route('business-claim.success')->with('success', 'Your business claim has been submitted successfully! We will review it and get back to you soon.');
    }

    /**
     * Show claim success page
     */
    public function success()
    {
        return view('business-claim.success');
    }

    /**
     * Public search for claimable businesses
     */
    public function search(Request $request)
    {
        $query = $request->input('search');
        $properties = collect();

        if ($query && strlen($query) >= 2) {
            $properties = Property::where(function($q) use ($query) {
                    $q->where('business_name', 'LIKE', "%{$query}%")
                      ->orWhere('category', 'LIKE', "%{$query}%")
                      ->orWhere('subcategory', 'LIKE', "%{$query}%")
                      ->orWhere('city', 'LIKE', "%{$query}%")
                      ->orWhere('country', 'LIKE', "%{$query}%")
                      ->orWhere('domain', 'LIKE', "%{$query}%");
                })
                ->whereIn('status', ['Not Approved & Not Claimed', 'Not Claimed & Rejected', 'Not Claimed'])
                ->orderBy('business_name')
                ->paginate(12);
        }

        return view('business-claim.search', [
            'properties' => $properties,
            'query' => $query
        ]);
    }

    /**
     * Get subcategories for a category (AJAX)
     */
    public function getSubcategories($categoryId)
    {
        $subcategories = Subcategory::where('category_id', $categoryId)
            ->where('is_active', true)
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return response()->json($subcategories);
    }
}
