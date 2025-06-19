<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Property;
use App\Models\Rate;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Plan;
use App\Models\Referral;
use App\Models\Payment;
use App\Models\Wallet;
use App\Models\Admin;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Get statistics for the dashboard
        $totalUsers = User::count();
        $totalProperties = Property::count();
        $totalReviews = Rate::count();
        $totalCategories = Category::count();
        $totalSubcategories = Subcategory::count();
        $totalPlans = Plan::count();
        $totalReferrals = Referral::count();
        $totalPayments = Payment::count();
        $totalWallets = Wallet::count();
        $totalStaff = Admin::count();

        $pendingProperties = Property::where('status', 'Not Approved')->count();
        $approvedProperties = Property::where('status', 'Approved')->count();
        $activeCategories = Category::where('is_active', 1)->count();
        $activeSubcategories = Subcategory::where('is_active', 1)->count();
        $pendingReviews = Rate::where('status', 'Pending Approval')->count();
        $approvedReviews = Rate::where('status', 'Approved')->count();
        $activeReferrals = Referral::where('is_active', 1)->count();
        $confirmedPayments = Payment::whereIn('status', ['confirmed', 'CONFIRMED', 'success', 'completed'])->count();
        $pendingPayments = Payment::whereIn('status', ['pending', 'PENDING', 'processing'])->count();
        $totalRevenue = Payment::whereIn('status', ['confirmed', 'CONFIRMED', 'success', 'completed'])->sum('amount');
        $totalWalletBalance = Wallet::sum('balance');
        $verifiedUsers = User::where('email_verified_at', '!=', null)->count();
        $activeStaff = Admin::where('status', 'active')->count();

        // Calculate percentage increases based on last 30 days
        $lastMonth = Carbon::now()->subDays(30);
        $newUsersLastMonth = User::where('created_at', '>=', $lastMonth)->count();
        $newPropertiesLastMonth = Property::where('created_at', '>=', $lastMonth)->count();
        $newReviewsLastMonth = Rate::where('created_at', '>=', $lastMonth)->count();
        $newPaymentsLastMonth = Payment::where('created_at', '>=', $lastMonth)->count();

        // Calculate percentages (avoid division by zero)
        $totalUsersOld = $totalUsers - $newUsersLastMonth;
        $totalPropertiesOld = $totalProperties - $newPropertiesLastMonth;
        $totalReviewsOld = $totalReviews - $newReviewsLastMonth;
        $totalPaymentsOld = $totalPayments - $newPaymentsLastMonth;

        $newUsersPercentage = $totalUsersOld > 0 ? round(($newUsersLastMonth / $totalUsersOld) * 100, 1) : 0;
        $newPropertiesPercentage = $totalPropertiesOld > 0 ? round(($newPropertiesLastMonth / $totalPropertiesOld) * 100, 1) : 0;
        $newReviewsPercentage = $totalReviewsOld > 0 ? round(($newReviewsLastMonth / $totalReviewsOld) * 100, 1) : 0;
        $newPaymentsPercentage = $totalPaymentsOld > 0 ? round(($newPaymentsLastMonth / $totalPaymentsOld) * 100, 1) : 0;

        // Get recent activity data
        $recentProperties = Property::orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $recentReviews = Rate::with(['property', 'user'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $recentUsers = User::orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $recentPayments = Payment::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Monthly revenue chart data (last 6 months)
        $monthlyRevenue = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $revenue = Payment::whereIn('status', ['confirmed', 'CONFIRMED', 'success', 'completed'])
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->sum('amount');
            $monthlyRevenue[] = [
                'month' => $month->format('M Y'),
                'revenue' => $revenue
            ];
        }

        // Activity summary for today
        $todayStats = [
            'new_users' => User::whereDate('created_at', Carbon::today())->count(),
            'new_properties' => Property::whereDate('created_at', Carbon::today())->count(),
            'new_reviews' => Rate::whereDate('created_at', Carbon::today())->count(),
            'new_payments' => Payment::whereDate('created_at', Carbon::today())->count(),
        ];

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalProperties',
            'totalReviews',
            'totalCategories',
            'totalSubcategories',
            'totalPlans',
            'totalReferrals',
            'totalPayments',
            'totalWallets',
            'totalStaff',
            'pendingProperties',
            'approvedProperties',
            'activeCategories',
            'activeSubcategories',
            'pendingReviews',
            'approvedReviews',
            'activeReferrals',
            'confirmedPayments',
            'pendingPayments',
            'totalRevenue',
            'totalWalletBalance',
            'verifiedUsers',
            'activeStaff',
            'newUsersPercentage',
            'newPropertiesPercentage',
            'newReviewsPercentage',
            'newPaymentsPercentage',
            'recentProperties',
            'recentReviews',
            'recentUsers',
            'recentPayments',
            'monthlyRevenue',
            'todayStats'
        ));
    }
}
