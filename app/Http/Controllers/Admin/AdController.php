<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdController extends Controller
{
    /**
     * Display a listing of ads for admin review
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'pending');

        $ads = Ad::with(['property'])
                 ->when($status !== 'all', function($query) use ($status) {
                     return $query->where('status', $status);
                 })
                 ->orderBy('created_at', 'desc')
                 ->paginate(15);

        $stats = [
            'pending' => Ad::where('status', 'pending')->count(),
            'approved' => Ad::where('status', 'approved')->count(),
            'rejected' => Ad::where('status', 'rejected')->count(),
            'active' => Ad::where('status', 'active')->count(),
        ];

        return view('admin.ads.index', compact('ads', 'stats', 'status'));
    }

    /**
     * Display the specified ad for admin review
     */
    public function show(Ad $ad)
    {
        $ad->load(['property', 'approvedBy']);
        return view('admin.ads.show', compact('ad'));
    }

    /**
     * Update admin notes for an ad
     */
    public function updateNotes(Request $request, Ad $ad)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:1000'
        ]);

        $ad->update([
            'admin_notes' => $request->admin_notes
        ]);

        return redirect()->route('admin.ads.show', $ad)
            ->with('success', 'Admin notes updated successfully.');
    }

    /**
     * Approve an ad
     */
    public function approve(Request $request, Ad $ad)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:1000'
        ]);

        $ad->update([
            'status' => 'approved',
            'admin_notes' => $request->admin_notes,
            'approved_at' => now(),
            'approved_by' => Auth::guard('admin')->id(),
            'rejection_reason' => null
        ]);

        return redirect()->route('admin.ads.index')
            ->with('success', 'Ad approved successfully.');
    }

    /**
     * Reject an ad
     */
    public function reject(Request $request, Ad $ad)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:1000'
        ]);

        $ad->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'approved_at' => null,
            'approved_by' => null
        ]);

        return redirect()->route('admin.ads.index')
            ->with('success', 'Ad rejected successfully.');
    }

    /**
     * Activate an approved ad
     */
    public function activate(Ad $ad)
    {
        if ($ad->status !== 'approved') {
            return redirect()->route('admin.ads.index')
                ->with('error', 'Only approved ads can be activated.');
        }

        $ad->update(['status' => 'active']);

        return redirect()->route('admin.ads.index')
            ->with('success', 'Ad activated successfully.');
    }

    /**
     * Deactivate an active ad
     */
    public function deactivate(Ad $ad)
    {
        if ($ad->status !== 'active') {
            return redirect()->route('admin.ads.index')
                ->with('error', 'Only active ads can be deactivated.');
        }

        $ad->update(['status' => 'paused']);

        return redirect()->route('admin.ads.index')
            ->with('success', 'Ad deactivated successfully.');
    }

    /**
     * Delete an ad (admin only)
     */
    public function destroy(Ad $ad)
    {        // Delete associated image if exists
        if ($ad->image_path) {
            Storage::disk('public')->delete($ad->image_path);
        }

        $ad->delete();

        return redirect()->route('admin.ads.index')
            ->with('success', 'Ad deleted successfully.');
    }
}
