<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Mail\PropertyStatusMail;

class PropertyController extends Controller
{
    public function index(Request $request)
    {
        // Get the tab filter; default to 'web'
        $tab = $request->query('tab', 'web');

        // Filter properties by property_type, exclude specific claimed/not claimed statuses, and order by Not Approved first
        $properties = Property::where('property_type', $tab)
            ->whereNotIn('status', ['Not Approved & Not Claimed', 'Not Claimed & Rejected', 'Not Claimed'])
            ->orderByRaw("CASE WHEN status = 'Not Approved' THEN 0 ELSE 1 END")
            ->paginate(10);

        return view('admin.properties.index', compact('properties', 'tab'));
    }

    public function approve($id)
    {
        $property = Property::findOrFail($id);
        $property->status = 'Approved';
        $property->save();

        // Send approval email
        Mail::to($property->business_email)->send(new PropertyStatusMail($property, 'approved'));

        return redirect()->route('admin.properties.index')->with('success', 'Property approved and email sent.');
    }

    public function reject($id)
    {
        $property = Property::findOrFail($id);
        $property->status = 'Rejected';
        $property->save();

        // Send rejection email
        Mail::to($property->business_email)->send(new PropertyStatusMail($property, 'rejected'));

        return redirect()->route('admin.properties.index')->with('success', 'Property rejected and email sent.');
    }

    public function show($id)
    {
        $property = Property::findOrFail($id);
        return view('admin.properties.show', compact('property'));
    }

    public function edit($property)
    {
        $property = \App\Models\Property::findOrFail($property);
        $categories = \App\Models\Category::where('is_active', true)->orderBy('name')->get();
        $subcategories = \App\Models\Subcategory::where('is_active', true)->orderBy('name')->get();

        return view('admin.properties.edit', compact('property', 'categories', 'subcategories'));
    }

    public function update(Request $request, $property)
    {
        $property = Property::findOrFail($property);

        // Validate the request
        $validatedData = $request->validate([
            'property_type' => 'required|in:web,physical',
            'status' => 'required|in:Not Approved,Approved',
            'business_name' => 'required|string|max:255',
            'business_email' => 'required|email',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'zip_code' => 'nullable|string|max:20',
            'category_id' => 'nullable|exists:categories,id',
            'subcategory_id' => 'nullable|exists:subcategories,id',
            'annual_revenue' => 'nullable|string',
            'employee_count' => 'nullable|string',
            'domain' => 'nullable|string|max:255',
            'document' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'referred_by' => 'nullable|string|max:255',
            'plan_id' => 'nullable|integer',
        ]);

        // Convert category_id to category ID for database storage
        if ($request->filled('category_id')) {
            $validatedData['category'] = $request->category_id;
            unset($validatedData['category_id']);
        }

        // Convert subcategory_id to subcategory ID for database storage
        if ($request->filled('subcategory_id')) {
            $validatedData['subcategory'] = $request->subcategory_id;
            unset($validatedData['subcategory_id']);
        }

        // Handle file upload
        if ($request->hasFile('document')) {
            // Delete old document if exists
            if ($property->document_path) {
                Storage::disk('public')->delete($property->document_path);
            }

            $path = $request->file('document')->store('documents', 'public');
            $validatedData['document_path'] = $path;
        }

        $property->update($validatedData);

        // Check if this is an AJAX request
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Property updated successfully.']);
        }

        // For regular form submissions, redirect back with success message
        return redirect()->route('admin.properties.index')->with('success', 'Property updated successfully.');
    }

    public function destroy($id)
    {
        $property = Property::findOrFail($id);

        // Delete associated document if exists
        if ($property->document_path) {
            Storage::disk('public')->delete($property->document_path);
        }

        $property->delete();

        return redirect()->route('admin.properties.index')->with('success', 'Property deleted successfully.');
    }

    // Claim Business functionality
    public function claimIndex(Request $request)
    {
        // Get the tab filter; default to 'web'
        $tab = $request->query('tab', 'web');
        $search = $request->query('search');

        // Start building the query for properties with claim-related statuses
        $query = Property::with(['category', 'subcategory'])
            ->where('property_type', $tab)
            ->whereIn('status', ['Not Approved & Not Claimed', 'Not Claimed & Rejected', 'Not Claimed']);

        // Add search functionality if search term is provided
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('business_name', 'LIKE', "%{$search}%")
                  ->orWhere('category', 'LIKE', "%{$search}%")
                  ->orWhere('subcategory', 'LIKE', "%{$search}%")
                  ->orWhere('city', 'LIKE', "%{$search}%")
                  ->orWhere('country', 'LIKE', "%{$search}%")
                  ->orWhere('domain', 'LIKE', "%{$search}%")
                  ->orWhere('business_email', 'LIKE', "%{$search}%")
                  ->orWhere('first_name', 'LIKE', "%{$search}%")
                  ->orWhere('last_name', 'LIKE', "%{$search}%");
            });
        }

        $properties = $query->orderByRaw("CASE WHEN status = 'Not Approved & Not Claimed' THEN 0 WHEN status = 'Not Claimed' THEN 1 ELSE 2 END")
            ->paginate(10);

        // Add category and subcategory active status for each property
        foreach ($properties as $property) {
            $this->addCategoryActiveStatus($property);
        }

        return view('admin.properties.claim-index', compact('properties', 'tab', 'search'));
    }

    /**
     * Add category and subcategory active status to property
     * This method adds is_category_active and is_subcategory_active attributes
     */
    private function addCategoryActiveStatus($property)
    {
        // Initialize as active by default
        $property->is_category_active = true;
        $property->is_subcategory_active = true;
        $property->category_name = null;
        $property->subcategory_name = null;

        // Check category status
        if ($property->category) {
            if (is_object($property->category)) {
                // Using relationship
                $property->is_category_active = $property->category->is_active ?? true;
                $property->category_name = $property->category->name;
            } else {
                // Using string field - need to look up the category
                $category = null;
                if (is_numeric($property->category)) {
                    $category = \App\Models\Category::find($property->category);
                } else {
                    $category = \App\Models\Category::where('name', $property->category)->first();
                }

                if ($category) {
                    $property->is_category_active = $category->is_active ?? true;
                    $property->category_name = $category->name;
                } else {
                    $property->category_name = $property->category;
                }
            }
        }

        // Check subcategory status
        if ($property->subcategory) {
            if (is_object($property->subcategory)) {
                // Using relationship
                $property->is_subcategory_active = $property->subcategory->is_active ?? true;
                $property->subcategory_name = $property->subcategory->name;
            } else {
                // Using string field - need to look up the subcategory
                $subcategory = null;
                if (is_numeric($property->subcategory)) {
                    $subcategory = \App\Models\Subcategory::find($property->subcategory);
                } else {
                    $subcategory = \App\Models\Subcategory::where('name', $property->subcategory)->first();
                }

                if ($subcategory) {
                    $property->is_subcategory_active = $subcategory->is_active ?? true;
                    $property->subcategory_name = $subcategory->name;
                } else {
                    $property->subcategory_name = $property->subcategory;
                }
            }
        }
    }

    public function approveForClaim($id)
    {
        $property = Property::findOrFail($id);

        // Store original email for notification
        $originalEmail = $property->business_email;

        // Generate unique business email by appending random value
        do {
            $randomSuffix = rand(1000, 9999);
            $newEmail = $originalEmail . $randomSuffix;
        } while (Property::where('business_email', $newEmail)->exists());

        $property->business_email = $newEmail;
        $property->status = 'Not Claimed';
        $property->save();

        // Send approval email to the original email (without system-generated numbers)
        Mail::to($originalEmail)->send(new PropertyStatusMail($property, 'approved_for_claim', $originalEmail));

        return redirect()->route('admin.properties.claim-index')->with('success', 'Property approved for claim and email sent.');
    }

    public function rejectForClaim($id)
    {
        $property = Property::findOrFail($id);

        // Store original email for notification
        $originalEmail = $property->business_email;

        // Generate unique business email by appending random value
        do {
            $randomSuffix = rand(1000, 9999);
            $newEmail = $originalEmail . $randomSuffix;
        } while (Property::where('business_email', $newEmail)->exists());

        $property->business_email = $newEmail;
        $property->status = 'Not Claimed & Rejected';
        $property->save();

        // Send rejection email to the original email (without system-generated numbers)
        Mail::to($originalEmail)->send(new PropertyStatusMail($property, 'rejected_for_claim', $originalEmail));

        return redirect()->route('admin.properties.claim-index')->with('success', 'Property rejected for claim and email sent.');
    }

    public function claimProperty($id)
    {
        $property = Property::findOrFail($id);

        // Redirect to edit page with claim flag
        return redirect()->route('admin.properties.claim-edit', ['property' => $id, 'claim' => 'true']);
    }    public function claimEdit($id, Request $request)
    {
        $property = Property::findOrFail($id);
        $categories = \App\Models\Category::all();

        // Get subcategories based on the property's current category
        $subcategories = collect();
        if ($property->category) {
            $category = \App\Models\Category::where('name', $property->category)->first();
            if ($category) {
                $subcategories = \App\Models\Subcategory::where('category_id', $category->id)->get();
            }
        }

        // Check if this is a claim action
        $isClaim = $request->query('claim') === 'true';

        // If AJAX request, return just the form content
        if ($request->ajax()) {
            return view('admin.properties.claim-edit-form', compact('property', 'categories', 'subcategories', 'isClaim'));
        }

        return view('admin.properties.claim-edit', compact('property', 'categories', 'subcategories', 'isClaim'));
    }

    public function claimUpdate(Request $request, $id)
    {
        $property = Property::findOrFail($id);

        $validatedData = $request->validate([
            'business_name' => 'required|string|max:255',
            'business_email' => 'required|email|max:255',
            'property_type' => 'required|in:web,physical',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'zip_code' => 'required|string|max:10',
            'annual_revenue' => 'nullable|string|max:255',
            'employee_count' => 'nullable|string|max:255',
            'category' => 'required|string|max:255',
            'subcategory' => 'required|string|max:255',
            'status' => 'required|in:Not Approved,Approved,Rejected,Not Claimed,Not Approved & Not Claimed,Not Claimed & Rejected',
            'password' => 'nullable|string|min:6',
            'domain' => 'nullable|url|max:255',
            'document' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
        ]);

        // Look up category and subcategory IDs by their names or IDs
        if (is_numeric($validatedData['category'])) {
            // Already an ID, keep as is
            $categoryId = $validatedData['category'];
        } else {
            // Convert name to ID
            $category = \App\Models\Category::where('name', $validatedData['category'])->first();
            $categoryId = $category ? $category->id : $validatedData['category'];
        }

        if (is_numeric($validatedData['subcategory'])) {
            // Already an ID, keep as is
            $subcategoryId = $validatedData['subcategory'];
        } else {
            // Convert name to ID
            $subcategory = \App\Models\Subcategory::where('name', $validatedData['subcategory'])->first();
            $subcategoryId = $subcategory ? $subcategory->id : $validatedData['subcategory'];
        }

        $validatedData['category'] = $categoryId;
        $validatedData['subcategory'] = $subcategoryId;

        // Handle password update if provided
        if ($request->filled('password')) {
            $validatedData['password'] = bcrypt($request->password);
        } else {
            unset($validatedData['password']);
        }

        // Handle document upload if provided
        if ($request->hasFile('document')) {
            // Delete old document if exists
            if ($property->document_path) {
                Storage::disk('public')->delete($property->document_path);
            }

            $documentPath = $request->file('document')->store('business_documents', 'public');
            $validatedData['document_path'] = $documentPath;
        }

        $property->update($validatedData);

        // Check if this is a claim action (status changed to "Approved" with new login credentials)
        if ($validatedData['status'] === 'Approved' && $request->filled('password')) {
            // Extract original email (remove system-generated numbers)
            $originalEmail = $this->cleanEmailAddress($property->business_email);

            // Send claim notification with new login details
            Mail::to($originalEmail)->send(new PropertyStatusMail(
                $property,
                'claimed',
                $originalEmail,
                $validatedData['business_email'], // new login email
                $request->password // new password (plain text for email)
            ));
        }

        // Handle AJAX requests
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Property updated successfully.'
            ]);
        }

        return redirect()->route('admin.properties.claim-index')->with('success', 'Property updated successfully.');
    }

    public function claimDestroy($id)
    {
        $property = Property::findOrFail($id);

        // Delete business logo if exists
        if ($property->business_logo) {
            Storage::disk('public')->delete($property->business_logo);
        }

        $property->delete();

        return redirect()->route('admin.properties.claim-index')->with('success', 'Property deleted successfully.');
    }

    /**
     * Clean email address by removing system-generated numbers
     * @param string $email
     * @return string
     */
    private function cleanEmailAddress($email)
    {
        // Remove trailing numbers that were added by the system
        return preg_replace('/\d+$/', '', $email);
    }

    public function getSubcategoriesByName($categoryName)
    {
        $category = \App\Models\Category::where('name', $categoryName)->first();

        if (!$category) {
            return response()->json([]);
        }

        $subcategories = \App\Models\Subcategory::where('category_id', $category->id)->get();

        return response()->json($subcategories);
    }
}
