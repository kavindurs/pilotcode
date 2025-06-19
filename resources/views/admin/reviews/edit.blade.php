@extends('layouts.admin')

@section('active-reviews', 'menu-item-active')
@section('page-title', 'Edit Review')
@section('page-subtitle', 'Modify review details and status')

@section('content')
<div class="bg-gray-800 border border-gray-700 rounded-xl shadow-xl overflow-hidden">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-4 border-b border-gray-700">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-bold text-white mb-1">Edit Review</h2>
                <p class="text-gray-300 text-sm">{{ $review->property->business_name ?? 'Unknown Property' }}</p>
            </div>
            <div class="mt-3 sm:mt-0 flex space-x-2">
                <a href="{{ route('admin.reviews.show', $review->id) }}"
                   class="bg-gray-600 hover:bg-gray-500 text-white px-4 py-2 rounded-lg transition-colors duration-200 flex items-center">
                    <i class="fas fa-eye mr-2"></i>
                    View Review
                </a>
                <a href="{{ route('admin.reviews.index') }}"
                   class="bg-gray-600 hover:bg-gray-500 text-white px-4 py-2 rounded-lg transition-colors duration-200 flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to List
                </a>
            </div>
        </div>
    </div>

    <!-- Edit Form -->
    <div class="p-6">
        <form action="{{ route('admin.reviews.update', $review->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Review Details -->
                <div class="bg-gray-700 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                        <i class="fas fa-star text-yellow-400 mr-2"></i>
                        Review Details
                    </h3>

                    <!-- Rating -->
                    <div class="mb-4">
                        <label for="rating" class="block text-sm font-medium text-gray-300 mb-2">Rating *</label>
                        <select id="rating" name="rating" required
                                class="w-full px-4 py-2 bg-gray-600 border border-gray-500 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @for($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}" {{ $review->rating == $i ? 'selected' : '' }}>
                                    {{ $i }} Star{{ $i > 1 ? 's' : '' }}
                                </option>
                            @endfor
                        </select>
                        @error('rating')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Review Text -->
                    <div class="mb-4">
                        <label for="review" class="block text-sm font-medium text-gray-300 mb-2">Review Text *</label>
                        <textarea id="review" name="review" rows="6" required
                                  class="w-full px-4 py-2 bg-gray-600 border border-gray-500 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Enter review text...">{{ old('review', $review->review) }}</textarea>
                        @error('review')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="mb-4">
                        <label for="status" class="block text-sm font-medium text-gray-300 mb-2">Status *</label>
                        <select id="status" name="status" required
                                class="w-full px-4 py-2 bg-gray-600 border border-gray-500 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="Pending Approval" {{ $review->status === 'Pending Approval' ? 'selected' : '' }}>
                                Pending Approval
                            </option>
                            <option value="Approved" {{ $review->status === 'Approved' ? 'selected' : '' }}>
                                Approved
                            </option>
                            <option value="Rejected" {{ $review->status === 'Rejected' ? 'selected' : '' }}>
                                Rejected
                            </option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Current Information (Read-only) -->
                <div class="bg-gray-700 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                        <i class="fas fa-info-circle text-blue-400 mr-2"></i>
                        Current Information
                    </h3>

                    <!-- Current Rating Display -->
                    <div class="mb-4">
                        <label class="text-sm font-medium text-gray-300">Current Rating</label>
                        <div class="flex items-center mt-1">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star text-lg {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-500' }}"></i>
                            @endfor
                            <span class="ml-2 text-white font-medium">{{ $review->rating }}/5</span>
                        </div>
                    </div>

                    <!-- Reviewer Information -->
                    @if($review->user)
                    <div class="mb-4">
                        <label class="text-sm font-medium text-gray-300">Reviewer</label>
                        <p class="text-white">{{ $review->user->name }}</p>
                        <p class="text-gray-400 text-sm">{{ $review->user->email }}</p>
                    </div>
                    @else
                    <div class="mb-4">
                        <label class="text-sm font-medium text-gray-300">Reviewer</label>
                        <p class="text-white">{{ $review->name ?? 'Anonymous' }}</p>
                        <p class="text-gray-400 text-sm">{{ $review->email ?? 'Not provided' }}</p>
                    </div>
                    @endif

                    <!-- Property Information -->
                    @if($review->property)
                    <div class="mb-4">
                        <label class="text-sm font-medium text-gray-300">Property</label>
                        <p class="text-white">{{ $review->property->business_name }}</p>
                        <p class="text-gray-400 text-sm">
                            <span class="px-2 py-1 rounded-full text-xs
                                {{ $review->property->property_type === 'web' ? 'bg-green-800 text-green-200' : 'bg-purple-800 text-purple-200' }}">
                                {{ ucfirst($review->property->property_type) }}
                            </span>
                        </p>
                    </div>
                    @endif

                    <!-- Timestamps -->
                    <div class="mb-4">
                        <label class="text-sm font-medium text-gray-300">Submitted</label>
                        <p class="text-white">{{ $review->created_at->format('M d, Y \a\t h:i A') }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-300">Last Updated</label>
                        <p class="text-white">{{ $review->updated_at->format('M d, Y \a\t h:i A') }}</p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-600">
                <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white px-6 py-2 rounded-lg transition-colors duration-200 flex items-center justify-center">
                    <i class="fas fa-save mr-2"></i>
                    Update Review
                </button>
                <a href="{{ route('admin.reviews.show', $review->id) }}"
                   class="bg-gray-600 hover:bg-gray-500 text-white px-6 py-2 rounded-lg transition-colors duration-200 flex items-center justify-center">
                    <i class="fas fa-times mr-2"></i>
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Form validation and submission
    document.querySelector('form').addEventListener('submit', function(e) {
        const rating = document.getElementById('rating').value;
        const review = document.getElementById('review').value.trim();
        const status = document.getElementById('status').value;

        if (!rating || !review || !status) {
            e.preventDefault();
            alert('Please fill in all required fields.');
            return;
        }

        if (review.length < 10) {
            e.preventDefault();
            alert('Review text must be at least 10 characters long.');
            return;
        }
    });
</script>
@endpush
@endsection
