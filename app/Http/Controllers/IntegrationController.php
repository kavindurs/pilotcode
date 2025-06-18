<?php

namespace App\Http\Controllers;

use App\Models\HtmlIntegration;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class IntegrationController extends Controller
{
    /**
     * Display a listing of HTML integrations.
     */
    public function index()
    {
        // Get the property ID from the session
        $propertyId = session('property_id');

        if (!$propertyId) {
            return redirect()->route('property.login')
                ->with('error', 'Please login to access your HTML integrations.');
        }

        // Retrieve the property
        $property = Property::find($propertyId);

        if (!$property) {
            return redirect()->route('property.dashboard')
                ->with('error', 'Property not found.');
        }

        // Get active plan and character limit
        $activePlan = $property->getActivePlan();
        $characterLimit = $property->getHtmlCharacterLimit();

        // Get integrations for this property
        $integrations = HtmlIntegration::where('property_id', $propertyId)
                            ->orderBy('created_at', 'desc')
                            ->get();

        // Find the active integration
        $activeIntegration = HtmlIntegration::where('property_id', $propertyId)
                                  ->where('is_active', true)
                                  ->first();

        return view('property.integrations', compact('integrations', 'property', 'activePlan', 'characterLimit', 'activeIntegration'));
    }

    /**
     * Show the form for creating a new integration.
     */
    public function create()
    {
        $propertyId = session('property_id');

        if (!$propertyId) {
            return redirect()->route('property.login')
                ->with('error', 'Please login to add integrations.');
        }

        $property = Property::find($propertyId);

        if (!$property) {
            return redirect()->route('property.dashboard')
                ->with('error', 'Property not found.');
        }

        // Get character limit based on plan
        $activePlan = $property->getActivePlan();
        $characterLimit = $property->getHtmlCharacterLimit();

        // Get placement options
        $placementOptions = [
            'header' => 'Header',
            'footer' => 'Footer',
            'sidebar' => 'Sidebar',
            'custom' => 'Custom'
        ];

        return view('property.integration-form', compact('placementOptions', 'activePlan', 'characterLimit'));
    }

    /**
     * Store a newly created integration.
     */
    public function store(Request $request)
    {
        $propertyId = session('property_id');

        if (!$propertyId) {
            return redirect()->route('property.login')
                ->with('error', 'Please login to add integrations.');
        }

        $property = Property::find($propertyId);

        if (!$property) {
            return redirect()->route('property.dashboard')
                ->with('error', 'Property not found.');
        }

        // Get character limit based on plan
        $characterLimit = $property->getHtmlCharacterLimit();

        // Validate with dynamic character limit based on plan
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:100',
            'html_content' => "required|string|max:{$characterLimit}",
            'placement' => 'required|string|max:50',
            'integration_type' => 'nullable|string|max:50',
            'is_active' => 'nullable|boolean',
        ], [
            'html_content.max' => "The HTML content cannot exceed {$characterLimit} characters for your current plan."
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // If this integration is being marked as active, deactivate all other integrations
        if ($request->has('is_active')) {
            HtmlIntegration::where('property_id', $propertyId)
                ->update(['is_active' => false]);
        }

        // Create new integration
        $integration = new HtmlIntegration();
        $integration->title = $request->title;
        $integration->html_content = $request->html_content;
        $integration->placement = $request->placement;
        $integration->integration_type = $request->integration_type ?? 'custom';
        $integration->is_active = $request->has('is_active') ? 1 : 0;
        $integration->property_id = $propertyId;
        $integration->save();

        return redirect()->route('property.integrations')
            ->with('success', 'HTML integration created successfully.');
    }

    /**
     * Show the form for editing the specified integration.
     */
    public function edit($id)
    {
        $propertyId = session('property_id');

        if (!$propertyId) {
            return redirect()->route('property.login')
                ->with('error', 'Please login to edit integrations.');
        }

        $property = Property::find($propertyId);

        if (!$property) {
            return redirect()->route('property.dashboard')
                ->with('error', 'Property not found.');
        }

        $integration = HtmlIntegration::find($id);

        if (!$integration) {
            return redirect()->route('property.integrations')
                ->with('error', 'Integration not found.');
        }

        // Check if this integration belongs to the property
        if ($integration->property_id != $propertyId) {
            return redirect()->route('property.integrations')
                ->with('error', 'You do not have permission to edit this integration.');
        }

        // Get character limit based on plan
        $activePlan = $property->getActivePlan();
        $characterLimit = $property->getHtmlCharacterLimit();

        // Check if current content exceeds the limit of current plan
        if (strlen($integration->html_content) > $characterLimit) {
            return redirect()->route('property.integrations')
                ->with('error', "This integration's HTML content exceeds your current plan's limit of {$characterLimit} characters. Please upgrade your plan to edit this integration.");
        }

        // Get placement options
        $placementOptions = [
            'header' => 'Header',
            'footer' => 'Footer',
            'sidebar' => 'Sidebar',
            'custom' => 'Custom'
        ];

        return view('property.integration-form', compact('integration', 'placementOptions', 'activePlan', 'characterLimit'));
    }

    /**
     * Update the specified integration.
     */
    public function update(Request $request, $id)
    {
        $propertyId = session('property_id');

        if (!$propertyId) {
            return redirect()->route('property.login')
                ->with('error', 'Please login to update integrations.');
        }

        $property = Property::find($propertyId);

        if (!$property) {
            return redirect()->route('property.dashboard')
                ->with('error', 'Property not found.');
        }

        $integration = HtmlIntegration::find($id);

        if (!$integration) {
            return redirect()->route('property.integrations')
                ->with('error', 'Integration not found.');
        }

        // Check if this integration belongs to the property
        if ($integration->property_id != $propertyId) {
            return redirect()->route('property.integrations')
                ->with('error', 'You do not have permission to update this integration.');
        }

        // Get character limit based on plan
        $characterLimit = $property->getHtmlCharacterLimit();

        // Validate with dynamic character limit based on plan
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:100',
            'html_content' => "required|string|max:{$characterLimit}",
            'placement' => 'required|string|max:50',
            'integration_type' => 'nullable|string|max:50',
            'is_active' => 'nullable|boolean',
        ], [
            'html_content.max' => "The HTML content cannot exceed {$characterLimit} characters for your current plan."
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // If this integration is being marked as active, deactivate all other integrations
        if ($request->has('is_active') && !$integration->is_active) {
            HtmlIntegration::where('property_id', $propertyId)
                ->where('id', '!=', $id)
                ->update(['is_active' => false]);
        }

        // Update integration
        $integration->title = $request->title;
        $integration->html_content = $request->html_content;
        $integration->placement = $request->placement;
        $integration->integration_type = $request->integration_type ?? 'custom';
        $integration->is_active = $request->has('is_active') ? 1 : 0;
        $integration->save();

        return redirect()->route('property.integrations')
            ->with('success', 'HTML integration updated successfully.');
    }

    /**
     * Remove the specified integration.
     */
    public function destroy($id)
    {
        $propertyId = session('property_id');

        if (!$propertyId) {
            return redirect()->route('property.login')
                ->with('error', 'Please login to delete integrations.');
        }

        $property = Property::find($propertyId);

        if (!$property) {
            return redirect()->route('property.dashboard')
                ->with('error', 'Property not found.');
        }

        $integration = HtmlIntegration::find($id);

        if (!$integration) {
            return redirect()->route('property.integrations')
                ->with('error', 'Integration not found.');
        }

        // Check if this integration belongs to the property
        if ($integration->property_id != $propertyId) {
            return redirect()->route('property.integrations')
                ->with('error', 'You do not have permission to delete this integration.');
        }

        // Delete integration
        $integration->delete();

        return redirect()->route('property.integrations')
            ->with('success', 'HTML integration deleted successfully.');
    }
}
