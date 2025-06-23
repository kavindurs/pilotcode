<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\Property;
use App\Models\AdminSetting;
use App\Services\GenieBusinessPaymentService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SimpleAdController extends Controller
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

        // Get ads for this property
        $ads = Ad::where('property_id', $property->id)
                 ->orderBy('created_at', 'desc')
                 ->get();

        return view('property.ads.index_simple', compact('property', 'ads'));
    }

    /**
     * Show the form for creating a new ad
     */
    public function create()
    {
        // Check if property is logged in
        if (!session('property_id')) {
            return redirect()->route('property.login')
                ->with('error', 'Please login to request promotion.');
        }

        $property = Property::find(session('property_id'));

        if (!$property) {
            return redirect()->route('property.login')->with('error', 'Property not found.');
        }

        // Get current daily cost for display
        $dailyCost = AdminSetting::getAdDailyCost();

        return view('property.ads.create_simple', compact('property', 'dailyCost'));
    }

    /**
     * Store a newly created ad request with payment
     */
    public function store(Request $request)
    {
        // Check if property is logged in
        if (!session('property_id')) {
            return redirect()->route('property.login')
                ->with('error', 'Please login to request promotion.');
        }

        $property = Property::find(session('property_id'));

        if (!$property) {
            return redirect()->route('property.login')->with('error', 'Property not found.');
        }

        $validated = $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date'
        ]);

        // Calculate total days and cost
        $startDate = Carbon::parse($validated['start_date']);
        $endDate = Carbon::parse($validated['end_date']);
        $totalDays = $startDate->diffInDays($endDate) + 1; // +1 to include both start and end days
        $dailyCost = AdminSetting::getAdDailyCost();
        $totalAmount = $totalDays * $dailyCost;

        // Check for overlapping active/approved ads for this property
        $overlappingAds = Ad::where('property_id', $property->id)
            ->whereIn('status', ['active', 'approved'])
            ->where(function ($query) use ($validated) {
                $query->whereBetween('start_date', [$validated['start_date'], $validated['end_date']])
                      ->orWhereBetween('end_date', [$validated['start_date'], $validated['end_date']])
                      ->orWhere(function ($q) use ($validated) {
                          $q->where('start_date', '<=', $validated['start_date'])
                            ->where('end_date', '>=', $validated['end_date']);
                      });
            })->exists();

        if ($overlappingAds) {
            return back()->withErrors([
                'start_date' => 'You already have an active or approved promotion for this period.'
            ])->withInput();
        }

        // Create the ad request with payment pending status
        $ad = Ad::create([
            'property_id' => $property->id,
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'amount' => $totalAmount,  // Using existing column
            'days' => $totalDays,      // Using existing column
            'total_amount' => $totalAmount,  // Also populate this for consistency
            'total_days' => $totalDays,      // Also populate this for consistency
            'daily_rate' => $dailyCost,
            'payment_status' => 'pending',
            'status' => 'payment_pending' // Changed from 'pending' to indicate payment is required
        ]);

        // Initialize payment service
        $paymentService = new GenieBusinessPaymentService();

        // Create payment request
        $paymentResult = $paymentService->createPayment(
            $totalAmount,
            "Ad Promotion for {$property->business_name} ({$totalDays} days)",
            $property->email ?: 'noemail@example.com',
            $property->business_name,
            $property->id,
            $ad->id,
            route('property.ads.payment.success', $ad->id)
        );

        if ($paymentResult['success']) {
            // Store payment ID and redirect to payment page
            $ad->update([
                'payment_intent_id' => $paymentResult['data']['id'] ?? null,  // Using correct column
                'payment_notes' => json_encode($paymentResult['data'])         // Using correct column
            ]);

            // Redirect to payment gateway
            if (isset($paymentResult['data']['payment_url'])) {
                return redirect($paymentResult['data']['payment_url']);
            } else {
                // If no payment URL, show payment details and manual completion
                return redirect()->route('property.ads.payment.manual', $ad->id);
            }
        } else {
            // Payment creation failed, delete the ad and show error
            $ad->delete();

            return back()->withErrors([
                'payment' => 'Payment processing failed: ' . $paymentResult['error']
            ])->withInput();
        }
    }

    /**
     * Display the specified ad
     */
    public function show(Ad $ad)
    {
        // Check if property is logged in and owns this ad
        if (!session('property_id') || $ad->property_id !== session('property_id')) {
            abort(403, 'Unauthorized access to this ad.');
        }

        return view('property.ads.show_simple', compact('ad'));
    }

    /**
     * Show the form for editing the specified ad
     */
    public function edit(Ad $ad)
    {
        // Check if property is logged in and owns this ad
        if (!session('property_id') || $ad->property_id !== session('property_id')) {
            abort(403, 'Unauthorized access to this ad.');
        }

        // Only allow editing of pending ads
        if ($ad->status !== 'pending') {
            return redirect()->route('property.ads.index')
                ->with('error', 'You can only edit pending promotion requests.');
        }

        return view('property.ads.edit_simple', compact('ad'));
    }

    /**
     * Update the specified ad
     */
    public function update(Request $request, Ad $ad)
    {
        // Check if property is logged in and owns this ad
        if (!session('property_id') || $ad->property_id !== session('property_id')) {
            abort(403, 'Unauthorized access to this ad.');
        }

        // Only allow updating of pending ads
        if ($ad->status !== 'pending') {
            return redirect()->route('property.ads.index')
                ->with('error', 'You can only edit pending promotion requests.');
        }

        $validated = $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date'
        ]);

        // Check for overlapping active/approved ads for this property (excluding current ad)
        $overlappingAds = Ad::where('property_id', $ad->property_id)
            ->where('id', '!=', $ad->id)
            ->whereIn('status', ['active', 'approved'])
            ->where(function ($query) use ($validated) {
                $query->whereBetween('start_date', [$validated['start_date'], $validated['end_date']])
                      ->orWhereBetween('end_date', [$validated['start_date'], $validated['end_date']])
                      ->orWhere(function ($q) use ($validated) {
                          $q->where('start_date', '<=', $validated['start_date'])
                            ->where('end_date', '>=', $validated['end_date']);
                      });
            })->exists();

        if ($overlappingAds) {
            return back()->withErrors([
                'start_date' => 'You already have an active or approved promotion for this period.'
            ])->withInput();
        }

        $ad->update($validated);

        return redirect()->route('property.ads.index')
            ->with('success', 'Promotion request updated successfully!');
    }

    /**
     * Remove the specified ad
     */
    public function destroy(Ad $ad)
    {
        // Check if property is logged in and owns this ad
        if (!session('property_id') || $ad->property_id !== session('property_id')) {
            abort(403, 'Unauthorized access to this ad.');
        }

        // Only allow deletion of pending ads
        if ($ad->status !== 'pending') {
            return redirect()->route('property.ads.index')
                ->with('error', 'You can only cancel pending promotion requests.');
        }

        $ad->delete();

        return redirect()->route('property.ads.index')
            ->with('success', 'Promotion request cancelled successfully.');
    }

    /**
     * Handle payment success callback
     */
    public function paymentSuccess(Request $request, Ad $ad)
    {
        // Verify payment with Genie Business API
        $paymentService = new GenieBusinessPaymentService();
        $paymentResult = $paymentService->verifyPayment($ad->payment_intent_id);

        if ($paymentResult['success'] && $paymentResult['data']['status'] === 'completed') {
            // Payment successful, update ad status
            $ad->update([
                'payment_status' => 'completed',
                'paid_at' => now(),  // Using correct column
                'status' => 'pending', // Now ready for admin review
                'payment_notes' => json_encode($paymentResult['data'])  // Using correct column
            ]);

            return redirect()->route('property.ads.index')
                ->with('success', 'Payment completed successfully! Your promotion request has been submitted for admin review.');
        } else {
            // Payment verification failed
            return redirect()->route('property.ads.index')
                ->with('error', 'Payment verification failed. Please contact support if this issue persists.');
        }
    }

    /**
     * Handle payment cancellation
     */
    public function paymentCancel(Request $request)
    {
        return redirect()->route('property.ads.index')
            ->with('error', 'Payment was cancelled. Your promotion request was not submitted.');
    }

    /**
     * Handle payment callback from Genie Business
     */
    public function paymentCallback(Request $request)
    {
        // Handle webhook/callback from Genie Business
        $paymentId = $request->input('payment_id');
        $status = $request->input('status');

        if ($paymentId) {
            $ad = Ad::where('payment_intent_id', $paymentId)->first();  // Using correct column

            if ($ad) {
                if ($status === 'completed') {
                    $ad->update([
                        'payment_status' => 'completed',
                        'paid_at' => now(),  // Using correct column
                        'status' => 'pending',
                        'payment_notes' => json_encode($request->all())  // Using correct column
                    ]);
                } elseif ($status === 'failed') {
                    $ad->update([
                        'payment_status' => 'failed',
                        'payment_notes' => json_encode($request->all())  // Using correct column
                    ]);
                }
            }
        }

        return response()->json(['status' => 'ok']);
    }

    /**
     * Show manual payment page (if payment URL not available)
     */
    public function paymentManual(Ad $ad)
    {
        // Check if property is logged in and owns this ad
        if (!session('property_id') || $ad->property_id !== session('property_id')) {
            abort(403, 'Unauthorized access to this ad.');
        }

        return view('property.ads.payment_manual', compact('ad'));
    }
}
