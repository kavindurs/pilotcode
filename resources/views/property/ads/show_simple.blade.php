@extends('layouts.business')

@section('active-ads-manager', 'menu-item-active')
@section('title', 'View Promotion Request')

@section('page-title')
    Promotion Request Details
@endsection

@section('page-subtitle', 'View your property promotion request details and status.')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
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
                    <span class="text-gray-300">Request Details</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Request Status -->
            <div class="bg-gray-900/50 backdrop-blur-sm border border-gray-800 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                    <i class="fas fa-info-circle text-blue-400 mr-2"></i>
                    Request Status
                </h3>

                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-400">Status</span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium border {{ $ad->getStatusBadgeClass() }}">
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
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-gray-400">Submitted</span>
                        <div class="text-right">
                            <p class="text-white">{{ $ad->created_at->format('M j, Y') }}</p>
                            <p class="text-gray-500 text-sm">{{ $ad->created_at->format('g:i A') }}</p>
                        </div>
                    </div>

                    @if($ad->approved_at)
                        <div class="flex items-center justify-between">
                            <span class="text-gray-400">{{ $ad->status === 'rejected' ? 'Processed' : 'Approved' }}</span>
                            <div class="text-right">
                                <p class="text-white">{{ $ad->approved_at->format('M j, Y') }}</p>
                                <p class="text-gray-500 text-sm">{{ $ad->approved_at->format('g:i A') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Promotion Period -->
            <div class="bg-gray-900/50 backdrop-blur-sm border border-gray-800 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-white mb-6 flex items-center">
                    <i class="fas fa-calendar-alt text-blue-400 mr-2"></i>
                    Promotion Period
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gray-800/50 rounded-lg p-4">
                        <label class="block text-sm font-medium text-gray-400 mb-2">Start Date</label>
                        <p class="text-white text-lg">{{ $ad->start_date->format('l, F j, Y') }}</p>
                        <p class="text-gray-400 text-sm">{{ $ad->start_date->diffForHumans() }}</p>
                    </div>

                    <div class="bg-gray-800/50 rounded-lg p-4">
                        <label class="block text-sm font-medium text-gray-400 mb-2">End Date</label>
                        <p class="text-white text-lg">{{ $ad->end_date->format('l, F j, Y') }}</p>
                        <p class="text-gray-400 text-sm">{{ $ad->end_date->diffForHumans() }}</p>
                    </div>

                    <div class="md:col-span-2 bg-gray-800/50 rounded-lg p-4">
                        <label class="block text-sm font-medium text-gray-400 mb-2">Duration</label>
                        <p class="text-white text-lg">{{ $ad->start_date->diffInDays($ad->end_date) + 1 }} days</p>
                        <p class="text-gray-400 text-sm">
                            From {{ $ad->start_date->format('M j') }} to {{ $ad->end_date->format('M j, Y') }}
                        </p>
                    </div>
                </div>

                <!-- Period Status -->
                <div class="mt-4">
                    @if($ad->start_date <= now() && $ad->end_date >= now())
                        <div class="bg-green-900/20 border border-green-700/50 rounded-lg p-4">
                            <div class="flex items-center">
                                <i class="fas fa-calendar-check text-green-400 mr-2"></i>
                                <span class="text-green-300 font-medium">Current promotion period</span>
                            </div>
                            <p class="text-green-400 text-sm mt-1">Your property is eligible to be displayed on the homepage</p>
                        </div>
                    @elseif($ad->start_date > now())
                        <div class="bg-blue-900/20 border border-blue-700/50 rounded-lg p-4">
                            <div class="flex items-center">
                                <i class="fas fa-calendar-day text-blue-400 mr-2"></i>
                                <span class="text-blue-300 font-medium">Future promotion</span>
                            </div>
                            <p class="text-blue-400 text-sm mt-1">Promotion will start in {{ $ad->start_date->diffForHumans() }}</p>
                        </div>
                    @else
                        <div class="bg-orange-900/20 border border-orange-700/50 rounded-lg p-4">
                            <div class="flex items-center">
                                <i class="fas fa-calendar-times text-orange-400 mr-2"></i>
                                <span class="text-orange-300 font-medium">Promotion period ended</span>
                            </div>
                            <p class="text-orange-400 text-sm mt-1">This promotion ended {{ $ad->end_date->diffForHumans() }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Admin Feedback -->
            @if($ad->admin_notes || $ad->rejection_reason)
                <div class="bg-gray-900/50 backdrop-blur-sm border border-gray-800 rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                        <i class="fas fa-comment text-blue-400 mr-2"></i>
                        Admin Feedback
                    </h3>

                    @if($ad->admin_notes)
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-400 mb-2">Notes from Admin</label>
                            <div class="bg-gray-800/50 p-4 rounded-lg">
                                <p class="text-gray-300">{{ $ad->admin_notes }}</p>
                            </div>
                        </div>
                    @endif

                    @if($ad->rejection_reason)
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">Rejection Reason</label>
                            <div class="bg-red-900/20 border border-red-700/50 p-4 rounded-lg">
                                <p class="text-red-300">{{ $ad->rejection_reason }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Property Info -->
            <div class="bg-gray-900/50 backdrop-blur-sm border border-gray-800 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                    <i class="fas fa-building text-blue-400 mr-2"></i>
                    Property
                </h3>

                <div class="space-y-4">
                    <div class="w-full h-32 bg-gray-700 rounded-lg flex items-center justify-center">
                        <i class="fas fa-building text-gray-400 text-2xl"></i>
                    </div>

                    <div>
                        <h4 class="text-white font-medium">{{ $ad->property->business_name }}</h4>
                        <p class="text-gray-400 text-sm">{{ $ad->property->business_address }}</p>
                        <p class="text-gray-400 text-sm">{{ $ad->property->city }}, {{ $ad->property->province }}</p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-gray-900/50 backdrop-blur-sm border border-gray-800 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                    <i class="fas fa-cog text-blue-400 mr-2"></i>
                    Actions
                </h3>

                <div class="space-y-3">
                    @if($ad->status === 'pending')
                        <a href="{{ route('property.ads.edit', $ad) }}"
                           class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-edit mr-2"></i>
                            Edit Request
                        </a>

                        <form action="{{ route('property.ads.destroy', $ad) }}"
                              method="POST"
                              onsubmit="return confirm('Are you sure you want to cancel this promotion request?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors flex items-center justify-center">
                                <i class="fas fa-trash mr-2"></i>
                                Cancel Request
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('property.ads.index') }}"
                       class="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to List
                    </a>
                </div>
            </div>

            <!-- Help -->
            <div class="bg-blue-900/20 border border-blue-700/50 rounded-xl p-6">
                <h3 class="text-blue-300 font-medium mb-3 flex items-center">
                    <i class="fas fa-question-circle mr-2"></i>
                    How it works
                </h3>
                <ul class="text-blue-400 text-sm space-y-2">
                    <li>• Submit promotion request with desired dates</li>
                    <li>• Admin reviews your request</li>
                    <li>• If approved, your property appears on homepage</li>
                    <li>• Promotion runs during your selected dates</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
