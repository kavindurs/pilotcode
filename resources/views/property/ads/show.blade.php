@extends('layouts.business')

@section('active-ads-manager', 'menu-item-active')
@section('title', 'Ad Details')

@section('page-title')
    Ad Details
@endsection

@section('page-subtitle', 'View detailed information about your advertising campaign.')

@section('content')
<div class="max-w-6xl mx-auto space-y-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('property.dashboard') }}" class="text-gray-400 hover:text-white transition-colors">
                    <i class="fas fa-home mr-2"></i>
                    Dashboard
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-600 mx-2"></i>
                    <a href="{{ route('property.ads.index') }}" class="text-gray-400 hover:text-white transition-colors">
                        Ads Manager
                    </a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-600 mx-2"></i>
                    <span class="text-gray-300">{{ Str::limit($ad->title, 30) }}</span>
                </div>
            </li>
        </ol>
    </nav>

    @if(session('success'))
        <div class="bg-green-900 border border-green-700 text-green-300 p-4 rounded-lg mb-6 flex items-center">
            <i class="fas fa-check-circle mr-3"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Ad Header -->
    <div class="bg-gray-900/50 backdrop-blur-sm border border-gray-800 rounded-xl p-6">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="flex-1">
                <div class="flex items-center gap-4 mb-4">
                    <h1 class="text-2xl font-bold text-white">{{ $ad->title }}</h1>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium border {{ $ad->getStatusBadgeClass() }}">
                        {{ ucfirst($ad->status) }}
                    </span>
                </div>
                <p class="text-gray-300">{{ $ad->description }}</p>

                @if($ad->rejection_reason)
                    <div class="mt-4 p-4 bg-red-900/20 border border-red-500/30 rounded-lg">
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-triangle text-red-400 mr-2 mt-0.5"></i>
                            <div>
                                <p class="text-red-300 font-medium">Rejection Reason:</p>
                                <p class="text-red-200/80 mt-1">{{ $ad->rejection_reason }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="flex flex-col sm:flex-row gap-3">
                @if(in_array($ad->status, ['pending', 'rejected']))
                    <a href="{{ route('property.ads.edit', $ad) }}"
                       class="inline-flex items-center justify-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white font-medium rounded-lg transition-all duration-200">
                        <i class="fas fa-edit mr-2"></i>
                        Edit Ad
                    </a>
                @endif

                @if(in_array($ad->status, ['active', 'paused']))
                    <form action="{{ route('property.ads.toggle-status', $ad) }}" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                                class="inline-flex items-center justify-center px-4 py-2 {{ $ad->status === 'active' ? 'bg-orange-600 hover:bg-orange-700' : 'bg-green-600 hover:bg-green-700' }} text-white font-medium rounded-lg transition-all duration-200">
                            <i class="fas fa-{{ $ad->status === 'active' ? 'pause' : 'play' }} mr-2"></i>
                            {{ $ad->status === 'active' ? 'Pause Ad' : 'Resume Ad' }}
                        </button>
                    </form>
                @endif

                <a href="{{ route('property.ads.index') }}"
                   class="inline-flex items-center justify-center px-4 py-2 border border-gray-600 text-gray-300 font-medium rounded-lg hover:bg-gray-800 transition-all duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Ads
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Ad Preview -->
            @if($ad->image_path)
            <div class="bg-gray-900/50 backdrop-blur-sm border border-gray-800 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                    <i class="fas fa-image text-purple-400 mr-2"></i>
                    Ad Preview
                </h3>
                <div class="bg-gray-800 rounded-lg p-4">
                    <img src="{{ asset('storage/' . $ad->image_path) }}"
                         alt="{{ $ad->title }}"
                         class="w-full max-w-md mx-auto rounded-lg border border-gray-600">
                </div>
            </div>
            @endif

            <!-- Performance Metrics -->
            <div class="bg-gray-900/50 backdrop-blur-sm border border-gray-800 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-white mb-6 flex items-center">
                    <i class="fas fa-chart-line text-green-400 mr-2"></i>
                    Performance Metrics
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-gradient-to-br from-blue-500/10 to-blue-600/10 backdrop-blur-sm border border-blue-500/20 rounded-xl p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-blue-200/70 text-sm font-medium">Total Views</p>
                                <h4 class="text-2xl font-bold text-white">{{ number_format($ad->total_views) }}</h4>
                            </div>
                            <div class="bg-blue-500/20 p-3 rounded-lg">
                                <i class="fas fa-eye text-blue-400"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-green-500/10 to-green-600/10 backdrop-blur-sm border border-green-500/20 rounded-xl p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-green-200/70 text-sm font-medium">Total Clicks</p>
                                <h4 class="text-2xl font-bold text-white">{{ number_format($ad->total_clicks) }}</h4>
                            </div>
                            <div class="bg-green-500/20 p-3 rounded-lg">
                                <i class="fas fa-mouse-pointer text-green-400"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-purple-500/10 to-purple-600/10 backdrop-blur-sm border border-purple-500/20 rounded-xl p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-purple-200/70 text-sm font-medium">Click Rate</p>
                                <h4 class="text-2xl font-bold text-white">{{ $ad->click_through_rate }}%</h4>
                            </div>
                            <div class="bg-purple-500/20 p-3 rounded-lg">
                                <i class="fas fa-percentage text-purple-400"></i>
                            </div>
                        </div>
                    </div>
                </div>

                @if($ad->total_views > 0 || $ad->total_clicks > 0)
                    <div class="mt-6 p-4 bg-gray-800 rounded-lg">
                        <h5 class="text-white font-medium mb-2">Performance Insights</h5>
                        <div class="space-y-2 text-sm text-gray-300">
                            @if($ad->click_through_rate > 2)
                                <div class="flex items-center text-green-400">
                                    <i class="fas fa-thumbs-up mr-2"></i>
                                    Great click-through rate! Your ad is performing well.
                                </div>
                            @elseif($ad->click_through_rate > 1)
                                <div class="flex items-center text-yellow-400">
                                    <i class="fas fa-chart-line mr-2"></i>
                                    Good click-through rate. Consider optimizing for better results.
                                </div>
                            @else
                                <div class="flex items-center text-gray-400">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    Consider updating your ad content to improve engagement.
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-8">
            <!-- Ad Details -->
            <div class="bg-gray-900/50 backdrop-blur-sm border border-gray-800 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                    <i class="fas fa-info-circle text-blue-400 mr-2"></i>
                    Ad Details
                </h3>

                <div class="space-y-4">
                    <div class="flex justify-between items-start">
                        <span class="text-gray-400 text-sm">Property ID</span>
                        <span class="text-white text-sm font-mono">{{ $ad->property_id }}</span>
                    </div>

                    <div class="flex justify-between items-start">
                        <span class="text-gray-400 text-sm">Ad Type</span>
                        <span class="text-white text-sm">{{ ucfirst($ad->ad_type) }}</span>
                    </div>

                    <div class="flex justify-between items-start">
                        <span class="text-gray-400 text-sm">Placement</span>
                        <span class="text-white text-sm">{{ ucfirst(str_replace('_', ' ', $ad->placement)) }}</span>
                    </div>

                    @if($ad->target_url)
                    <div class="flex justify-between items-start">
                        <span class="text-gray-400 text-sm">Target URL</span>
                        <a href="{{ $ad->target_url }}" target="_blank" class="text-blue-400 hover:text-blue-300 text-sm break-all">
                            {{ Str::limit($ad->target_url, 30) }}
                            <i class="fas fa-external-link-alt ml-1"></i>
                        </a>
                    </div>
                    @endif

                    <div class="flex justify-between items-start">
                        <span class="text-gray-400 text-sm">Created</span>
                        <span class="text-white text-sm">{{ $ad->created_at->format('M j, Y g:i A') }}</span>
                    </div>

                    @if($ad->approved_at)
                    <div class="flex justify-between items-start">
                        <span class="text-gray-400 text-sm">Approved</span>
                        <span class="text-white text-sm">{{ $ad->approved_at->format('M j, Y g:i A') }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Campaign Duration -->
            <div class="bg-gray-900/50 backdrop-blur-sm border border-gray-800 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                    <i class="fas fa-calendar-alt text-green-400 mr-2"></i>
                    Campaign Duration
                </h3>

                <div class="space-y-4">
                    <div class="flex justify-between items-start">
                        <span class="text-gray-400 text-sm">Start Date</span>
                        <span class="text-white text-sm">{{ $ad->start_date->format('M j, Y') }}</span>
                    </div>

                    <div class="flex justify-between items-start">
                        <span class="text-gray-400 text-sm">End Date</span>
                        <span class="text-white text-sm">{{ $ad->end_date->format('M j, Y') }}</span>
                    </div>

                    <div class="flex justify-between items-start">
                        <span class="text-gray-400 text-sm">Duration</span>
                        <span class="text-white text-sm">{{ $ad->start_date->diffInDays($ad->end_date) }} days</span>
                    </div>

                    @if($ad->isActive())
                        <div class="text-green-400 text-sm flex items-center">
                            <i class="fas fa-circle text-xs mr-2"></i>
                            Currently Active
                        </div>
                    @elseif($ad->isExpired())
                        <div class="text-orange-400 text-sm flex items-center">
                            <i class="fas fa-clock text-xs mr-2"></i>
                            Campaign Expired
                        </div>
                    @else
                        <div class="text-blue-400 text-sm flex items-center">
                            <i class="fas fa-clock text-xs mr-2"></i>
                            Scheduled
                        </div>
                    @endif
                </div>
            </div>

            <!-- Budget Information -->
            <div class="bg-gray-900/50 backdrop-blur-sm border border-gray-800 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                    <i class="fas fa-dollar-sign text-emerald-400 mr-2"></i>
                    Budget Information
                </h3>

                <div class="space-y-4">
                    <div class="flex justify-between items-start">
                        <span class="text-gray-400 text-sm">Total Budget</span>
                        <span class="text-white text-sm font-medium">${{ number_format($ad->budget, 2) }}</span>
                    </div>

                    <div class="flex justify-between items-start">
                        <span class="text-gray-400 text-sm">Cost Per Click</span>
                        <span class="text-white text-sm">${{ number_format($ad->cost_per_click, 2) }}</span>
                    </div>

                    <div class="flex justify-between items-start">
                        <span class="text-gray-400 text-sm">Spent</span>
                        <span class="text-white text-sm">${{ number_format($ad->total_clicks * $ad->cost_per_click, 2) }}</span>
                    </div>

                    <div class="flex justify-between items-start">
                        <span class="text-gray-400 text-sm">Remaining</span>
                        <span class="text-white text-sm">${{ number_format($ad->budget - ($ad->total_clicks * $ad->cost_per_click), 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
