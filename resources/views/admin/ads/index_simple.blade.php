@extends('layouts.admin')

@section('title', 'Ad Requests Management')
@section('active-ads-manager', 'bg-gray-800 text-white')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-white">Ad Requests Management</h1>
            <p class="text-gray-400">Review and manage property promotion requests</p>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="bg-green-900/50 border border-green-700 text-green-200 px-4 py-3 rounded-lg">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-900/50 border border-red-700 text-red-200 px-4 py-3 rounded-lg">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ session('error') }}
            </div>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
        <div class="bg-gray-900/50 backdrop-blur-sm border border-gray-800 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">Total Requests</p>
                    <p class="text-2xl font-bold text-white">{{ $stats['total'] }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-900 rounded-lg flex items-center justify-center">
                    <i class="fas fa-bullhorn text-blue-400"></i>
                </div>
            </div>
        </div>

        <div class="bg-gray-900/50 backdrop-blur-sm border border-gray-800 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">Pending</p>
                    <p class="text-2xl font-bold text-yellow-400">{{ $stats['pending'] }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-900 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clock text-yellow-400"></i>
                </div>
            </div>
        </div>

        <div class="bg-gray-900/50 backdrop-blur-sm border border-gray-800 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">Approved</p>
                    <p class="text-2xl font-bold text-blue-400">{{ $stats['approved'] }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-900 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check text-blue-400"></i>
                </div>
            </div>
        </div>

        <div class="bg-gray-900/50 backdrop-blur-sm border border-gray-800 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">Active</p>
                    <p class="text-2xl font-bold text-green-400">{{ $stats['active'] }}</p>
                </div>
                <div class="w-12 h-12 bg-green-900 rounded-lg flex items-center justify-center">
                    <i class="fas fa-play text-green-400"></i>
                </div>
            </div>
        </div>

        <div class="bg-gray-900/50 backdrop-blur-sm border border-gray-800 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">Rejected</p>
                    <p class="text-2xl font-bold text-red-400">{{ $stats['rejected'] }}</p>
                </div>
                <div class="w-12 h-12 bg-red-900 rounded-lg flex items-center justify-center">
                    <i class="fas fa-times text-red-400"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Ad Cost Settings -->
    <div class="bg-gray-900/50 backdrop-blur-sm border border-gray-800 rounded-xl">
        <div class="p-6 border-b border-gray-800">
            <h3 class="text-lg font-semibold text-white flex items-center">
                <i class="fas fa-dollar-sign text-green-400 mr-2"></i>
                Ad Pricing Settings
            </h3>
            <p class="text-gray-400 text-sm mt-1">Configure the daily cost for ad promotion requests</p>
        </div>

        <div class="p-6">
            <form action="{{ route('admin.settings.ad-cost.update') }}" method="POST" class="flex items-end gap-4">
                @csrf
                @method('PUT')

                <div class="flex-1 max-w-xs">
                    <label for="ad_daily_cost" class="block text-sm font-medium text-gray-300 mb-2">
                        Daily Cost (USD)
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-400 text-sm">$</span>
                        </div>
                        <input type="number"
                               id="ad_daily_cost"
                               name="ad_daily_cost"
                               value="{{ $currentAdCost ?? \App\Models\AdminSetting::getAdDailyCost() }}"
                               step="0.01"
                               min="0.01"
                               max="100"
                               class="pl-8 pr-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent w-full"
                               required>
                    </div>
                    @error('ad_daily_cost')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                        class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-200">
                    <i class="fas fa-save mr-2"></i>
                    Update Cost
                </button>
            </form>

            <div class="mt-4 p-4 bg-blue-900/20 border border-blue-800 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-info-circle text-blue-400 mr-2"></i>
                    <span class="text-blue-400 text-sm font-medium">Current Setting:</span>
                </div>
                <p class="text-gray-300 text-sm mt-1">
                    Business owners will pay <strong>${{ number_format($currentAdCost ?? \App\Models\AdminSetting::getAdDailyCost(), 2) }}</strong> per day for ad promotion requests.
                </p>
            </div>
        </div>
    </div>

    <!-- Ads Table -->
    <div class="bg-gray-900/50 backdrop-blur-sm border border-gray-800 rounded-xl overflow-hidden">
        <div class="p-6 border-b border-gray-800">
            <h3 class="text-lg font-semibold text-white flex items-center">
                <i class="fas fa-list text-blue-400 mr-2"></i>
                Ad Requests
            </h3>
        </div>

        @if($ads->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-800/50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                Property
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                Promotion Period
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                Submitted
                            </th>
                            <th class="px-6 py-4 text-right text-xs font-medium text-gray-400 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        @foreach($ads as $ad)
                            <tr class="hover:bg-gray-800/30 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-12 h-12 bg-gray-700 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-building text-gray-400"></i>
                                        </div>
                                        <div>
                                            <p class="text-white font-medium">{{ $ad->property->business_name }}</p>
                                            <p class="text-gray-400 text-sm">{{ $ad->property->business_email }}</p>
                                            <p class="text-gray-500 text-xs">{{ $ad->property->city }}, {{ $ad->property->province }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm">
                                        <p class="text-white">{{ $ad->start_date->format('M j, Y') }}</p>
                                        <p class="text-gray-400">to {{ $ad->end_date->format('M j, Y') }}</p>
                                        <p class="text-gray-500 text-xs">
                                            {{ $ad->start_date->diffInDays($ad->end_date) + 1 }} days
                                        </p>
                                        @if($ad->start_date <= now() && $ad->end_date >= now())
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-500/20 text-green-400 border border-green-500/30 mt-1">
                                                <i class="fas fa-calendar-check mr-1"></i>
                                                Current Period
                                            </span>
                                        @elseif($ad->start_date > now())
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-500/20 text-blue-400 border border-blue-500/30 mt-1">
                                                <i class="fas fa-calendar-day mr-1"></i>
                                                Future
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-orange-500/20 text-orange-400 border border-orange-500/30 mt-1">
                                                <i class="fas fa-calendar-times mr-1"></i>
                                                Expired
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $ad->getStatusBadgeClass() }}">
                                        @switch($ad->status)
                                            @case('pending')
                                                <i class="fas fa-clock mr-1"></i>
                                                Pending Review
                                                @break
                                            @case('approved')
                                                <i class="fas fa-check mr-1"></i>
                                                Approved
                                                @break
                                            @case('active')
                                                <i class="fas fa-play mr-1"></i>
                                                Active
                                                @break
                                            @case('rejected')
                                                <i class="fas fa-times mr-1"></i>
                                                Rejected
                                                @break
                                        @endswitch
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-400 text-sm">
                                    <div>
                                        <p>{{ $ad->created_at->format('M j, Y') }}</p>
                                        <p class="text-xs text-gray-500">{{ $ad->created_at->format('g:i A') }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('admin.ads.show', $ad) }}"
                                           class="inline-flex items-center px-3 py-1 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm">
                                            <i class="fas fa-eye mr-1"></i>
                                            Review
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($ads->hasPages())
                <div class="px-6 py-4 border-t border-gray-800">
                    {{ $ads->links() }}
                </div>
            @endif
        @else
            <div class="p-12 text-center">
                <div class="w-16 h-16 bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-bullhorn text-gray-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-medium text-white mb-2">No ad requests yet</h3>
                <p class="text-gray-400">Ad requests from business owners will appear here for review.</p>
            </div>
        @endif
    </div>
</div>
@endsection
