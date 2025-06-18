@extends('layouts.business')

@section('active-home', 'bg-blue-600')

@section('title', 'Property Dashboard')

@section('page-title')
    Welcome, {{ $property->business_name }}
@endsection

@section('page-subtitle', 'Your analytics hub for managing business reviews and performance metrics.')

@push('head')
    <!-- Include Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')
    @php
        use Carbon\Carbon;
        use Carbon\CarbonPeriod;

        // Filter approved reviews for the current property.
        $approvedRates = $rates->where('status', 'Approved')
                                ->where('property_id', $property->id);

        // Total review count and scoreness score for the stats section.
        $totalReviewCount = $approvedRates->count();
        $scorenessScore = $approvedRates->avg('rate') ?? 0;

        // Define recent reviews (3 most recent) using created_at.
        $recentReviews = $rates->sortByDesc('created_at')->take(3);

        // Get current month in "Y-m" format.
        $currentMonth = Carbon::now()->format('Y-m');
        // Filter reviews for the current month based on created_at.
        // Only approved data is considered here.
        $approvedCurrentMonth = $approvedRates->filter(function($rate) use ($currentMonth) {
            return Carbon::parse($rate->created_at)->format('Y-m') === $currentMonth;
        });

        // Create a period for the current month (start to end).
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();
        $period = CarbonPeriod::create($startDate, $endDate);

        $chartLabels = [];
        $chartData = [];

        // Loop through each day of the current month and count approved reviews based on created_at.
        foreach ($period as $date) {
            $dayLabel = $date->format('d');  // Day of month (01-31)
            $chartLabels[] = $dayLabel;
            $count = $approvedCurrentMonth->filter(function($rate) use ($date) {
                return Carbon::parse($rate->created_at)->format('Y-m-d') === $date->format('Y-m-d');
            })->count();
            $chartData[] = $count;
        }
    @endphp

    <!-- Instructions Card -->
    <div class="bg-gradient-to-r from-gray-900 to-gray-800 border-l-4 border-yellow-500 rounded-lg p-6 mb-8 shadow-sm">
        <div class="flex items-start">
            <div class="flex-shrink-0 pt-0.5">
                <i class="fas fa-lightbulb text-2xl text-yellow-400 mr-4"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-white mb-1">Quick Start Guide</h3>
                <p class="text-gray-300 mb-2">Your dashboard provides a complete overview of your business performance and review metrics.</p>
                <ul class="text-sm text-gray-300 space-y-1 mt-2">
                    <li class="flex items-center"><i class="fas fa-check-circle text-green-400 mr-2"></i> Track review performance metrics and trends</li>
                    <li class="flex items-center">
                        <i class="fas fa-check-circle text-green-400 mr-2"></i>
                        Use the
                        <a href="{{ route('property.invitations') }}" class="text-yellow-400 hover:underline px-2"> Review Invitations </a>
                        section to request customer feedback
                      </li>
                      <li class="flex items-center mb-2">
                        <i class="fas fa-check-circle text-green-400 mr-2"></i>
                        Manage your
                        <a href="{{ route('property.products') }}" class="text-yellow-400 hover:underline px-2"> Products </a>
                        to improve customer satisfaction
                      </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Dashboard Stats Section -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-gray-900 rounded-lg shadow-md overflow-hidden border border-gray-800 transition-all hover:shadow-lg">
            <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-4">
                <h2 class="text-lg font-semibold text-white flex items-center">
                    <i class="fas fa-star text-yellow-400 mr-2"></i> Review Score
                </h2>
            </div>
            <div class="p-6">
                <p class="text-4xl font-bold text-yellow-400 text-center">{{ number_format($scorenessScore, 1) }}</p>
                <p class="text-sm text-gray-400 text-center mt-2">Average rating from approved reviews</p>
            </div>
        </div>

        <div class="bg-gray-900 rounded-lg shadow-md overflow-hidden border border-gray-800 transition-all hover:shadow-lg">
            <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-4">
                <h2 class="text-lg font-semibold text-white flex items-center">
                    <i class="fas fa-comments text-gray-300 mr-2"></i> Total Reviews
                </h2>
            </div>
            <div class="p-6">
                <p class="text-4xl font-bold text-gray-200 text-center">{{ $totalReviewCount }}</p>
                <p class="text-sm text-gray-400 text-center mt-2">Approved reviews for your business</p>
            </div>
        </div>

        <div class="bg-gray-900 rounded-lg shadow-md overflow-hidden border border-gray-800 transition-all hover:shadow-lg">
            <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-4">
                <h2 class="text-lg font-semibold text-white flex items-center">
                    <i class="fas fa-envelope text-gray-300 mr-2"></i> Monthly Invitations
                </h2>
            </div>
            <div class="p-6">
                @php
                    // Get active plan for the property
                    $activePlan = $property->getActivePlan();
                    $planName = $activePlan ? $activePlan->name : 'Free';

                    // Determine email limit based on plan
                    $emailLimit = 0;
                    if ($planName == 'Basic') $emailLimit = 30;
                    elseif ($planName == 'Pro') $emailLimit = 75;
                    elseif ($planName == 'Premium') $emailLimit = 200;

                    // Calculate emails used this month
                    $emailsUsed = App\Models\ReviewInvitation::where('property_id', $property->id)
                                    ->whereMonth('created_at', now()->month)
                                    ->whereYear('created_at', now()->year)
                                    ->count();
                @endphp
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-medium text-gray-300">{{ $emailsUsed }} / {{ $emailLimit }}</span>
                    <span class="text-xs font-medium text-gray-400">{{ $planName }} Plan</span>
                </div>
                <div class="w-full bg-gray-800 rounded-full h-2.5">
                    @php
                        $percentage = $emailLimit > 0 ? min(($emailsUsed / $emailLimit) * 100, 100) : 0;
                        $barColor = $percentage < 70 ? 'bg-green-600' : ($percentage < 90 ? 'bg-yellow-500' : 'bg-red-500');
                    @endphp
                    <div class="{{ $barColor }} h-2.5 rounded-full" style="width: {{ $percentage }}%"></div>
                </div>
                <p class="text-xs text-gray-400 mt-2 text-center">Invitations sent this month</p>
            </div>
        </div>
    </div>

    <!-- Property Details Section -->
    <section class="bg-gray-900 rounded-lg shadow-md p-6 mb-8 border border-gray-800">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-white flex items-center">
                <i class="fas fa-building text-yellow-400 mr-2"></i> Business Details
            </h2>
            <a href="{{ route('property.settings') }}" class="text-sm text-yellow-400 hover:text-yellow-300 flex items-center">
                <i class="fas fa-edit mr-1"></i> Edit Details
            </a>
        </div>

        <div class="grid gap-4 md:grid-cols-3">
            <div class="p-4 bg-gray-800 rounded-lg">
                <p class="text-sm font-medium text-gray-400 mb-1">Business Type</p>
                <p class="font-semibold text-gray-200">{{ $property->property_type ?? 'Not specified' }}</p>
            </div>

            <div class="p-4 bg-gray-800 rounded-lg">
                <p class="text-sm font-medium text-gray-400 mb-1">Location</p>
                <p class="font-semibold text-gray-200">{{ $property->city }}, {{ $property->country }}</p>
            </div>

            <div class="p-4 bg-gray-800 rounded-lg">
                <p class="text-sm font-medium text-gray-400 mb-1">Annual Revenue</p>
                <p class="font-semibold text-gray-200">{{ $property->annual_revenue }}</p>
            </div>

            <div class="p-4 bg-gray-800 rounded-lg">
                <p class="text-sm font-medium text-gray-400 mb-1">Status</p>
                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $property->status == 'Active' ? 'bg-green-900 text-green-300' : 'bg-yellow-900 text-yellow-300' }}">
                    {{ $property->status }}
                </span>
            </div>

            <div class="p-4 bg-gray-800 rounded-lg">
                <p class="text-sm font-medium text-gray-400 mb-1">Contact Email</p>
                <p class="font-semibold text-gray-200">{{ $property->business_email }}</p>
            </div>

            <div class="p-4 bg-gray-800 rounded-lg">
                <p class="text-sm font-medium text-gray-400 mb-1">Subscription</p>
                <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-700 text-yellow-400">
                    {{ $planName }} Plan
                </span>
            </div>
        </div>
    </section>

    <!-- Analytics Chart -->
    <section class="bg-gray-900 rounded-lg shadow-md p-6 mb-8 border border-gray-800">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-white flex items-center">
                <i class="fas fa-chart-line text-yellow-400 mr-2"></i> Review Analytics
            </h2>
            <div class="flex items-center space-x-2">
                <span class="text-sm text-gray-300">{{ Carbon::now()->format('F Y') }}</span>
            </div>
        </div>

        <div class="mt-4">
            <canvas id="reviewsChart" height="300"></canvas>
        </div>

        <div class="mt-4 text-center text-sm text-gray-400">
            <p>Chart shows daily approved reviews for the current month</p>
        </div>
    </section>

    <!-- Recent Reviews Section -->
    <section class="bg-gray-900 rounded-lg shadow-md p-6 border border-gray-800">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-white flex items-center">
                <i class="fas fa-comment-alt text-yellow-400 mr-2"></i> Recent Reviews
            </h2>
            <a href="#" class="text-sm text-yellow-400 hover:text-yellow-300 flex items-center">
                <i class="fas fa-external-link-alt mr-1"></i> View All Reviews
            </a>
        </div>

        @if($recentReviews->count())
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($recentReviews as $review)
                    <div class="border border-gray-700 rounded-lg p-5 hover:shadow-md transition-shadow bg-gray-800 relative">
                        <div class="absolute top-4 right-4">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star {{ $i <= $review->rate ? 'text-yellow-400' : 'text-gray-600' }}"></i>
                            @endfor
                        </div>

                        <div class="mt-5">
                            <p class="text-gray-300 mb-4 line-clamp-3">{{ $review->review }}</p>

                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 rounded-full bg-gray-700 flex items-center justify-center">
                                        <i class="fas fa-user text-gray-400"></i>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-200">Customer</p>
                                    <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($review->experienced_date)->format('F d, Y') }}</p>
                                </div>
                            </div>

                            <div class="mt-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $review->status == 'Approved' ? 'bg-green-900 text-green-300' : 'bg-yellow-900 text-yellow-300' }}">
                                    {{ $review->status }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($recentReviews->count() < 1)
                <div class="text-center py-10">
                    <div class="mx-auto w-24 h-24 rounded-full bg-gray-800 flex items-center justify-center mb-4">
                        <i class="fas fa-comment-slash text-4xl text-gray-600"></i>
                    </div>
                    <h3 class="text-lg font-medium text-white mb-2">No Reviews Yet</h3>
                    <p class="text-gray-400 max-w-md mx-auto mb-6">Start collecting reviews to build trust with potential customers and improve your business.</p>
                    <a href="{{ route('property.invitations.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                        <i class="fas fa-paper-plane mr-2"></i> Send Review Invitations
                    </a>
                </div>
            @endif
        @else
            <div class="text-center py-10">
                <div class="mx-auto w-24 h-24 rounded-full bg-gray-800 flex items-center justify-center mb-4">
                    <i class="fas fa-comment-slash text-4xl text-gray-600"></i>
                </div>
                <h3 class="text-lg font-medium text-white mb-2">No Reviews Yet</h3>
                <p class="text-gray-400 max-w-md mx-auto mb-6">Start collecting reviews to build trust with potential customers and improve your business.</p>
                <a href="{{ route('property.invitations.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                    <i class="fas fa-paper-plane mr-2"></i> Send Review Invitations
                </a>
            </div>
        @endif
    </section>

    <!-- Action Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8 mb-30">
        <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-lg p-6 border border-gray-700 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 rounded-full bg-gray-700 flex items-center justify-center mr-4">
                    <i class="fas fa-envelope-open-text text-xl text-yellow-400"></i>
                </div>
                <h3 class="font-semibold text-white">Send Invitations</h3>
            </div>
            <p class="text-sm text-gray-300 mb-4">Invite your customers to leave reviews by sending personalized email invitations.</p>
            <a href="{{ route('property.invitations') }}" class="text-sm font-medium text-yellow-400 hover:text-yellow-300 flex items-center">
                Get Started <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>

        <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-lg p-6 border border-gray-700 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 rounded-full bg-gray-700 flex items-center justify-center mr-4">
                    <i class="fas fa-code text-xl text-yellow-400"></i>
                </div>
                <h3 class="font-semibold text-white">Website Integration</h3>
            </div>
            <p class="text-sm text-gray-300 mb-4">Add review widgets to your website to showcase your customer feedback.</p>
            <a href="{{ route('property.integrations') }}" class="text-sm font-medium text-yellow-400 hover:text-yellow-300 flex items-center">
                Get Started <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>

        <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-lg p-6 border border-gray-700 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 rounded-full bg-gray-700 flex items-center justify-center mr-4">
                    <i class="fas fa-boxes text-xl text-yellow-400"></i>
                </div>
                <h3 class="font-semibold text-white">Manage Products</h3>
            </div>
            <p class="text-sm text-gray-300 mb-4">Add and manage products or services that customers can review.</p>
            <a href="{{ route('property.products') }}" class="text-sm font-medium text-yellow-400 hover:text-yellow-300 flex items-center">
                Get Started <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>

        <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-lg p-6 border border-gray-700 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 rounded-full bg-gray-700 flex items-center justify-center mr-4">
                    <i class="fas fa-puzzle-piece text-xl text-yellow-400"></i>
                </div>
                <h3 class="font-semibold text-white">Website Widgets</h3>
            </div>
            <p class="text-sm text-gray-300 mb-4">Customize how your reviews appear on your website with display widgets.</p>
            <a href="{{ route('property.widgets') }}" class="text-sm font-medium text-yellow-400 hover:text-yellow-300 flex items-center">
                Get Started <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Chart.js Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var chartLabels = {!! json_encode($chartLabels) !!};
            var chartData = {!! json_encode($chartData) !!};
            var ctx = document.getElementById('reviewsChart').getContext('2d');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: chartLabels,
                    datasets: [{
                        label: 'Daily Reviews',
                        data: chartData,
                        fill: true,
                        borderColor: '#facc15',
                        backgroundColor: 'rgba(250, 204, 21, 0.1)',
                        tension: 0.4,
                        borderWidth: 3,
                        pointBackgroundColor: '#facc15',
                        pointBorderColor: '#1f2937',
                        pointRadius: 4,
                        pointHoverRadius: 6
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
                            backgroundColor: 'rgba(17, 24, 39, 0.9)',
                            padding: 12,
                            titleFont: {
                                size: 14,
                                weight: 'bold'
                            },
                            bodyFont: {
                                size: 13
                            },
                            displayColors: false,
                            callbacks: {
                                title: function(tooltipItems) {
                                    const date = new Date();
                                    const currentYear = date.getFullYear();
                                    const currentMonth = date.getMonth();
                                    return `${new Date(currentYear, currentMonth, tooltipItems[0].label).toLocaleDateString('en-US', {month: 'short', day: 'numeric'})}`;
                                },
                                label: function(context) {
                                    let label = ' Reviews: ';
                                    label += context.raw;
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false,
                                color: 'rgba(255, 255, 255, 0.1)'
                            },
                            ticks: {
                                font: {
                                    size: 11
                                },
                                color: '#9ca3af'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0,
                                font: {
                                    size: 11
                                },
                                color: '#9ca3af',
                                callback: function(value) {
                                    return value;
                                }
                            },
                            grid: {
                                borderDash: [3],
                                color: 'rgba(75, 85, 99, 0.3)'
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
