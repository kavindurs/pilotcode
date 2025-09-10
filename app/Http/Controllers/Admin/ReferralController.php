<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Referral;
use App\Models\ReferralEarning;
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

        // Get all referral rates for the 3-level system
        $referralRates = ReferralRate::orderBy('id')->get();

        return view('admin.referrals.index', compact('referrals', 'search', 'status', 'referralRates'));
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
            'referral_code' => 'required|string|max:20|regex:/^[A-Za-z0-9]+$/|unique:referrals,referral_code,' . $id,
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

        // If referral code is being changed, update associated earnings records
        $oldReferralCode = $referral->referral_code;
        if ($validatedData['referral_code'] !== $oldReferralCode) {
            // Update any existing referral earnings with the new code
            ReferralEarning::where('referral_code', $oldReferralCode)
                ->update(['referral_code' => $validatedData['referral_code']]);
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

    /**
     * Update multiple referral rates for the 3-level system.
     */
    public function updateRates(Request $request)
    {
        $request->validate([
            'rates.*.rate' => 'required|numeric|min:0|max:100',
            'rates.*.description' => 'required|string|max:255',
        ], [
            'rates.*.rate.required' => 'Rate is required for all levels',
            'rates.*.rate.numeric' => 'Rate must be a valid number',
            'rates.*.rate.min' => 'Rate cannot be less than 0%',
            'rates.*.rate.max' => 'Rate cannot be more than 100%',
            'rates.*.description.required' => 'Description is required for all levels',
        ]);

        try {
            foreach ($request->rates as $id => $data) {
                $referralRate = ReferralRate::find($id);
                if ($referralRate) {
                    $referralRate->update([
                        'rate' => $data['rate'],
                        'description' => $data['description'],
                    ]);
                }
            }

            return redirect()->back()->with('success', 'Referral rates updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update referral rates: ' . $e->getMessage());
        }
    }

    /**
     * Create a new referral rate level.
     */
    public function storeRate(Request $request)
    {
        $request->validate([
            'rate' => 'required|numeric|min:0|max:100',
            'description' => 'required|string|max:255',
        ]);

        try {
            ReferralRate::create([
                'rate' => $request->rate,
                'description' => $request->description,
            ]);

            return redirect()->back()->with('success', 'New referral rate level added successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to add referral rate: ' . $e->getMessage());
        }
    }

    /**
     * Delete a referral rate level.
     */
    public function destroyRate($id)
    {
        try {
            $referralRate = ReferralRate::findOrFail($id);

            // Don't allow deletion of the first 3 levels as they are core to the system
            if ($referralRate->id <= 3) {
                return redirect()->back()->with('error', 'Cannot delete core referral levels (1-3). You can only edit their rates.');
            }

            $referralRate->delete();

            return redirect()->back()->with('success', 'Referral rate level deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete referral rate: ' . $e->getMessage());
        }
    }
}
