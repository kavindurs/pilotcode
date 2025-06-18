<section class="w-full relative py-8 sm:py-12 lg:py-16 xl:py-24 bg-blue-800 overflow-hidden">
    <!-- Background circle patterns -->
    <div class="absolute top-0 right-0 w-96 h-96 bg-blue-500 rounded-full opacity-30 transform translate-x-1/3 -translate-y-1/4"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 bg-blue-500 rounded-full opacity-20 transform -translate-x-1/3 translate-y-1/4"></div>

    <!-- Content container with max width -->
    <div class="relative px-4 mx-auto sm:px-6 lg:px-8 max-w-7xl">
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 lg:gap-12 items-start lg:items-center">
            <!-- Left Column: Text Content (spans 3 columns) -->
            <div class="lg:col-span-3 max-w-2xl">
                <!-- Bold headline with all caps for impact -->
                <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-extrabold leading-tight tracking-tight text-white uppercase">
                    HELPING USERS<br class="hidden md:block">
                    FIND <span class="text-yellow-300">AUTHENTIC</span><br class="hidden md:block">
                    REVIEWS!
                </h1>

                <!-- Subheading -->
                <p class="mt-4 sm:mt-6 text-lg sm:text-xl leading-relaxed text-white">
                    Scoreness is a trusted rating and review platform where users evaluate and compare both physical businesses and online services.
                </p>

                <!-- Search Bar with Autocomplete -->
                <div class="mt-6 sm:mt-8">
                    <div class="relative w-full max-w-lg">
                        <input
                            type="text"
                            id="search-input"
                            placeholder="Search for businesses or categories..."
                            class="w-full py-3 sm:py-4 px-4 sm:px-5 pl-10 sm:pl-12 text-sm sm:text-base text-black placeholder-gray-500 bg-white border-0 rounded-lg shadow-lg focus:outline-none focus:ring-2 focus:ring-white"
                        />
                        <div class="absolute left-3 sm:left-4 top-1/2 transform -translate-y-1/2 text-blue-600">
                            <i class="fas fa-search text-base sm:text-lg"></i>
                        </div>
                        <button
                            id="search-button"
                            type="button"
                            class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-blue-600 text-white px-3 py-1 sm:px-4 sm:py-2 rounded-md text-sm hover:bg-blue-700 transition-colors"
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

                    <div class="mt-2 flex flex-wrap gap-2 text-xs text-blue-100">
                        <span>Trending:</span>
                        <a href="#" class="hover:text-white trending-search">Restaurants</a>
                        <a href="#" class="hover:text-white trending-search">Hotels</a>
                        <a href="#" class="hover:text-white trending-search">Online Shops</a>
                    </div>
                </div>

                <!-- Widget: Next Step Cheatsheet - Improved spacing on mobile -->
                <div class="mt-6 sm:mt-8 flex flex-col sm:flex-row items-start gap-4 sm:gap-6">
                    <!-- Widget Image - Smaller on mobile -->
                    <div class="flex-shrink-0 w-24 sm:w-32 md:w-36">
                        <img
                            src="http://cima.wuaze.com/wp-content/uploads/2022/07/widget-12.png"
                            alt="Free Cheatsheet"
                            class="w-full h-auto drop-shadow-lg"
                        />
                    </div>

                    <!-- Widget Content - Better spacing on mobile -->
                    <div>
                        <h3 class="text-lg sm:text-xl font-bold text-white mb-1 sm:mb-2">Free Business Review Guide</h3>
                        <p class="text-xs sm:text-sm text-blue-100 mb-3 sm:mb-4">
                            Discover how to evaluate businesses effectively in 5 minutes. Perfect for consumers and business owners alike!
                        </p>

                        <!-- CTA Button - More compact on mobile -->
                        <a
                            href="#"
                            class="inline-flex items-center justify-center px-4 sm:px-6 py-2 sm:py-3 text-sm sm:text-base font-bold text-white transition-all duration-200 bg-orange-500 border-0 rounded-md hover:bg-orange-600 shadow-lg"
                        >
                            REVIEW NOW
                        </a>
                    </div>
                </div>
            </div>

            <!-- Right Column: Image (spans 2 columns) - Hidden on mobile screens -->
            <div class="hidden sm:block mt-8 lg:mt-0 lg:col-span-2 relative">
                <div class="relative">
                    <img
                        class="w-full h-auto rounded-lg"
                        src="{{ asset('images/hero-person.png') }}"
                        alt="Business Expert"
                        onerror="this.onerror=null; this.src='http://cima.wuaze.com/wp-content/uploads/2022/11/Image-2.png';"
                    />
                </div>
            </div>
        </div>

        <!-- Stats Section - Adjusted for mobile -->
        <div class="mt-10 sm:mt-16 grid grid-cols-2 md:grid-cols-4 gap-2 sm:gap-4 text-center border-t border-blue-500 pt-6 sm:pt-8">
            <div class="p-2 sm:p-4">
                <p class="text-2xl sm:text-3xl font-bold text-white">7M+</p>
                <p class="text-xs sm:text-sm text-blue-200">Reviews Submitted</p>
            </div>
            <div class="p-2 sm:p-4">
                <p class="text-2xl sm:text-3xl font-bold text-white">10K+</p>
                <p class="text-xs sm:text-sm text-blue-200">Businesses Listed</p>
            </div>
            <div class="p-2 sm:p-4">
                <p class="text-2xl sm:text-3xl font-bold text-white">75K+</p>
                <p class="text-xs sm:text-sm text-blue-200">Active Users</p>
            </div>
            <div class="p-2 sm:p-4">
                <p class="text-2xl sm:text-3xl font-bold text-white">125K+</p>
                <p class="text-xs sm:text-sm text-blue-200">Monthly Visits</p>
            </div>
        </div>
    </div>
</section>

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
