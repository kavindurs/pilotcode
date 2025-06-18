@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md p-8">
        <div class="flex items-center mb-8 pb-4 border-b border-gray-200">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Write a Review</h2>
                <p class="text-gray-600 mt-1">for {{ $property->business_name }}</p>
            </div>
        </div>

        <form action="{{ route('rate.store', $property) }}" method="POST" class="space-y-8">
            @csrf

            <!-- Rating Section -->
            <div class="space-y-4">
                <label class="block text-sm font-semibold text-gray-700">Overall Rating</label>
                <div class="flex items-center space-x-2" id="ratingContainer">
                    @for ($i = 1; $i <= 5; $i++)
                        <label class="cursor-pointer transition-transform hover:scale-110 star-label" data-rating="{{ $i }}">
                            <input type="radio"
                                   name="rate"
                                   value="{{ $i }}"
                                   class="hidden peer rating-input"
                                   required>
                            <span class="text-3xl text-gray-300 star-icon transition-colors">★</span>
                        </label>
                    @endfor
                </div>
                @error('rate')
                    <p class="text-red-500 text-sm mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                @enderror
            </div>

            <!-- Review Section -->
            <div class="space-y-4">
                <label class="block text-sm font-semibold text-gray-700">Your Review</label>
                <div class="relative">
                    <textarea
                        name="review"
                        required
                        class="w-full border border-gray-300 rounded-lg p-4 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-shadow resize-none"
                        rows="4"
                        maxlength="250"
                        placeholder="Share your experience with this business...">{{ old('review') }}</textarea>
                    <div class="absolute bottom-2 right-2 text-xs text-gray-500">
                        <span id="charCount">0</span>/250
                    </div>
                </div>
                @error('review')
                    <p class="text-red-500 text-sm mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                @enderror
            </div>

            <!-- Experience Date Section -->
            <div class="space-y-4">
                <label class="block text-sm font-semibold text-gray-700">When did you visit?</label>
                <div class="relative">
                    <input type="date"
                           name="experienced_date"
                           required
                           max="{{ date('Y-m-d') }}"
                           class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent pr-10"
                           value="{{ old('experienced_date') }}">
                    <div class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                        <i class="fas fa-calendar"></i>
                    </div>
                </div>
                @error('experienced_date')
                    <p class="text-red-500 text-sm mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="pt-4">
                <button type="submit"
                    class="w-full bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 transition-all duration-200">
                    Submit Review
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Existing textarea code
    const textarea = document.querySelector('textarea[name="review"]');
    const charCount = document.getElementById('charCount');

    function updateCharCount() {
        charCount.textContent = textarea.value.length;
    }

    textarea.addEventListener('input', updateCharCount);
    updateCharCount();

    // New star rating code
    const ratingContainer = document.getElementById('ratingContainer');
    const stars = ratingContainer.querySelectorAll('.star-label');

    function updateStars(rating) {
        stars.forEach(star => {
            const starRating = parseInt(star.dataset.rating);
            const starIcon = star.querySelector('.star-icon');
            if (starRating <= rating) {
                starIcon.classList.add('text-yellow-400');
                starIcon.classList.remove('text-gray-300');
            } else {
                starIcon.classList.remove('text-yellow-400');
                starIcon.classList.add('text-gray-300');
            }
        });
    }

    // Handle click events
    stars.forEach(star => {
        star.addEventListener('click', () => {
            const rating = parseInt(star.dataset.rating);
            updateStars(rating);
        });

        // Handle hover effects
        star.addEventListener('mouseenter', () => {
            const rating = parseInt(star.dataset.rating);
            updateStars(rating);
        });
    });

    // Reset stars when mouse leaves the container (if no rating is selected)
    ratingContainer.addEventListener('mouseleave', () => {
        const selectedRating = ratingContainer.querySelector('input:checked');
        if (selectedRating) {
            updateStars(parseInt(selectedRating.value));
        } else {
            updateStars(0);
        }
    });

    // Set initial state if there's a previously selected rating
    const initialRating = ratingContainer.querySelector('input:checked');
    if (initialRating) {
        updateStars(parseInt(initialRating.value));
    }
});
</script>
@endpush
@endsection
