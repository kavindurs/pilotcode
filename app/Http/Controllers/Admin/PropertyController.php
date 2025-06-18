<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
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

        return redirect()->back()->with('success', 'Property approved and email sent.');
    }

    public function reject($id)
    {
        $property = Property::findOrFail($id);
        $property->status = 'Rejected';
        $property->save();

        // Send rejection email
        Mail::to($property->business_email)->send(new PropertyStatusMail($property, 'rejected'));

        return redirect()->back()->with('success', 'Property rejected and email sent.');
    }

    public function edit($id)
    {
        $property = \App\Models\Property::findOrFail($id);
        return view('admin.properties.edit', compact('property'));
    }

    public function update(Request $request, $id)
    {
        $property = Property::findOrFail($id);
        // Validate and update the property
        $data = $request->validate([
            'business_name'  => 'required|string|max:255',
            'city'           => 'required|string|max:255',
            'country'        => 'required|string|max:255',
            'business_email' => 'required|email',
            // add other validations as needed
        ]);

        $property->update($data);

        return redirect()->route('admin.properties.index')->with('success', 'Property updated successfully.');
    }
}
