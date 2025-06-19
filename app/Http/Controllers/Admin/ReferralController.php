<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Referral;
use App\Models\ReferralRate;
use App\Models\User;
use Illuminate\Http\Request;

class ReferralController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $referrals = Referral::with('user')
            ->when($search, function($query) use ($search) {
                $query->where('referral_code', 'like', "%{$search}%")
                      ->orWhereHas('user', function($q) use ($search) {
                          $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                      });
            })
            ->when($status !== null, function($query) use ($status) {
                if ($status === 'active') {
                    $query->where('is_active', 1);
                } elseif ($status === 'inactive') {
                    $query->where('is_active', 0);
                } elseif ($status === 'expired') {
                    $query->where('expires_at', '<', now());
                }
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Get the current referral rate
        $referralRate = ReferralRate::first();

        return view('admin.referrals.index', compact('referrals', 'search', 'status', 'referralRate'));
    }

    public function create()
    {
        $users = User::orderBy('name')->get();
        return view('admin.referrals.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'commission_rate' => 'required|numeric|min:0|max:100',
            'is_active' => 'boolean',
            'expires_at' => 'nullable|date|after:today'
        ]);

        $validatedData['is_active'] = $request->has('is_active') ? 1 : 0;

        // Check if user already has an active referral
        $existingReferral = Referral::where('user_id', $validatedData['user_id'])
            ->where('is_active', 1)
            ->first();

        if ($existingReferral) {
            return redirect()->back()->withErrors(['user_id' => 'This user already has an active referral program.']);
        }

        Referral::create($validatedData);

        return redirect()->route('admin.referrals.index')->with('success', 'Referral created successfully.');
    }

    public function show($id)
    {
        $referral = Referral::with(['user', 'earnings'])->findOrFail($id);
        return view('admin.referrals.show', compact('referral'));
    }

    public function edit($id)
    {
        $referral = Referral::with('user')->findOrFail($id);
        $users = User::orderBy('name')->get();
        return view('admin.referrals.edit', compact('referral', 'users'));
    }

    public function update(Request $request, $id)
    {
        $referral = Referral::findOrFail($id);

        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'commission_rate' => 'required|numeric|min:0|max:100',
            'is_active' => 'boolean',
            'expires_at' => 'nullable|date'
        ]);

        $validatedData['is_active'] = $request->has('is_active') ? 1 : 0;

        // Check if user already has another active referral (excluding current one)
        if ($validatedData['user_id'] != $referral->user_id) {
            $existingReferral = Referral::where('user_id', $validatedData['user_id'])
                ->where('is_active', 1)
                ->where('id', '!=', $id)
                ->first();

            if ($existingReferral) {
                return redirect()->back()->withErrors(['user_id' => 'This user already has another active referral program.']);
            }
        }

        $referral->update($validatedData);

        return redirect()->route('admin.referrals.index')->with('success', 'Referral updated successfully.');
    }

    public function destroy($id)
    {
        $referral = Referral::findOrFail($id);
        $referral->delete();

        return redirect()->route('admin.referrals.index')->with('success', 'Referral deleted successfully.');
    }

    public function activate($id)
    {
        $referral = Referral::findOrFail($id);
        $referral->update(['is_active' => 1]);

        return redirect()->route('admin.referrals.index')->with('success', 'Referral activated successfully.');
    }

    public function deactivate($id)
    {
        $referral = Referral::findOrFail($id);
        $referral->update(['is_active' => 0]);

        return redirect()->route('admin.referrals.index')->with('success', 'Referral deactivated successfully.');
    }

    public function updateReferralRate(Request $request)
    {
        $validatedData = $request->validate([
            'rate' => 'required|numeric|min:0|max:100'
        ]);

        $referralRate = ReferralRate::first();
        if ($referralRate) {
            $referralRate->update(['rate' => $validatedData['rate']]);
        } else {
            ReferralRate::create(['rate' => $validatedData['rate']]);
        }

        return redirect()->route('admin.referrals.index')->with('success', 'Referral rate updated successfully.');
    }
}
