@extends('layouts.app')

@section('title', 'Write a Review - Scoreness')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <!-- Container wrapper for consistent width -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-0">
        <!-- Modern Header with Pattern Background - Similar to Contact Us Page -->
        <div class="bg-white rounded-xl overflow-hidden shadow-sm mb-6">
            <div class="relative bg-gradient-to-br from-blue-500 to-indigo-600">
                <!-- Background Pattern -->
                <div class="absolute inset-0 opacity-10">
                    <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%">
                        <defs>
                            <pattern id="pattern" width="40" height="40" patternUnits="userSpaceOnUse">
                                <path d="M0 20 L40 20" stroke="#fff" stroke-width="1" fill="none" />
                                <path d="M20 0 L20 40" stroke="#fff" stroke-width="1" fill="none" />
                            </pattern>
                        </defs>
                        <rect width="100%" height="100%" fill="url(#pattern)" />
                    </svg>
                </div>

                <div class="relative px-8 py-16 sm:px-10 sm:py-14">
                    <div class="flex flex-col md:flex-row md:items-center">
                        <!-- Left: Icon -->
                        <div class="flex-shrink-0 mb-6 md:mb-0 md:mr-8">
                            <div class="w-20 h-20 rounded-full bg-white p-1 shadow-lg flex items-center justify-center">
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-100 to-blue-200 rounded-full">
                                    <i class="fas fa-star text-blue-600 text-3xl"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Right: Page Title and Description -->
                        <div class="text-white">
                            <h1 class="text-3xl font-bold mb-2">Write a Review</h1>
                            <p class="text-blue-100 max-w-2xl">
                                Share your experience to help others make informed decisions. Your honest reviews help businesses improve and guide consumers to the best choices.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Search and Review Guide -->
            <div class="bg-white rounded-lg shadow-sm p-6 sm:p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Find a Business to Review</h2>

                <!-- Search Bar with Autocomplete (Keeping original functionality) -->
                <div class="relative w-full mb-6">
                    <input
                        type="text"
                        id="search-input"
                        placeholder="Search for businesses or categories..."
                        class="block w-full pl-10 pr-12 py-3 bg-gray-50 border-0 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500"
                    />
                    <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                        <i class="fas fa-search"></i>
                    </div>
                    <button
                        id="search-button"
                        type="button"
                        class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-blue-600 text-white px-4 py-1 rounded-md text-sm hover:bg-blue-700 transition-colors"
                    >
                        Search
                    </button>

                    <!-- Autocomplete results container -->
                    <div id="search-results" class="absolute left-0 right-0 mt-1 bg-white rounded-md shadow-lg max-h-96 overflow-y-auto z-50 hidden">
                        <!-- Results will be inserted here by JavaScript -->
                        <div id="loading-indicator" class="p-4 text-center hidden">
                            <div class="inline-block animate-spin rounded-full h-5 w-5 border-t-2 border-b-2 border-blue-600"></div>
                            <span class="ml-2 text-sm text-gray-600">Searching...</span>
                        </div>
                        <div id="no-results" class="p-4 text-center hidden">
                            <span class="text-sm text-gray-600">No results found</span>
                        </div>
                        <div id="results-container"></div>
                    </div>
                </div>

                <div class="mb-6">
                    <div class="flex flex-wrap gap-2 text-sm">
                        <span class="text-gray-500">Trending:</span>
                        <a href="#" class="text-blue-600 hover:underline trending-search">Restaurants</a>
                        <a href="#" class="text-blue-600 hover:underline trending-search">Hotels</a>
                        <a href="#" class="text-blue-600 hover:underline trending-search">Online Shops</a>
                    </div>
                </div>

                <!-- Popular Categories -->
                <div class="border-t border-gray-200 pt-6 mt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Popular Categories</h3>

                    @php
                        // Define icons for common subcategories
                        $icons = [
                            'restaurants' => 'fa-utensils',
                            'cafes' => 'fa-coffee',
                            'hotels' => 'fa-hotel',
                            'retail' => 'fa-shopping-bag',
                            'online shop' => 'fa-shopping-cart',
                            'grocery' => 'fa-shopping-basket',
                            'electronics' => 'fa-laptop',
                            'clothing' => 'fa-tshirt',
                            'healthcare' => 'fa-heartbeat',
                            'education' => 'fa-graduation-cap',
                            'financial' => 'fa-money-bill-wave',
                            'automotive' => 'fa-car',
                            'fitness' => 'fa-dumbbell',
                            'beauty' => 'fa-spa',
                            'travel' => 'fa-plane'
                        ];

                        // Get top subcategories with the most businesses - with additional check to ensure they're strings
                        $subcategories = DB::table('properties')
                            ->select('subcategory', DB::raw('count(*) as business_count'))
                            ->whereNotNull('subcategory')
                            ->where('subcategory', '!=', '')
                            ->where(function($query) {
                                // Exclude purely numeric subcategories as they're likely IDs
                                $query->where('subcategory', 'NOT REGEXP', '^[0-9]+$')
                                      ->orWhere(function($q) {
                                          // If we need to include numeric subcategories, make sure they're meaningful
                                          $q->where('subcategory', 'REGEXP', '^[0-9]+$')
                                            ->where(DB::raw('LENGTH(subcategory)'), '>', 1);
                                      });
                            })
                            ->where('status', 'Approved')
                            ->groupBy('subcategory')
                            ->orderBy('business_count', 'desc')
                            ->take(8)
                            ->get();

                        // For debugging - if still having issues
                        // dd($subcategories);

                        // If subcategory is a foreign key to another table with names, try joining
                        if (count($subcategories) == 0 || is_numeric($subcategories->first()->subcategory ?? '')) {
                            // Try to join with a subcategories table if it exists
                            try {
                                $subcategories = DB::table('properties')
                                    ->join('subcategories', 'properties.subcategory', '=', 'subcategories.id')
                                    ->select('subcategories.name as subcategory', DB::raw('count(*) as business_count'))
                                    ->where('properties.status', 'Approved')
                                    ->groupBy('subcategories.name')
                                    ->orderBy('business_count', 'desc')
                                    ->take(8)
                                    ->get();
                            } catch (\Exception $e) {
                                // If join fails, fallback to original query
                            }
                        }

                        // If still no results, fetch business categories instead
                        if (count($subcategories) == 0) {
                            $subcategories = DB::table('properties')
                                ->select('category as subcategory', DB::raw('count(*) as business_count'))
                                ->whereNotNull('category')
                                ->where('category', '!=', '')
                                ->where('status', 'Approved')
                                ->groupBy('category')
                                ->orderBy('business_count', 'desc')
                                ->take(8)
                                ->get();
                        }
                    @endphp

                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                        @foreach($subcategories as $subcategory)
                            @php
                                // Find appropriate icon or use default
                                $iconClass = 'fa-store';
                                foreach($icons as $key => $icon) {
                                    if (stripos($subcategory->subcategory, $key) !== false) {
                                        $iconClass = $icon;
                                        break;
                                    }
                                }

                                // Make sure we display the proper name as a string
                                $subcategoryName = strval($subcategory->subcategory);

                                // Debug: Print the actual value if there's an issue
                                // dd($subcategory);
                            @endphp

                            <a href="/search?query={{ urlencode($subcategoryName) }}" class="flex flex-col items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                <div class="w-10 h-10 flex items-center justify-center bg-blue-100 text-blue-600 rounded-full mb-2">
                                    <i class="fas {{ $iconClass }}"></i>
                                </div>
                                <span class="text-sm text-gray-900 text-center">{{ $subcategoryName }}</span>
                                <span class="text-xs text-gray-500">{{ $subcategory->business_count }} {{ Str::plural('business', $subcategory->business_count) }}</span>
                            </a>
                        @endforeach
                    </div>

                    @if(count($subcategories) == 0)
                        <div class="text-center py-4 text-gray-500">
                            No subcategories found.
                        </div>
                    @endif

                    <div class="mt-4 text-center">
                        <a href="/categories" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            View all categories <i class="fas fa-chevron-right ml-1 text-xs"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- How to Write Reviews -->
            <div class="bg-white rounded-lg shadow-sm p-6 sm:p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">How to Write a Helpful Review</h2>

                <div class="space-y-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-12 w-12 rounded-md bg-blue-100 text-blue-600">
                                <i class="fas fa-info-circle text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-gray-900">Be Specific</h3>
                            <p class="mt-2 text-base text-gray-600">
                                Include details about your experience. What did you like or dislike? Specific details help others understand your perspective.
                            </p>
                        </div>
                    </div>

                    <div class="flex">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-12 w-12 rounded-md bg-blue-100 text-blue-600">
                                <i class="fas fa-check-circle text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-gray-900">Be Honest</h3>
                            <p class="mt-2 text-base text-gray-600">
                                Share your genuine opinion and experience. Authentic reviews, whether positive or negative, are the most helpful for other users.
                            </p>
                        </div>
                    </div>

                    <div class="flex">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-12 w-12 rounded-md bg-blue-100 text-blue-600">
                                <i class="fas fa-comment text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-gray-900">Be Constructive</h3>
                            <p class="mt-2 text-base text-gray-600">
                                Even when sharing negative experiences, focus on being constructive rather than just complaining. Suggest ways the business could improve.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Review Guidelines -->
                <div class="mt-8 bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Review Guidelines</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-3">
                        <div class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                            <span class="text-sm text-gray-600">Write about your own experience</span>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                            <span class="text-sm text-gray-600">Focus on the products/services offered</span>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                            <span class="text-sm text-gray-600">Provide balanced feedback</span>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                            <span class="text-sm text-gray-600">Be respectful and constructive</span>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-times-circle text-red-500 mt-1 mr-2"></i>
                            <span class="text-sm text-gray-600">Don't include personal information</span>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-times-circle text-red-500 mt-1 mr-2"></i>
                            <span class="text-sm text-gray-600">Don't use offensive language</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Reviews Section -->
        <div class="bg-white rounded-lg shadow-sm p-6 sm:p-8 mb-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Recent Community Reviews</h2>
                <a href="/categories" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Rate Now</a>
            </div>

            @php
                $latestReviews = App\Models\Rate::with(['user', 'property'])
                    ->where('status', 'Approved')
                    ->orderBy('created_at', 'desc')
                    ->take(3)
                    ->get();
            @endphp

            <div class="grid md:grid-cols-3 gap-6">
                @foreach($latestReviews as $review)
                    <div class="border border-gray-200 rounded-lg p-5 relative">
                        <div class="absolute top-5 right-5 text-blue-500">
                            <i class="fas fa-quote-right text-3xl opacity-10"></i>
                        </div>

                        <div class="flex items-start mb-4">
                            <div class="flex-shrink-0 mr-3">
                                @if($review->user && isset($review->user->profile_photo_path))
                                    <img src="/storage/{{ $review->user->profile_photo_path }}" alt="{{ $review->user->name }}" class="w-10 h-10 rounded-full">
                                @else
                                    <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center font-bold">
                                        {{ $review->user ? substr($review->user->name, 0, 1) : 'U' }}
                                    </div>
                                @endif
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900 text-sm">{{ $review->user ? $review->user->name : 'Anonymous User' }}</h4>
                                <div class="flex items-center">
                                    <div class="flex text-yellow-400 text-xs">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $review->rate)
                                                <i class="fas fa-star"></i>
                                            @else
                                                <i class="far fa-star"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    <span class="ml-2 text-xs text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h5 class="font-medium text-gray-900 text-sm">{{ $review->title ?? 'Review for ' . optional($review->property)->business_name }}</h5>
                            <p class="text-gray-600 mt-1 text-sm line-clamp-3">{{ $review->description }}</p>
                        </div>

                        @if($review->property)
                        <div class="flex items-center pt-2 border-t border-gray-200">
                            <a href="/property/{{ $review->property->id }}" class="flex items-center">
                                @if($review->property->profile_picture)
                                    <img src="/storage/{{ $review->property->profile_picture }}" alt="{{ $review->property->business_name }}" class="w-6 h-6 object-cover rounded-full mr-2">
                                @else
                                    <div class="w-6 h-6 bg-gray-200 text-gray-600 rounded-full flex items-center justify-center text-xs font-bold mr-2">
                                        {{ substr($review->property->business_name, 0, 1) }}
                                    </div>
                                @endif
                                <span class="text-xs text-gray-600">{{ $review->property->business_name }}</span>
                            </a>
                        </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <!-- CTA Section -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-lg shadow-sm overflow-hidden mb-8">
            <div class="p-8 sm:p-10 text-center">
                <h2 class="text-2xl font-bold text-white mb-4">Ready to Share Your Experience?</h2>
                <p class="text-blue-100 mb-6 max-w-2xl mx-auto">
                    If you can't find the business you're looking for, don't worry, add it so business owners can request their listing later.
                </p>
                <a href="/add-business" class="inline-flex items-center justify-center px-6 py-3 text-base font-medium rounded-md shadow-sm text-blue-700 bg-white hover:bg-blue-50">
                    Add a Business
                </a>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for Auto-suggest Search -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const searchButton = document.getElementById('search-button');
    const searchResults = document.getElementById('search-results');
    const resultsContainer = document.getElementById('results-container');
    const loadingIndicator = document.getElementById('loading-indicator');
    const noResults = document.getElementById('no-results');
    const trendingSearches = document.querySelectorAll('.trending-search');

    let debounceTimer;
    const debounceDelay = 300; // milliseconds

    // Function to perform search
    function performSearch(query) {
        if (!query || query.length < 2) {
            searchResults.classList.add('hidden');
            return;
        }

        // Show loading indicator
        searchResults.classList.remove('hidden');
        loadingIndicator.classList.remove('hidden');
        noResults.classList.add('hidden');
        resultsContainer.innerHTML = '';

        // Fetch search results via AJAX
        fetch(`/api/search?query=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                loadingIndicator.classList.add('hidden');

                if (data.properties.length === 0 && data.subcategories.length === 0) {
                    noResults.classList.remove('hidden');
                    return;
                }

                // Clear previous results
                resultsContainer.innerHTML = '';

                // Add business results
                if (data.properties.length > 0) {
                    const businessesHeading = document.createElement('h3');
                    businessesHeading.className = 'px-4 pt-3 text-xs font-semibold text-gray-500 uppercase tracking-wider';
                    businessesHeading.textContent = 'Businesses';
                    resultsContainer.appendChild(businessesHeading);

                    data.properties.slice(0, 5).forEach(property => {
                        const item = document.createElement('a');
                        item.href = `/property/${property.id}`;
                        item.className = 'block px-4 py-3 hover:bg-gray-50 transition-colors border-t border-gray-100 flex items-center';

                        // Business icon/image
                        const imageContainer = document.createElement('div');
                        imageContainer.className = 'flex-shrink-0 w-10 h-10 mr-3';

                        if (property.profile_picture) {
                            const img = document.createElement('img');
                            img.src = `/storage/${property.profile_picture}`;
                            img.alt = property.business_name;
                            img.className = 'w-10 h-10 object-cover rounded-md';
                            img.onerror = function() {
                                this.onerror = null;
                                this.src = '/images/default-business.png';
                            };
                            imageContainer.appendChild(img);
                        } else {
                            const placeholder = document.createElement('div');
                            placeholder.className = 'w-10 h-10 bg-blue-100 flex items-center justify-center rounded-md text-blue-600 font-semibold';
                            placeholder.textContent = property.business_name.substring(0, 2);
                            imageContainer.appendChild(placeholder);
                        }

                        item.appendChild(imageContainer);

                        // Business details
                        const details = document.createElement('div');
                        details.className = 'flex-grow';

                        const name = document.createElement('p');
                        name.className = 'font-medium text-gray-900';
                        name.textContent = property.business_name;
                        details.appendChild(name);

                        const category = document.createElement('p');
                        category.className = 'text-xs text-gray-500';
                        category.textContent = `${property.category} · ${property.city}, ${property.country}`;
                        details.appendChild(category);

                        item.appendChild(details);
                        resultsContainer.appendChild(item);
                    });

                    // Show "View all businesses" if there are more than 5
                    if (data.properties.length > 5) {
                        const viewAll = document.createElement('a');
                        viewAll.href = `/search?query=${encodeURIComponent(query)}`;
                        viewAll.className = 'block px-4 py-3 text-center text-sm text-blue-600 hover:text-blue-800 font-medium border-t border-gray-100 bg-gray-50';
                        viewAll.textContent = `View all ${data.properties.length} businesses`;
                        resultsContainer.appendChild(viewAll);
                    }
                }

                // Add category results
                if (data.subcategories.length > 0) {
                    const categoriesHeading = document.createElement('h3');
                    categoriesHeading.className = 'px-4 pt-3 text-xs font-semibold text-gray-500 uppercase tracking-wider border-t border-gray-200 mt-2';
                    categoriesHeading.textContent = 'Categories';
                    resultsContainer.appendChild(categoriesHeading);

                    data.subcategories.slice(0, 4).forEach(category => {
                        const item = document.createElement('a');
                        item.href = `/category/${category.slug}`;
                        item.className = 'block px-4 py-3 hover:bg-gray-50 transition-colors border-t border-gray-100';

                        const name = document.createElement('p');
                        name.className = 'font-medium text-blue-600';
                        name.textContent = category.name;
                        item.appendChild(name);

                        if (category.description) {
                            const description = document.createElement('p');
                            description.className = 'text-xs text-gray-600 mt-1';
                            description.textContent = category.description.length > 60 ?
                                category.description.substring(0, 60) + '...' : category.description;
                            item.appendChild(description);
                        }

                        resultsContainer.appendChild(item);
                    });
                }
            })
            .catch(error => {
                console.error('Search error:', error);
                loadingIndicator.classList.add('hidden');

                const errorMsg = document.createElement('div');
                errorMsg.className = 'p-4 text-center text-red-500';
                errorMsg.textContent = 'An error occurred while searching. Please try again.';
                resultsContainer.appendChild(errorMsg);
            });
    }

    // Event listener for input changes (with debounce)
    searchInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            performSearch(this.value.trim());
        }, debounceDelay);
    });

    // Event listener for search button click
    searchButton.addEventListener('click', function() {
        const query = searchInput.value.trim();
        if (query) {
            window.location.href = `/search?query=${encodeURIComponent(query)}`;
        }
    });

    // Event listener for Enter key press
    searchInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            const query = this.value.trim();
            if (query) {
                window.location.href = `/search?query=${encodeURIComponent(query)}`;
            }
        }
    });

    // Event listeners for trending search terms
    trendingSearches.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const term = this.textContent.trim();
            searchInput.value = term;
            performSearch(term);
        });
    });

    // Close search results when clicking outside
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
            searchResults.classList.add('hidden');
        }
    });

    // Focus on search input shows results if we have a query
    searchInput.addEventListener('focus', function() {
        if (this.value.trim().length >= 2) {
            performSearch(this.value.trim());
        }
    });
});
</script>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Multi-step form
    const step1 = document.getElementById('step1');
    const step2 = document.getElementById('step2');
    const step3 = document.getElementById('step3');

    const step1Next = document.getElementById('step1Next');
    const step2Prev = document.getElementById('step2Prev');
    const step2Next = document.getElementById('step2Next');
    const step3Prev = document.getElementById('step3Prev');

    const step1Indicator = document.getElementById('step1Indicator');
    const step2Indicator = document.getElementById('step2Indicator');
    const step3Indicator = document.getElementById('step3Indicator');

    const progressBar = document.getElementById('progressBar');

    const propertyTypeRadios = document.querySelectorAll('input[name="property_type"]');
    const domainField = document.getElementById('domain_field');
    const cityField = document.getElementById('city_field');
    const zipCodeField = document.getElementById('zip_code_field');

    const categorySelect = document.getElementById('category-select');
    const existingCategoryContainer = document.getElementById('existing-category-container');
    const newCategoryContainer = document.getElementById('new-category-container');

    const subcategorySelect = document.getElementById('subcategory-select');
    const existingSubcategoryContainer = document.getElementById('existing-subcategory-container');
    const newSubcategoryContainer = document.getElementById('new-subcategory-container');

    const existingCategory = document.getElementById('existing_category');
    const existingSubcategory = document.getElementById('existing_subcategory');

    const countrySelect = document.getElementById('country');
    const otherCountryField = document.getElementById('other_country_field');

    const businessNameInput = document.getElementById('business_name');
    const businessEmailInput = document.getElementById('business_email');
    const cityInput = document.getElementById('city');
    const newCategoryInput = document.getElementById('new_category');

    // Toggle between physical and web business
    propertyTypeRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            step1Next.disabled = false;

            if (this.value === 'Web') {
                domainField.classList.remove('hidden');
                cityField.classList.add('hidden');
                zipCodeField.classList.add('hidden');
            } else {
                domainField.classList.add('hidden');
                cityField.classList.remove('hidden');
                zipCodeField.classList.remove('hidden');
            }
        });
    });

    // Toggle category type
    categorySelect.addEventListener('change', function() {
        if (this.value === 'existing') {
            existingCategoryContainer.classList.remove('hidden');
            newCategoryContainer.classList.add('hidden');
        } else {
            existingCategoryContainer.classList.add('hidden');
            newCategoryContainer.classList.remove('hidden');
        }
    });

    // Toggle subcategory type
    subcategorySelect.addEventListener('change', function() {
        if (this.value === 'existing') {
            existingSubcategoryContainer.classList.remove('hidden');
            newSubcategoryContainer.classList.add('hidden');
        } else {
            existingSubcategoryContainer.classList.add('hidden');
            newSubcategoryContainer.classList.remove('hidden');
        }
    });

    // Load subcategories when category changes
    existingCategory.addEventListener('change', function() {
        const categoryId = this.value;

        if (categoryId) {
            existingSubcategory.disabled = true;
            existingSubcategory.innerHTML = '<option value="">Loading...</option>';

            fetch(`/api/subcategories/${categoryId}`)
                .then(response => response.json())
                .then(data => {
                    existingSubcategory.innerHTML = '<option value="">Select Subcategory</option>';

                    data.forEach(subcategory => {
                        const option = document.createElement('option');
                        option.value = subcategory.id;
                        option.textContent = subcategory.name;
                        existingSubcategory.appendChild(option);
                    });

                    existingSubcategory.disabled = false;
                })
                .catch(error => {
                    console.error('Error loading subcategories:', error);
                    existingSubcategory.innerHTML = '<option value="">Error loading subcategories</option>';
                    existingSubcategory.disabled = false;
                });
        } else {
            existingSubcategory.innerHTML = '<option value="">First select a category</option>';
            existingSubcategory.disabled = true;
        }
    });

    // Toggle other country field
    countrySelect.addEventListener('change', function() {
        if (this.value === 'Other') {
            otherCountryField.classList.remove('hidden');
        } else {
            otherCountryField.classList.add('hidden');
        }
    });

    // Step navigation
    step1Next.addEventListener('click', function() {
        step1.classList.add('hidden');
        step2.classList.remove('hidden');

        step1Indicator.classList.remove('text-blue-600');
        step1Indicator.querySelector('div').classList.remove('bg-blue-600');
        step1Indicator.querySelector('div').classList.add('bg-green-600');
        step1Indicator.querySelector('span').classList.add('text-green-600');

        step2Indicator.classList.remove('opacity-50');
        step2Indicator.querySelector('div').classList.remove('bg-gray-300');
        step2Indicator.querySelector('div').classList.add('bg-blue-600');
        step2Indicator.querySelector('div').classList.add('text-white');
        step2Indicator.querySelector('span').classList.add('text-blue-600');

        progressBar.classList.remove('w-1/4');
        progressBar.classList.add('w-2/4');
    });

    // Update step 3 next button to go to step 4
    step3Next.addEventListener('click', function() {
        step3.classList.add('hidden');
        step4.classList.remove('hidden');

        step3Indicator.classList.remove('text-blue-600');
        step3Indicator.querySelector('div').classList.remove('bg-blue-600');
        step3Indicator.querySelector('div').classList.add('bg-green-600');
        step3Indicator.querySelector('span').classList.add('text-green-600');

        step4Indicator.classList.remove('opacity-50');
        step4Indicator.querySelector('div').classList.remove('bg-gray-300');
        step4Indicator.querySelector('div').classList.add('bg-blue-600');
        step4Indicator.querySelector('div').classList.add('text-white');
        step4Indicator.querySelector('span').classList.add('text-blue-600');

        progressBar.classList.remove('w-2/4');
        progressBar.classList.add('w-3/4');

        // Prepare subcategory options based on selected category
        prepareSubcategoryStep();
    });

    step2Prev.addEventListener('click', function() {
        step2.classList.add('hidden');
        step1.classList.remove('hidden');

        step1Indicator.classList.add('text-blue-600');
        step1Indicator.querySelector('div').classList.remove('bg-green-600');
        step1Indicator.querySelector('div').classList.add('bg-blue-600');

        step2Indicator.classList.add('opacity-50');
        step2Indicator.querySelector('div').classList.add('bg-gray-300');
        step2Indicator.querySelector('div').classList.remove('bg-blue-600');
        step2Indicator.querySelector('span').classList.remove('text-blue-600');

        progressBar.classList.remove('w-2/4');
        progressBar.classList.add('w-1/4');
    });

    step3Prev.addEventListener('click', function() {
        step3.classList.add('hidden');
        step2.classList.remove('hidden');

        step2Indicator.classList.add('text-blue-600');
        step2Indicator.querySelector('div').classList.remove('bg-green-600');
        step2Indicator.querySelector('div').classList.add('bg-blue-600');
        step2Indicator.querySelector('span').classList.remove('text-green-600');

        step3Indicator.classList.add('opacity-50');
        step3Indicator.querySelector('div').classList.add('bg-gray-300');
        step3Indicator.querySelector('div').classList.remove('bg-blue-600');
        step3Indicator.querySelector('span').classList.remove('text-blue-600');

        progressBar.classList.remove('w-3/4');
        progressBar.classList.add('w-2/4');
    });

    // Form validation
    // Step 2 validation
    function validateStep2() {
        let isValid = true;

        // Business name
        if (!businessNameInput.value.trim()) {
            showError(businessNameInput, 'Business name is required');
            isValid = false;
        } else {
            hideError(businessNameInput);
        }

        // Business email
        if (!businessEmailInput.value.trim()) {
            showError(businessEmailInput, 'Business email is required');
            isValid = false;
        } else if (!isValidEmail(businessEmailInput.value.trim())) {
            showError(businessEmailInput, 'Please enter a valid email address');
            isValid = false;
        } else {
            hideError(businessEmailInput);
        }

        // City (only required for physical businesses)
        const propertyType = document.querySelector('input[name="property_type"]:checked').value;
        if (propertyType === 'Physical' && !cityInput.value.trim()) {
            showError(cityInput, 'City is required for physical businesses');
            isValid = false;
        } else {
            hideError(cityInput);
        }

        // Country
        if (!countrySelect.value) {
            showError(countrySelect, 'Country is required');
            isValid = false;
        } else {
            hideError(countrySelect);
        }

        // Domain (only required for web businesses)
        const domainInput = document.getElementById('domain');
        if (propertyType === 'Web' && !domainInput.value.trim()) {
            showError(domainInput, 'Domain is required for web businesses');
            isValid = false;
        } else {
            hideError(domainInput);
        }

        step2Next.disabled = !isValid;
        return isValid;
    }

    function showError(element, message) {
        const errorDiv = element.nextElementSibling;
        errorDiv.textContent = message;
        errorDiv.classList.remove('hidden');
        element.classList.add('border-red-500');
    }

    function hideError(element) {
        const errorDiv = element.nextElementSibling;
        errorDiv.textContent = '';
        errorDiv.classList.add('hidden');
        element.classList.remove('border-red-500');
    }

    function isValidEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    // Add input event listeners for step 2 validation
    businessNameInput.addEventListener('input', validateStep2);
    businessEmailInput.addEventListener('input', validateStep2);
    cityInput.addEventListener('input', validateStep2);
    countrySelect.addEventListener('change', validateStep2);

    if (document.getElementById('domain')) {
        document.getElementById('domain').addEventListener('input', validateStep2);
    }

    // Form submission handling
    const addPropertyForm = document.getElementById('addPropertyForm');
    const formSuccess = document.getElementById('formSuccess');
    const viewBusinessLink = document.getElementById('viewBusinessLink');

    addPropertyForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const propertyType = formData.get('property_type');

        // Handle category and subcategory logic
        if (formData.get('category_type') === 'existing') {
            formData.set('category', formData.get('existing_category'));
        } else {
            formData.set('category', formData.get('new_category'));
        }

        if (formData.get('subcategory_type') === 'existing') {
            formData.set('subcategory', formData.get('existing_subcategory'));
        } else if (formData.get('category_type') === 'new') {
            formData.set('subcategory', formData.get('new_category_subcategory'));
        } else {
            formData.set('subcategory', formData.get('new_subcategory'));
        }

        // Handle country logic
        if (formData.get('country') === 'Other') {
            formData.set('country', formData.get('other_country'));
        }

        // Set defaults for non-physical businesses
        if (propertyType === 'Web') {
            formData.set('city', 'Online');
            formData.set('zip_code', '00000');
        }

        // Set default status
        formData.set('status', 'Not Approved');

        // Submit form via AJAX
        fetch('/properties', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (!response.ok) {
                throw response;
            }
            return response.json();
        })
        .then(data => {
            // Handle success
            addPropertyForm.classList.add('hidden');
            formSuccess.classList.remove('hidden');

            // Set view link
            if (data.property && data.property.id) {
                viewBusinessLink.href = `/property/${data.property.id}`;
            }

            // Scroll to success message
            formSuccess.scrollIntoView({ behavior: 'smooth' });
        })
        .catch(error => {
            if (error.json) {
                error.json().then(errorData => {
                    // Show validation errors
                    if (errorData.errors) {
                        Object.keys(errorData.errors).forEach(field => {
                            const input = document.getElementById(field);
                            if (input) {
                                showError(input, errorData.errors[field][0]);
                            }
                        });
                    } else {
                        alert('An error occurred while submitting the form. Please try again.');
                    }
                });
            } else {
                alert('An error occurred while submitting the form. Please try again.');
            }
        });
    });
});
</script>

<!-- Add this script to fix the Continue button issue and update the category/subcategory selection -->
@push('scripts')
<script>
// Fix for step 2 to step 3 navigation
document.addEventListener('DOMContentLoaded', function() {
    // Get the step2Next button
    const step2Next = document.getElementById('step2Next');

    // Enable step2Next button immediately when fields have content
    function validateStep2() {
        const businessName = document.getElementById('business_name').value.trim();
        const businessEmail = document.getElementById('business_email').value.trim();
        const countrySelected = document.getElementById('country').value !== '';

        // Property type specific validations
        const propertyType = document.querySelector('input[name="property_type"]:checked')?.value;
        let specificFieldValid = true;

        if (propertyType === 'Physical') {
            const cityField = document.getElementById('city');
            if (cityField && !cityField.value.trim()) {
                specificFieldValid = false;
            }
        } else if (propertyType === 'Web') {
            const domainField = document.getElementById('domain');
            if (domainField && !domainField.value.trim()) {
                specificFieldValid = false;
            }
        }

        // Basic validation - enable button if we have business name, email, and country
        const isValid = businessName && businessEmail && countrySelected && specificFieldValid;

        // Enable or disable the button based on our validation
        if (step2Next) {
            step2Next.disabled = !isValid;

            // Visual feedback
            if (isValid) {
                step2Next.classList.remove('bg-blue-300');
                step2Next.classList.add('bg-blue-600');
            } else {
                step2Next.classList.add('bg-blue-300');
                step2Next.classList.remove('bg-blue-600');
            }
        }
    }

    // Add event listeners to all form fields in step 2
    const step2Fields = [
        'business_name',
        'business_email',
        'city',
        'domain',
        'country'
    ];

    step2Fields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field) {
            field.addEventListener('input', validateStep2);
            field.addEventListener('change', validateStep2);
        }
    });

    // Run validation on page load to properly set the button state
    validateStep2();

    // Alternative fix: Force enable the button
    // (use this only if the above solution doesn't work)
    if (step2Next) {
        step2Next.disabled = false;

        // Add a direct click handler that will bypass the disabled state
        step2Next.addEventListener('click', function() {
            const step2 = document.getElementById('step2');
            const step3 = document.getElementById('step3');

            if (step2 && step3) {
                step2.classList.add('hidden');
                step3.classList.remove('hidden');

                // Update the step indicators
                const step2Indicator = document.getElementById('step2Indicator');
                const step3Indicator = document.getElementById('step3Indicator');

                if (step2Indicator && step3Indicator) {
                    // Update step 2 indicator to completed
                    step2Indicator.classList.remove('text-blue-600');
                    step2Indicator.querySelector('div').classList.remove('bg-blue-600');
                    step2Indicator.querySelector('div').classList.add('bg-green-600');
                    step2Indicator.querySelector('span').classList.add('text-green-600');

                    // Update step 3 indicator to active
                    step3Indicator.classList.remove('opacity-50');
                    step3Indicator.querySelector('div').classList.remove('bg-gray-300');
                    step3Indicator.querySelector('div').classList.add('bg-blue-600');
                    step3Indicator.querySelector('div').classList.add('text-white');
                    step3Indicator.querySelector('span').classList.add('text-blue-600');
                }

                // Update progress bar
                const progressBar = document.getElementById('progressBar');
                if (progressBar) {
                    progressBar.classList.remove('w-2/4');
                    progressBar.classList.add('w-3/4');
                }
            }
        });
    }
});
</script>

<!-- FINAL FIX - TWO-STEP APPROACH FOR CATEGORIES AND SUBCATEGORIES -->
@push('scripts')
<script>
(function() {
    console.log('*** TWO-STEP CATEGORY/SUBCATEGORY SELECTION FIX APPLIED ***');

    // Get references to the key elements
    const categorySelect = document.getElementById('existing_category');
    const subcategorySelect = document.getElementById('existing_subcategory');
    const subcategoryContainer = document.getElementById('existing-subcategory-container');
    const newSubcategoryContainer = document.getElementById('new-subcategory-container');
    const subcategoryTypeSelect = document.getElementById('subcategory-select');

    // Early exit if elements don't exist
    if (!categorySelect || !subcategorySelect) {
        console.error('Required elements not found');
        return;
    }

    // Step 1: Create a completely new category select element to avoid event conflicts
    const newCategorySelect = document.createElement('select');
    newCategorySelect.id = categorySelect.id;
    newCategorySelect.name = categorySelect.name;
    newCategorySelect.className = categorySelect.className;

    // Copy all options from original select
    Array.from(categorySelect.options).forEach(option => {
        newCategorySelect.add(new Option(option.text, option.value));
    });

    // Replace the original select with our new one
    categorySelect.parentNode.replaceChild(newCategorySelect, categorySelect);

    // Step 2: Add a focused event handler for category selection
    newCategorySelect.addEventListener('change', function() {
        const categoryId = this.value;
        console.log('Category changed to:', categoryId);

        // Update subcategory input placeholder if it exists
        const newSubcategoryInput = document.getElementById('new_subcategory');
        if (newSubcategoryInput && this.selectedIndex > 0) {
            const categoryName = this.options[this.selectedIndex].text;
            newSubcategoryInput.placeholder = `Enter subcategory related to ${categoryName}...`;
        }

        // Reset subcategory dropdown
        subcategorySelect.innerHTML = '<option value="">Loading subcategories...</option>';
        subcategorySelect.disabled = true;

        if (!categoryId || categoryId === '') {
            subcategorySelect.innerHTML = '<option value="">First select a category</option>';
            return;
        }

        // Step 3: Fetch subcategories using the web route
        console.log('Fetching subcategories for category ID:', categoryId);
        fetch(`/subcategories/${categoryId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Subcategories loaded:', data);

                // Clear and repopulate dropdown
                subcategorySelect.innerHTML = '<option value="">Select Subcategory</option>';

                if (Array.isArray(data) && data.length > 0) {
                    // We have subcategories - populate the dropdown
                    data.forEach(sub => {
                        const option = document.createElement('option');
                        option.value = sub.id;
                        option.textContent = sub.name;
                        subcategorySelect.appendChild(option);
                    });

                    // Enable the dropdown
                    subcategorySelect.disabled = false;

                    // Show existing subcategories section
                    subcategoryTypeSelect.value = 'existing';
                    subcategoryContainer.classList.remove('hidden');
                    newSubcategoryContainer.classList.add('hidden');
                } else {
                    // No subcategories found - switch to "add new"
                    subcategorySelect.innerHTML = '<option value="">No subcategories found</option>';
                    subcategoryTypeSelect.value = 'new';
                    subcategoryContainer.classList.add('hidden');
                    newSubcategoryContainer.classList.remove('hidden');
                }
            })
            .catch(error => {
                console.error('Failed to load subcategories:', error);
                subcategorySelect.innerHTML = '<option value="">Error loading subcategories</option>';
                subcategorySelect.disabled = false;
            });
    });

    // Step 4: Make sure subcategory type switcher works properly
    if (subcategoryTypeSelect) {
        subcategoryTypeSelect.addEventListener('change', function() {
            if (this.value === 'existing') {
                subcategoryContainer.classList.remove('hidden');
                newSubcategoryContainer.classList.add('hidden');
            } else {
                subcategoryContainer.classList.add('hidden');
                newSubcategoryContainer.classList.remove('hidden');
            }
        });
    }
})();
</script>
@endpush

@push('scripts')
<script>
// Fix for 4-step display
(function() {
    // Update progress bar to show four steps instead of three
    const progressBar = document.getElementById('progressBar');
    if (progressBar) {
        progressBar.classList.remove('w-1/3');
        progressBar.classList.add('w-1/4');
    }

    // Check if we need to add the fourth step indicator
    if (!document.getElementById('step4Indicator')) {
        const stepIndicatorContainer = document.querySelector('.flex.items-center.justify-between.mb-2');
        if (stepIndicatorContainer) {
            // Clone step 3 indicator to create step 4
            const step3Indicator = document.getElementById('step3Indicator');
            const step4Indicator = step3Indicator.cloneNode(true);

            // Update ID and text
            step4Indicator.id = 'step4Indicator';
            const numberDiv = step4Indicator.querySelector('div');
            if (numberDiv) {
                numberDiv.textContent = '4';
            }

            const textSpan = step4Indicator.querySelector('span');
            if (textSpan) {
                textSpan.textContent = 'Subcategory';
            }

            // Add to container
            stepIndicatorContainer.appendChild(step4Indicator);

            // Update step3 text to be just "Category" instead of "Category & Subcategory"
            const step3Text = step3Indicator.querySelector('span');
            if (step3Text) {
                step3Text.textContent = 'Category';
            }
        }
    }
})();
</script>
@endpush
