<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Payment; // Add this line to import the Payment model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\Category;
use App\Models\Subcategory;
use App\Mail\OTPMail;
use App\Models\Rate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Exports\ReviewsExport;
use App\Services\PDFReportService;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Traits\PropertyPlanHelper;
use Illuminate\Support\Str;
use App\Http\Controllers\ReferralController;

class PropertyController extends Controller
{
    use PropertyPlanHelper;

    public function step1(Request $request)
    {
        $categories = Category::where('is_active', true)
            ->with(['subcategories' => function($query) {
                $query->where('is_active', true);
            }])
            ->get();

        // Capture referral code from URL parameter
        $referralCode = $request->get('ref');
        if ($referralCode) {
            session(['referral_code' => $referralCode]);
        }

        return view('property.step1', compact('categories', 'referralCode'));
    }

    public function submitStep1(Request $request)
    {
        $validated = $request->validate([
            'property_type' => 'required',
            'business_name' => 'required',
            'city' => 'required',
            'country' => 'required',
            'zip_code' => 'required',
            'annual_revenue' => 'required',
            'employee_count' => 'required',
            'category' => 'required',
            'subcategory' => 'required',
        ]);

        session(['step1_data' => $validated]);
        return redirect()->route('property.create.step2');
    }

    public function step2()
    {
        if (!session('step1_data')) {
            return redirect()->route('property.create.step1');
        }
        return view('property.step2', ['property_type' => session('step1_data.property_type')]);
    }

    public function submitStep2(Request $request)
    {
        $validated = $request->validate([
            'business_email' => 'required|email|unique:properties',
            'first_name' => 'required',
            'last_name' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        if (session('step1_data.property_type') === 'Web') {
            $request->validate(['domain' => 'required|url']);
            $validated['domain'] = $request->input('domain'); // Add this line to store the provided domain
        } else {
            $request->validate(['document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048']);
        }

        if ($request->hasFile('document')) {
            $path = $request->file('document')->store('business_documents', 'public');
            $validated['document_path'] = $path;
        }

        // Generate OTP
        $otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

        // Store OTP in session with timestamp
        session([
            'otp' => [
                'code' => $otp,
                'expires_at' => now()->addMinutes(10),
                'attempts' => 0
            ]
        ]);
        session(['step2_data' => $validated]);

        try {
            Mail::to($validated['business_email'])
                ->send(new OTPMail($otp, $validated['first_name']));

            return redirect()->route('property.create.step3')
                ->with('success', 'OTP has been sent to your email');
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Failed to send OTP. Please try again.'])
                ->withInput();
        }
    }

    public function step3()
    {
        if (!session('step2_data')) {
            return redirect()->route('property.create.step2');
        }
        return view('property.step3');
    }

    public function submitStep3(Request $request)
    {
        $request->validate(['otp' => 'required|numeric|digits:6']);

        $otpData = session('otp');

        if (!$otpData) {
            return back()->withErrors(['otp' => 'OTP session has expired. Please request a new one.']);
        }

        if (now()->isAfter($otpData['expires_at'])) {
            session()->forget('otp');
            return back()->withErrors(['otp' => 'OTP has expired. Please request a new one.']);
        }

        if ($otpData['attempts'] >= 3) {
            session()->forget('otp');
            return back()->withErrors(['otp' => 'Too many attempts. Please request a new OTP.']);
        }

        // Increment attempts
        session(['otp.attempts' => $otpData['attempts'] + 1]);

        if ($request->otp !== $otpData['code']) {
            return back()->withErrors(['otp' => 'Invalid OTP code.']);
        }

        $propertyData = array_merge(
            session('step1_data'),
            session('step2_data'),
            ['status' => 'Not Approved']
        );

        // Add referral code if present
        if (session('referral_code')) {
            $propertyData['referred_by'] = session('referral_code');
        }

        $propertyData['password'] = Hash::make($propertyData['password']);

        // Create the property record
        $property = Property::create($propertyData);

        // Automatically add a payment record using the property data.
        Payment::create([
            'business_email' => $propertyData['business_email'],
            'plan_id'        => 1,
            'order_id'       => $propertyData['business_email'] . '-1-' . time(),
            // Set the amount to zero, or to a listing fee if applicable.
            'amount'         => 0,
            'currency'       => 'USD',
            'status'         => 'pending',
            'transaction_id' => null,
        ]);

        // Clear sessions
        session()->forget(['step1_data', 'step2_data', 'otp', 'referral_code']);

        return redirect()->route('property.success')
            ->with('success', 'Your property has been successfully registered!');
    }

    public function success()
    {
        return view('property.success');
    }

    public function resendOTP()
    {
        $step2Data = session('step2_data');

        if (!$step2Data) {
            return response()->json(['error' => 'Session expired'], 400);
        }

        $otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

        session([
            'otp' => [
                'code' => $otp,
                'expires_at' => now()->addMinutes(10),
                'attempts' => 0
            ]
        ]);

        try {
            Mail::to($step2Data['business_email'])
                ->send(new OTPMail($otp, $step2Data['first_name']));

            return response()->json(['message' => 'OTP resent successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to send OTP'], 500);
        }
    }

    public function showBySubcategory($subcategory, Request $request)
    {
        // Get the category first
        $category = Category::whereHas('subcategories', function($query) use ($subcategory) {
            $query->where('name', $subcategory);
        })->first();

        $query = Property::where('subcategory', $subcategory)
            ->where('status', 'Approved');

        // Apply rating filter
        if ($request->rating && $request->rating !== 'any') {
            $minRating = floatval($request->rating);
            $query->whereHas('rates', function($q) use ($minRating) {
                $q->where('status', 'Approved')
                  ->havingRaw('AVG(rate) >= ?', [$minRating]);
            });
        }

        // Apply location filters
        if ($request->country) {
            $query->where('country', $request->country);
        }

        if ($request->zip_code) {
            $query->where(function($q) use ($request) {
                $q->where('zip_code', $request->zip_code)
                  ->orWhere('city', 'LIKE', '%' . $request->zip_code . '%');
            });
        }

        // Apply property type filter - if no type selected, show all
        if (!empty($request->property_type)) {
            $query->whereIn('property_type', $request->property_type);
        } else {
            $query->whereIn('property_type', ['physical', 'web']);
        }

        $properties = $query->with(['category', 'subcategory'])
            ->withCount(['rates' => function($query) {
                $query->where('status', 'Approved');
            }])
            ->withAvg(['rates' => function($query) {
                $query->where('status', 'Approved');
            }], 'rate')
            ->get();

        // Get unique countries for dropdown
        $countries = Property::where('status', 'Approved')
            ->distinct()
            ->pluck('country')
            ->sort()
            ->values();

        // Get property types count for display
        $propertyTypeCounts = Property::where('status', 'Approved')
            ->where('subcategory', $subcategory)
            ->selectRaw('property_type, count(*) as count')
            ->groupBy('property_type')
            ->pluck('count', 'property_type')
            ->toArray();

        return view('properties.by-subcategory', compact(
            'properties',
            'subcategory',
            'category',
            'countries',
            'propertyTypeCounts'
        ));
    }

    public function settings()
    {
        $property = Property::find(session('property_id'));
        if (!$property) {
            return redirect()->route('property.login')->with('error', 'Property not found.');
        }
        return view('property.settings', compact('property'));
    }

    /**
     * Update property settings - limited to specific fields
     */
    public function updateSettings(Request $request)
    {
        $property = Property::find(session('property_id'));
        if (!$property) {
            return redirect()->route('property.login')->with('error', 'Property not found.');
        }

        // Only validate the editable fields
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'annual_revenue' => 'required|string|in:1-9999,10000-99999,100000-999999,1000000+',
            'employee_count' => 'required|string|in:1-9,10-49,50-99,100-499,500-999,1000-9999,10000+',
            'profile_picture' => 'nullable|image|max:2048',

            // Include the hidden fields in validation to prevent tampering
            'property_type' => 'required|string',
            'business_name' => 'required|string',
            'business_email' => 'required|email',
            'domain' => 'nullable|string',
            'city' => 'required|string',
            'country' => 'required|string',
            'zip_code' => 'required|string',
            'category' => 'required|string',
            'subcategory' => 'required|string',
        ]);

        // Only allow editing of specific fields
        $editableData = [
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'annual_revenue' => $validated['annual_revenue'],
            'employee_count' => $validated['employee_count']
        ];

        // Handle profile picture upload if provided
        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if it exists
            if ($property->profile_picture) {
                Storage::disk('public')->delete($property->profile_picture);
            }

            // Store the new profile picture
            $editableData['profile_picture'] = $request->file('profile_picture')->store('profile_pictures', 'public');
        }

        // Update property with only the editable fields
        $property->update($editableData);

        return redirect()->route('property.settings')->with('success', 'Profile settings updated successfully.');
    }

    /**
     * Show the details for a specific property.
     *
     * @param  \App\Models\Property  $property
     * @return \Illuminate\View\View
     */
    public function show(Property $property)
    {
        // Load rates with user information (including profile pictures)
        $property->load(['rates' => function($query) {
            $query->with('user')->where('status', 'Approved')->latest();
        }]);

        // Calculate the average rating
        $property->rates_avg_rate = $property->rates->avg('rate') ?? 0;
        $property->rates_count = $property->rates->count();

        return view('properties.show', compact('property'));
    }

    /**
     * Display the reviews for the logged in property
     *
     * @return \Illuminate\View\View
     */
    public function reviews()
    {
        // Check if property is logged in
        if (!session('property_id')) {
            return redirect()->route('property.login')
                ->with('error', 'Please login to access your business dashboard.');
        }

        // Get the property from session
        $property = \App\Models\Property::select(['id', 'business_name', 'profile_picture'])
            ->find(session('property_id'));

        if (!$property) {
            return redirect()->route('property.login')->with('error', 'Property not found.');
        }

        // Get all approved reviews for this property with user information
        $reviews = \App\Models\Rate::with('user')
            ->where('property_id', $property->id)
            ->where('status', 'Approved')
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate review statistics
        $totalReviews = $reviews->count();
        $averageRating = $reviews->avg('rate') ?? 0;

        $ratingDistribution = [
            '5' => $reviews->where('rate', 5)->count(),
            '4' => $reviews->where('rate', 4)->count(),
            '3' => $reviews->where('rate', 3)->count(),
            '2' => $reviews->where('rate', 2)->count(),
            '1' => $reviews->where('rate', 1)->count(),
        ];

        // Get counts of recent reviews
        $recentReviews = $reviews->where('created_at', '>=', now()->subDays(30))->count();

        return view('property.reviews', compact(
            'property',
            'reviews',
            'totalReviews',
            'averageRating',
            'ratingDistribution',
            'recentReviews'
        ));
    }

    /**
     * Display the review analysis dashboard
     *
     * @return \Illuminate\View\View
     */
    public function reviewAnalysis()
    {
        // Check if property is logged in
        if (!session('property_id')) {
            return redirect()->route('property.login')
                ->with('error', 'Please login to access your business dashboard.');
        }

        // Get the property from session
        $property = Property::find(session('property_id'));

        if (!$property) {
            return redirect()->route('property.login')->with('error', 'Property not found.');
        }

        // Premium plan IDs: 3, 4, 6, 7
        $premiumPlanIds = [3, 4, 6, 7];
        $hasProAccess = in_array($property->plan_id, $premiumPlanIds);

        // If plan_id is not premium, check if they have premium payment
        if (!$hasProAccess && $property->business_email) {
            $propertyEmail = strtolower(trim($property->business_email));

            // Get payments for this email
            $payments = \App\Models\Payment::whereRaw('LOWER(TRIM(business_email)) = ?', [$propertyEmail])->get();

            // Check if any payment has a premium plan
            foreach ($payments as $payment) {
                if (in_array($payment->plan_id, $premiumPlanIds)) {
                    $hasProAccess = true;
                    break;
                }
            }
        }

        // Get all approved reviews for this property
        $reviews = Rate::where('property_id', $property->id)
            ->where('status', 'Approved')
            ->orderBy('created_at', 'desc')
            ->get();

        // Basic statistics - available to all plans
        $totalReviews = $reviews->count();
        $averageRating = $reviews->avg('rate') ?? 0;

        // Rating distribution - available to all plans
        $ratingDistribution = [
            '5' => $reviews->where('rate', 5)->count(),
            '4' => $reviews->where('rate', 4)->count(),
            '3' => $reviews->where('rate', 3)->count(),
            '2' => $reviews->where('rate', 2)->count(),
            '1' => $reviews->where('rate', 1)->count(),
        ];

        // Initialize variables to avoid undefined errors
        $reviewTrend = [];
        $ratingTrend = [];
        $sentimentCounts = ['positive' => 0, 'neutral' => 0, 'negative' => 0];
        $sentimentData = ['positive' => 0, 'neutral' => 0, 'negative' => 0];
        $topKeywords = [];

        // Only process advanced analytics for premium plans
        if ($hasProAccess) {
            // Month-by-month trend analysis
            for ($i = 11; $i >= 0; $i--) {
                $month = now()->subMonths($i);
                $monthName = $month->format('M Y');

                $monthReviews = $reviews->filter(function($review) use ($month) {
                    return $review->created_at->year == $month->year &&
                           $review->created_at->month == $month->month;
                });

                $reviewTrend[$monthName] = $monthReviews->count();
                $ratingTrend[$monthName] = $monthReviews->avg('rate') ?? 0;
            }

            // Sentiment analysis calculation
            // Count reviews by sentiment category
            $positiveReviews = $reviews->filter(fn($r) => $r->rate >= 4)->count();
            $neutralReviews = $reviews->filter(fn($r) => $r->rate == 3)->count();
            $negativeReviews = $reviews->filter(fn($r) => $r->rate <= 2)->count();

            // Calculate raw counts for sentiment data
            $sentimentCounts = [
                'positive' => $positiveReviews,
                'neutral' => $neutralReviews,
                'negative' => $negativeReviews
            ];

            // Calculate percentages - ensure no division by zero
            if ($totalReviews > 0) {
                $sentimentData = [
                    'positive' => round(($positiveReviews / $totalReviews) * 100, 1),
                    'neutral' => round(($neutralReviews / $totalReviews) * 100, 1),
                    'negative' => round(($negativeReviews / $totalReviews) * 100, 1),
                ];
            }

            // Extract keywords for premium plans
            if ($totalReviews > 0) {
                // Get all review texts
                $reviewTexts = $reviews->pluck('review')->filter()->toArray();
                $allReviewText = implode(' ', $reviewTexts);

                // Define common words to exclude
                $commonWords = [
                    'the', 'and', 'was', 'is', 'in', 'to', 'a', 'of', 'for', 'with', 'on', 'at',
                    'from', 'by', 'this', 'that', 'it', 'or', 'as', 'be', 'are', 'they', 'were',
                    'we', 'very', 'our', 'my', 'i', 'me', 'have', 'has', 'had', 'you', 'your',
                    'their', 'his', 'her', 'but', 'not', 'what', 'all', 'when', 'who', 'how',
                    'which', 'where', 'why', 'there', 'here', 'can', 'will', 'just', 'more', 'also'
                ];

                // Clean the text and split into words
                $cleanText = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', strtolower($allReviewText));
                $words = preg_split('/\s+/', $cleanText, -1, PREG_SPLIT_NO_EMPTY);

                // Count word occurrences
                $wordCount = array_count_values($words);

                // Remove common words, short words, and numbers
                foreach ($wordCount as $word => $count) {
                    if (in_array($word, $commonWords) || strlen($word) < 3 || is_numeric($word) || empty(trim($word))) {
                        unset($wordCount[$word]);
                    }
                }

                // Sort by frequency and get top 20
                arsort($wordCount);
                $topKeywords = array_slice($wordCount, 0, 20, true);

                // If no keywords found after filtering
                if (empty($topKeywords)) {
                    $topKeywords = ['No significant keywords found' => 0];
                }
            }
        } else {
            // For non-premium users, prepare limited data for basic stats
            // Set up basic trend data for the last 3 months
            for ($i = 2; $i >= 0; $i--) {
                $month = now()->subMonths($i);
                $monthName = $month->format('M Y');

                $monthReviews = $reviews->filter(function($review) use ($month) {
                    return $review->created_at->year == $month->year &&
                           $review->created_at->month == $month->month;
                });

                $reviewTrend[$monthName] = $monthReviews->count();
                $ratingTrend[$monthName] = $monthReviews->avg('rate') ?? 0;
            }
        }

        return view('property.review-analysis', compact(
            'property',
            'totalReviews',
            'averageRating',
            'ratingDistribution',
            'reviewTrend',
            'ratingTrend',
            'sentimentData',
            'sentimentCounts',
            'topKeywords',
            'hasProAccess'
        ));
    }

    /**
     * Download Excel export of reviews
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadExcel()
    {
        // Check if property is logged in
        if (!session('property_id')) {
            return redirect()->route('property.login')
                ->with('error', 'Please login to access your business dashboard.');
        }

        // Get the property from session
        $property = \App\Models\Property::select(['id', 'business_name'])
            ->find(session('property_id'));

        if (!$property) {
            return redirect()->route('property.login')->with('error', 'Property not found.');
        }

        // Get all approved reviews for this property
        $reviews = \App\Models\Rate::with('user')
            ->where('property_id', $property->id)
            ->where('status', 'Approved')
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate statistics
        $statistics = [
            'totalReviews' => $reviews->count(),
            'averageRating' => $reviews->avg('rate') ?? 0,
        ];

        // Generate and download Excel file
        return Excel::download(
            new ReviewsExport($reviews, $property, $statistics),
            $property->business_name . ' - Review Data.xlsx'
        );
    }

    /**
     * Download PDF monthly report
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\RedirectResponse
     */
    public function downloadPdf()
    {
        // Check if property is logged in
        if (!session('property_id')) {
            return redirect()->route('property.login')
                ->with('error', 'Please login to access your business dashboard.');
        }

        // Get the property from session
        $property = Property::find(session('property_id'));

        if (!$property) {
            return redirect()->route('property.login')->with('error', 'Property not found.');
        }

        // Check for premium plan access - using the helper trait
        $hasProAccess = $this->hasProOrPremiumPlan($property->plan_id) ||
                        $this->getActivePremiumPlan($property->business_email) !== false;

        if (!$hasProAccess) {
            return redirect()->route('property.review.analysis')
                ->with('error', 'This feature requires a Pro or Premium plan. Please upgrade to access detailed reports.');
        }

        // Get all approved reviews for this property
        $reviews = \App\Models\Rate::with('user')
            ->where('property_id', $property->id)
            ->where('status', 'Approved')
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate statistics
        $ratingDistribution = [
            '5' => $reviews->where('rate', 5)->count(),
            '4' => $reviews->where('rate', 4)->count(),
            '3' => $reviews->where('rate', 3)->count(),
            '2' => $reviews->where('rate', 2)->count(),
            '1' => $reviews->where('rate', 1)->count(),
        ];

        // Sentiment analysis
        $positiveReviews = $reviews->filter(fn($r) => $r->rate >= 4)->count();
        $neutralReviews = $reviews->filter(fn($r) => $r->rate == 3)->count();
        $negativeReviews = $reviews->filter(fn($r) => $r->rate <= 2)->count();

        $totalReviews = $reviews->count();
        $sentimentData = [
            'positive' => $totalReviews > 0 ? ($positiveReviews / $totalReviews) * 100 : 0,
            'neutral' => $totalReviews > 0 ? ($neutralReviews / $totalReviews) * 100 : 0,
            'negative' => $totalReviews > 0 ? ($negativeReviews / $totalReviews) * 100 : 0,
        ];

        $statistics = [
            'totalReviews' => $totalReviews,
            'averageRating' => $reviews->avg('rate') ?? 0,
            'ratingDistribution' => $ratingDistribution,
            'sentimentData' => $sentimentData,
        ];

        // Generate PDF service
        $pdfService = new PDFReportService();
        $pdf = $pdfService->generateMonthlyReport($property, $reviews, $statistics);

        // Download PDF
        return $pdf->download($property->business_name . ' - Monthly Report.pdf');
    }

    /**
     * Download chart image
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function downloadChartImage(Request $request)
    {
        // Check if property is logged in
        if (!session('property_id')) {
            return redirect()->route('property.login')
                ->with('error', 'Please login to access your business dashboard.');
        }

        // Get the property
        $property = Property::find(session('property_id'));

        if (!$property) {
            return redirect()->route('property.login')->with('error', 'Property not found.');
        }

        // Check for premium plan access - using the helper trait
        $hasProAccess = $this->hasProOrPremiumPlan($property->plan_id) ||
                        $this->getActivePremiumPlan($property->business_email) !== false;

        if (!$hasProAccess) {
            return redirect()->route('property.review.analysis')
                ->with('error', 'This feature requires a Pro or Premium plan. Please upgrade to access chart downloads.');
        }

        // Validate the input
        $request->validate([
            'chart_data' => 'required',
            'chart_type' => 'required|in:rating,trend,sentiment,distribution',
        ]);

        // Decode the base64 image data
        $imageData = base64_decode(str_replace('data:image/png;base64,', '', $request->chart_data));

        // Generate filename
        $chartTypes = [
            'rating' => 'Average Rating',
            'trend' => 'Review Trend',
            'sentiment' => 'Sentiment Analysis',
            'distribution' => 'Rating Distribution'
        ];

        $filename = $chartTypes[$request->chart_type] . ' - ' . now()->format('Y-m-d') . '.png';

        // Return the image for download
        return response($imageData)
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Show the plan upgrade page
     *
     * @return \Illuminate\View\View
     */
    public function planUpgrade()
    {
        // Check if property is logged in
        if (!session('property_id')) {
            return redirect()->route('property.login')
                ->with('error', 'Please login to access your business dashboard.');
        }

        $property = \App\Models\Property::with('plan')->find(session('property_id'));

        if (!$property) {
            return redirect()->route('property.login')->with('error', 'Property not found.');
        }

        $plans = \App\Models\Plan::all();

        return view('property.plans.upgrade', compact('property', 'plans'));
    }

    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'property_type' => 'required|string|in:Physical,Web',
            'business_name' => 'required|string|max:255',
            'business_email' => 'required|email|max:255',
            'city' => 'nullable|string|max:255',
            'country' => 'required|string|max:255',
            'domain' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:255',
            'category_type' => 'required|string|in:existing,new',
            'subcategory_type' => 'required|string|in:existing,new',
        ]);

        DB::beginTransaction();

        try {
            // Handle category
            $categoryId = null;

            if ($request->category_type === 'existing') {
                $categoryId = $request->existing_category;

                $category = Category::find($categoryId);
                if (!$category) {
                    throw new \Exception('Selected category not found');
                }
            } else {
                // Create new category
                $category = new Category();
                $category->name = $request->new_category;
                $category->slug = Str::slug($request->new_category);
                $category->is_active = 0; // Pending approval
                $category->save();

                $categoryId = $category->id;
            }

            // Handle subcategory
            $subcategoryId = null;

            if ($request->subcategory_type === 'existing') {
                $subcategoryId = $request->existing_subcategory;

                $subcategory = Subcategory::where('id', $subcategoryId)
                    ->where('category_id', $categoryId)
                    ->first();

                if (!$subcategory) {
                    throw new \Exception('Selected subcategory not found');
                }
            } else {
                // Create new subcategory
                $subcategory = new Subcategory();

                if ($request->category_type === 'existing') {
                    $subcategory->name = $request->new_subcategory;
                } else {
                    $subcategory->name = $request->new_category_subcategory ?? $request->new_subcategory;
                }

                $subcategory->category_id = $categoryId;
                $subcategory->slug = Str::slug($subcategory->name);
                $subcategory->is_active = 0; // Pending approval
                $subcategory->save();

                $subcategoryId = $subcategory->id;
            }

            // Create the property
            $property = new Property();
            $property->property_type = $request->property_type;
            $property->business_name = $request->business_name;
            $property->business_email = $request->business_email;

            if ($request->property_type === 'Web') {
                $property->city = 'Online';
                $property->domain = $request->domain;
                $property->zip_code = '00000';
            } else {
                $property->city = $request->city;
                $property->zip_code = $request->zip_code ?? '';
                $property->domain = $request->domain ?? '';
            }

            $property->country = $request->country === 'Other' ? $request->other_country : $request->country;

            // Store the IDs as strings (since your database uses VARCHAR)
            $property->category = (string)$categoryId;
            $property->subcategory = (string)$subcategoryId;

            // Set default values
            $property->annual_revenue = 'Not Specified';
            $property->employee_count = 'Not Specified';

            // Set user details
            $user = auth()->user();
            $property->first_name = $user->first_name ?? explode(' ', $user->name)[0] ?? 'User';
            $property->last_name = $user->last_name ?? (count(explode(' ', $user->name)) > 1 ? explode(' ', $user->name)[1] : 'Account');
            $property->password = bcrypt(Str::random(12));
            $property->status = 'Not Approved';

            $property->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Business added successfully',
                'property_id' => $property->id,
                'category_created' => $request->category_type === 'new',
                'subcategory_created' => $request->subcategory_type === 'new'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
