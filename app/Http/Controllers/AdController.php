<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AdController extends Controller
{
    /**
     * Display a listing of ads for the logged-in property
     */
    public function index()
    {
        // Check if property is logged in
        if (!session('property_id')) {
            return redirect()->route('property.login')
                ->with('error', 'Please login to access your ads dashboard.');
        }

        $property = Property::find(session('property_id'));

        if (!$property) {
            return redirect()->route('property.login')->with('error', 'Property not found.');
        }

        // Get ads for this property with pagination
        $ads = Ad::where('property_id', $property->id)
                 ->orderBy('created_at', 'desc')
                 ->paginate(10);

        // Get statistics
        $stats = [
            'total_ads' => Ad::where('property_id', $property->id)->count(),
            'active_ads' => Ad::where('property_id', $property->id)->where('status', 'active')->count(),
            'pending_ads' => Ad::where('property_id', $property->id)->where('status', 'pending')->count(),
            'total_views' => Ad::where('property_id', $property->id)->sum('total_views'),
            'total_clicks' => Ad::where('property_id', $property->id)->sum('total_clicks'),
            'total_budget' => Ad::where('property_id', $property->id)->sum('budget')
        ];

        return view('property.ads.index', compact('property', 'ads', 'stats'));
    }

    /**
     * Show the form for creating a new ad
     */
    public function create()
    {
        // Check if property is logged in
        if (!session('property_id')) {
            return redirect()->route('property.login')
                ->with('error', 'Please login to access ads creation.');
        }

        $property = Property::find(session('property_id'));

        if (!$property) {
            return redirect()->route('property.login')->with('error', 'Property not found.');
        }

        return view('property.ads.create', compact('property'));
    }

    /**
     * Store a newly created ad in storage
     */
    public function store(Request $request)
    {
        // Check if property is logged in
        if (!session('property_id')) {
            return redirect()->route('property.login')
                ->with('error', 'Please login to create ads.');
        }

        $property = Property::find(session('property_id'));

        if (!$property) {
            return redirect()->route('property.login')->with('error', 'Property not found.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'target_url' => 'nullable|url|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'ad_type' => 'required|in:banner,featured,promoted',
            'placement' => 'required|in:homepage,category,search_results,property_details',
            'budget' => 'required|numeric|min:0|max:10000',
            'cost_per_click' => 'required|numeric|min:0|max:100',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date'
        ]);

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('ads', 'public');
        }

        // Create the ad
        $ad = Ad::create([
            'property_id' => $property->id,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'target_url' => $validated['target_url'],
            'image_path' => $imagePath,
            'ad_type' => $validated['ad_type'],
            'placement' => $validated['placement'],
            'budget' => $validated['budget'],
            'cost_per_click' => $validated['cost_per_click'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'status' => 'pending'
        ]);

        return redirect()->route('property.ads.index')
            ->with('success', 'Ad created successfully! It will be reviewed by our admin team before going live.');
    }

    /**
     * Display the specified ad
     */
    public function show(Ad $ad)
    {
        // Check if property is logged in and owns this ad
        if (!session('property_id') || $ad->property_id !== session('property_id')) {
            return redirect()->route('property.login')
                ->with('error', 'Access denied.');
        }

        $property = Property::find(session('property_id'));

        return view('property.ads.show', compact('ad', 'property'));
    }

    /**
     * Show the form for editing the specified ad
     */
    public function edit(Ad $ad)
    {
        // Check if property is logged in and owns this ad
        if (!session('property_id') || $ad->property_id !== session('property_id')) {
            return redirect()->route('property.login')
                ->with('error', 'Access denied.');
        }

        // Only allow editing of pending or rejected ads
        if (!in_array($ad->status, ['pending', 'rejected'])) {
            return redirect()->route('property.ads.index')
                ->with('error', 'You can only edit pending or rejected ads.');
        }

        $property = Property::find(session('property_id'));

        return view('property.ads.edit', compact('ad', 'property'));
    }

    /**
     * Update the specified ad in storage
     */
    public function update(Request $request, Ad $ad)
    {
        // Check if property is logged in and owns this ad
        if (!session('property_id') || $ad->property_id !== session('property_id')) {
            return redirect()->route('property.login')
                ->with('error', 'Access denied.');
        }

        // Only allow editing of pending or rejected ads
        if (!in_array($ad->status, ['pending', 'rejected'])) {
            return redirect()->route('property.ads.index')
                ->with('error', 'You can only edit pending or rejected ads.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'target_url' => 'nullable|url|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'ad_type' => 'required|in:banner,featured,promoted',
            'placement' => 'required|in:homepage,category,search_results,property_details',
            'budget' => 'required|numeric|min:0|max:10000',
            'cost_per_click' => 'required|numeric|min:0|max:100',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date'
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($ad->image_path) {
                Storage::disk('public')->delete($ad->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('ads', 'public');
        }

        // Update the ad and reset status to pending for review
        $ad->update(array_merge($validated, [
            'status' => 'pending',
            'rejection_reason' => null
        ]));

        return redirect()->route('property.ads.index')
            ->with('success', 'Ad updated successfully! It will be reviewed again by our admin team.');
    }

    /**
     * Remove the specified ad from storage
     */
    public function destroy(Ad $ad)
    {
        // Check if property is logged in and owns this ad
        if (!session('property_id') || $ad->property_id !== session('property_id')) {
            return redirect()->route('property.login')
                ->with('error', 'Access denied.');
        }

        // Delete associated image if exists
        if ($ad->image_path) {
            Storage::disk('public')->delete($ad->image_path);
        }

        $ad->delete();

        return redirect()->route('property.ads.index')
            ->with('success', 'Ad deleted successfully.');
    }

    /**
     * Pause/Resume an ad
     */
    public function toggleStatus(Ad $ad)
    {
        // Check if property is logged in and owns this ad
        if (!session('property_id') || $ad->property_id !== session('property_id')) {
            return redirect()->route('property.login')
                ->with('error', 'Access denied.');
        }

        // Only allow toggling active/paused ads
        if (!in_array($ad->status, ['active', 'paused'])) {
            return redirect()->route('property.ads.index')
                ->with('error', 'You can only pause/resume active ads.');
        }

        $newStatus = $ad->status === 'active' ? 'paused' : 'active';
        $ad->update(['status' => $newStatus]);

        $message = $newStatus === 'active' ? 'Ad resumed successfully.' : 'Ad paused successfully.';

        return redirect()->route('property.ads.index')
            ->with('success', $message);
    }
}
