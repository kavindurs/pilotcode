<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Wallet;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class WalletController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $currency = $request->input('currency');
        $sort = $request->input('sort', 'created_at');
        $direction = $request->input('direction', 'desc');

        $wallets = Wallet::with('user')
            ->when($search, function($query) use ($search) {
                $query->whereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                             ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($currency, function($query) use ($currency) {
                $query->where('currency', $currency);
            })
            ->orderBy($sort, $direction)
            ->paginate(15);

        $currencies = Wallet::distinct()->pluck('currency');

        return view('admin.wallets.index', compact('wallets', 'search', 'currency', 'sort', 'direction', 'currencies'));
    }

    public function show($id)
    {
        $wallet = Wallet::with('user')->findOrFail($id);
        return view('admin.wallets.show', compact('wallet'));
    }

    public function edit($id)
    {
        $wallet = Wallet::with('user')->findOrFail($id);
        $users = User::orderBy('name')->get();
        return view('admin.wallets.edit', compact('wallet', 'users'));
    }

    public function update(Request $request, $id)
    {
        $wallet = Wallet::findOrFail($id);

        $validatedData = $request->validate([
            'user_id' => ['required', 'exists:users,id', Rule::unique('wallets')->ignore($id)],
            'balance' => 'required|numeric|min:0|max:999999999.99',
            'pending_balance' => 'required|numeric|min:0|max:999999999.99',
            'total_earned' => 'required|numeric|min:0|max:999999999.99',
            'total_withdrawn' => 'required|numeric|min:0|max:999999999.99',
            'currency' => 'required|string|size:3|in:USD,EUR,GBP,CAD,AUD,INR,LKR'
        ]);

        $wallet->update($validatedData);

        return redirect()->route('admin.wallets.index')->with('success', 'Wallet updated successfully.');
    }

    public function destroy($id)
    {
        $wallet = Wallet::findOrFail($id);

        // Store user info for the success message
        $userName = $wallet->user->name ?? 'Unknown User';

        $wallet->delete();

        return redirect()->route('admin.wallets.index')->with('success', "Wallet for {$userName} deleted successfully.");
    }

    public function adjustBalance(Request $request, $id)
    {
        $wallet = Wallet::findOrFail($id);

        $validatedData = $request->validate([
            'adjustment_type' => 'required|in:add,subtract',
            'amount' => 'required|numeric|min:0.01|max:999999999.99',
            'reason' => 'required|string|max:255'
        ]);

        if ($validatedData['adjustment_type'] === 'add') {
            $wallet->balance += $validatedData['amount'];
            $wallet->total_earned += $validatedData['amount'];
        } else {
            if ($wallet->balance < $validatedData['amount']) {
                return back()->withErrors(['amount' => 'Insufficient balance for this deduction.']);
            }
            $wallet->balance -= $validatedData['amount'];
            $wallet->total_withdrawn += $validatedData['amount'];
        }

        $wallet->save();

        $action = $validatedData['adjustment_type'] === 'add' ? 'added to' : 'deducted from';
        return redirect()->route('admin.wallets.show', $wallet->id)
            ->with('success', "Successfully {$action} wallet. Amount: {$wallet->currency} {$validatedData['amount']}");
    }
}
