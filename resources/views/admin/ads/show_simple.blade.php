@extends('layouts.admin')

@section('title', 'Review Ad Request')
@section('active-ads-manager', 'bg-gray-800 text-white')

@section('content')
<div class="space-y-6">
    <!-- Breadcrumb -->
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol                        @if($ad->paid_at)
                            <div>
                                <label class="block text-sm font-medium text-gray-400">Payment Completed</label>
                                <p class="text-white">{{ $ad->paid_at->format('M j, Y g:i A') }}</p>
                            </div>
                        @endif="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('admin.dashboard') }}" class="text-gray-400 hover:text-white transition-colors">
                    <i class="fas fa-home mr-2"></i>
                    Dashboard
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-600 mx-2"></i>
                    <a href="{{ route('admin.ads.index') }}" class="text-gray-400 hover:text-white transition-colors">
                        Ad Requests
                    </a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-600 mx-2"></i>
                    <span class="text-gray-300">Review Request</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="flex justify-between items-start">
        <div>
            <h1 class="text-2xl font-bold text-white">Review Ad Request</h1>
            <p class="text-gray-400">Review and approve/reject property promotion request</p>
        </div>

        <div class="flex items-center space-x-4">
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
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Property Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Property Details -->
            <div class="bg-gray-900/50 backdrop-blur-sm border border-gray-800 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-white mb-6 flex items-center">
                    <i class="fas fa-building text-blue-400 mr-2"></i>
                    Property Information
                </h3>

                <div class="space-y-6">
                    <!-- Property Basic Info -->
                    <div class="flex items-start space-x-4">
                        <div class="w-24 h-24 bg-gray-700 rounded-lg flex items-center justify-center">
                            <i class="fas fa-building text-gray-400 text-xl"></i>
                        </div>

                        <div class="flex-1">
                            <h4 class="text-xl font-semibold text-white">{{ $ad->property->business_name }}</h4>
                            <p class="text-gray-400">{{ $ad->property->business_address }}</p>
                            <p class="text-gray-400">{{ $ad->property->city }}, {{ $ad->property->province }}</p>
                            <p class="text-gray-500 text-sm mt-2">Contact: {{ $ad->property->business_email }}</p>
                            @if($ad->property->business_phone)
                                <p class="text-gray-500 text-sm">Phone: {{ $ad->property->business_phone }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Property Description -->
                    @if($ad->property->business_description)
                        <div>
                            <h5 class="text-white font-medium mb-2">Business Description:</h5>
                            <p class="text-gray-400 text-sm">{{ $ad->property->business_description }}</p>
                        </div>
                    @endif

                </div>
            </div>

            <!-- Promotion Details -->
            <div class="bg-gray-900/50 backdrop-blur-sm border border-gray-800 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-white mb-6 flex items-center">
                    <i class="fas fa-calendar-alt text-blue-400 mr-2"></i>
                    Promotion Details
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Start Date</label>
                        <div class="bg-gray-800 p-3 rounded-lg">
                            <p class="text-white">{{ $ad->start_date->format('l, F j, Y') }}</p>
                            <p class="text-gray-400 text-sm">{{ $ad->start_date->diffForHumans() }}</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">End Date</label>
                        <div class="bg-gray-800 p-3 rounded-lg">
                            <p class="text-white">{{ $ad->end_date->format('l, F j, Y') }}</p>
                            <p class="text-gray-400 text-sm">{{ $ad->end_date->diffForHumans() }}</p>
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-400 mb-2">Duration</label>
                        <div class="bg-gray-800 p-3 rounded-lg">
                            <p class="text-white">{{ $ad->start_date->diffInDays($ad->end_date) + 1 }} days</p>
                            <p class="text-gray-400 text-sm">
                                From {{ $ad->start_date->format('M j') }} to {{ $ad->end_date->format('M j, Y') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Admin Notes History -->
            @if($ad->admin_notes || $ad->rejection_reason)
                <div class="bg-gray-900/50 backdrop-blur-sm border border-gray-800 rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                        <i class="fas fa-sticky-note text-blue-400 mr-2"></i>
                        Admin Notes
                    </h3>

                    @if($ad->admin_notes)
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-400 mb-2">Notes</label>
                            <div class="bg-gray-800 p-3 rounded-lg">
                                <p class="text-gray-300">{{ $ad->admin_notes }}</p>
                            </div>
                        </div>
                    @endif

                    @if($ad->rejection_reason)
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">Rejection Reason</label>
                            <div class="bg-red-900/20 border border-red-700/50 p-3 rounded-lg">
                                <p class="text-red-300">{{ $ad->rejection_reason }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <!-- Actions Panel -->
        <div class="space-y-6">
            <!-- Request Information -->
            <div class="bg-gray-900/50 backdrop-blur-sm border border-gray-800 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                    <i class="fas fa-info-circle text-blue-400 mr-2"></i>
                    Request Info
                </h3>

                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-400">Submitted</label>
                        <p class="text-white">{{ $ad->created_at->format('M j, Y g:i A') }}</p>
                        <p class="text-gray-500 text-sm">{{ $ad->created_at->diffForHumans() }}</p>
                    </div>

                    @if($ad->approved_at)
                        <div>
                            <label class="block text-sm font-medium text-gray-400">Approved</label>
                            <p class="text-white">{{ $ad->approved_at->format('M j, Y g:i A') }}</p>
                            @if($ad->approvedBy)
                                <p class="text-gray-500 text-sm">by {{ $ad->approvedBy->name }}</p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Payment Information -->
            @if($ad->total_amount)
                <div class="bg-gray-900/50 backdrop-blur-sm border border-gray-800 rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                        <i class="fas fa-credit-card text-green-400 mr-2"></i>
                        Payment Details
                    </h3>

                    <div class="space-y-3">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-400">Total Days</label>
                                <p class="text-white">{{ $ad->total_days }} days</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-400">Total Amount</label>
                                <p class="text-white font-bold">${{ number_format($ad->total_amount, 2) }} USD</p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-400">Payment Status</label>
                            <div class="mt-1">
                                @switch($ad->payment_status)
                                    @case('pending')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-500/20 text-yellow-400 border border-yellow-500/30">
                                            <i class="fas fa-clock mr-1"></i>
                                            Payment Pending
                                        </span>
                                        @break
                                    @case('completed')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-500/20 text-green-400 border border-green-500/30">
                                            <i class="fas fa-check mr-1"></i>
                                            Payment Completed
                                        </span>
                                        @break
                                    @case('failed')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-500/20 text-red-400 border border-red-500/30">
                                            <i class="fas fa-times mr-1"></i>
                                            Payment Failed
                                        </span>
                                        @break
                                    @case('refunded')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-500/20 text-blue-400 border border-blue-500/30">
                                            <i class="fas fa-undo mr-1"></i>
                                            Payment Refunded
                                        </span>
                                        @break
                                @endswitch
                            </div>
                        </div>

                        @if($ad->payment_intent_id)
                            <div>
                                <label class="block text-sm font-medium text-gray-400">Payment ID</label>
                                <p class="text-white font-mono text-sm">{{ $ad->payment_intent_id }}</p>
                            </div>
                        @endif

                        @if($ad->paid_at)
                            <div>
                                <label class="block text-sm font-medium text-gray-400">Payment Completed</label>
                                <p class="text-white">{{ $ad->payment_completed_at->format('M j, Y g:i A') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Action Buttons -->
            @if($ad->status === 'pending')
                <div class="bg-gray-900/50 backdrop-blur-sm border border-gray-800 rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                        <i class="fas fa-gavel text-blue-400 mr-2"></i>
                        Review Actions
                    </h3>

                    <!-- Approve Form -->
                    <form action="{{ route('admin.ads.approve', $ad) }}" method="POST" class="mb-4">
                        @csrf
                        <div class="mb-4">
                            <label for="admin_notes" class="block text-sm font-medium text-gray-400 mb-2">
                                Admin Notes (Optional)
                            </label>
                            <textarea name="admin_notes"
                                      id="admin_notes"
                                      rows="3"
                                      class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                      placeholder="Add any notes about this approval..."></textarea>
                        </div>
                        <button type="submit"
                                class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center justify-center">
                            <i class="fas fa-check mr-2"></i>
                            Approve Request
                        </button>
                    </form>

                    <!-- Reject Form -->
                    <form action="{{ route('admin.ads.reject', $ad) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="rejection_reason" class="block text-sm font-medium text-gray-400 mb-2">
                                Rejection Reason <span class="text-red-400">*</span>
                            </label>
                            <textarea name="rejection_reason"
                                      id="rejection_reason"
                                      rows="3"
                                      class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                      placeholder="Explain why this request is being rejected..."
                                      required></textarea>
                        </div>
                        <button type="submit"
                                class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors flex items-center justify-center">
                            <i class="fas fa-times mr-2"></i>
                            Reject Request
                        </button>
                    </form>
                </div>
            @elseif($ad->status === 'approved')
                <div class="bg-gray-900/50 backdrop-blur-sm border border-gray-800 rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                        <i class="fas fa-toggle-on text-blue-400 mr-2"></i>
                        Status Control
                    </h3>

                    <form action="{{ route('admin.ads.activate', $ad) }}" method="POST" class="mb-3">
                        @csrf
                        <button type="submit"
                                class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center justify-center">
                            <i class="fas fa-play mr-2"></i>
                            Activate Now
                        </button>
                    </form>
                    <p class="text-gray-400 text-sm text-center">Manually activate this approved ad</p>
                </div>
            @elseif($ad->status === 'active')
                <div class="bg-gray-900/50 backdrop-blur-sm border border-gray-800 rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                        <i class="fas fa-toggle-off text-blue-400 mr-2"></i>
                        Status Control
                    </h3>

                    <form action="{{ route('admin.ads.deactivate', $ad) }}" method="POST" class="mb-3">
                        @csrf
                        <button type="submit"
                                class="w-full px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors flex items-center justify-center">
                            <i class="fas fa-pause mr-2"></i>
                            Deactivate
                        </button>
                    </form>
                    <p class="text-gray-400 text-sm text-center">Temporarily deactivate this ad</p>
                </div>
            @endif

            <!-- Quick Actions -->
            <div class="bg-gray-900/50 backdrop-blur-sm border border-gray-800 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                    <i class="fas fa-external-link-alt text-blue-400 mr-2"></i>
                    Quick Actions
                </h3>

                <div class="space-y-2">
                    <a href="{{ route('admin.properties.show', $ad->property) }}"
                       class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-building mr-2"></i>
                        View Property
                    </a>

                    <a href="{{ route('admin.ads.index') }}"
                       class="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
