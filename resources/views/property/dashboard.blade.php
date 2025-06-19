@extends('layouts.business')

@section('active-home', 'bg-blue-600')

@section('title', 'Dashboard')

@section('page-title')
    Welcome back, {{ $property->business_name }}
@endsection

@section('page-subtitle', 'Here\'s what\'s happening with your business today.')

@push('head')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')
<div class="space-y-8">
    @php
        use Carbon\Carbon;
        use Carbon\CarbonPeriod;

        // Calculate metrics
        $approvedRates = $rates->where('status', 'Approved')->where('property_id', $property->id);
        $totalReviews = $approvedRates->count();
        $averageRating = $approvedRates->avg('rate') ?? 0;
        $recentReviews = $rates->sortByDesc('created_at')->take(4);

        // Monthly data
        $currentMonth = Carbon::now()->format('Y-m');
        $monthlyReviews = $approvedRates->filter(function($rate) use ($currentMonth) {
            return Carbon::parse($rate->created_at)->format('Y-m') === $currentMonth;
        })->count();

        // Step 1: Get the property ID from the logged-in property
        $propertyId = $property->id;

        // Step 2: Get the plan_id associated with this property_id from payments table
        $currentPayment = App\Models\Payment::where('property_id', $propertyId)
                            ->whereIn('status', ['completed', 'success'])
                            ->latest()
                            ->first();

        // Initialize default values
        $planDetails = null;
        $planName = 'Free';
        $emailLimit = 0;
        $planPrice = 0;
        $planDuration = null;
        $planFeatures = [];
        $subscriptionStatus = 'Free Plan';
        $nextBillingDate = null;
        $paymentMethod = null;
        $paymentAmount = null;
        $planDescription = null;        // Step 3: If payment exists, get the plan details from plans table using plan_id
        if ($currentPayment && $currentPayment->plan_id) {
            $planDetails = App\Models\Plan::find($currentPayment->plan_id);

            if ($planDetails) {
                // Get plan information from plans table (using actual database fields)
                $planName = $planDetails->name;
                $planPrice = $planDetails->price;
                $emailLimit = $planDetails->review_invitation_limit; // Use actual review invitation limit

                // Get payment information
                $paymentAmount = $currentPayment->amount ?? $planPrice;
                $paymentMethod = $currentPayment->payment_method ?? 'N/A';
                $subscriptionStatus = 'Active';

                // Create plan features array based on actual plan limits
                $planFeatures = [];
                if ($planDetails->product_limit > 0) {
                    $planFeatures[] = $planDetails->product_limit . ' Products';
                }
                if ($planDetails->widget_limit > 0) {
                    $planFeatures[] = $planDetails->widget_limit . ' Widgets';
                }
                if ($planDetails->html_integration_limit > 0) {
                    $planFeatures[] = number_format($planDetails->html_integration_limit) . ' HTML Integrations';
                }
                if ($planDetails->review_invitation_limit > 0) {
                    $planFeatures[] = $planDetails->review_invitation_limit . ' Review Invitations per month';
                }
                if ($planDetails->referral_code) {
                    $planFeatures[] = 'Referral Code Access';
                }

                // Add basic features for all paid plans
                if ($planDetails->price > 0) {
                    $planFeatures[] = 'Email Support';
                    $planFeatures[] = 'Analytics Dashboard';
                    $planFeatures[] = 'Custom Branding';
                }
            }
        }

        $emailsUsed = App\Models\ReviewInvitation::where('property_id', $property->id)
                        ->whereMonth('created_at', now()->month)
                        ->whereYear('created_at', now()->year)
                        ->count();

        // Chart data for the last 7 days
        $chartLabels = [];
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $chartLabels[] = $date->format('M j');
            $count = $approvedRates->filter(function($rate) use ($date) {
                return Carbon::parse($rate->created_at)->format('Y-m-d') === $date->format('Y-m-d');
            })->count();
            $chartData[] = $count;
        }
    @endphp

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Average Rating -->
        <div class="bg-gradient-to-br from-amber-500/10 to-orange-500/10 backdrop-blur-sm border border-amber-500/20 rounded-2xl p-6 hover:shadow-2xl transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-amber-200/70 text-sm font-medium">Average Rating</p>
                    <p class="text-3xl font-bold text-amber-400 mt-1">{{ number_format($averageRating, 1) }}</p>
                    <div class="flex items-center mt-2">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-4 h-4 {{ $i <= round($averageRating) ? 'text-amber-400' : 'text-gray-600' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endfor
                    </div>
                </div>
                <div class="bg-amber-500/20 p-3 rounded-xl">
                    <svg class="w-8 h-8 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Reviews -->
        <div class="bg-gradient-to-br from-blue-500/10 to-cyan-500/10 backdrop-blur-sm border border-blue-500/20 rounded-2xl p-6 hover:shadow-2xl transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-200/70 text-sm font-medium">Total Reviews</p>
                    <p class="text-3xl font-bold text-blue-400 mt-1">{{ $totalReviews }}</p>
                    <p class="text-blue-200/50 text-xs mt-1">All time</p>
                </div>
                <div class="bg-blue-500/20 p-3 rounded-xl">
                    <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Monthly Reviews -->
        <div class="bg-gradient-to-br from-emerald-500/10 to-teal-500/10 backdrop-blur-sm border border-emerald-500/20 rounded-2xl p-6 hover:shadow-2xl transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-emerald-200/70 text-sm font-medium">This Month</p>
                    <p class="text-3xl font-bold text-emerald-400 mt-1">{{ $monthlyReviews }}</p>
                    <p class="text-emerald-200/50 text-xs mt-1">{{ Carbon::now()->format('F Y') }}</p>
                </div>
                <div class="bg-emerald-500/20 p-3 rounded-xl">
                    <svg class="w-8 h-8 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Email Usage -->
        <div class="bg-gradient-to-br from-purple-500/10 to-pink-500/10 backdrop-blur-sm border border-purple-500/20 rounded-2xl p-6 hover:shadow-2xl transition-all duration-300">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-purple-200/70 text-sm font-medium">Email Usage</p>
                    <p class="text-3xl font-bold text-purple-400 mt-1">{{ $emailsUsed }}</p>
                    <div class="flex items-center justify-between mt-2">
                        <span class="text-purple-200/50 text-xs">{{ $emailLimit }} limit</span>
                        <span class="text-xs bg-purple-500/20 text-purple-300 px-2 py-1 rounded-full">{{ $planName }}</span>
                    </div>
                    @if($emailLimit > 0)
                        <div class="w-full bg-purple-900/30 rounded-full h-1.5 mt-2">
                            @php $percentage = min(($emailsUsed / $emailLimit) * 100, 100); @endphp
                            <div class="bg-gradient-to-r from-purple-500 to-pink-500 h-1.5 rounded-full transition-all duration-300" style="width: {{ $percentage }}%"></div>
                        </div>
                    @endif
                </div>
                <div class="bg-purple-500/20 p-3 rounded-xl ml-4">
                    <svg class="w-8 h-8 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        <!-- Chart Section -->
        <div class="xl:col-span-2">
            <div class="bg-gray-900/50 backdrop-blur-sm border border-gray-800 rounded-2xl p-6 hover:shadow-2xl transition-all duration-300">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-xl font-semibold text-white">Review Trends</h3>
                        <p class="text-gray-400 text-sm">Last 7 days activity</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 bg-amber-500 rounded-full"></div>
                        <span class="text-sm text-gray-300">Reviews</span>
                    </div>
                </div>
                <div class="relative h-80">
                    <canvas id="trendsChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Business Info & Quick Actions -->
        <div class="space-y-6">
            <!-- Business Status -->
            <div class="bg-gray-900/50 backdrop-blur-sm border border-gray-800 rounded-2xl p-6 hover:shadow-2xl transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-white">Business Status</h3>
                    <span class="px-3 py-1 text-xs font-medium rounded-full {{ $property->status == 'Active' ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' : 'bg-amber-500/20 text-amber-400 border border-amber-500/30' }}">
                        {{ $property->status }}
                    </span>
                </div>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400 text-sm">Type</span>
                        <span class="text-white text-sm">{{ $property->property_type ?? 'Not specified' }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400 text-sm">Location</span>
                        <span class="text-white text-sm">{{ $property->city }}, {{ $property->country }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400 text-sm">Plan</span>
                        <span class="text-amber-400 text-sm font-medium">{{ $planName }}</span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-gray-900/50 backdrop-blur-sm border border-gray-800 rounded-2xl p-6 hover:shadow-2xl transition-all duration-300">
                <h3 class="text-lg font-semibold text-white mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    <a href="{{ route('property.invitations') }}" class="flex items-center p-3 bg-gradient-to-r from-blue-600/20 to-blue-700/20 border border-blue-500/30 rounded-xl hover:from-blue-600/30 hover:to-blue-700/30 transition-all duration-200 group">
                        <div class="bg-blue-500/30 p-2 rounded-lg mr-3 group-hover:bg-blue-500/40 transition-all duration-200">
                            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-white font-medium text-sm">Send Invitations</p>
                            <p class="text-gray-400 text-xs">Request customer reviews</p>
                        </div>
                        <svg class="w-4 h-4 text-gray-400 ml-auto group-hover:text-blue-400 transition-all duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>

                    <a href="{{ route('property.products') }}" class="flex items-center p-3 bg-gradient-to-r from-emerald-600/20 to-emerald-700/20 border border-emerald-500/30 rounded-xl hover:from-emerald-600/30 hover:to-emerald-700/30 transition-all duration-200 group">
                        <div class="bg-emerald-500/30 p-2 rounded-lg mr-3 group-hover:bg-emerald-500/40 transition-all duration-200">
                            <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-white font-medium text-sm">Manage Products</p>
                            <p class="text-gray-400 text-xs">Add or edit products</p>
                        </div>
                        <svg class="w-4 h-4 text-gray-400 ml-auto group-hover:text-emerald-400 transition-all duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>

                    <a href="{{ route('property.integrations') }}" class="flex items-center p-3 bg-gradient-to-r from-purple-600/20 to-purple-700/20 border border-purple-500/30 rounded-xl hover:from-purple-600/30 hover:to-purple-700/30 transition-all duration-200 group">
                        <div class="bg-purple-500/30 p-2 rounded-lg mr-3 group-hover:bg-purple-500/40 transition-all duration-200">
                            <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-white font-medium text-sm">Website Integration</p>
                            <p class="text-gray-400 text-xs">Add review widgets</p>
                        </div>
                        <svg class="w-4 h-4 text-gray-400 ml-auto group-hover:text-purple-400 transition-all duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>

                    <a href="{{ route('property.settings') }}" class="flex items-center p-3 bg-gradient-to-r from-gray-600/20 to-gray-700/20 border border-gray-500/30 rounded-xl hover:from-gray-600/30 hover:to-gray-700/30 transition-all duration-200 group">
                        <div class="bg-gray-500/30 p-2 rounded-lg mr-3 group-hover:bg-gray-500/40 transition-all duration-200">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-white font-medium text-sm">Settings</p>
                            <p class="text-gray-400 text-xs">Update business info</p>
                        </div>
                        <svg class="w-4 h-4 text-gray-400 ml-auto group-hover:text-gray-300 transition-all duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Comprehensive Property Details -->
    <div class="bg-gray-900/50 backdrop-blur-sm border border-gray-800 rounded-2xl p-6 hover:shadow-2xl transition-all duration-300">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-xl font-semibold text-white flex items-center">
                    <svg class="w-6 h-6 text-amber-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    Complete Property Information
                </h3>
                <p class="text-gray-400 text-sm">All details about your registered business</p>
            </div>
            <a href="{{ route('property.settings') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-amber-500/20 to-orange-500/20 border border-amber-500/30 rounded-lg hover:from-amber-500/30 hover:to-orange-500/30 transition-all duration-200 text-amber-400 text-sm font-medium">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit Property
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
            <!-- Basic Information -->
            <div class="bg-gray-800/30 rounded-xl p-5 border border-gray-700/50">
                <h4 class="text-lg font-semibold text-white mb-4 flex items-center">
                    <svg class="w-5 h-5 text-blue-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Basic Information
                </h4>
                <div class="space-y-3">
                    <div class="flex justify-between items-start">
                        <span class="text-gray-400 text-sm">Business Name</span>
                        <span class="text-white text-sm font-medium text-right">{{ $property->business_name }}</span>
                    </div>
                    <div class="flex justify-between items-start">
                        <span class="text-gray-400 text-sm">Property Type</span>
                        <span class="text-white text-sm">{{ $property->property_type ?? 'Not specified' }}</span>
                    </div>
                    <div class="flex justify-between items-start">
                        <span class="text-gray-400 text-sm">Industry</span>
                        <span class="text-white text-sm">{{ $property->industry ?? 'Not specified' }}</span>
                    </div>
                    <div class="flex justify-between items-start">
                        <span class="text-gray-400 text-sm">Status</span>
                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $property->status == 'Active' ? 'bg-emerald-500/20 text-emerald-400' : 'bg-amber-500/20 text-amber-400' }}">
                            {{ $property->status }}
                        </span>
                    </div>
                    <div class="flex justify-between items-start">
                        <span class="text-gray-400 text-sm">Registration Date</span>
                        <span class="text-white text-sm">{{ Carbon::parse($property->created_at)->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="bg-gray-800/30 rounded-xl p-5 border border-gray-700/50">
                <h4 class="text-lg font-semibold text-white mb-4 flex items-center">
                    <svg class="w-5 h-5 text-emerald-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Contact Details
                </h4>
                <div class="space-y-3">
                    <div class="flex justify-between items-start">
                        <span class="text-gray-400 text-sm">Business Email</span>
                        <span class="text-white text-sm">{{ $property->business_email }}</span>
                    </div>
                    <div class="flex justify-between items-start">
                        <span class="text-gray-400 text-sm">Phone</span>
                        <span class="text-white text-sm">{{ $property->phone ?? 'Not provided' }}</span>
                    </div>
                    <div class="flex justify-between items-start">
                        <span class="text-gray-400 text-sm">Website</span>
                        <span class="text-white text-sm">
                            @if($property->website)
                                <a href="{{ $property->website }}" target="_blank" class="text-blue-400 hover:text-blue-300">{{ $property->website }}</a>
                            @else
                                Not provided
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between items-start">
                        <span class="text-gray-400 text-sm">Address</span>
                        <span class="text-white text-sm text-right">
                            {{ $property->address ?? 'Not provided' }}
                            @if($property->address && ($property->city || $property->country))
                                <br>
                            @endif
                            @if($property->city || $property->country)
                                {{ $property->city }}@if($property->city && $property->country), @endif{{ $property->country }}
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <!-- Business Details -->
            <div class="bg-gray-800/30 rounded-xl p-5 border border-gray-700/50">
                <h4 class="text-lg font-semibold text-white mb-4 flex items-center">
                    <svg class="w-5 h-5 text-purple-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Business Metrics
                </h4>
                <div class="space-y-3">
                    <div class="flex justify-between items-start">
                        <span class="text-gray-400 text-sm">Annual Revenue</span>
                        <span class="text-white text-sm">{{ $property->annual_revenue ?? 'Not specified' }}</span>
                    </div>
                    <div class="flex justify-between items-start">
                        <span class="text-gray-400 text-sm">Employee Count</span>
                        <span class="text-white text-sm">{{ $property->employee_count ?? 'Not specified' }}</span>
                    </div>
                    <div class="flex justify-between items-start">
                        <span class="text-gray-400 text-sm">Founded Year</span>
                        <span class="text-white text-sm">{{ $property->founded_year ?? 'Not specified' }}</span>
                    </div>
                    <div class="flex justify-between items-start">
                        <span class="text-gray-400 text-sm">Current Plan</span>
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-amber-500/20 text-amber-400">
                            {{ $planName }} Plan
                        </span>
                    </div>
                    <div class="flex justify-between items-start">
                        <span class="text-gray-400 text-sm">Total Reviews</span>
                        <span class="text-emerald-400 text-sm font-medium">{{ $totalReviews }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Information -->
        @if($property->description)
        <div class="mt-6 bg-gray-800/30 rounded-xl p-5 border border-gray-700/50">
            <h4 class="text-lg font-semibold text-white mb-3 flex items-center">
                <svg class="w-5 h-5 text-cyan-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Business Description
            </h4>
            <p class="text-gray-300 text-sm leading-relaxed">{{ $property->description }}</p>
        </div>
        @endif

        <!-- Account & Subscription Information -->
        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-gray-800/30 rounded-xl p-5 border border-gray-700/50 h-full">
                <h4 class="text-lg font-semibold text-white mb-4 flex items-center">
                    <svg class="w-5 h-5 text-indigo-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Account Information & Plan Features
                </h4>

                <!-- Account Information Section -->
                <div class="space-y-3 mb-6">
                    <div class="flex justify-between items-start">
                        <span class="text-gray-400 text-sm">Property ID</span>
                        <span class="text-white text-sm font-mono">#{{ $property->id }}</span>
                    </div>
                    <div class="flex justify-between items-start">
                        <span class="text-gray-400 text-sm">Last Updated</span>
                        <span class="text-white text-sm">{{ Carbon::parse($property->updated_at)->format('M d, Y g:i A') }}</span>
                    </div>
                    <div class="flex justify-between items-start">
                        <span class="text-gray-400 text-sm">Verification Status</span>
                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $property->verified_at ? 'bg-emerald-500/20 text-emerald-400' : 'bg-yellow-500/20 text-yellow-400' }}">
                            {{ $property->verified_at ? 'Verified' : 'Pending Verification' }}
                        </span>
                    </div>
                </div>

                <!-- Plan Features Section -->
                @if($planDetails && !empty($planFeatures))
                <div class="pt-4 border-t border-gray-700/50">
                    <h5 class="text-sm font-semibold text-white mb-3 flex items-center">
                        <svg class="w-4 h-4 text-emerald-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Plan Features
                    </h5>
                    <ul class="space-y-2">
                        @foreach($planFeatures as $feature)
                            <li class="flex items-center text-gray-300 text-sm">
                                <svg class="w-3 h-3 text-emerald-400 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                {{ $feature }}
                            </li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>

            <div class="bg-gray-800/30 rounded-xl p-5 border border-gray-700/50 h-full">
                <h4 class="text-lg font-semibold text-white mb-4 flex items-center">
                    <svg class="w-5 h-5 text-pink-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                    </svg>
                    Subscription Details
                </h4>
                <div class="space-y-3">
                    <!-- Subscription Information -->
                    <div class="flex justify-between items-start">
                        <span class="text-gray-400 text-sm">Current Plan</span>
                        <span class="text-amber-400 text-sm font-medium">{{ $planName }}</span>
                    </div>

                    @if($planDetails)
                        <div class="flex justify-between items-start">
                            <span class="text-gray-400 text-sm">Plan ID</span>
                            <span class="text-white text-sm font-mono">#{{ $planDetails->id }}</span>
                        </div>
                        <div class="flex justify-between items-start">
                            <span class="text-gray-400 text-sm">Plan Price</span>
                            <span class="text-white text-sm">${{ number_format($planPrice, 2) }} / month</span>
                        </div>
                        <div class="flex justify-between items-start">
                            <span class="text-gray-400 text-sm">Paid Amount</span>
                            <span class="text-white text-sm">${{ number_format($paymentAmount, 2) }}</span>
                        </div>

                        <!-- Plan Limits Section -->
                        <div class="pt-3 border-t border-gray-700/50">
                            <h5 class="text-sm font-semibold text-white mb-2">Plan Limits</h5>
                            <div class="grid grid-cols-2 gap-2 text-xs">
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Products:</span>
                                    <span class="text-white">{{ $planDetails->product_limit == 0 ? 'Unlimited' : $planDetails->product_limit }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Widgets:</span>
                                    <span class="text-white">{{ $planDetails->widget_limit == 0 ? 'Unlimited' : $planDetails->widget_limit }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-400">HTML Integrations:</span>
                                    <span class="text-white">{{ number_format($planDetails->html_integration_limit) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Review Invitations:</span>
                                    <span class="text-white">{{ $planDetails->review_invitation_limit }}/month</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Referral Code:</span>
                                    <span class="text-white">{{ $planDetails->referral_code ? 'Enabled' : 'Disabled' }}</span>
                                </div>
                            </div>
                        </div>
                        @if($paymentMethod)
                            <div class="flex justify-between items-start">
                                <span class="text-gray-400 text-sm">Payment Method</span>
                                <span class="text-white text-sm">{{ ucfirst($paymentMethod) }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between items-start">
                            <span class="text-gray-400 text-sm">Payment ID</span>
                            <span class="text-white text-sm font-mono">#{{ $currentPayment->id }}</span>
                        </div>
                        <div class="flex justify-between items-start">
                            <span class="text-gray-400 text-sm">Payment Date</span>
                            <span class="text-white text-sm">{{ Carbon::parse($currentPayment->created_at)->format('M d, Y g:i A') }}</span>
                        </div>
                        <div class="flex justify-between items-start">
                            <span class="text-gray-400 text-sm">Payment Status</span>
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-emerald-500/20 text-emerald-400">
                                {{ ucfirst($currentPayment->status) }}
                            </span>
                        </div>
                    @else
                        <div class="bg-amber-500/10 border border-amber-500/20 rounded-lg p-3">
                            <p class="text-amber-400 text-xs">
                                @if(!$currentPayment)
                                    No completed/successful payments found for Property ID: {{ $propertyId }}
                                @elseif(!$currentPayment->plan_id)
                                    Payment found but no plan_id associated with it
                                @else
                                    Plan ID {{ $currentPayment->plan_id }} not found in plans table
                                @endif
                            </p>
                        </div>
                    @endif

                    <!-- Usage Information -->
                    <div class="pt-3 border-t border-gray-700/50">
                        <div class="flex justify-between items-start">
                            <span class="text-gray-400 text-sm">Email Limit</span>
                            <span class="text-white text-sm">{{ $emailLimit > 0 ? number_format($emailLimit) : 'Unlimited' }} per month</span>
                        </div>
                        <div class="flex justify-between items-start">
                            <span class="text-gray-400 text-sm">Emails Used</span>
                            <span class="text-white text-sm">{{ $emailsUsed }} this month</span>
                        </div>
                        @if($emailLimit > 0)
                            <div class="flex justify-between items-start">
                                <span class="text-gray-400 text-sm">Usage Percentage</span>
                                <span class="text-white text-sm">{{ round(($emailsUsed / $emailLimit) * 100, 1) }}%</span>
                            </div>
                            <div class="mt-2">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-gray-400 text-xs">Usage Progress</span>
                                    <span class="text-gray-400 text-xs">{{ $emailsUsed }}/{{ $emailLimit }}</span>
                                </div>
                                <div class="w-full bg-gray-700 rounded-full h-2">
                                    @php $usagePercentage = ($emailsUsed / $emailLimit) * 100; @endphp
                                    <div class="bg-gradient-to-r from-blue-500 to-purple-500 h-2 rounded-full transition-all duration-300" style="width: {{ min($usagePercentage, 100) }}%"></div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="flex justify-between items-start pt-3 border-t border-gray-700/50">
                        <span class="text-gray-400 text-sm">Subscription Status</span>
                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $subscriptionStatus == 'Active' ? 'bg-emerald-500/20 text-emerald-400' : 'bg-gray-500/20 text-gray-400' }}">
                            {{ $subscriptionStatus }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Reviews -->
    @if($recentReviews->count() > 0)
    <div class="bg-gray-900/50 backdrop-blur-sm border border-gray-800 rounded-2xl p-6 hover:shadow-2xl transition-all duration-300">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-xl font-semibold text-white">Recent Reviews</h3>
                <p class="text-gray-400 text-sm">Latest customer feedback</p>
            </div>
            <a href="#" class="text-amber-400 hover:text-amber-300 text-sm font-medium flex items-center group">
                View all
                <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
            @foreach($recentReviews as $review)
            <div class="bg-gray-800/50 border border-gray-700 rounded-xl p-5 hover:bg-gray-800/70 transition-all duration-200">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-4 h-4 {{ $i <= $review->rate ? 'text-amber-400' : 'text-gray-600' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        @endfor
                    </div>
                    <span class="text-xs px-2 py-1 rounded-full {{ $review->status == 'Approved' ? 'bg-emerald-500/20 text-emerald-400' : 'bg-amber-500/20 text-amber-400' }}">
                        {{ $review->status }}
                    </span>
                </div>
                <p class="text-gray-300 text-sm mb-4 line-clamp-3">{{ $review->review }}</p>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                            <span class="text-white text-xs font-medium">{{ substr($review->user->name ?? 'A', 0, 1) }}</span>
                        </div>
                        <div class="ml-2">
                            <p class="text-white text-sm font-medium">{{ $review->user->name ?? 'Anonymous' }}</p>
                            <p class="text-gray-400 text-xs">{{ Carbon::parse($review->created_at)->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @else
    <!-- Empty State -->
    <div class="bg-gray-900/50 backdrop-blur-sm border border-gray-800 rounded-2xl p-12 text-center hover:shadow-2xl transition-all duration-300">
        <div class="mx-auto w-20 h-20 bg-gray-800 rounded-full flex items-center justify-center mb-6">
            <svg class="w-10 h-10 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>
        </div>
        <h3 class="text-xl font-semibold text-white mb-2">No Reviews Yet</h3>
        <p class="text-gray-400 mb-6 max-w-md mx-auto">Start building trust with your customers by collecting your first reviews.</p>
        <a href="{{ route('property.invitations.create') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-medium rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            Send First Invitation
        </a>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const chartLabels = {!! json_encode($chartLabels) !!};
    const chartData = {!! json_encode($chartData) !!};
    const ctx = document.getElementById('trendsChart').getContext('2d');

    // Create gradient
    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, 'rgba(245, 158, 11, 0.8)');
    gradient.addColorStop(1, 'rgba(245, 158, 11, 0.1)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartLabels,
            datasets: [{
                label: 'Reviews',
                data: chartData,
                fill: true,
                backgroundColor: gradient,
                borderColor: '#f59e0b',
                borderWidth: 3,
                pointBackgroundColor: '#f59e0b',
                pointBorderColor: '#1f2937',
                pointBorderWidth: 2,
                pointRadius: 6,
                pointHoverRadius: 8,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(17, 24, 39, 0.95)',
                    padding: 16,
                    titleFont: {
                        size: 14,
                        weight: 'bold'
                    },
                    bodyFont: {
                        size: 13
                    },
                    displayColors: false,
                    borderColor: 'rgba(245, 158, 11, 0.3)',
                    borderWidth: 1,
                    cornerRadius: 12,
                    callbacks: {
                        title: function(tooltipItems) {
                            return tooltipItems[0].label;
                        },
                        label: function(context) {
                            return `${context.raw} ${context.raw === 1 ? 'review' : 'reviews'}`;
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: 12
                        },
                        color: '#9ca3af'
                    }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0,
                        font: {
                            size: 12
                        },
                        color: '#9ca3af',
                        stepSize: 1
                    },
                    grid: {
                        color: 'rgba(75, 85, 99, 0.2)',
                        borderDash: [5, 5]
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            }
        }
    });
});
</script>
@endpush
