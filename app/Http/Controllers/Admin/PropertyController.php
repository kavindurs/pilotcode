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

        // Filter properties by property_type and order by Not Approved first
        $properties = Property::where('property_type', $tab)
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

        // Convert category_id to category name if provided
        if ($request->filled('category_id')) {
            $category = \App\Models\Category::find($request->category_id);
            if ($category) {
                $validatedData['category'] = $category->name;
            }
            unset($validatedData['category_id']);
        }

        // Convert subcategory_id to subcategory name if provided
        if ($request->filled('subcategory_id')) {
            $subcategory = \App\Models\Subcategory::find($request->subcategory_id);
            if ($subcategory) {
                $validatedData['subcategory'] = $subcategory->name;
            }
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
}
