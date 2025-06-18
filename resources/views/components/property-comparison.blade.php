<div class="property-comparison-tool" id="propertyComparisonTool">
    <div class="max-w-7xl mx-auto px-6">
        <!-- Section heading with subtitle in the same style as feature.blade.php -->
        <div class="text-center mb-12 mt-12">
            <h2 class="text-3xl font-bold leading-tight text-gray-900 sm:text-4xl xl:text-5xl font-pj">Property Comparison Tool</h2>
            <p class="mt-4 text-base leading-7 text-gray-600 sm:mt-8 font-pj">Compare two properties side by side to make informed decisions</p>
        </div>

        <!-- Property Selection -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Property 1 Selection -->
            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                <h3 class="text-lg font-semibold mb-4">Select First Property</h3>
                <div class="mb-4">
                    <select id="categoryFilter1" class="w-full px-3 py-2 border border-gray-300 rounded-md mb-2">
                        <option value="">All Categories</option>
                    </select>
                    <select id="property1Select" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                        <option value="">Choose a property</option>
                    </select>
                </div>
            </div>

            <!-- Property 2 Selection -->
            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                <h3 class="text-lg font-semibold mb-4">Select Second Property</h3>
                <div class="mb-4">
                    <select id="categoryFilter2" class="w-full px-3 py-2 border border-gray-300 rounded-md mb-2">
                        <option value="">All Categories</option>
                    </select>
                    <select id="property2Select" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                        <option value="">Choose a property</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Compare Button -->
        <div class="text-center mb-8">
            <button id="compareBtn"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg disabled:opacity-50 disabled:cursor-not-allowed"
                    disabled>
                Compare Properties
            </button>
        </div>

        <!-- Loading Spinner -->
        <div id="loadingSpinner" class="hidden text-center py-8">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
            <p class="mt-2 text-gray-600">Loading comparison...</p>
        </div>
    </div>
</div>

<!-- Comparison Results Modal -->
<div id="comparisonModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-6xl w-full max-h-[90vh] overflow-y-auto">
            <!-- Modal Header -->
            <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex justify-between items-center">
                <h2 class="text-2xl font-bold text-gray-900">Property Comparison Results</h2>
                <button id="closeModal" class="text-gray-400 hover:text-gray-600 text-2xl">
                    ×
                </button>
            </div>

            <!-- Modal Content -->
            <div id="modalContent" class="p-6">
                <!-- Comparison Results -->
                <div id="comparisonResults">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Property 1 Details -->
                        <div id="property1Details" class="bg-gray-50 rounded-lg p-6">
                            <!-- Content will be populated by JavaScript -->
                        </div>

                        <!-- Property 2 Details -->
                        <div id="property2Details" class="bg-gray-50 rounded-lg p-6">
                            <!-- Content will be populated by JavaScript -->
                        </div>
                    </div>

                    <!-- Comparison Summary -->
                    <div id="comparisonSummary" class="mt-8 bg-blue-50 rounded-lg p-6">
                        <h3 class="text-xl font-bold mb-4 text-center">Comparison Summary</h3>
                        <div id="summaryContent" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Content will be populated by JavaScript -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.property-comparison-tool {
    font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.star-rating {
    display: inline-flex;
    align-items: center;
}

.star {
    color: #ffd700;
    font-size: 1.2em;
}

.property-image {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 8px;
}

.rating-bar {
    background-color: #e5e7eb;
    height: 8px;
    border-radius: 4px;
    overflow: hidden;
}

.rating-fill {
    background-color: #fbbf24;
    height: 100%;
    transition: width 0.3s ease;
}

.comparison-metric {
    text-align: center;
    padding: 1rem;
    border-radius: 8px;
    background: white;
}

.metric-winner {
    background-color: #dcfce7;
    border: 2px solid #16a34a;
}

.metric-tie {
    background-color: #fef3c7;
    border: 2px solid #d97706;
}
</style>

<script>
class PropertyComparison {
    constructor() {
        this.properties = [];
        this.categories = [];
        this.selectedProperty1 = null;
        this.selectedProperty2 = null;
        this.init();
    }

    async init() {
        console.log('Initializing PropertyComparison...');
        await this.loadCategories();
        await this.loadProperties();
        this.setupEventListeners();
    }

    async loadCategories() {
        console.log('Loading categories...');
        try {
            const response = await fetch('/api/property-comparison/categories');
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            this.categories = await response.json();
            console.log('Categories loaded:', this.categories);
            this.populateCategoryFilters();
        } catch (error) {
            console.error('Error loading categories:', error);
        }
    }    populateCategoryFilters() {
        console.log('Populating category filters...', this.categories);
        const categoryFilter1 = document.getElementById('categoryFilter1');
        const categoryFilter2 = document.getElementById('categoryFilter2');

        if (!categoryFilter1 || !categoryFilter2) {
            console.error('Category filter elements not found');
            return;
        }

        [categoryFilter1, categoryFilter2].forEach(filter => {
            // Clear existing options except the first one
            filter.innerHTML = '<option value="">All Categories</option>';
            // The categories response is an object with id as key and name as value
            for (const [id, name] of Object.entries(this.categories)) {
                const option = document.createElement('option');
                option.value = name; // Use category name as value for filtering
                option.textContent = name;
                filter.appendChild(option);
            }
        });
        console.log('Category filters populated successfully');
    }

    async loadProperties(category = '', selectId = '') {
        console.log('Loading properties...', { category, selectId });
        try {
            const params = new URLSearchParams();
            if (category) params.append('category', category);

            const response = await fetch(`/api/property-comparison/properties?${params}`);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const properties = await response.json();

            console.log('Properties loaded:', properties);

            if (selectId) {
                this.populatePropertySelect(selectId, properties);
            } else {
                this.populatePropertySelect('property1Select', properties);
                this.populatePropertySelect('property2Select', properties);
            }
        } catch (error) {
            console.error('Error loading properties:', error);
        }
    }

    populatePropertySelect(selectId, properties) {
        console.log('Populating property select:', selectId, properties);
        const select = document.getElementById(selectId);

        if (!select) {
            console.error('Select element not found:', selectId);
            return;
        }

        select.innerHTML = '<option value="">Choose a property</option>';

        if (properties.length === 0) {
            const option = document.createElement('option');
            option.value = '';
            option.textContent = 'No properties found';
            option.disabled = true;
            select.appendChild(option);
            return;
        }

        properties.forEach(property => {
            const option = document.createElement('option');
            option.value = property.id;
            option.textContent = `${property.business_name} (${property.city}, ${property.country})`;
            select.appendChild(option);
        });
    }

    setupEventListeners() {
        console.log('Setting up event listeners...');

        // Category filters
        const categoryFilter1 = document.getElementById('categoryFilter1');
        const categoryFilter2 = document.getElementById('categoryFilter2');

        if (categoryFilter1) {
            categoryFilter1.addEventListener('change', (e) => {
                console.log('Category 1 changed:', e.target.value);
                this.loadProperties(e.target.value, 'property1Select');
            });
        }

        if (categoryFilter2) {
            categoryFilter2.addEventListener('change', (e) => {
                console.log('Category 2 changed:', e.target.value);
                this.loadProperties(e.target.value, 'property2Select');
            });
        }

        // Property selections
        const property1Select = document.getElementById('property1Select');
        const property2Select = document.getElementById('property2Select');

        if (property1Select) {
            property1Select.addEventListener('change', (e) => {
                console.log('Property 1 selected:', e.target.value);
                this.selectedProperty1 = e.target.value;
                this.toggleCompareButton();
            });
        }

        if (property2Select) {
            property2Select.addEventListener('change', (e) => {
                console.log('Property 2 selected:', e.target.value);
                this.selectedProperty2 = e.target.value;
                this.toggleCompareButton();
            });
        }

        // Compare button
        const compareBtn = document.getElementById('compareBtn');
        if (compareBtn) {
            compareBtn.addEventListener('click', () => {
                console.log('Compare button clicked');
                this.compareProperties();
            });
        }

        // Close modal button
        const closeModal = document.getElementById('closeModal');
        if (closeModal) {
            closeModal.addEventListener('click', () => {
                this.hideResults();
            });
        }

        // Close modal when clicking outside
        const modal = document.getElementById('comparisonModal');
        if (modal) {
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    this.hideResults();
                }
            });
        }
    }

    toggleCompareButton() {
        const compareBtn = document.getElementById('compareBtn');
        if (!compareBtn) return;

        const canCompare = this.selectedProperty1 && this.selectedProperty2 &&
                          this.selectedProperty1 !== this.selectedProperty2;
        compareBtn.disabled = !canCompare;
        console.log('Compare button enabled:', canCompare);
    }

    async compareProperties() {
        if (!this.selectedProperty1 || !this.selectedProperty2) return;

        this.showLoading(true);
        this.hideResults();

        try {
            const response = await fetch('/api/property-comparison/compare', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify({
                    property1_id: this.selectedProperty1,
                    property2_id: this.selectedProperty2
                })
            });

            const data = await response.json();

            if (response.ok) {
                this.displayComparison(data.property1, data.property2);
            } else {
                alert('Error: ' + (data.error || 'Failed to compare properties'));
            }
        } catch (error) {
            console.error('Error comparing properties:', error);
            alert('An error occurred while comparing properties');
        } finally {
            this.showLoading(false);
        }
    }

    displayComparison(property1, property2) {
        this.displayPropertyDetails('property1Details', property1);
        this.displayPropertyDetails('property2Details', property2);
        this.displayComparisonSummary(property1, property2);
        this.showResults();
    }

    displayPropertyDetails(containerId, property) {
        const container = document.getElementById(containerId);
        if (!container) return;

        const stats = property.review_stats;
        const avgRating = parseFloat(stats.average_rating || 0);

        container.innerHTML = `
            <div class="text-center mb-4">
                ${property.profile_picture ?
                    `<img src="/storage/${property.profile_picture}" alt="${property.business_name}" class="property-image mx-auto mb-3">` :
                    '<div class="property-image mx-auto mb-3 bg-gray-200 flex items-center justify-center"><i class="fas fa-building text-gray-400"></i></div>'
                }
                <h3 class="text-xl font-bold">${property.business_name}</h3>
                <p class="text-gray-600">${property.category_name || property.category} • ${property.city}, ${property.country}</p>
            </div>

            <div class="mb-4 p-4 bg-white rounded-lg">
                <h4 class="font-semibold mb-2">Rating Overview</h4>
                <div class="text-center mb-3">
                    <div class="text-3xl font-bold text-yellow-500">${avgRating.toFixed(1)}</div>
                    <div class="star-rating mb-1">
                        ${this.generateStars(avgRating)}
                    </div>
                    <div class="text-sm text-gray-600">${stats.total_reviews} reviews</div>
                </div>

                <div class="space-y-2">
                    ${[5,4,3,2,1].map(star => `
                        <div class="flex items-center text-sm">
                            <span class="w-3">${star}</span>
                            <span class="star">★</span>
                            <div class="rating-bar flex-1 mx-2">
                                <div class="rating-fill" style="width: ${this.calculatePercentage(stats[this.getStarProperty(star)], stats.total_reviews)}%"></div>
                            </div>
                            <span class="w-8 text-right">${stats[this.getStarProperty(star)] || 0}</span>
                        </div>
                    `).join('')}
                </div>
            </div>

            <div class="mb-4 p-4 bg-white rounded-lg">
                <h4 class="font-semibold mb-2">Business Details</h4>
                <div class="space-y-2 text-sm">
                    ${property.subcategory_name ? `<p><strong>Subcategory:</strong> ${property.subcategory_name}</p>` : ''}
                    ${property.domain ? `<p><strong>Domain:</strong> ${property.domain}</p>` : ''}
                    ${property.business_email ? `<p><strong>Email:</strong> ${property.business_email}</p>` : ''}
                    ${property.property_type ? `<p><strong>Property Type:</strong> ${property.property_type}</p>` : ''}
                    ${property.annual_revenue ? `<p><strong>Annual Revenue:</strong> ${property.annual_revenue}</p>` : ''}
                    ${property.employee_count ? `<p><strong>Employee Count:</strong> ${property.employee_count}</p>` : ''}
                </div>
            </div>

            ${property.latest_reviews && property.latest_reviews.length > 0 ? `
                <div class="p-4 bg-white rounded-lg">
                    <h4 class="font-semibold mb-2">Recent Reviews</h4>
                    <div class="space-y-3">
                        ${property.latest_reviews.map(review => `
                            <div class="border-l-4 border-blue-200 pl-3">
                                <div class="flex items-center mb-1">
                                    <div class="star-rating mr-2">
                                        ${this.generateStars(review.rate)}
                                    </div>
                                    <span class="text-sm text-gray-600">${review.user ? review.user.name : 'Anonymous'}</span>
                                </div>
                                <p class="text-sm text-gray-700">${review.review ? review.review.substring(0, 100) + (review.review.length > 100 ? '...' : '') : 'No comment'}</p>
                            </div>
                        `).join('')}
                    </div>
                </div>
            ` : ''}
        `;
    }

    displayComparisonSummary(property1, property2) {
        const container = document.getElementById('summaryContent');
        if (!container) return;

        const stats1 = property1.review_stats;
        const stats2 = property2.review_stats;

        const avgRating1 = parseFloat(stats1.average_rating || 0);
        const avgRating2 = parseFloat(stats2.average_rating || 0);
        const totalReviews1 = parseInt(stats1.total_reviews || 0);
        const totalReviews2 = parseInt(stats2.total_reviews || 0);

        const ratingWinner = avgRating1 > avgRating2 ? 'property1' : avgRating2 > avgRating1 ? 'property2' : 'tie';
        const reviewCountWinner = totalReviews1 > totalReviews2 ? 'property1' : totalReviews2 > totalReviews1 ? 'property2' : 'tie';

        container.innerHTML = `
            <div class="comparison-metric ${ratingWinner === 'property1' ? 'metric-winner' : ratingWinner === 'tie' ? 'metric-tie' : ''}">
                <h4 class="font-semibold">Average Rating</h4>
                <div class="text-2xl font-bold text-yellow-500">${avgRating1.toFixed(1)}</div>
                <div class="text-sm text-gray-600">${property1.business_name}</div>
            </div>

            <div class="comparison-metric ${reviewCountWinner === 'property1' ? 'metric-winner' : reviewCountWinner === 'tie' ? 'metric-tie' : ''}">
                <h4 class="font-semibold">Total Reviews</h4>
                <div class="text-2xl font-bold text-blue-600">${totalReviews1}</div>
                <div class="text-sm text-gray-600">${property1.business_name}</div>
            </div>

            <div class="comparison-metric">
                <h4 class="font-semibold">Categories</h4>
                <div class="text-sm">
                    <div class="mb-1"><strong>${property1.business_name}:</strong> ${property1.category_name || property1.category}</div>
                    <div><strong>${property2.business_name}:</strong> ${property2.category_name || property2.category}</div>
                </div>
            </div>

            <div class="comparison-metric ${ratingWinner === 'property2' ? 'metric-winner' : ratingWinner === 'tie' ? 'metric-tie' : ''}">
                <h4 class="font-semibold">Average Rating</h4>
                <div class="text-2xl font-bold text-yellow-500">${avgRating2.toFixed(1)}</div>
                <div class="text-sm text-gray-600">${property2.business_name}</div>
            </div>

            <div class="comparison-metric ${reviewCountWinner === 'property2' ? 'metric-winner' : reviewCountWinner === 'tie' ? 'metric-tie' : ''}">
                <h4 class="font-semibold">Total Reviews</h4>
                <div class="text-2xl font-bold text-blue-600">${totalReviews2}</div>
                <div class="text-sm text-gray-600">${property2.business_name}</div>
            </div>

            <div class="comparison-metric">
                <h4 class="font-semibold">Locations</h4>
                <div class="text-sm">
                    <div class="mb-1"><strong>${property1.business_name}:</strong> ${property1.city}, ${property1.country}</div>
                    <div><strong>${property2.business_name}:</strong> ${property2.city}, ${property2.country}</div>
                </div>
            </div>
        `;
    }

    generateStars(rating) {
        const fullStars = Math.floor(rating);
        const hasHalfStar = rating % 1 >= 0.5;
        const emptyStars = 5 - fullStars - (hasHalfStar ? 1 : 0);

        return '★'.repeat(fullStars) +
               (hasHalfStar ? '☆' : '') +
               '☆'.repeat(emptyStars);
    }

    getStarProperty(star) {
        const map = { 5: 'five_star', 4: 'four_star', 3: 'three_star', 2: 'two_star', 1: 'one_star' };
        return map[star];
    }

    calculatePercentage(value, total) {
        return total > 0 ? (value / total) * 100 : 0;
    }

    showLoading(show) {
        const spinner = document.getElementById('loadingSpinner');
        if (spinner) {
            spinner.classList.toggle('hidden', !show);
        }
    }

    showResults() {
        const modal = document.getElementById('comparisonModal');
        if (modal) {
            modal.classList.remove('hidden');
            // Prevent body scroll when modal is open
            document.body.style.overflow = 'hidden';
        }
    }

    hideResults() {
        const modal = document.getElementById('comparisonModal');
        if (modal) {
            modal.classList.add('hidden');
            // Restore body scroll when modal is closed
            document.body.style.overflow = '';
        }
    }
}

// Initialize the comparison tool when the DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, checking for propertyComparisonTool...');
    if (document.getElementById('propertyComparisonTool')) {
        console.log('Found propertyComparisonTool, initializing...');
        new PropertyComparison();
    } else {
        console.log('propertyComparisonTool not found');
    }
});
</script>

