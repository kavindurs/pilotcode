@extends('layouts.business')

@section('active-ads-manager', 'menu-item-active')
@section('title', 'Ads Manager')

@section('page-title')
    Ads Manager
@endsection

@section('page-subtitle', 'Create and manage your advertising campaigns to promote your business.')

@section('content')
<div class="space-y-8">
    @if(session('success'))
        <div class="bg-green-900 border border-green-700 text-green-300 p-4 rounded-lg mb-6 flex items-center">
            <i class="fas fa-check-circle mr-3"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-900 border border-red-700 text-red-300 p-4 rounded-lg mb-6 flex items-center">
            <i class="fas fa-exclamation-circle mr-3"></i>
            {{ session('error') }}
        </div>
    @endif

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-6">
        <div class="bg-gradient-to-br from-blue-500/10 to-blue-600/10 backdrop-blur-sm border border-blue-500/20 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-200/70 text-sm font-medium">Total Ads</p>
                    <h3 class="text-2xl font-bold text-white">{{ $stats['total_ads'] }}</h3>
                </div>
                <div class="bg-blue-500/20 p-3 rounded-xl">
                    <i class="fas fa-bullhorn text-blue-400 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500/10 to-green-600/10 backdrop-blur-sm border border-green-500/20 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-200/70 text-sm font-medium">Active Ads</p>
                    <h3 class="text-2xl font-bold text-white">{{ $stats['active_ads'] }}</h3>
                </div>
                <div class="bg-green-500/20 p-3 rounded-xl">
                    <i class="fas fa-play-circle text-green-400 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-yellow-500/10 to-yellow-600/10 backdrop-blur-sm border border-yellow-500/20 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-200/70 text-sm font-medium">Pending</p>
                    <h3 class="text-2xl font-bold text-white">{{ $stats['pending_ads'] }}</h3>
                </div>
                <div class="bg-yellow-500/20 p-3 rounded-xl">
                    <i class="fas fa-clock text-yellow-400 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500/10 to-purple-600/10 backdrop-blur-sm border border-purple-500/20 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-200/70 text-sm font-medium">Total Views</p>
                    <h3 class="text-2xl font-bold text-white">{{ number_format($stats['total_views']) }}</h3>
                </div>
                <div class="bg-purple-500/20 p-3 rounded-xl">
                    <i class="fas fa-eye text-purple-400 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-indigo-500/10 to-indigo-600/10 backdrop-blur-sm border border-indigo-500/20 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-indigo-200/70 text-sm font-medium">Total Clicks</p>
                    <h3 class="text-2xl font-bold text-white">{{ number_format($stats['total_clicks']) }}</h3>
                </div>
                <div class="bg-indigo-500/20 p-3 rounded-xl">
                    <i class="fas fa-mouse-pointer text-indigo-400 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-emerald-500/10 to-emerald-600/10 backdrop-blur-sm border border-emerald-500/20 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-emerald-200/70 text-sm font-medium">Total Budget</p>
                    <h3 class="text-2xl font-bold text-white">${{ number_format($stats['total_budget'], 2) }}</h3>
                </div>
                <div class="bg-emerald-500/20 p-3 rounded-xl">
                    <i class="fas fa-dollar-sign text-emerald-400 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Bar -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <h2 class="text-xl font-semibold text-white">Your Ads</h2>
        <a href="{{ route('property.ads.create') }}"
           class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-medium rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl">
            <i class="fas fa-plus mr-2"></i>
            Create New Ad
        </a>
    </div>

    <!-- Ads Table -->
    <div class="bg-gray-900/50 backdrop-blur-sm border border-gray-800 rounded-xl overflow-hidden">
        @if($ads->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-700">
                    <thead class="bg-gray-800/50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Ad Details</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Type & Placement</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Duration</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Performance</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Budget</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @foreach($ads as $ad)
                        <tr class="hover:bg-gray-800/30 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-start space-x-3">
                                    @if($ad->image_path)
                                        <img src="{{ asset('storage/' . $ad->image_path) }}"
                                             alt="{{ $ad->title }}"
                                             class="w-12 h-12 rounded-lg object-cover border border-gray-600">
                                    @else
                                        <div class="w-12 h-12 bg-gray-700 rounded-lg flex items-center justify-center border border-gray-600">
                                            <i class="fas fa-image text-gray-400"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <h4 class="text-white font-medium">{{ Str::limit($ad->title, 30) }}</h4>
                                        <p class="text-gray-400 text-sm">{{ Str::limit($ad->description, 50) }}</p>
                                        <p class="text-gray-500 text-xs">Property ID: {{ $ad->property_id }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="space-y-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100/10 text-blue-300 border border-blue-500/20">
                                        {{ ucfirst($ad->ad_type) }}
                                    </span>
                                    <div class="text-gray-400 text-sm">{{ ucfirst(str_replace('_', ' ', $ad->placement)) }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $ad->getStatusBadgeClass() }}">
                                    {{ ucfirst($ad->status) }}
                                </span>
                                @if($ad->rejection_reason)
                                    <div class="text-red-400 text-xs mt-1" title="{{ $ad->rejection_reason }}">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        Reason provided
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-300">
                                    <div>{{ $ad->start_date->format('M j, Y') }}</div>
                                    <div class="text-gray-400">to {{ $ad->end_date->format('M j, Y') }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm">
                                    <div class="text-gray-300">{{ number_format($ad->total_views) }} views</div>
                                    <div class="text-gray-300">{{ number_format($ad->total_clicks) }} clicks</div>
                                    <div class="text-gray-400">{{ $ad->click_through_rate }}% CTR</div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm">
                                    <div class="text-gray-300 font-medium">${{ number_format($ad->budget, 2) }}</div>
                                    <div class="text-gray-400">${{ number_format($ad->cost_per_click, 2) }} per click</div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('property.ads.show', $ad) }}"
                                       class="text-blue-400 hover:text-blue-300 transition-colors"
                                       title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    @if(in_array($ad->status, ['pending', 'rejected']))
                                        <a href="{{ route('property.ads.edit', $ad) }}"
                                           class="text-yellow-400 hover:text-yellow-300 transition-colors"
                                           title="Edit Ad">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endif

                                    @if(in_array($ad->status, ['active', 'paused']))
                                        <form action="{{ route('property.ads.toggle-status', $ad) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                    class="{{ $ad->status === 'active' ? 'text-orange-400 hover:text-orange-300' : 'text-green-400 hover:text-green-300' }} transition-colors"
                                                    title="{{ $ad->status === 'active' ? 'Pause Ad' : 'Resume Ad' }}">
                                                <i class="fas fa-{{ $ad->status === 'active' ? 'pause' : 'play' }}"></i>
                                            </button>
                                        </form>
                                    @endif

                                    <form action="{{ route('property.ads.destroy', $ad) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Are you sure you want to delete this ad?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="text-red-400 hover:text-red-300 transition-colors"
                                                title="Delete Ad">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($ads->hasPages())
                <div class="px-6 py-4 border-t border-gray-700">
                    {{ $ads->links() }}
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="w-24 h-24 bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-bullhorn text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-xl font-semibold text-white mb-2">No ads yet</h3>
                <p class="text-gray-400 mb-6 max-w-md mx-auto">
                    Create your first advertising campaign to promote your business and reach more customers.
                </p>
                <a href="{{ route('property.ads.create') }}"
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-medium rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl">
                    <i class="fas fa-plus mr-2"></i>
                    Create Your First Ad
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
