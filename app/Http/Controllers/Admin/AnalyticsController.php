<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Property;
use App\Models\Payment;
use App\Models\ReviewInvitation;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function index()
    {
        // Users Analytics
        $usersData = $this->getUsersAnalytics();

        // Properties Analytics
        $propertiesData = $this->getPropertiesAnalytics();

        // Payments Analytics
        $paymentsData = $this->getPaymentsAnalytics();

        // Review Invitations Analytics
        $reviewInvitationsData = $this->getReviewInvitationsAnalytics();

        // Products Analytics
        $productsData = $this->getProductsAnalytics();

        // Ads Analytics (if ads_simple table exists)
        $adsData = $this->getAdsAnalytics();

        return view('admin.analytics.index', compact(
            'usersData',
            'propertiesData',
            'paymentsData',
            'reviewInvitationsData',
            'productsData',
            'adsData'
        ));
    }

    private function getUsersAnalytics()
    {
        $last30Days = Carbon::now()->subDays(30);
        $last12Months = Carbon::now()->subMonths(12);

        // Daily registrations for last 30 days
        $dailyRegistrations = User::where('created_at', '>=', $last30Days)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(function ($item) {
                return [
                    'date' => Carbon::parse($item->date)->format('M d'),
                    'count' => $item->count
                ];
            });

        // Monthly registrations for last 12 months
        $monthlyRegistrations = User::where('created_at', '>=', $last12Months)
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get()
            ->map(function ($item) {
                return [
                    'month' => Carbon::createFromDate($item->year, $item->month, 1)->format('M Y'),
                    'count' => $item->count
                ];
            });

        // User status distribution
        $userStatuses = User::selectRaw('
                CASE
                    WHEN email_verified_at IS NOT NULL THEN "Verified"
                    ELSE "Unverified"
                END as status,
                COUNT(*) as count
            ')
            ->groupBy('status')
            ->get();

        return [
            'total' => User::count(),
            'verified' => User::whereNotNull('email_verified_at')->count(),
            'dailyRegistrations' => $dailyRegistrations,
            'monthlyRegistrations' => $monthlyRegistrations,
            'statusDistribution' => $userStatuses
        ];
    }

    private function getPropertiesAnalytics()
    {
        $last30Days = Carbon::now()->subDays(30);

        // Daily property additions
        $dailyProperties = Property::where('created_at', '>=', $last30Days)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(function ($item) {
                return [
                    'date' => Carbon::parse($item->date)->format('M d'),
                    'count' => $item->count
                ];
            });

        // Properties by status
        $propertiesByStatus = Property::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();

        // Properties by type
        $propertiesByType = Property::selectRaw('property_type, COUNT(*) as count')
            ->groupBy('property_type')
            ->get();

        return [
            'total' => Property::count(),
            'approved' => Property::where('status', 'Approved')->count(),
            'pending' => Property::where('status', 'Not Claimed')->count(),
            'dailyAdditions' => $dailyProperties,
            'statusDistribution' => $propertiesByStatus,
            'typeDistribution' => $propertiesByType
        ];
    }

    private function getPaymentsAnalytics()
    {
        $last30Days = Carbon::now()->subDays(30);
        $last12Months = Carbon::now()->subMonths(12);

        // Daily payments
        $dailyPayments = Payment::where('created_at', '>=', $last30Days)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count, SUM(amount) as total_amount')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(function ($item) {
                return [
                    'date' => Carbon::parse($item->date)->format('M d'),
                    'count' => $item->count,
                    'amount' => $item->total_amount
                ];
            });

        // Monthly revenue
        $monthlyRevenue = Payment::where('created_at', '>=', $last12Months)
            ->where('status', 'completed')
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(amount) as total_amount')
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get()
            ->map(function ($item) {
                return [
                    'month' => Carbon::createFromDate($item->year, $item->month, 1)->format('M Y'),
                    'amount' => $item->total_amount
                ];
            });

        // Payment status distribution
        $paymentStatuses = Payment::selectRaw('status, COUNT(*) as count, SUM(amount) as total_amount')
            ->groupBy('status')
            ->get();

        return [
            'total' => Payment::count(),
            'completed' => Payment::where('status', 'completed')->count(),
            'totalRevenue' => Payment::where('status', 'completed')->sum('amount'),
            'dailyPayments' => $dailyPayments,
            'monthlyRevenue' => $monthlyRevenue,
            'statusDistribution' => $paymentStatuses
        ];
    }

    private function getReviewInvitationsAnalytics()
    {
        $last30Days = Carbon::now()->subDays(30);

        // Daily review invitations
        $dailyInvitations = ReviewInvitation::where('created_at', '>=', $last30Days)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(function ($item) {
                return [
                    'date' => Carbon::parse($item->date)->format('M d'),
                    'count' => $item->count
                ];
            });

        // Invitation status distribution
        $invitationStatuses = ReviewInvitation::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();

        return [
            'total' => ReviewInvitation::count(),
            'sent' => ReviewInvitation::where('status', 'sent')->count(),
            'opened' => ReviewInvitation::where('status', 'opened')->count(),
            'dailyInvitations' => $dailyInvitations,
            'statusDistribution' => $invitationStatuses
        ];
    }

    private function getProductsAnalytics()
    {
        $last30Days = Carbon::now()->subDays(30);

        // Daily product additions
        $dailyProducts = Product::where('created_at', '>=', $last30Days)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(function ($item) {
                return [
                    'date' => Carbon::parse($item->date)->format('M d'),
                    'count' => $item->count
                ];
            });

        // Products by status (if status column exists)
        $productStatuses = collect();
        if (DB::getSchemaBuilder()->hasColumn('products', 'status')) {
            $productStatuses = Product::selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->get();
        }

        return [
            'total' => Product::count(),
            'dailyAdditions' => $dailyProducts,
            'statusDistribution' => $productStatuses
        ];
    }

    private function getAdsAnalytics()
    {
        // Check if ads_simple table exists
        if (!DB::getSchemaBuilder()->hasTable('ads_simple')) {
            return [
                'total' => 0,
                'active' => 0,
                'dailyCreated' => collect(),
                'statusDistribution' => collect()
            ];
        }

        $last30Days = Carbon::now()->subDays(30);

        // Daily ad creations
        $dailyAds = DB::table('ads_simple')
            ->where('created_at', '>=', $last30Days)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(function ($item) {
                return [
                    'date' => Carbon::parse($item->date)->format('M d'),
                    'count' => $item->count
                ];
            });

        // Ad status distribution (if status column exists)
        $adStatuses = collect();
        if (DB::getSchemaBuilder()->hasColumn('ads_simple', 'status')) {
            $adStatuses = DB::table('ads_simple')
                ->selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->get();
        }

        return [
            'total' => DB::table('ads_simple')->count(),
            'active' => DB::getSchemaBuilder()->hasColumn('ads_simple', 'status')
                ? DB::table('ads_simple')->where('status', 'active')->count()
                : 0,
            'dailyCreated' => $dailyAds,
            'statusDistribution' => $adStatuses
        ];
    }
}
