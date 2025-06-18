<?php

namespace App\Http\Controllers;

use App\Models\Referral;
use App\Models\ReferralEarning;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReferralController extends Controller
{
    /**
     * Show the referral dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        $referral = $user->getOrCreateReferral();
        $wallet = $user->getOrCreateWallet();

        $earnings = $user->referralEarnings()
            ->with(['referredUser', 'property', 'plan'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $stats = [
            'total_referrals' => $referral->total_referrals,
            'total_earnings' => $referral->total_earnings,
            'pending_earnings' => $user->referralEarnings()->where('status', 'pending')->sum('commission_amount'),
            'wallet_balance' => $wallet->balance,
            'pending_balance' => $wallet->pending_balance,
            'commission_rate' => $referral->getCommissionRate(), // Get from system-wide rate
        ];

        // Generate both link types
        $userLink = $referral->getUserReferralLink();
        $propertyLink = $referral->getPropertyReferralLink();

        return view('referrals.index', compact('referral', 'wallet', 'earnings', 'stats', 'userLink', 'propertyLink'));
    }

    /**
     * Update referral settings (only referral code can be changed).
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'referral_code' => 'required|string|max:20|unique:referrals,referral_code,' . Auth::user()->referral->id,
        ]);

        $referral = Auth::user()->referral;
        $referral->update([
            'referral_code' => strtoupper($request->referral_code),
            'referral_link' => Referral::generateReferralLink($request->referral_code),
        ]);

        return redirect()->back()->with('success', 'Referral code updated successfully!');
    }

    /**
     * Process a referral (called when someone registers through a referral link).
     */
    public static function processReferral($referralCode, $userId, $propertyId = null, $planId = null, $planAmount = null)
    {
        $referral = Referral::where('referral_code', $referralCode)->first();

        if (!$referral || !$referral->isValid()) {
            return false;
        }

        // Create referral earning record
        if ($propertyId && $planId && $planAmount) {
            $earning = ReferralEarning::create([
                'referrer_id' => $referral->user_id,
                'referred_user_id' => $userId, // Can be null for property referrals
                'property_id' => $propertyId,
                'plan_id' => $planId,
                'referral_code' => $referralCode,
                'plan_amount' => $planAmount,
                'commission_rate' => $referral->getCommissionRate(), // Use system-wide rate
                'status' => 'pending',
            ]);

            // Add to referrer's pending wallet balance
            $wallet = $referral->user->getOrCreateWallet();
            $wallet->addMoney($earning->commission_amount, 'pending_balance');

            // Update referral stats
            $referral->updateStats();

            return $earning;
        }

        return true;
    }

    /**
     * Generate a new referral code.
     */
    public function generateNewCode()
    {
        $referral = Auth::user()->referral;
        $newCode = Referral::generateReferralCode(Auth::user()->id);

        $referral->update([
            'referral_code' => $newCode,
            'referral_link' => Referral::generateReferralLink($newCode, 'user'),
        ]);

        return response()->json([
            'success' => true,
            'referral_code' => $newCode,
            'user_link' => $referral->getUserReferralLink(),
            'property_link' => $referral->getPropertyReferralLink(),
        ]);
    }

    /**
     * Toggle referral status.
     */
    public function toggleStatus()
    {
        $referral = Auth::user()->referral;
        $referral->update(['is_active' => !$referral->is_active]);

        return response()->json([
            'success' => true,
            'is_active' => $referral->is_active,
        ]);
    }

    /**
     * Get referral statistics for API.
     */
    public function getStats()
    {
        $user = Auth::user();
        $referral = $user->referral;
        $wallet = $user->wallet;

        if (!$referral || !$wallet) {
            return response()->json(['error' => 'Referral or wallet not found'], 404);
        }

        return response()->json([
            'total_referrals' => $referral->total_referrals,
            'total_earnings' => $referral->total_earnings,
            'pending_earnings' => $user->referralEarnings()->where('status', 'pending')->sum('commission_amount'),
            'wallet_balance' => $wallet->balance,
            'pending_balance' => $wallet->pending_balance,
            'commission_rate' => $referral->commission_rate,
            'is_active' => $referral->is_active,
        ]);
    }
}
