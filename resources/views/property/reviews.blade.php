@extends('layouts.business')


@section('active-reviews', 'bg-blue-600')
@section('page-title', 'Reviews Dashboard')
@section('page-subtitle', 'View and manage customer reviews')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
    <!-- Main Content Area -->
    <div class="lg:col-span-3">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
        <!-- Average Rating Card -->
        <div class="bg-gray-900 rounded-lg shadow border border-gray-800 p-5">
            <div class="flex items-center mb-2">
                <div class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center mr-3">
                    <i class="fas fa-star text-yellow-500"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-200">Average Rating</h3>
            </div>
            <div class="flex items-center">
                <span class="text-3xl font-bold text-white mr-2">{{ isset($averageRating) ? number_format($averageRating, 1) : '0.0' }}</span>
                <div class="star-rating text-xl relative inline-block">
                    <div class="stars-bg text-gray-700">★★★★★</div>
                    <div class="stars-fg text-yellow-400 absolute top-0 left-0 overflow-hidden whitespace-nowrap" style="width: {{ isset($averageRating) ? (($averageRating / 5) * 100) : 0 }}%">★★★★★</div>
                </div>
            </div>
            <p class="text-sm text-gray-400 mt-2">Based on {{ isset($totalReviews) ? $totalReviews : 0 }} reviews</p>
        </div>

        <!-- Total Reviews Card -->
        <div class="bg-gray-900 rounded-lg shadow border border-gray-800 p-5">
            <div class="flex items-center mb-2">
                <div class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center mr-3">
                    <i class="fas fa-comment-alt text-green-500"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-200">Total Reviews</h3>
            </div>
            <div class="flex items-center">
                <span class="text-3xl font-bold text-white">{{ isset($totalReviews) ? $totalReviews : 0 }}</span>
            </div>
            <p class="text-sm text-gray-400 mt-2">Total verified reviews</p>
        </div>

        <!-- Recent Reviews Card -->
        <div class="bg-gray-900 rounded-lg shadow border border-gray-800 p-5">
            <div class="flex items-center mb-2">
                <div class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center mr-3">
                    <i class="fas fa-clock text-purple-500"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-200">Recent Reviews</h3>
            </div>
            <div class="flex items-center">
                <span class="text-3xl font-bold text-white">{{ isset($recentReviews) ? $recentReviews : 0 }}</span>
            </div>
            <p class="text-sm text-gray-400 mt-2">In the last 30 days</p>
        </div>

        <!-- Call to Action Card -->
        <div class="bg-gray-900 rounded-lg shadow border border-gray-800 p-5">
            <div class="flex items-center mb-2">
                <div class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center mr-3">
                    <i class="fas fa-envelope text-yellow-500"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-200">Get More Reviews</h3>
            </div>
            <p class="text-sm text-gray-400 mb-3">Invite your customers to share their experience</p>
            <a href="{{ route('property.invitations') }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white text-sm font-medium rounded-md hover:bg-yellow-700 transition-colors">
                <i class="fas fa-paper-plane mr-2"></i>
                Send Invitations
            </a>
        </div>
    </div>

    <!-- Rating Distribution -->
    <div class="bg-gray-900 rounded-lg shadow border border-gray-800 mb-6 p-6">
        <h3 class="text-lg font-medium text-gray-200 mb-4">Rating Distribution</h3>
        <div class="space-y-3 max-w-2xl">
            @foreach(range(5, 1) as $rating)
                <div class="flex items-center">
                    <div class="w-12 text-sm text-gray-400 font-medium">{{ $rating }} stars</div>
                    <div class="flex-grow mx-3">
                        <div class="h-2.5 bg-gray-800 rounded-full">
                            <div class="h-2.5 bg-yellow-600 rounded-full" style="width: {{ isset($totalReviews) && isset($ratingDistribution) && $totalReviews > 0 ? (($ratingDistribution[$rating] ?? 0) / $totalReviews * 100) : 0 }}%"></div>
                        </div>
                    </div>
                    <div class="w-12 text-sm text-gray-400 font-medium">{{ isset($ratingDistribution) ? ($ratingDistribution[$rating] ?? 0) : 0 }}</div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Reviews Filter -->
    <div class="bg-gray-900 rounded-lg shadow border border-gray-800 mb-6 p-4">
        <div class="flex flex-wrap gap-4 items-center">
            <span class="text-gray-300">Filter:</span>
            <select id="rating-filter" class="bg-gray-800 border border-gray-700 text-gray-200 rounded-md px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                <option value="all">All Ratings</option>
                <option value="5">5 Stars Only</option>
                <option value="4">4 Stars Only</option>
                <option value="3">3 Stars Only</option>
                <option value="2">2 Stars Only</option>
                <option value="1">1 Star Only</option>
            </select>

            <div class="relative ml-auto">
                <input type="text" id="search-reviews" placeholder="Search reviews..."
                       class="w-full sm:w-64 bg-gray-800 border border-gray-700 text-gray-200 rounded-md pl-9 pr-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-500"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Reviews Section -->
    @if(isset($reviews) && $reviews->count() > 0)
        <div class="bg-gray-900 rounded-lg shadow border border-gray-800 p-6">
            <h2 class="text-xl font-bold text-white mb-6">All Reviews</h2>

            <!-- Reviews Grid - Using your exact card design -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="reviews-container">
                @foreach($reviews as $review)
                    <div class="review-card bg-gray-800 rounded-lg shadow-sm border border-gray-700 p-4 h-full flex flex-col" data-rating="{{ $review->rate }}">
                        <div class="flex items-start justify-between mb-3 pb-3 border-b border-gray-700">
                            <div class="flex items-center">
                                <!-- User profile picture - dynamically loads from users table -->
                                <div class="w-10 h-10 rounded-full overflow-hidden bg-gray-700 flex items-center justify-center text-gray-400 mr-3 shadow-sm">
                                    @if($review->user && $review->user->profile_picture)
                                        <img src="{{ asset('storage/' . $review->user->profile_picture) }}"
                                             alt="{{ $review->user->name ?? 'User' }}"
                                             class="w-full h-full object-cover">
                                    @else
                                        <i class="fas fa-user"></i>
                                    @endif
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-200">{{ $review->user->name ?? 'Anonymous' }}</h3>
                                    <p class="text-xs text-gray-500">{{ $review->created_at->format('M d, Y') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center border border-gray-700 px-2 py-1 rounded-md bg-gray-700">
                                <span class="text-sm font-bold text-yellow-500 mr-1.5">{{ number_format($review->rate, 1) }}</span>
                                <div class="star-rating text-sm relative inline-block">
                                    <div class="stars-bg text-gray-600">★★★★★</div>
                                    <div class="stars-fg text-yellow-400 absolute top-0 left-0 overflow-hidden whitespace-nowrap" style="width: {{ ($review->rate / 5) * 100 }}%">★★★★★</div>
                                </div>
                            </div>
                        </div>

                        <div class="flex-grow">
                            <!-- Show review text from the review column in rates table -->
                            @if($review->review)
                                <p class="text-gray-300 text-sm leading-relaxed review-text">
                                    {{ Str::limit($review->review, 150) }}
                                </p>
                                @if(strlen($review->review) > 150)
                                    <button class="text-yellow-500 hover:text-yellow-400 text-xs mt-1 focus:outline-none"
                                            onclick="toggleReviewText(this)">
                                        Read more
                                    </button>
                                    <p class="text-gray-300 text-sm leading-relaxed hidden full-review">
                                        {{ $review->review }}
                                    </p>
                                @endif
                            @else
                                <p class="text-gray-500 italic text-sm">No comment provided</p>
                            @endif
                        </div>

                        <!-- Review Footer with Helpful Button -->
                        <div class="mt-3 pt-3 border-t border-gray-700 flex justify-between items-center">
                            <div class="text-xs text-gray-500">
                                <i class="far fa-clock mr-1"></i>
                                {{ $review->created_at->diffForHumans() }}
                            </div>
                            @if($review->experienced_date)
                            <div class="text-xs text-gray-500">
                                <i class="far fa-calendar-alt mr-1"></i>
                                Visited on {{ \Carbon\Carbon::parse($review->experienced_date)->format('M d, Y') }}
                            </div>
                            @endif
                            <button class="text-xs text-gray-500 hover:text-yellow-500 flex items-center focus:outline-none">
                                <i class="far fa-thumbs-up mr-1"></i> Helpful
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- No Results Message (initially hidden) -->
            <div id="no-results" class="hidden text-center py-10">
                <div class="w-16 h-16 mx-auto bg-gray-800 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-search text-gray-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-medium text-white">No matching reviews</h3>
                <p class="mt-2 text-gray-400">Try adjusting your search or filter criteria.</p>
            </div>
        </div>
    @else
        <!-- No Reviews State -->
        <div class="bg-gray-900 rounded-lg shadow border border-gray-800 p-10 text-center">
            <div class="w-20 h-20 mx-auto bg-gray-800 rounded-full flex items-center justify-center mb-4">
                <i class="fas fa-star text-yellow-500 text-3xl"></i>
            </div>
            <h3 class="text-xl font-bold text-white mb-2">No Reviews Yet</h3>
            <p class="text-gray-400 mb-6 max-w-md mx-auto">You haven't received any reviews for your business yet. Start inviting customers to share their experience!</p>
            <a href="{{ route('property.invitations') }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700 transition-colors">
                <i class="fas fa-paper-plane mr-2"></i>
                Request Reviews
            </a>
        </div>
    @endif
    </div>

    <!-- Right Sidebar -->
    <div class="lg:col-span-1">
        <!-- Review Analytics Card -->
        <div class="bg-gray-800 border border-gray-700 rounded-lg shadow-lg p-6 mb-6">
            <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                <i class="fas fa-chart-bar mr-2 text-blue-400"></i>
                Review Analytics
            </h3>
            <div class="space-y-4">
                <div class="bg-gray-700 rounded-lg p-3">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-sm text-gray-300">Response Rate</span>
                        <span class="text-sm font-medium text-green-400">
                            {{ isset($totalReviews) && $totalReviews > 0 ? round(($totalReviews / max($totalReviews + 5, 1)) * 100) : 0 }}%
                        </span>
                    </div>
                    <div class="w-full bg-gray-600 rounded-full h-2">
                        <div class="bg-green-500 h-2 rounded-full" style="width: {{ isset($totalReviews) && $totalReviews > 0 ? round(($totalReviews / max($totalReviews + 5, 1)) * 100) : 0 }}%"></div>
                    </div>
                </div>
                <div class="bg-gray-700 rounded-lg p-3">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-sm text-gray-300">5-Star Reviews</span>
                        <span class="text-sm font-medium text-yellow-400">
                            {{ isset($totalReviews) && isset($ratingDistribution) && $totalReviews > 0 ? round((($ratingDistribution[5] ?? 0) / $totalReviews) * 100) : 0 }}%
                        </span>
                    </div>
                    <div class="w-full bg-gray-600 rounded-full h-2">
                        <div class="bg-yellow-500 h-2 rounded-full" style="width: {{ isset($totalReviews) && isset($ratingDistribution) && $totalReviews > 0 ? round((($ratingDistribution[5] ?? 0) / $totalReviews) * 100) : 0 }}%"></div>
                    </div>
                </div>
                <div class="bg-gray-700 rounded-lg p-3">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-sm text-gray-300">Avg. Rating Trend</span>
                        <span class="text-sm font-medium text-blue-400">
                            <i class="fas fa-arrow-up mr-1"></i>+0.2
                        </span>
                    </div>
                    <p class="text-xs text-gray-400">Compared to last month</p>
                </div>
            </div>
        </div>

        <!-- Review Management Tips -->
        <div class="bg-gray-800 border border-gray-700 rounded-lg shadow-lg p-6 mb-6">
            <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                <i class="fas fa-lightbulb mr-2 text-yellow-400"></i>
                Review Management Tips
            </h3>
            <div class="space-y-3">
                <div class="flex items-start">
                    <i class="fas fa-check-circle text-green-400 mr-3 mt-0.5 flex-shrink-0"></i>
                    <div>
                        <p class="text-sm text-gray-300">Respond to all reviews professionally and promptly</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <i class="fas fa-check-circle text-green-400 mr-3 mt-0.5 flex-shrink-0"></i>
                    <div>
                        <p class="text-sm text-gray-300">Thank customers for positive feedback</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <i class="fas fa-check-circle text-green-400 mr-3 mt-0.5 flex-shrink-0"></i>
                    <div>
                        <p class="text-sm text-gray-300">Address negative reviews constructively</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <i class="fas fa-check-circle text-green-400 mr-3 mt-0.5 flex-shrink-0"></i>
                    <div>
                        <p class="text-sm text-gray-300">Use insights to improve your service</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions Card -->
        <div class="bg-gray-800 border border-gray-700 rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                <i class="fas fa-bolt mr-2 text-purple-400"></i>
                Quick Actions
            </h3>
            <div class="space-y-3">
                <a href="{{ route('property.invitations') }}"
                   class="w-full flex items-center justify-center px-4 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                    <i class="fas fa-paper-plane mr-2"></i>
                    Send Invitations
                </a>
                <button class="w-full flex items-center justify-center px-4 py-3 bg-gray-700 text-gray-300 rounded-md hover:bg-gray-600 transition-colors">
                    <i class="fas fa-download mr-2"></i>
                    Export Reviews
                </button>
                <button class="w-full flex items-center justify-center px-4 py-3 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                    <i class="fas fa-share-alt mr-2"></i>
                    Share Widget
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Star rating styles */
    .star-rating {
        display: inline-flex;
        position: relative;
    }
    .stars-bg {
        color: rgba(75, 85, 99, 0.5);
    }
    .stars-fg {
        color: #FACC15;
        position: absolute;
        top: 0;
        left: 0;
        overflow: hidden;
        white-space: nowrap;
    }
</style>
@endpush

@push('scripts')
<script>
// Toggle read more functionality
function toggleReviewText(button) {
    const card = button.closest('.review-card');
    const reviewText = card.querySelector('.review-text');
    const fullReview = card.querySelector('.full-review');

    if (fullReview.classList.contains('hidden')) {
        reviewText.classList.add('hidden');
        fullReview.classList.remove('hidden');
        button.innerText = 'Read less';
    } else {
        reviewText.classList.remove('hidden');
        fullReview.classList.add('hidden');
        button.innerText = 'Read more';
    }
}

// Filter functionality
document.addEventListener('DOMContentLoaded', function() {
    const ratingFilter = document.getElementById('rating-filter');
    const searchInput = document.getElementById('search-reviews');
    const reviewCards = document.querySelectorAll('.review-card');
    const reviewsContainer = document.getElementById('reviews-container');
    const noResults = document.getElementById('no-results');

    function applyFilters() {
        const rating = ratingFilter.value;
        const searchText = searchInput.value.toLowerCase();

        let visibleCount = 0;

        reviewCards.forEach(card => {
            const cardRating = card.getAttribute('data-rating');
            const cardText = card.textContent.toLowerCase();

            const ratingMatch = rating === 'all' || cardRating === rating;
            const textMatch = !searchText || cardText.includes(searchText);

            if (ratingMatch && textMatch) {
                card.classList.remove('hidden');
                visibleCount++;
            } else {
                card.classList.add('hidden');
            }
        });

        if (visibleCount === 0) {
            reviewsContainer.classList.add('hidden');
            noResults.classList.remove('hidden');
        } else {
            reviewsContainer.classList.remove('hidden');
            noResults.classList.add('hidden');
        }
    }

    ratingFilter.addEventListener('change', applyFilters);
    searchInput.addEventListener('input', applyFilters);
});
</script>
@endpush
