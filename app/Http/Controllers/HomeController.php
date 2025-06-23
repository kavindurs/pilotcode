<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function getTopRatedBusinesses()
{
    // Get top rated properties with their average ratings
    $topRatedBusinesses = \App\Models\Property::select(
            'properties.id',
            'properties.business_name',
            'properties.domain',
            'properties.profile_picture',
            'properties.category',
            DB::raw('AVG(rates.rate) as average_rating'),
            DB::raw('COUNT(rates.id) as review_count')
        )
        ->join('rates', 'properties.id', '=', 'rates.property_id')
        ->where('rates.status', 'Approved') // Only count approved reviews
        ->groupBy('properties.id', 'properties.business_name', 'properties.domain', 'properties.profile_picture', 'properties.category')
        ->orderBy('average_rating', 'desc') // Sort by highest rating
        ->limit(4) // Get top 4
        ->get();

    return view('home.top_rated_businesses', compact('topRatedBusinesses'));
}

/**
 * Get latest reviews for display on homepage
 *
 * @return \Illuminate\View\View
 */
public function getLatestReviews()
{
    $latestReviews = \App\Models\Rate::with(['user', 'property'])
        ->where('status', 'Approved')
        ->orderBy('created_at', 'desc')
        ->take(8)
        ->get();

    return view('home.latest_reviews', ['latestReviews' => $latestReviews]);
}

public function pricing()
{
    // Fetch all plans from the database
    $plans = \App\Models\Plan::all();

    // Pass the plans to the view - note the 'priceing' spelling to match your file name
    return view('home.priceing', compact('plans'));
}    /**
     * Get promoted properties for homepage display
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPromotedProperties()
    {
        return \App\Models\Property::select([
            'properties.id',
            'properties.business_name',
            'properties.city',
            'properties.country',
            'properties.zip_code',
            'properties.business_email',
            'properties.domain',
            'properties.property_type',
            'properties.category',
            'properties.subcategory',
            'properties.profile_picture',
            'properties.created_at',
            'properties.updated_at',
            DB::raw('AVG(rates.rate) as average_rating'),
            DB::raw('COUNT(rates.id) as review_count')
        ])
        ->leftJoin('rates', function($join) {
            $join->on('properties.id', '=', 'rates.property_id')
                 ->where('rates.status', '=', 'Approved');
        })
        ->whereHas('ads', function ($query) {
            $query->where('status', 'active')
                  ->where('start_date', '<=', now())
                  ->where('end_date', '>=', now());
        })
        ->with(['ads' => function ($query) {
            $query->where('status', 'active')
                  ->where('start_date', '<=', now())
                  ->where('end_date', '>=', now());
        }])
        ->groupBy([
            'properties.id',
            'properties.business_name',
            'properties.city',
            'properties.country',
            'properties.zip_code',
            'properties.business_email',
            'properties.domain',
            'properties.property_type',
            'properties.category',
            'properties.subcategory',
            'properties.profile_picture',
            'properties.created_at',
            'properties.updated_at'
        ])
        ->orderBy('properties.created_at', 'desc')
        ->get();
    }

    /**
     * Display the homepage with promoted properties
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get promoted properties (those with active ads)
        $promotedProperties = $this->getPromotedProperties();

        return view('welcome', compact('promotedProperties'));
    }

}
