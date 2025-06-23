<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminSetting;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SettingsController extends Controller
{
    /**
     * Display the admin settings page.
     */
    public function index()
    {
        $settings = [
            'ad_daily_cost' => AdminSetting::getAdDailyCost(),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update the ad daily cost setting.
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

        return redirect()->route('admin.settings.index')
                       ->with('success', 'Ad daily cost updated successfully to $' . number_format($request->ad_daily_cost, 2));
    }
}
