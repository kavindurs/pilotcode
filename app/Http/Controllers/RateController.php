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

    public function store(Request $request, Property $property)
    {
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

        return redirect()->route('properties.subcategory', $property->subcategory)
            ->with('success', 'Your review has been submitted and is pending approval.');
    }

    public function index()
    {
        // Adjust the status if needed (e.g. 'pending', 'Pending Approval')
        $pendingReviews = Rate::where('status', 'Pending Approval')->paginate(10);
        return view('admin.reviews.index', compact('pendingReviews'));
    }
}
