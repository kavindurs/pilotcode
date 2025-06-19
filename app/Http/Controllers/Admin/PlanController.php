<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $plans = Plan::withCount('properties')
            ->when($search, function($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('price', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.plans.index', compact('plans', 'search'));
    }

    public function show($id)
    {
        $plan = Plan::with('properties')->findOrFail($id);
        return view('admin.plans.show', compact('plan'));
    }

    public function create()
    {
        return view('admin.plans.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'product_limit' => 'required|integer|min:0',
            'widget_limit' => 'required|integer|min:0',
            'html_integration_limit' => 'required|integer|min:0',
            'review_invitation_limit' => 'required|integer|min:0',
            'referral_code' => 'boolean'
        ]);

        $validatedData['referral_code'] = $request->has('referral_code') ? 1 : 0;

        Plan::create($validatedData);

        return redirect()->route('admin.plans.index')->with('success', 'Plan created successfully.');
    }

    public function edit($id)
    {
        $plan = Plan::findOrFail($id);
        return view('admin.plans.edit', compact('plan'));
    }

    public function update(Request $request, $id)
    {
        $plan = Plan::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'product_limit' => 'required|integer|min:0',
            'widget_limit' => 'required|integer|min:0',
            'html_integration_limit' => 'required|integer|min:0',
            'review_invitation_limit' => 'required|integer|min:0',
            'referral_code' => 'boolean'
        ]);

        $validatedData['referral_code'] = $request->has('referral_code') ? 1 : 0;

        $plan->update($validatedData);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Plan updated successfully.']);
        }

        return redirect()->route('admin.plans.index')->with('success', 'Plan updated successfully.');
    }

    public function destroy($id)
    {
        $plan = Plan::findOrFail($id);

        // Check if plan has associated properties
        if ($plan->properties()->count() > 0) {
            return redirect()->route('admin.plans.index')
                ->with('error', 'Cannot delete plan with associated properties. Please reassign properties first.');
        }

        $plan->delete();

        return redirect()->route('admin.plans.index')->with('success', 'Plan deleted successfully.');
    }
}
