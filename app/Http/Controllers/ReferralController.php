<?php

namespace App\Http\Controllers;

use App\Models\Referral;
use App\Models\ReferralEarning;
use App\Models\ReferralRate;
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

        // For user registration, we don't process earnings immediately
        // Earnings are processed when user makes a purchase
        if (!$propertyId || !$planId || !$planAmount) {
            return true;
        }

        // Process 3-level referral earnings for property purchases
        $referredUser = User::find($userId);
        if (!$referredUser) {
            return false;
        }

        return self::process3LevelReferralEarnings($referredUser, $propertyId, $planId, $planAmount);
    }

    /**
     * Process 3-level referral earnings based on the user's referral chain.
     */
    public static function process3LevelReferralEarnings($user, $propertyId, $planId, $planAmount)
    {
        $earnings = [];

        // Get referral rates from database (IDs 1, 2, 3 for levels 1, 2, 3)
        $level1Rate = ReferralRate::find(1)?->rate ?? 10.00;
        $level2Rate = ReferralRate::find(2)?->rate ?? 5.00;
        $level3Rate = ReferralRate::find(3)?->rate ?? 2.50;

        // Level 1: Direct referrer
        if ($user->referred_by) {
            $level1Referrer = User::find($user->referred_by);
            if ($level1Referrer) {
                $earning = ReferralEarning::create([
                    'referrer_id' => $level1Referrer->id,
                    'referred_user_id' => $user->id,
                    'property_id' => $propertyId,
                    'plan_id' => $planId,
                    'referral_code' => $level1Referrer->referral?->referral_code ?? '',
                    'plan_amount' => $planAmount,
                    'commission_rate' => $level1Rate,
                    'status' => 'pending',
                ]);

                // Add to referrer's pending wallet balance
                $wallet = $level1Referrer->getOrCreateWallet();
                $wallet->addMoney($earning->commission_amount, 'pending_balance');

                // Update referral stats
                if ($level1Referrer->referral) {
                    $level1Referrer->referral->updateStats();
                }

                $earnings[] = $earning;

                // Level 2: Referrer of Level 1 referrer
                if ($level1Referrer->referred_by) {
                    $level2Referrer = User::find($level1Referrer->referred_by);
                    if ($level2Referrer) {
                        $earning2 = ReferralEarning::create([
                            'referrer_id' => $level2Referrer->id,
                            'referred_user_id' => $user->id,
                            'property_id' => $propertyId,
                            'plan_id' => $planId,
                            'referral_code' => $level2Referrer->referral?->referral_code ?? '',
                            'plan_amount' => $planAmount,
                            'commission_rate' => $level2Rate,
                            'status' => 'pending',
                        ]);

                        $wallet2 = $level2Referrer->getOrCreateWallet();
                        $wallet2->addMoney($earning2->commission_amount, 'pending_balance');

                        if ($level2Referrer->referral) {
                            $level2Referrer->referral->updateStats();
                        }

                        $earnings[] = $earning2;

                        // Level 3: Referrer of Level 2 referrer
                        if ($level2Referrer->referred_by) {
                            $level3Referrer = User::find($level2Referrer->referred_by);
                            if ($level3Referrer) {
                                $earning3 = ReferralEarning::create([
                                    'referrer_id' => $level3Referrer->id,
                                    'referred_user_id' => $user->id,
                                    'property_id' => $propertyId,
                                    'plan_id' => $planId,
                                    'referral_code' => $level3Referrer->referral?->referral_code ?? '',
                                    'plan_amount' => $planAmount,
                                    'commission_rate' => $level3Rate,
                                    'status' => 'pending',
                                ]);

                                $wallet3 = $level3Referrer->getOrCreateWallet();
                                $wallet3->addMoney($earning3->commission_amount, 'pending_balance');

                                if ($level3Referrer->referral) {
                                    $level3Referrer->referral->updateStats();
                                }

                                $earnings[] = $earning3;
                            }
                        }
                    }
                }
            }
        }

        return $earnings;
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
