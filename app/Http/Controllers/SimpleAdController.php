<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\Property;
use App\Models\AdminSetting;
use App\Services\GenieBusinessPaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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

        // Auto-update payment statuses before displaying ads
        $this->autoUpdatePaymentStatuses();

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
            'total_amount' => ($totalAmount)*300,  // Also populate this for consistency
            'total_days' => $totalDays,      // Also populate this for consistency
            'daily_rate' => $dailyCost,
            'payment_status' => 'pending',
            'status' => 'payment_pending' // Changed from 'pending' to indicate payment is required
        ]);

        // Initialize payment service
        $paymentService = new GenieBusinessPaymentService();

        // Create payment request
        $paymentResult = $paymentService->createPayment(
            $totalAmount * 100, // Convert to cents for payment gateway
            "Ad Promotion for {$property->business_name} ({$totalDays} days)",
            $property->business_email ?: 'noemail@example.com',
            $property->business_name,
            $property->id,
            $ad->id,
            route('property.ads.payment.success', $ad->id)
        );

        // Debug logging
        Log::info('Payment Service Result', [
            'success' => $paymentResult['success'],
            'data' => $paymentResult['data'] ?? null,
            'error' => $paymentResult['error'] ?? null
        ]);

        if ($paymentResult['success']) {
            // Store payment ID and redirect to payment page
            $ad->update([
                'payment_intent_id' => $paymentResult['data']['id'] ?? null,  // Using correct column
                'payment_notes' => json_encode($paymentResult['data'])         // Using correct column
            ]);

            // Redirect to payment gateway or verification page
            if (isset($paymentResult['data']['payment_url'])) {
                Log::info('Redirecting to payment URL', [
                    'payment_url' => $paymentResult['data']['payment_url'],
                    'ad_id' => $ad->id
                ]);

                // Redirect directly to payment gateway for both localhost and production
                Log::info('Redirecting to payment gateway', [
                    'payment_url' => $paymentResult['data']['payment_url'],
                    'ad_id' => $ad->id
                ]);
                return redirect($paymentResult['data']['payment_url']);
            } else {
                Log::warning('No payment URL in response, redirecting to ads index');
                // If no payment URL, redirect to ads index with error message
                return redirect()->route('property.ads.index')
                    ->with('error', 'Payment URL not available. Please try again or contact support.');
            }
        } else {
            // Payment creation failed, delete the ad and show error
            Log::error('Payment creation failed, deleting ad', [
                'ad_id' => $ad->id,
                'error' => $paymentResult['error']
            ]);
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
        // Log all incoming request data for debugging
        Log::info('Payment Success Callback', [
            'ad_id' => $ad->id,
            'ad_payment_intent_id' => $ad->payment_intent_id,
            'request_params' => $request->all(),
            'request_url' => $request->fullUrl()
        ]);

        // Handle sandbox payment
        if ($request->has('sandbox') && $request->sandbox === 'true') {
            $transactionId = $request->get('transaction_id');

            // Update ad for sandbox payment
            $ad->update([
                'payment_intent_id' => $transactionId,
                'payment_status' => 'paid',
                'paid_at' => now(),
                'status' => 'pending', // Now ready for admin review
                'payment_notes' => json_encode([
                    'sandbox' => true,
                    'transaction_id' => $transactionId,
                    'completed_at' => now()
                ])
            ]);

            return redirect()->route('property.ads.index')
                ->with('success', 'Payment completed successfully! Your promotion request has been submitted for admin review. (Sandbox Mode)');
        }

        // Get transaction ID from request parameters or use stored payment intent ID
        $transactionId = $request->get('transaction_id') ??
                        $request->get('id') ??
                        $ad->payment_intent_id;

        if (!$transactionId) {
            Log::error('No transaction ID found for payment verification', [
                'ad_id' => $ad->id,
                'request_params' => $request->all()
            ]);

            return redirect()->route('property.ads.index')
                ->with('error', 'Payment verification failed: No transaction ID found. Please contact support.');
        }

        // Verify payment with Genie Business API
        $paymentService = new GenieBusinessPaymentService();
        $paymentResult = $paymentService->verifyPayment($transactionId);

        Log::info('Payment Verification Result', [
            'ad_id' => $ad->id,
            'transaction_id' => $transactionId,
            'verification_success' => $paymentResult['success'],
            'verification_data' => $paymentResult['data'] ?? null,
            'verification_error' => $paymentResult['error'] ?? null
        ]);        if ($paymentResult['success'] && isset($paymentResult['data'])) {
            // Genie Business uses 'state' field, not 'status'
            $paymentStatus = $paymentResult['data']['state'] ?? $paymentResult['data']['status'] ?? 'unknown';

            Log::info('Payment verification response details', [
                'ad_id' => $ad->id,
                'transaction_id' => $transactionId,
                'payment_state' => $paymentResult['data']['state'] ?? 'not_found',
                'payment_status' => $paymentResult['data']['status'] ?? 'not_found',
                'resolved_status' => $paymentStatus
            ]);

            // Accept various successful payment statuses (Genie Business uses 'CONFIRMED')
            if (in_array($paymentStatus, ['CONFIRMED', 'completed', 'success', 'confirmed', 'paid'])) {
                // Payment successful, update ad status
                $ad->update([
                    'payment_intent_id' => $transactionId,
                    'payment_status' => 'paid',
                    'paid_at' => now(),
                    'status' => 'pending', // Now ready for admin review
                    'payment_notes' => json_encode($paymentResult['data'])
                ]);

                Log::info('Ad status updated to paid', [
                    'ad_id' => $ad->id,
                    'transaction_id' => $transactionId,
                    'payment_status' => $paymentStatus
                ]);

                return redirect()->route('property.ads.index')
                    ->with('success', 'Payment completed successfully! Your promotion request has been submitted for admin review.');
            } else {
                // Payment not yet completed
                Log::warning('Payment not completed', [
                    'ad_id' => $ad->id,
                    'transaction_id' => $transactionId,
                    'payment_status' => $paymentStatus
                ]);

                return redirect()->route('property.ads.index')
                    ->with('error', "Payment status: {$paymentStatus}. Please complete your payment or contact support.");
            }
        } else {
            // Payment verification failed
            Log::error('Payment verification failed', [
                'ad_id' => $ad->id,
                'transaction_id' => $transactionId,
                'error' => $paymentResult['error'] ?? 'Unknown error'
            ]);

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
                        'payment_status' => 'paid',
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

        return view('property.ads.payment_verification', compact('ad'));
    }

    /**
     * Retry payment for a pending ad
     */
    public function paymentRetry(Ad $ad)
    {
        // Check if property is logged in and owns this ad
        if (!session('property_id') || $ad->property_id !== session('property_id')) {
            abort(403, 'Unauthorized access to this ad.');
        }

        // Only allow retry for ads with payment_pending status
        if ($ad->status !== 'payment_pending') {
            return redirect()->route('property.ads.index')
                ->with('error', 'Payment retry is only available for ads with pending payment status.');
        }

        // First, check if there's an existing payment that might have been completed
        if ($ad->payment_intent_id) {
            $paymentService = new GenieBusinessPaymentService();
            $existingPaymentResult = $paymentService->verifyPayment($ad->payment_intent_id);

            Log::info('Checking existing payment before retry', [
                'ad_id' => $ad->id,
                'payment_intent_id' => $ad->payment_intent_id,
                'verification_result' => $existingPaymentResult
            ]);

            if ($existingPaymentResult['success'] && isset($existingPaymentResult['data'])) {
                $paymentStatus = $existingPaymentResult['data']['state'] ?? $existingPaymentResult['data']['status'] ?? 'unknown';

                // If payment is already confirmed, update the ad
                if (in_array($paymentStatus, ['CONFIRMED', 'completed', 'success', 'confirmed', 'paid'])) {
                    $ad->update([
                        'payment_status' => 'paid',
                        'paid_at' => now(),
                        'status' => 'pending',
                        'payment_notes' => json_encode($existingPaymentResult['data'])
                    ]);

                    return redirect()->route('property.ads.index')
                        ->with('success', 'Payment found and verified! Your promotion request has been submitted for admin review.');
                }
            }
        }

        // Check for other potential payments by searching with ad-specific local IDs
        $paymentService = new GenieBusinessPaymentService();
        $expectedAmount = $ad->total_amount * 100; // Convert to cents

        // Try to find payments by checking common patterns of local IDs for this ad
        $possibleLocalIds = [
            'AD_' . $ad->id . '_',  // Current pattern
            'ad_' . $ad->id . '_',  // Lowercase variant
            $ad->id                 // Just the ad ID
        ];

        foreach ($possibleLocalIds as $localIdPattern) {
            // This would need to be implemented in the payment service if we had a search function
            // For now, we'll proceed with creating a new payment
        }

        // Get property details
        $property = Property::find($ad->property_id);

        // Create new payment request
        $paymentResult = $paymentService->createPayment(
            $ad->total_amount * 100, // Convert to cents for payment gateway
            'Property Ad Promotion - ' . ($property->business_name ?? 'Property #' . $property->id),
            $property->business_email ?? 'noemail@property' . $property->id . '.local',
            $property->business_name ?? $property->contact_person ?? 'Property Owner #' . $property->id,
            $ad->property_id,
            $ad->id,
            route('property.ads.payment.success', $ad->id)
        );

        if ($paymentResult['success']) {
            // Update payment intent ID for the retry
            $ad->update([
                'payment_intent_id' => $paymentResult['data']['id'] ?? null,
                'payment_notes' => json_encode($paymentResult['data'])
            ]);

            // Check if this is a sandbox environment
            if (isset($paymentResult['data']['sandbox']) && $paymentResult['data']['sandbox']) {
                // Redirect to sandbox simulation
                $transactionId = $paymentResult['data']['id'];
                return redirect()->route('property.ads.payment.success', [
                    'ad' => $ad->id,
                    'sandbox' => 'true',
                    'transaction_id' => $transactionId
                ]);
            } else {
                // Redirect to actual payment URL
                $paymentUrl = $paymentResult['data']['payment_url'] ?? $paymentResult['data']['checkout_url'];
                if ($paymentUrl) {
                    Log::info('Redirecting to payment gateway', [
                        'ad_id' => $ad->id,
                        'payment_url' => $paymentUrl
                    ]);
                    return redirect($paymentUrl);
                } else {
                    // Fallback to ads index if no payment URL
                    return redirect()->route('property.ads.index')
                        ->with('error', 'Payment URL not available. Please try again or contact support.');
                }
            }
        } else {
            // Payment creation failed
            return redirect()->route('property.ads.index')
                ->with('error', 'Failed to initiate payment: ' . ($paymentResult['error'] ?? 'Unknown error'));
        }
    }

    /**
     * Manually verify payment by transaction ID
     */
    public function verifyPaymentManual(Request $request, Ad $ad)
    {
        // Check if property is logged in and owns this ad
        if (!session('property_id') || $ad->property_id !== session('property_id')) {
            abort(403, 'Unauthorized access to this ad.');
        }

        $transactionId = $request->get('transaction_id');

        if (!$transactionId) {
            return back()->with('error', 'Transaction ID is required for manual verification.');
        }

        // Verify payment with Genie Business API
        $paymentService = new GenieBusinessPaymentService();
        $paymentResult = $paymentService->verifyPayment($transactionId);

        Log::info('Manual Payment Verification', [
            'ad_id' => $ad->id,
            'transaction_id' => $transactionId,
            'verification_success' => $paymentResult['success'],
            'verification_data' => $paymentResult['data'] ?? null
        ]);

        if ($paymentResult['success'] && isset($paymentResult['data'])) {
            $paymentData = $paymentResult['data'];
            $paymentStatus = $paymentData['state'] ?? $paymentData['status'] ?? 'unknown';

            // Check if payment is for this ad (by local_id or amount)
            $expectedLocalId = 'AD_' . $ad->id . '_';
            $localId = $paymentData['localId'] ?? '';
            $paymentAmount = $paymentData['amount'] ?? 0;
            $expectedAmount = $ad->total_amount * 100; // Convert to cents

            // More flexible matching: either local ID starts with expected pattern OR amount matches
            $localIdMatches = str_starts_with($localId, $expectedLocalId);
            $amountMatches = $paymentAmount == $expectedAmount;

            Log::info('Payment verification matching', [
                'ad_id' => $ad->id,
                'transaction_id' => $transactionId,
                'expected_local_id' => $expectedLocalId,
                'actual_local_id' => $localId,
                'local_id_matches' => $localIdMatches,
                'expected_amount' => $expectedAmount,
                'actual_amount' => $paymentAmount,
                'amount_matches' => $amountMatches
            ]);

            if (!$localIdMatches && !$amountMatches) {
                return back()->with('error', 'Payment verification failed: Transaction does not match this ad (Expected amount: LKR ' . number_format($ad->total_amount, 2) . ', Found: LKR ' . number_format($paymentAmount / 100, 2) . ').');
            }

            // Accept successful payment statuses
            if (in_array($paymentStatus, ['CONFIRMED', 'completed', 'success', 'confirmed', 'paid'])) {
                // Update ad with successful payment
                $ad->update([
                    'payment_intent_id' => $transactionId,
                    'payment_status' => 'paid',
                    'paid_at' => now(),
                    'status' => 'pending',
                    'payment_notes' => json_encode($paymentData)
                ]);

                Log::info('Manual payment verification successful', [
                    'ad_id' => $ad->id,
                    'transaction_id' => $transactionId,
                    'payment_status' => $paymentStatus
                ]);

                return redirect()->route('property.ads.index')
                    ->with('success', 'Payment verified successfully! Your ad is now ready for admin review.');
            } else {
                return back()->with('error', 'Payment verification failed: Payment status is ' . $paymentStatus);
            }
        } else {
            return back()->with('error', 'Payment verification failed: Could not verify payment with gateway.');
        }
    }

    /**
     * Auto-update payment statuses for all payment_pending ads
     */
    private function autoUpdatePaymentStatuses()
    {
        $pendingAds = Ad::where('status', 'payment_pending')
                        ->whereNotNull('payment_intent_id')
                        ->get();

        $paymentService = new GenieBusinessPaymentService();
        $updatedCount = 0;

        foreach ($pendingAds as $ad) {
            $result = $paymentService->verifyPayment($ad->payment_intent_id);

            if ($result['success'] && isset($result['data'])) {
                $paymentStatus = $result['data']['state'] ?? $result['data']['status'] ?? 'unknown';

                if (in_array($paymentStatus, ['CONFIRMED', 'completed', 'success', 'confirmed', 'paid'])) {
                    $ad->update([
                        'payment_status' => 'paid',
                        'paid_at' => now(),
                        'status' => 'pending',
                        'payment_notes' => json_encode($result['data'])
                    ]);

                    Log::info('Auto-updated payment status', [
                        'ad_id' => $ad->id,
                        'payment_intent_id' => $ad->payment_intent_id,
                        'payment_status' => $paymentStatus
                    ]);

                    $updatedCount++;
                }
            }
        }

        return $updatedCount;
    }
}
