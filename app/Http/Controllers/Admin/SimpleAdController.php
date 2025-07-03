<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\Property;
use App\Models\AdminSetting;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SimpleAdController extends Controller
{
    /**
     * Display a listing of all ad requests
     */
    public function index()
    {
        $ads = Ad::with(['property'])
                 ->orderBy('created_at', 'desc')
                 ->paginate(20);

        $stats = [
            'total' => Ad::count(),
            'pending' => Ad::where('status', 'pending')->count(),
            'approved' => Ad::where('status', 'approved')->count(),
            'active' => Ad::where('status', 'active')->count(),
            'rejected' => Ad::where('status', 'rejected')->count(),
        ];

        $currentAdCost = AdminSetting::getAdDailyCost();

        return view('admin.ads.index_simple', compact('ads', 'stats', 'currentAdCost'));
    }

    /**
     * Display the specified ad for review
     */
    public function show(Ad $ad)
    {
        $ad->load(['property', 'approvedBy']);
        return view('admin.ads.show_simple', compact('ad'));
    }

    /**
     * Approve an ad request
     */
    public function approve(Request $request, Ad $ad)
    {
        if ($ad->status !== 'pending') {
            return back()->with('error', 'Only pending ads can be approved.');
        }

        // Check if payment has been completed
        if ($ad->payment_status !== 'paid') {
            return back()->with('error', 'Payment must be completed before approving the ad.');
        }

        // Check if the start date has passed
        if (Carbon::now()->gt($ad->start_date)) {
            // If start date has passed, make it active immediately
            $ad->update([
                'status' => 'active',
                'approved_at' => now(),
                'approved_by' => auth('admin')->id(),
                'admin_notes' => $request->input('admin_notes')
            ]);
            $message = 'Ad request approved and activated successfully!';
        } else {
            // Otherwise, mark as approved and schedule for activation
            $ad->update([
                'status' => 'approved',
                'approved_at' => now(),
                'approved_by' => auth('admin')->id(),
                'admin_notes' => $request->input('admin_notes')
            ]);
            $message = 'Ad request approved successfully! It will be activated on the start date.';
        }

        return back()->with('success', $message);
    }

    /**
     * Reject an ad request
     */
    public function reject(Request $request, Ad $ad)
    {
        if ($ad->status !== 'pending') {
            return back()->with('error', 'Only pending ads can be rejected.');
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:1000'
        ]);

        // If payment was completed, process refund
        if ($ad->payment_status === 'paid' && $ad->payment_intent_id) {  // Using correct column
            $paymentService = new \App\Services\GenieBusinessPaymentService();
            $refundResult = $paymentService->refundPayment(
                $ad->payment_intent_id,  // Using correct column
                null, // Full refund
                'Ad promotion rejected by admin: ' . $validated['rejection_reason']
            );

            if ($refundResult['success']) {
                $ad->update([
                    'payment_status' => 'refunded',
                    'status' => 'rejected',
                    'rejection_reason' => $validated['rejection_reason'],
                    'admin_notes' => $request->input('admin_notes')
                ]);
                $message = 'Ad request rejected and payment refunded successfully.';
            } else {
                $ad->update([
                    'status' => 'rejected',
                    'rejection_reason' => $validated['rejection_reason'],
                    'admin_notes' => $request->input('admin_notes') . ' [Refund failed: ' . $refundResult['error'] . ']'
                ]);
                $message = 'Ad request rejected. Note: Refund failed - please process manually.';
            }
        } else {
            $ad->update([
                'status' => 'rejected',
                'rejection_reason' => $validated['rejection_reason'],
                'admin_notes' => $request->input('admin_notes')
            ]);
            $message = 'Ad request rejected successfully.';
        }

        return back()->with('success', $message);
    }

    /**
     * Manually activate an approved ad
     */
    public function activate(Ad $ad)
    {
        if ($ad->status !== 'approved') {
            return back()->with('error', 'Only approved ads can be activated.');
        }

        $ad->update(['status' => 'active']);

        return back()->with('success', 'Ad activated successfully!');
    }

    /**
     * Deactivate an active ad
     */
    public function deactivate(Ad $ad)
    {
        if ($ad->status !== 'active') {
            return back()->with('error', 'Only active ads can be deactivated.');
        }

        $ad->update(['status' => 'approved']);

        return back()->with('success', 'Ad deactivated successfully!');
    }

    /**
     * Update the ad daily cost setting
     */
    public function updateAdCost(Request $request)
    {
        $request->validate([
            'ad_daily_cost' => 'required|numeric|min:0.01|max:100',
        ]);

        AdminSetting::set(
            'ad_daily_cost',
            $request->ad_daily_cost,
            'number',
            'Daily cost for ad promotion requests (USD)'
        );

        return back()->with('success', 'Ad daily cost updated successfully to $' . number_format($request->ad_daily_cost, 2));
    }
}
