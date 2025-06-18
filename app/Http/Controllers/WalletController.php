<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    /**
     * Show the wallet dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        $wallet = $user->getOrCreateWallet();

        return view('wallet.index', compact('wallet'));
    }

    /**
     * Request withdrawal.
     */
    public function requestWithdrawal(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'method' => 'required|string|in:binance',
            'details' => 'required|string',
        ]);

        $wallet = Auth::user()->wallet;

        if (!$wallet || $wallet->balance < $request->amount) {
            return redirect()->back()->with('error', 'Insufficient balance for withdrawal.');
        }

        // Here you would typically create a withdrawal request record
        // and process it through your payment system

        // For now, we'll just simulate the withdrawal
        $wallet->withdrawMoney($request->amount);

        return redirect()->back()->with('success', 'Withdrawal request submitted successfully!');
    }

    /**
     * Get wallet balance via API.
     */
    public function getBalance()
    {
        $wallet = Auth::user()->getOrCreateWallet();

        return response()->json([
            'balance' => $wallet->balance,
            'pending_balance' => $wallet->pending_balance,
            'total_earned' => $wallet->total_earned,
            'total_withdrawn' => $wallet->total_withdrawn,
            'formatted_balance' => $wallet->formatted_balance,
            'formatted_pending_balance' => $wallet->formatted_pending_balance,
        ]);
    }

    /**
     * Test withdrawal method (GET access for testing).
     */
    public function testWithdrawal()
    {
        $wallet = Auth::user()->getOrCreateWallet();

        if ($wallet->balance < 10) {
            return redirect()->route('wallet.index')->with('error', 'Need at least $10 balance for test withdrawal.');
        }

        // Test withdrawal of $10
        $wallet->withdrawMoney(10);

        return redirect()->route('wallet.index')->with('success', 'Test withdrawal of $10 completed successfully!');
    }
}
