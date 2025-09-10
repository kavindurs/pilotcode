<?php
namespace App\Http\Controllers;

use App\Models\Rate;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RateController extends Controller
{
    public function create(Property $property)
    {
        return view('rates.create', compact('property'));
    }

    public function store(Request $request, Property $property = null)
    {
        // Handle case where property might not be bound correctly
        if (!$property && $request->has('property_id')) {
            $property = Property::find($request->input('property_id'));
        }

        if (!$property) {
            return redirect()->back()->with('error', 'Property not found.');
        }

        // Debug information
        \Log::info('Rate store called', [
            'property_id' => $property->id,
            'request_data' => $request->all(),
            'url' => $request->fullUrl()
        ]);

        $validated = $request->validate([
            'rate' => 'required|integer|between:1,5',
            'review' => 'required|string|max:250',
            'experienced_date' => 'required|date|before_or_equal:today',
        ]);

        $rate = new Rate([
            'rate' => $validated['rate'],
            'review' => $validated['review'],
            'experienced_date' => $validated['experienced_date'],
            'status' => 'Pending Approval'
        ]);

        $rate->property_id = $property->id;
        $rate->user_id = Auth::id();
        $rate->save();

        return redirect()->route('categories.index')
            ->with('success', 'Your review has been submitted and is pending approval.');
    }

    public function index()
    {
        // Adjust the status if needed (e.g. 'pending', 'Pending Approval')
        $pendingReviews = Rate::where('status', 'Pending Approval')->paginate(10);
        return view('admin.reviews.index', compact('pendingReviews'));
    }
}
