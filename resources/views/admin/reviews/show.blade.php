@extends('layouts.admin')

@section('active-reviews', 'menu-item-active')
@section('page-title', 'Review Details')
@section('page-subtitle', 'View review information and details')

@section('content')
<div class="bg-gray-800 border border-gray-700 rounded-xl shadow-xl overflow-hidden">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-4 border-b border-gray-700">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-bold text-white mb-1">Review Details</h2>
                <p class="text-gray-300 text-sm">{{ $review->property->business_name ?? 'Unknown Property' }}</p>
            </div>
            <div class="mt-3 sm:mt-0 flex space-x-2">
                <a href="{{ route('admin.reviews.edit', $review->id) }}"
                   class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-lg transition-colors duration-200 flex items-center">
                    <i class="fas fa-edit mr-2"></i>
                    Edit Review
                </a>
                <a href="{{ route('admin.reviews.index') }}"
                   class="bg-gray-600 hover:bg-gray-500 text-white px-4 py-2 rounded-lg transition-colors duration-200 flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to List
                </a>
            </div>
        </div>
    </div>

    <!-- Review Information -->
    <div class="p-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Review Details -->
            <div class="bg-gray-700 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                    <i class="fas fa-star text-yellow-400 mr-2"></i>
                    Review Details
                </h3>
                <div class="space-y-4">
                    <div>
                        <label class="text-sm font-medium text-gray-300">Rating</label>
                        <div class="flex items-center mt-1">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star text-lg {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-500' }}"></i>
                            @endfor
                            <span class="ml-2 text-white font-medium">{{ $review->rating }}/5</span>
                        </div>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-300">Review Text</label>
                        <div class="mt-1 p-4 bg-gray-600 rounded-lg">
                            <p class="text-white leading-relaxed">{{ $review->review }}</p>
                        </div>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-300">Status</label>
                        <p class="text-white mt-1">
                            <span class="px-3 py-1 rounded-full text-sm font-medium
                                @if($review->status === 'Approved') bg-green-800 text-green-200
                                @elseif($review->status === 'Rejected') bg-red-800 text-red-200
                                @else bg-yellow-800 text-yellow-200
                                @endif">
                                {{ $review->status }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- User Information -->
            <div class="bg-gray-700 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                    <i class="fas fa-user text-blue-400 mr-2"></i>
                    Reviewer Information
                </h3>
                <div class="space-y-3">
                    @if($review->user)
                    <div>
                        <label class="text-sm font-medium text-gray-300">Name</label>
                        <p class="text-white">{{ $review->user->name }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-300">Email</label>
                        <p class="text-white">{{ $review->user->email }}</p>
                    </div>
                    @else
                    <div>
                        <label class="text-sm font-medium text-gray-300">Reviewer Name</label>
                        <p class="text-white">{{ $review->name ?? 'Anonymous' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-300">Reviewer Email</label>
                        <p class="text-white">{{ $review->email ?? 'Not provided' }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Property Information -->
            @if($review->property)
            <div class="bg-gray-700 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                    <i class="fas fa-building text-green-400 mr-2"></i>
                    Property Information
                </h3>
                <div class="space-y-3">
                    <div>
                        <label class="text-sm font-medium text-gray-300">Business Name</label>
                        <p class="text-white">{{ $review->property->business_name }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-300">Property Type</label>
                        <p class="text-white">
                            <span class="px-2 py-1 rounded-full text-xs
                                {{ $review->property->property_type === 'web' ? 'bg-green-800 text-green-200' : 'bg-purple-800 text-purple-200' }}">
                                {{ ucfirst($review->property->property_type) }}
                            </span>
                        </p>
                    </div>
                    @if($review->property->domain)
                    <div>
                        <label class="text-sm font-medium text-gray-300">Domain</label>
                        <p class="text-white">
                            <a href="https://{{ $review->property->domain }}" target="_blank" class="text-blue-400 hover:text-blue-300 transition-colors">
                                {{ $review->property->domain }}
                                <i class="fas fa-external-link-alt ml-1 text-xs"></i>
                            </a>
                        </p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Timestamps -->
            <div class="bg-gray-700 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                    <i class="fas fa-clock text-purple-400 mr-2"></i>
                    Timeline
                </h3>
                <div class="space-y-3">
                    <div>
                        <label class="text-sm font-medium text-gray-300">Submitted At</label>
                        <p class="text-white">{{ $review->created_at->format('M d, Y \a\t h:i A') }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-300">Last Updated</label>
                        <p class="text-white">{{ $review->updated_at->format('M d, Y \a\t h:i A') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        @if($review->status === 'Pending Approval')
        <div class="mt-6 flex flex-wrap gap-3">
            <form action="{{ route('admin.reviews.approve', $review->id) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="bg-green-600 hover:bg-green-500 text-white px-6 py-2 rounded-lg transition-colors duration-200 flex items-center">
                    <i class="fas fa-check mr-2"></i>
                    Approve Review
                </button>
            </form>
            <form action="{{ route('admin.reviews.reject', $review->id) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="bg-red-600 hover:bg-red-500 text-white px-6 py-2 rounded-lg transition-colors duration-200 flex items-center" onclick="return confirm('Are you sure you want to reject this review?')">
                    <i class="fas fa-times mr-2"></i>
                    Reject Review
                </button>
            </form>
        </div>
        @endif

        <!-- Delete Button -->
        <div class="mt-4 pt-4 border-t border-gray-600">
            <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-700 hover:bg-red-600 text-white px-6 py-2 rounded-lg transition-colors duration-200 flex items-center" onclick="return confirm('Are you sure you want to permanently delete this review? This action cannot be undone.')">
                    <i class="fas fa-trash mr-2"></i>
                    Delete Review
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
