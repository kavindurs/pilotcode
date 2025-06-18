<!-- filepath: c:\xampp\htdocs\pilot\resources\views\property\review-analysis.blade.php -->

@extends('layouts.business')

@section('title', 'Review Analysis')
@section('active-review-analysis', 'bg-blue-600')
@section('page-title', 'Review Analysis')
@section('page-subtitle', 'Gain insights from your customer reviews')

@push('head')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
@endpush

@section('content')
<div >
    <!-- Premium Plan Check -->
    @if(!$hasProAccess)
        <div class="bg-gray-900 text-white rounded-lg shadow p-6 mb-6 border border-gray-800">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-2">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mt-1">
                            <div class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center">
                                <i class="fas fa-lock text-yellow-500"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-xl font-bold text-white mb-2">Premium Feature</h3>
                            <p class="text-gray-300 mb-4">Review Analysis is available exclusively to Pro and Premium plan subscribers.</p>
                            <div class="flex flex-wrap gap-3">
                                <a href="{{ url('/plans') }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-white hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                    <i class="fas fa-arrow-circle-up mr-2"></i> Upgrade Now
                                </a>
                                <a href="{{ route('property.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-gray-700 rounded-md font-semibold text-gray-200 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                    <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-800 rounded-lg p-4">
                    <h4 class="font-semibold text-yellow-400 mb-3">Premium Features Include:</h4>
                    <ul class="space-y-2">
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-yellow-400 mt-1 mr-2"></i>
                            <span class="text-sm text-gray-300">Advanced Analytics Dashboard</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-yellow-400 mt-1 mr-2"></i>
                            <span class="text-sm text-gray-300">Sentiment Analysis</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-yellow-400 mt-1 mr-2"></i>
                            <span class="text-sm text-gray-300">Keyword Extraction</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-yellow-400 mt-1 mr-2"></i>
                            <span class="text-sm text-gray-300">Downloadable Reports</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- Only show analysis content to premium users -->
    @if($hasProAccess)
    <!-- Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-gray-900 text-white rounded-lg shadow p-5 border border-gray-800">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-lg font-medium text-gray-200">Average Rating</h3>
                <div class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center">
                    <i class="fas fa-star text-yellow-500"></i>
                </div>
            </div>
            <div class="flex items-center">
                <span class="text-3xl font-bold text-white mr-2">{{ number_format($averageRating, 1) }}/5.0</span>
                <div class="star-rating text-xl relative inline-block">
                    <div class="stars-bg text-gray-700">★★★★★</div>
                    <div class="stars-fg text-yellow-400 absolute top-0 left-0 overflow-hidden whitespace-nowrap" style="width: {{ ($averageRating / 5) * 100 }}%">★★★★★</div>
                </div>
            </div>
            <p class="text-sm text-gray-400 mt-2">From {{ $totalReviews }} reviews</p>
        </div>

        <!-- Sentiment Breakdown Box -->
        <div class="bg-gray-900 text-white rounded-lg shadow p-5 border border-gray-800">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-200">Sentiment Breakdown</h3>
                <div class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center">
                    <i class="fas fa-smile text-yellow-500"></i>
                </div>
            </div>

            <!-- Sentiment progress bars -->
            <div class="space-y-4">
                <!-- Positive sentiment -->
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <div class="text-sm font-medium text-gray-300">Positive</div>
                        <div class="text-sm font-medium text-gray-300">{{ $sentimentCounts['positive'] }} ({{ number_format($sentimentData['positive'], 1) }}%)</div>
                    </div>
                    <div class="w-full bg-gray-800 rounded-full h-2.5">
                        <div class="bg-green-500 h-2.5 rounded-full" style="width: {{ $sentimentData['positive'] }}%"></div>
                    </div>
                </div>

                <!-- Neutral sentiment -->
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <div class="text-sm font-medium text-gray-300">Neutral</div>
                        <div class="text-sm font-medium text-gray-300">{{ $sentimentCounts['neutral'] }} ({{ number_format($sentimentData['neutral'], 1) }}%)</div>
                    </div>
                    <div class="w-full bg-gray-800 rounded-full h-2.5">
                        <div class="bg-blue-500 h-2.5 rounded-full" style="width: {{ $sentimentData['neutral'] }}%"></div>
                    </div>
                </div>

                <!-- Negative sentiment -->
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <div class="text-sm font-medium text-gray-300">Negative</div>
                        <div class="text-sm font-medium text-gray-300">{{ $sentimentCounts['negative'] }} ({{ number_format($sentimentData['negative'], 1) }}%)</div>
                    </div>
                    <div class="w-full bg-gray-800 rounded-full h-2.5">
                        <div class="bg-red-500 h-2.5 rounded-full" style="width: {{ $sentimentData['negative'] }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Sentiment count summary -->
            <div class="grid grid-cols-3 gap-2 mt-4 text-center text-sm">
                <div class="bg-gray-800 rounded p-2 border border-gray-700">
                    <span class="text-green-400 font-medium">{{ $sentimentCounts['positive'] }}</span>
                    <span class="text-gray-400 block">4-5 ★</span>
                </div>
                <div class="bg-gray-800 rounded p-2 border border-gray-700">
                    <span class="text-blue-400 font-medium">{{ $sentimentCounts['neutral'] }}</span>
                    <span class="text-gray-400 block">3 ★</span>
                </div>
                <div class="bg-gray-800 rounded p-2 border border-gray-700">
                    <span class="text-red-400 font-medium">{{ $sentimentCounts['negative'] }}</span>
                    <span class="text-gray-400 block">1-2 ★</span>
                </div>
            </div>
        </div>

        <div class="bg-gray-900 text-white rounded-lg shadow p-5 border border-gray-800">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-lg font-medium text-gray-200">Trend Analysis</h3>
                <div class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center">
                    <i class="fas fa-chart-line text-yellow-500"></i>
                </div>
            </div>
            <div class="mt-2">
                @php
                    $lastTwoMonths = array_slice($reviewTrend, -2, 2, true);
                    $keys = array_keys($lastTwoMonths);
                    $currentMonth = end($keys);
                    $previousMonth = prev($keys);

                    $currentCount = $lastTwoMonths[$currentMonth];
                    $previousCount = $lastTwoMonths[$previousMonth];

                    $percentChange = $previousCount > 0
                        ? (($currentCount - $previousCount) / $previousCount) * 100
                        : ($currentCount > 0 ? 100 : 0);
                @endphp

                <div class="flex items-center">
                    <span class="text-2xl font-bold text-white mr-2">{{ $currentCount }}</span>
                    <span class="text-sm text-gray-400">reviews this month</span>
                </div>

                <div class="flex items-center mt-1">
                    @if($percentChange > 0)
                        <span class="text-green-400 text-sm flex items-center">
                            <i class="fas fa-arrow-up mr-1"></i> {{ number_format(abs($percentChange), 1) }}%
                        </span>
                    @elseif($percentChange < 0)
                        <span class="text-red-400 text-sm flex items-center">
                            <i class="fas fa-arrow-down mr-1"></i> {{ number_format(abs($percentChange), 1) }}%
                        </span>
                    @else
                        <span class="text-gray-400 text-sm flex items-center">
                            <i class="fas fa-minus mr-1"></i> 0%
                        </span>
                    @endif
                    <span class="text-xs text-gray-500 ml-1">vs last month</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Rating Distribution Chart -->
        <div class="bg-gray-900 text-white rounded-lg shadow p-6 border border-gray-800">
            <h3 class="text-lg font-medium text-gray-200 mb-4">Rating Distribution</h3>
            <div class="h-64">
                <canvas id="ratingDistributionChart"></canvas>
            </div>
        </div>

        <!-- Review Trend Chart -->
        <div class="bg-gray-900 text-white rounded-lg shadow p-6 border border-gray-800">
            <h3 class="text-lg font-medium text-gray-200 mb-4">Review Trend (Last 12 Months)</h3>
            <div class="h-64">
                <canvas id="reviewTrendChart"></canvas>
            </div>
        </div>

        <!-- Rating Trend Chart -->
        <div class="bg-gray-900 text-white rounded-lg shadow p-6 border border-gray-800">
            <h3 class="text-lg font-medium text-gray-200 mb-4">Average Rating Trend</h3>
            <div class="h-64">
                <canvas id="ratingTrendChart"></canvas>
            </div>
        </div>

        <!-- Sentiment Analysis Pie Chart -->
        <div class="bg-gray-900 text-white rounded-lg shadow p-6 border border-gray-800">
            <h3 class="text-lg font-medium text-gray-200 mb-4">Sentiment Analysis</h3>
            <div class="h-64">
                <canvas id="sentimentChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Word Cloud and Top Keywords -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Top Keywords -->
        <div class="bg-gray-900 text-white rounded-lg shadow p-6 border border-gray-800">
            <h3 class="text-lg font-medium text-gray-200 mb-4">Most Mentioned Keywords</h3>

            @if(count($topKeywords) > 0)
                <div class="flex flex-wrap gap-2">
                    @foreach($topKeywords as $word => $count)
                        <div class="px-3 py-1 bg-gray-800 text-yellow-400 rounded-full text-sm border border-gray-700">
                            {{ $word }} <span class="text-gray-300 font-semibold ml-1">{{ $count }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="py-4 text-center text-gray-400">
                    <i class="fas fa-search mb-2 text-xl"></i>
                    <p>No significant keywords found in your reviews.</p>
                    <p class="text-sm mt-1">As you collect more detailed reviews, keywords will appear here.</p>
                </div>
            @endif
        </div>

        <!-- Action Items Based on Analysis -->
        <div class="bg-gray-900 text-white rounded-lg shadow p-6 border border-gray-800">
            <h3 class="text-lg font-medium text-gray-200 mb-4">Suggested Actions</h3>
            <ul class="space-y-3">
                @if($totalReviews < 10)
                    <li class="flex items-start">
                        <div class="flex-shrink-0 w-5 h-5 rounded-full bg-gray-800 flex items-center justify-center mt-0.5">
                            <i class="fas fa-lightbulb text-yellow-400 text-xs"></i>
                        </div>
                        <p class="ml-2 text-sm text-gray-300">Send more review invitations to build your review presence</p>
                    </li>
                @endif

                @if($sentimentData['negative'] > 20)
                    <li class="flex items-start">
                        <div class="flex-shrink-0 w-5 h-5 rounded-full bg-gray-800 flex items-center justify-center mt-0.5">
                            <i class="fas fa-exclamation text-red-400 text-xs"></i>
                        </div>
                        <p class="ml-2 text-sm text-gray-300">Focus on addressing negative feedback to improve customer satisfaction</p>
                    </li>
                @endif

                @if(end($reviewTrend) < prev($reviewTrend))
                    <li class="flex items-start">
                        <div class="flex-shrink-0 w-5 h-5 rounded-full bg-gray-800 flex items-center justify-center mt-0.5">
                            <i class="fas fa-bell text-yellow-400 text-xs"></i>
                        </div>
                        <p class="ml-2 text-sm text-gray-300">Review volume is decreasing - consider a new review campaign</p>
                    </li>
                @endif

                <li class="flex items-start">
                    <div class="flex-shrink-0 w-5 h-5 rounded-full bg-gray-800 flex items-center justify-center mt-0.5">
                        <i class="fas fa-check text-green-400 text-xs"></i>
                    </div>
                    <p class="ml-2 text-sm text-gray-300">Highlight top keywords in your marketing to leverage positive feedback</p>
                </li>

                <li class="flex items-start">
                    <div class="flex-shrink-0 w-5 h-5 rounded-full bg-gray-800 flex items-center justify-center mt-0.5">
                        <i class="fas fa-chart-pie text-purple-400 text-xs"></i>
                    </div>
                    <p class="ml-2 text-sm text-gray-300">Consider adding these insights to your quarterly business review</p>
                </li>
            </ul>
        </div>
    </div>

    <!-- Download Reports Section -->
    <div class="bg-gray-900 text-white rounded-lg shadow p-6 pb-64 border border-gray-800">
        <h3 class="text-lg font-medium text-gray-200 mb-4">Download Reports</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <a href="{{ route('property.review.pdf') }}" target="_blank" class="flex items-center p-4 border border-gray-700 rounded-lg hover:bg-gray-800 transition-colors">
                <div class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center mr-3">
                    <i class="fas fa-file-pdf text-red-400"></i>
                </div>
                <div>
                    <h4 class="font-medium text-gray-200">Monthly Report</h4>
                    <p class="text-xs text-gray-400">Detailed PDF analysis</p>
                </div>
            </a>

           <!-- <a href="{{ route('property.review.excel') }}" target="_blank" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 transition-colors">
                <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center mr-3">
                    <i class="fas fa-file-excel text-green-600"></i>
                </div>
                <div>
                    <h4 class="font-medium text-gray-800">Excel Export</h4>
                    <p class="text-xs text-gray-500">Raw data for analysis</p>
                </div>
            </a> -->

            <button id="download-chart-menu" class="flex items-center p-4 border border-gray-700 rounded-lg hover:bg-gray-800 transition-colors relative">
                <div class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center mr-3">
                    <i class="fas fa-file-image text-purple-400"></i>
                </div>
                <div>
                    <h4 class="font-medium text-gray-200">Chart Images</h4>
                    <p class="text-xs text-gray-400">For presentations</p>
                </div>
            </button>
        </div>

        <!-- Chart Download Dropdown (Hidden initially) -->
        <div id="chart-dropdown" class="hidden absolute z-10 mt-2 bg-gray-800 border border-gray-700 rounded-lg shadow-lg p-3">
            <h5 class="font-medium text-gray-200 mb-2 px-2">Select Chart to Download</h5>
            <div class="space-y-1">
                <button data-chart="ratingDistributionChart" class="download-chart-btn w-full text-left px-3 py-2 text-sm text-gray-300 hover:bg-gray-700 rounded transition-colors">
                    Rating Distribution
                </button>
                <button data-chart="reviewTrendChart" class="download-chart-btn w-full text-left px-3 py-2 text-sm text-gray-300 hover:bg-gray-700 rounded transition-colors">
                    Review Trend
                </button>
                <button data-chart="ratingTrendChart" class="download-chart-btn w-full text-left px-3 py-2 text-sm text-gray-300 hover:bg-gray-700 rounded transition-colors">
                    Rating Trend
                </button>
                <button data-chart="sentimentChart" class="download-chart-btn w-full text-left px-3 py-2 text-sm text-gray-300 hover:bg-gray-700 rounded transition-colors">
                    Sentiment Analysis
                </button>
            </div>
        </div>

        <!-- Hidden form for chart image download -->
        <form id="chart-download-form" action="{{ route('property.review.chart-image') }}" method="POST" class="hidden">
            @csrf
            <input type="hidden" name="chart_data" id="chart-data-input">
            <input type="hidden" name="chart_type" id="chart-type-input">
        </form>
    </div>
    @else
    <!-- Basic Stats Section for Non-Premium Users -->
    <div class="bg-gray-900 text-white rounded-lg shadow p-6 mb-6 border border-gray-800">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium text-gray-200">Basic Review Statistics</h3>
            <div class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center">
                <i class="fas fa-chart-simple text-yellow-500"></i>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="border border-gray-700 rounded-lg p-4 text-center bg-gray-800">
                <div class="text-2xl font-bold text-white">{{ number_format($averageRating, 1) }}</div>
                <div class="text-sm text-gray-400">Average Rating</div>
            </div>
            <div class="border border-gray-700 rounded-lg p-4 text-center bg-gray-800">
                <div class="text-2xl font-bold text-white">{{ $totalReviews }}</div>
                <div class="text-sm text-gray-400">Total Reviews</div>
            </div>
            <div class="border border-gray-700 rounded-lg p-4 text-center bg-gray-800">
                <div class="text-2xl font-bold text-white">{{ array_sum(array_slice($reviewTrend, -1)) }}</div>
                <div class="text-sm text-gray-400">Last Month</div>
            </div>
        </div>

        <div class="mt-6 text-center">
            <p class="text-gray-300 mb-4">Upgrade to our Pro or Premium plan to unlock detailed analytics, custom reports, and actionable insights.</p>
            <a href="{{ url('/plans') }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white font-medium rounded-lg hover:bg-yellow-700 transition-colors">
                <i class="fas fa-arrow-circle-up mr-2"></i> Upgrade Your Plan
            </a>
        </div>
    </div>

    <!-- Premium Features Preview -->
    <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-lg shadow p-6 mb-6 border border-gray-700">
        <h3 class="text-xl font-bold text-white mb-4">Premium Analytics Features</h3>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-gray-900 rounded-lg shadow p-4 border border-gray-800">
                <div class="w-12 h-12 rounded-full bg-gray-800 flex items-center justify-center mb-3 mx-auto">
                    <i class="fas fa-chart-line text-yellow-500"></i>
                </div>
                <h4 class="text-center font-semibold text-gray-200 mb-2">Trend Analysis</h4>
                <p class="text-sm text-gray-400 text-center">Track your review performance over time with detailed monthly trends.</p>
            </div>

            <div class="bg-gray-900 rounded-lg shadow p-4 border border-gray-800">
                <div class="w-12 h-12 rounded-full bg-gray-800 flex items-center justify-center mb-3 mx-auto">
                    <i class="fas fa-smile text-yellow-500"></i>
                </div>
                <h4 class="text-center font-semibold text-gray-200 mb-2">Sentiment Analysis</h4>
                <p class="text-sm text-gray-400 text-center">Understand customer emotions and identify areas for improvement.</p>
            </div>

            <div class="bg-gray-900 rounded-lg shadow p-4 border border-gray-800">
                <div class="w-12 h-12 rounded-full bg-gray-800 flex items-center justify-center mb-3 mx-auto">
                    <i class="fas fa-search text-yellow-500"></i>
                </div>
                <h4 class="text-center font-semibold text-gray-200 mb-2">Keyword Analysis</h4>
                <p class="text-sm text-gray-400 text-center">Discover what matters most to your customers through keyword extraction.</p>
            </div>
        </div>

        <div class="text-center">
            <a href="{{ url('/plans') }}" class="inline-flex items-center px-6 py-3 bg-yellow-600 text-white font-medium rounded-lg hover:bg-yellow-700 transition-colors">
                <i class="fas fa-unlock mr-2"></i> Unlock Premium Features
            </a>
        </div>
    </div>
    @endif
</div>

<style>
    .star-rating {
        position: relative;
        display: inline-block;
    }
    .stars-bg {
        display: inline-block;
    }
    .stars-fg {
        position: absolute;
        top: 0;
        left: 0;
        overflow: hidden;
    }
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Only initialize charts if user has premium access
    @if($hasProAccess)
    // Set chart defaults for dark theme
    Chart.defaults.color = '#D1D5DB'; // text color
    Chart.defaults.borderColor = '#374151'; // border color

    // Rating Distribution Chart
    const ratingCtx = document.getElementById('ratingDistributionChart').getContext('2d');
    const ratingDistributionChart = new Chart(ratingCtx, {
        type: 'bar',
        data: {
            labels: ['5 Stars', '4 Stars', '3 Stars', '2 Stars', '1 Star'],
            datasets: [{
                label: 'Number of Reviews',
                data: [
                    {{ $ratingDistribution['5'] }},
                    {{ $ratingDistribution['4'] }},
                    {{ $ratingDistribution['3'] }},
                    {{ $ratingDistribution['2'] }},
                    {{ $ratingDistribution['1'] }}
                ],
                backgroundColor: [
                    'rgba(16, 185, 129, 0.7)',  // Green
                    'rgba(59, 130, 246, 0.7)',  // Blue
                    'rgba(249, 115, 22, 0.7)',  // Orange
                    'rgba(234, 88, 12, 0.7)',   // Orange-red
                    'rgba(220, 38, 38, 0.7)'    // Red
                ],
                borderColor: [
                    'rgb(16, 185, 129)',
                    'rgb(59, 130, 246)',
                    'rgb(249, 115, 22)',
                    'rgb(234, 88, 12)',
                    'rgb(220, 38, 38)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    },
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)'
                    }
                },
                x: {
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)'
                    }
                }
            }
        }
    });

    // Review Trend Chart
    const reviewTrendCtx = document.getElementById('reviewTrendChart').getContext('2d');
    const reviewTrendChart = new Chart(reviewTrendCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode(array_keys($reviewTrend)) !!},
            datasets: [{
                label: 'Number of Reviews',
                data: {!! json_encode(array_values($reviewTrend)) !!},
                backgroundColor: 'rgba(59, 130, 246, 0.2)',
                borderColor: 'rgb(59, 130, 246)',
                borderWidth: 2,
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    },
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)'
                    }
                },
                x: {
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)'
                    }
                }
            }
        }
    });

    // Rating Trend Chart
    const ratingTrendCtx = document.getElementById('ratingTrendChart').getContext('2d');
    const ratingTrendChart = new Chart(ratingTrendCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode(array_keys($ratingTrend)) !!},
            datasets: [{
                label: 'Average Rating',
                data: {!! json_encode(array_values($ratingTrend)) !!},
                backgroundColor: 'rgba(16, 185, 129, 0.2)',
                borderColor: 'rgb(16, 185, 129)',
                borderWidth: 2,
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    min: 0,
                    max: 5,
                    ticks: {
                        stepSize: 1
                    },
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)'
                    }
                },
                x: {
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)'
                    }
                }
            }
        }
    });

    // Sentiment Analysis Pie Chart
    const sentimentCtx = document.getElementById('sentimentChart');
    if (sentimentCtx) {
        const sentimentChart = new Chart(sentimentCtx.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Positive (4-5★)', 'Neutral (3★)', 'Negative (1-2★)'],
                datasets: [{
                    data: [
                        {{ $sentimentCounts['positive'] }},
                        {{ $sentimentCounts['neutral'] }},
                        {{ $sentimentCounts['negative'] }}
                    ],
                    backgroundColor: [
                        'rgba(16, 185, 129, 0.8)', // Green
                        'rgba(59, 130, 246, 0.8)', // Blue
                        'rgba(239, 68, 68, 0.8)'   // Red
                    ],
                    borderColor: [
                        'rgb(16, 185, 129)',
                        'rgb(59, 130, 246)',
                        'rgb(239, 68, 68)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            pointStyle: 'circle',
                            color: '#D1D5DB'
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const value = context.raw;
                                const total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                                const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                                return `${context.label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });

        // Add this conditional check for the chart download button
        if (document.getElementById('download-chart-menu')) {
            // Add the chart to a list of available charts for download
            window.downloadableCharts = window.downloadableCharts || {};
            window.downloadableCharts['sentimentChart'] = sentimentChart;
        }
    }

    // Chart image download functionality
    const downloadChartMenu = document.getElementById('download-chart-menu');
    const chartDropdown = document.getElementById('chart-dropdown');
    const downloadButtons = document.querySelectorAll('.download-chart-btn');
    const downloadForm = document.getElementById('chart-download-form');
    const chartDataInput = document.getElementById('chart-data-input');
    const chartTypeInput = document.getElementById('chart-type-input');

    // Toggle dropdown menu
    downloadChartMenu.addEventListener('click', function(e) {
        e.stopPropagation();
        chartDropdown.classList.toggle('hidden');

        // Position the dropdown
        const rect = downloadChartMenu.getBoundingClientRect();
        chartDropdown.style.top = (rect.bottom + window.scrollY) + 'px';
        chartDropdown.style.left = rect.left + 'px';
    });

    // Close dropdown when clicking elsewhere
    document.addEventListener('click', function() {
        chartDropdown.classList.add('hidden');
    });

    // Prevent dropdown from closing when clicking inside it
    chartDropdown.addEventListener('click', function(e) {
        e.stopPropagation();
    });

    // Handle chart download buttons
    downloadButtons.forEach(button => {
        button.addEventListener('click', function() {
            const chartId = this.getAttribute('data-chart');
            const chartType = getChartTypeFromId(chartId);

            // Get chart canvas and convert to base64 image
            const canvas = document.getElementById(chartId);
            const imageData = canvas.toDataURL('image/png');

            // Set form values and submit
            chartDataInput.value = imageData;
            chartTypeInput.value = chartType;
            downloadForm.submit();

            // Hide dropdown
            chartDropdown.classList.add('hidden');
        });
    });

    // Helper function to determine chart type
    function getChartTypeFromId(chartId) {
        if (chartId === 'ratingDistributionChart') return 'distribution';
        if (chartId === 'reviewTrendChart') return 'trend';
        if (chartId === 'ratingTrendChart') return 'rating';
        if (chartId === 'sentimentChart') return 'sentiment';
        return 'chart';
    }
    @endif
});
</script>
@endpush
@endsection
