<div class="property-comparison-tool" id="propertyComparisonTool">
    <div class="max-w-7xl mx-auto px-6">
        <!-- Debug Status -->
        <div id="debugStatus" class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4">
            <strong>Status:</strong> <span id="statusText">Initializing...</span>
            <button onclick="window.testCategories()" class="ml-4 bg-blue-500 text-white px-3 py-1 rounded text-sm">Test Categories</button>
            <button onclick="window.showDebugInfo()" class="ml-2 bg-green-500 text-white px-3 py-1 rounded text-sm">Debug Info</button>
        </div>

        <!-- Section heading with subtitle in the same style as feature.blade.php -->
        <div class="text-center mb-12 mt-12">
            <h2 class="text-3xl font-bold leading-tight text-gray-900 sm:text-4xl xl:text-5xl font-pj">Property Comparison Tool</h2>
            <p class="mt-4 text-base leading-7 text-gray-600 sm:mt-8 font-pj">Compare two properties side by side to make informed decisions</p>
        </div>

        <!-- Property Selection -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Property Selection Section -->
            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center md:col-span-2">
                <h3 class="text-lg font-semibold mb-4">Select Category and Subcategory</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                        <select id="categoryFilter" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                            <option value="">Select a category</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Subcategory</label>
                        <select id="subcategoryFilter" class="w-full px-3 py-2 border border-gray-300 rounded-md" disabled>
                            <option value="">Select a subcategory</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Property 1 Selection -->
            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                <h3 class="text-lg font-semibold mb-4">Select First Property</h3>
                <div class="mb-4">
                    <select id="property1Select" class="w-full px-3 py-2 border border-gray-300 rounded-md" disabled>
                        <option value="">Choose a property</option>
                    </select>
                </div>
            </div>

            <!-- Property 2 Selection -->
            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                <h3 class="text-lg font-semibold mb-4">Select Second Property</h3>
                <div class="mb-4">
                    <select id="property2Select" class="w-full px-3 py-2 border border-gray-300 rounded-md" disabled>
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
console.log('Property comparison script is loading...');

class PropertyComparison {
    constructor() {
        this.properties = [];
        this.categories = [];
        this.subcategories = [];
        this.selectedCategory = null;
        this.selectedSubcategory = null;
        this.selectedProperty1 = null;
        this.selectedProperty2 = null;

        this.updateStatus('PropertyComparison instance created...');

        // Don't call async init in constructor, call it separately
        setTimeout(() => {
            this.init().catch(error => {
                console.error('Init failed:', error);
                this.updateStatus('Initialization failed: ' + error.message);
            });
        }, 100);
    }

    updateStatus(message) {
        console.log('Status:', message);
        const statusElement = document.getElementById('statusText');
        if (statusElement) {
            statusElement.textContent = message;
        }
    }    async init() {
        this.updateStatus('Initializing PropertyComparison...');
        console.log('Initializing PropertyComparison...');
        console.log('Current page:', window.location.href);
        console.log('Document ready state:', document.readyState);

        // Double-check that our DOM element exists
        const element = document.getElementById('propertyComparisonTool');
        if (!element) {
            console.error('propertyComparisonTool element not found during init!');
            this.updateStatus('Error: Element not found!');
            return;
        }

        this.updateStatus('Loading categories...');
        console.log('About to load categories...');
        await this.loadCategories();
        console.log('Categories loaded, setting up event listeners...');
        this.updateStatus('Setting up event listeners...');
        this.setupEventListeners();
        console.log('PropertyComparison initialization complete');
        this.updateStatus('Ready! Select a category to begin.');
    }

    async loadCategories() {
        this.updateStatus('Testing fetch capability...');

        // First, let's test if fetch is available
        if (typeof fetch === 'undefined') {
            this.updateStatus('Error: fetch is not available');
            console.error('fetch is not available');
            return;
        }

        this.updateStatus('Fetching categories from server...');
        console.log('Loading categories...');
        try {
            const url = `${window.location.origin}/api/property-comparison/categories`;
            console.log('Fetching from:', url);

            // Simple fetch first
            console.log('Starting fetch...');
            const response = await fetch(url);
            console.log('Fetch completed. Response:', response);
            console.log('Response status:', response.status);
            console.log('Response ok:', response.ok);

            if (!response.ok) {
                const errorText = await response.text();
                console.error('Response error text:', errorText);
                throw new Error(`HTTP error! status: ${response.status}, text: ${errorText}`);
            }

            console.log('Reading response as text...');
            const responseText = await response.text();
            console.log('Response text length:', responseText.length);
            console.log('Response text (first 100 chars):', responseText.substring(0, 100));

            try {
                console.log('Parsing JSON...');
                this.categories = JSON.parse(responseText);
                console.log('Categories parsed successfully:', this.categories);
                console.log('Categories count:', Object.keys(this.categories).length);

                this.updateStatus('Categories loaded, populating dropdown...');
                this.populateCategoryFilter();
            } catch (parseError) {
                console.error('JSON parse error:', parseError);
                console.error('Response text that failed to parse:', responseText);
                throw new Error(`Failed to parse JSON: ${parseError.message}`);
            }
        } catch (error) {
            console.error('Error loading categories:', error);
            console.error('Error stack:', error.stack);
            this.updateStatus('Error loading categories: ' + error.message);

            // Show user-friendly error
            const categoryFilter = document.getElementById('categoryFilter');
            if (categoryFilter) {
                categoryFilter.innerHTML = '<option value="">Error loading categories</option>';
            }
        }
    }    populateCategoryFilter() {
        console.log('Populating category filter...', this.categories);
        const categoryFilter = document.getElementById('categoryFilter');
        console.log('Category filter element:', categoryFilter);
        console.log('Category filter parent:', categoryFilter?.parentElement);
        console.log('Category filter tagName:', categoryFilter?.tagName);

        if (!categoryFilter) {
            console.error('Category filter element not found');
            console.log('All elements with ID "categoryFilter":');
            const allCategoryElements = document.querySelectorAll('#categoryFilter');
            console.log('Found elements:', allCategoryElements);
            this.updateStatus('Error: Category dropdown not found!');
            return;
        }

        // Clear existing options
        categoryFilter.innerHTML = '<option value="">Select a category</option>';
        console.log('Cleared existing options');

        // Add categories
        let addedCount = 0;
        for (const [id, name] of Object.entries(this.categories)) {
            console.log('Adding category:', id, name);
            const option = document.createElement('option');
            option.value = id;
            option.textContent = name;
            categoryFilter.appendChild(option);
            addedCount++;
        }

        console.log(`Category filter populated successfully with ${addedCount} categories`);
        console.log('Final category filter HTML:', categoryFilter.innerHTML);
        console.log('Final category filter options count:', categoryFilter.options.length);

        this.updateStatus(`Categories loaded! (${addedCount} categories available)`);

        // Hide the debug status after successful load
        setTimeout(() => {
            const debugDiv = document.getElementById('debugStatus');
            if (debugDiv) {
                debugDiv.style.display = 'none';
            }
        }, 3000);
    }

    async loadSubcategories(categoryId) {
        console.log('Loading subcategories for category:', categoryId);
        try {
            const response = await fetch(`/api/subcategories/${categoryId}`);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            this.subcategories = await response.json();
            console.log('Subcategories loaded:', this.subcategories);
            this.populateSubcategoryFilter();
        } catch (error) {
            console.error('Error loading subcategories:', error);
            this.subcategories = [];
            this.populateSubcategoryFilter();
        }
    }

    populateSubcategoryFilter() {
        const subcategoryFilter = document.getElementById('subcategoryFilter');

        if (!subcategoryFilter) {
            console.error('Subcategory filter element not found');
            return;
        }

        subcategoryFilter.innerHTML = '<option value="">Select a subcategory</option>';

        if (this.subcategories.length === 0) {
            subcategoryFilter.disabled = true;
            return;
        }

        subcategoryFilter.disabled = false;

        this.subcategories.forEach(subcategory => {
            const option = document.createElement('option');
            option.value = subcategory.id;
            option.textContent = subcategory.name;
            subcategoryFilter.appendChild(option);
        });
    }

    async loadProperties(subcategoryId = '') {
        console.log('Loading properties for subcategory:', subcategoryId);
        try {
            const params = new URLSearchParams();
            if (subcategoryId) params.append('subcategory', subcategoryId);

            const response = await fetch(`/api/property-comparison/properties?${params}`);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const properties = await response.json();

            console.log('Properties loaded:', properties);
            this.populatePropertySelects(properties);
        } catch (error) {
            console.error('Error loading properties:', error);
        }
    }

    populatePropertySelects(properties) {
        const property1Select = document.getElementById('property1Select');
        const property2Select = document.getElementById('property2Select');

        if (!property1Select || !property2Select) {
            console.error('Property select elements not found');
            return;
        }

        const resetSelects = () => {
            property1Select.innerHTML = '<option value="">Choose a property</option>';
            property2Select.innerHTML = '<option value="">Choose a property</option>';

            if (properties.length === 0) {
                const option = document.createElement('option');
                option.value = '';
                option.textContent = 'No properties found in this subcategory';
                option.disabled = true;
                property1Select.appendChild(option.cloneNode(true));
                property2Select.appendChild(option.cloneNode(true));
                property1Select.disabled = true;
                property2Select.disabled = true;
                return;
            }

            property1Select.disabled = false;
            property2Select.disabled = false;
        };

        resetSelects();

        properties.forEach(property => {
            const option1 = document.createElement('option');
            const option2 = document.createElement('option');

            option1.value = property.id;
            option1.textContent = `${property.business_name} (${property.city}, ${property.country})`;
            option2.value = property.id;
            option2.textContent = `${property.business_name} (${property.city}, ${property.country})`;

            property1Select.appendChild(option1);
            property2Select.appendChild(option2);
        });
    }

    setupEventListeners() {
        console.log('Setting up event listeners...');

        // Category filter
        const categoryFilter = document.getElementById('categoryFilter');
        if (categoryFilter) {
            categoryFilter.addEventListener('change', (e) => {
                console.log('Category changed:', e.target.value);
                this.selectedCategory = e.target.value;
                if (e.target.value) {
                    this.loadSubcategories(e.target.value);
                } else {
                    this.resetSubcategoryAndProperties();
                }
            });
        }

        // Subcategory filter
        const subcategoryFilter = document.getElementById('subcategoryFilter');
        if (subcategoryFilter) {
            subcategoryFilter.addEventListener('change', (e) => {
                console.log('Subcategory changed:', e.target.value);
                this.selectedSubcategory = e.target.value;
                if (e.target.value) {
                    this.loadProperties(e.target.value);
                } else {
                    this.resetProperties();
                }
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

    resetSubcategoryAndProperties() {
        const subcategoryFilter = document.getElementById('subcategoryFilter');
        if (subcategoryFilter) {
            subcategoryFilter.innerHTML = '<option value="">Select a subcategory</option>';
            subcategoryFilter.disabled = true;
        }
        this.resetProperties();
    }

    resetProperties() {
        const property1Select = document.getElementById('property1Select');
        const property2Select = document.getElementById('property2Select');

        if (property1Select) {
            property1Select.innerHTML = '<option value="">Choose a property</option>';
            property1Select.disabled = true;
        }
        if (property2Select) {
            property2Select.innerHTML = '<option value="">Choose a property</option>';
            property2Select.disabled = true;
        }

        this.selectedProperty1 = null;
        this.selectedProperty2 = null;
        this.toggleCompareButton();
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
                <h4 class="font-semibold mb-2">Review Analysis</h4>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div class="text-center">
                        <div class="text-lg font-bold text-green-600">${stats.positive_reviews || 0}</div>
                        <div class="text-xs text-gray-600">Positive Reviews (4-5★)</div>
                    </div>
                    <div class="text-center">
                        <div class="text-lg font-bold text-red-600">${stats.negative_reviews || 0}</div>
                        <div class="text-xs text-gray-600">Negative Reviews (1-2★)</div>
                    </div>
                    <div class="text-center col-span-2">
                        <div class="text-lg font-bold text-yellow-600">${stats.neutral_reviews || 0}</div>
                        <div class="text-xs text-gray-600">Neutral Reviews (3★)</div>
                    </div>
                </div>
                <div class="mt-2 text-xs text-center text-gray-500">
                    Positive Rate: ${this.calculatePercentage(stats.positive_reviews || 0, stats.total_reviews).toFixed(1)}%
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

            ${property.products && property.products.length > 0 ? `
                <div class="mb-4 p-4 bg-white rounded-lg">
                    <h4 class="font-semibold mb-2">Products & Services (${property.products.length})</h4>
                    <div class="space-y-2">
                        ${property.products.slice(0, 5).map(product => `
                            <div class="flex justify-between items-center text-sm border-b border-gray-100 pb-2">
                                <div>
                                    <div class="font-medium">${product.name}</div>
                                    ${product.category ? `<div class="text-xs text-gray-500">${product.category}</div>` : ''}
                                </div>
                                <div class="text-right">
                                    ${product.price ? `<div class="font-bold text-green-600">$${product.price}</div>` : ''}
                                    ${product.stock_quantity !== undefined ? `<div class="text-xs text-gray-500">${product.stock_quantity} in stock</div>` : ''}
                                </div>
                            </div>
                        `).join('')}
                        ${property.products.length > 5 ? `<div class="text-xs text-gray-500 text-center">+ ${property.products.length - 5} more products</div>` : ''}
                    </div>
                </div>
            ` : ''}

            ${property.latest_reviews && property.latest_reviews.length > 0 ? `
                <div class="p-4 bg-white rounded-lg">
                    <h4 class="font-semibold mb-2">Recent Reviews</h4>
                    <div class="space-y-3">
                        ${property.latest_reviews.map(review => `
                            <div class="border-l-4 ${this.getReviewBorderColor(review.rate)} pl-3">
                                <div class="flex items-center mb-1">
                                    <div class="star-rating mr-2">
                                        ${this.generateStars(review.rate)}
                                    </div>
                                    <span class="text-sm text-gray-600">${review.user ? review.user.name : 'Anonymous'}</span>
                                    <span class="ml-auto text-xs text-gray-400">${this.getReviewSentiment(review.rate)}</span>
                                </div>
                                <p class="text-sm text-gray-700">${review.review ? review.review.substring(0, 100) + (review.review.length > 100 ? '...' : '') : 'No comment'}</p>
                            </div>
                        `).join('')}
                    </div>
                </div>
            ` : ''}
        `;
    }

    getReviewBorderColor(rating) {
        if (rating >= 4) return 'border-green-400';
        if (rating >= 3) return 'border-yellow-400';
        return 'border-red-400';
    }

    getReviewSentiment(rating) {
        if (rating >= 4) return 'Positive';
        if (rating >= 3) return 'Neutral';
        return 'Negative';
    }
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
        const products1 = property1.products ? property1.products.length : 0;
        const products2 = property2.products ? property2.products.length : 0;
        const positive1 = parseInt(stats1.positive_reviews || 0);
        const positive2 = parseInt(stats2.positive_reviews || 0);

        const ratingWinner = avgRating1 > avgRating2 ? 'property1' : avgRating2 > avgRating1 ? 'property2' : 'tie';
        const reviewCountWinner = totalReviews1 > totalReviews2 ? 'property1' : totalReviews2 > totalReviews1 ? 'property2' : 'tie';
        const productWinner = products1 > products2 ? 'property1' : products2 > products1 ? 'property2' : 'tie';
        const positiveWinner = positive1 > positive2 ? 'property1' : positive2 > positive1 ? 'property2' : 'tie';

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

            <div class="comparison-metric ${productWinner === 'property1' ? 'metric-winner' : productWinner === 'tie' ? 'metric-tie' : ''}">
                <h4 class="font-semibold">Products</h4>
                <div class="text-2xl font-bold text-purple-600">${products1}</div>
                <div class="text-sm text-gray-600">${property1.business_name}</div>
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

            <div class="comparison-metric ${productWinner === 'property2' ? 'metric-winner' : productWinner === 'tie' ? 'metric-tie' : ''}">
                <h4 class="font-semibold">Products</h4>
                <div class="text-2xl font-bold text-purple-600">${products2}</div>
                <div class="text-sm text-gray-600">${property2.business_name}</div>
            </div>

            <div class="comparison-metric ${positiveWinner === 'property1' ? 'metric-winner' : positiveWinner === 'tie' ? 'metric-tie' : ''} md:col-span-2">
                <h4 class="font-semibold">Positive Reviews (4-5★)</h4>
                <div class="grid grid-cols-2 gap-4">
                    <div class="text-center">
                        <div class="text-xl font-bold text-green-600">${positive1}</div>
                        <div class="text-xs text-gray-600">${property1.business_name}</div>
                    </div>
                    <div class="text-center">
                        <div class="text-xl font-bold text-green-600">${positive2}</div>
                        <div class="text-xs text-gray-600">${property2.business_name}</div>
                    </div>
                </div>
            </div>

            <div class="comparison-metric md:col-span-2">
                <h4 class="font-semibold">Review Sentiment Analysis</h4>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <div class="text-center mb-1 font-medium">${property1.business_name}</div>
                        <div class="text-green-600">✓ Positive: ${this.calculatePercentage(positive1, totalReviews1).toFixed(1)}%</div>
                        <div class="text-red-600">✗ Negative: ${this.calculatePercentage(stats1.negative_reviews || 0, totalReviews1).toFixed(1)}%</div>
                    </div>
                    <div>
                        <div class="text-center mb-1 font-medium">${property2.business_name}</div>
                        <div class="text-green-600">✓ Positive: ${this.calculatePercentage(positive2, totalReviews2).toFixed(1)}%</div>
                        <div class="text-red-600">✗ Negative: ${this.calculatePercentage(stats2.negative_reviews || 0, totalReviews2).toFixed(1)}%</div>
                    </div>
                </div>
            </div>

            <div class="comparison-metric md:col-span-2">
                <h4 class="font-semibold">Business Categories</h4>
                <div class="text-sm">
                    <div class="mb-1"><strong>${property1.business_name}:</strong> ${property1.subcategory_name || property1.category_name || property1.category}</div>
                    <div><strong>${property2.business_name}:</strong> ${property2.subcategory_name || property2.category_name || property2.category}</div>
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
window.addEventListener('load', function() {
    console.log('Window loaded - starting comparison tool initialization');

    // Add test functions to window
    window.testCategories = async function() {
        console.log('Testing categories manually...');
        const statusElement = document.getElementById('statusText');
        if (statusElement) {
            statusElement.textContent = 'Testing categories manually...';
        }

        try {
            const response = await fetch('/api/property-comparison/categories');
            console.log('Manual test response:', response);
            const data = await response.json();
            console.log('Manual test data:', data);

            if (statusElement) {
                statusElement.textContent = 'Manual test successful! Categories: ' + Object.keys(data).length;
            }

            // Try to populate the dropdown manually
            const categoryFilter = document.getElementById('categoryFilter');
            if (categoryFilter) {
                categoryFilter.innerHTML = '<option value="">Select a category</option>';
                for (const [id, name] of Object.entries(data)) {
                    const option = document.createElement('option');
                    option.value = id;
                    option.textContent = name;
                    categoryFilter.appendChild(option);
                }
            }
        } catch(error) {
            console.error('Manual test failed:', error);
            if (statusElement) {
                statusElement.textContent = 'Manual test failed: ' + error.message;
            }
        }
    };

    window.showDebugInfo = function() {
        console.log('=== DEBUG INFO ===');
        console.log('Current URL:', window.location.href);
        console.log('Fetch available:', typeof fetch);
        console.log('PropertyComparison instance:', window.propertyComparison);

        const element = document.getElementById('propertyComparisonTool');
        console.log('Main element found:', !!element);

        const categoryFilter = document.getElementById('categoryFilter');
        console.log('Category filter found:', !!categoryFilter);
        console.log('Category filter options:', categoryFilter ? categoryFilter.options.length : 'N/A');

        const statusElement = document.getElementById('statusText');
        if (statusElement) {
            statusElement.textContent = 'Debug info logged to console';
        }
    };

    // Wait a moment to ensure everything is ready
    setTimeout(function() {
        console.log('Checking for propertyComparisonTool element...');
        const element = document.getElementById('propertyComparisonTool');
        console.log('Found element:', element);

        if (element) {
            console.log('Initializing PropertyComparison...');
            try {
                window.propertyComparison = new PropertyComparison();
                console.log('PropertyComparison created successfully');
            } catch (error) {
                console.error('Error creating PropertyComparison:', error);
                console.error('Error stack:', error.stack);

                // Update status with error
                const statusElement = document.getElementById('statusText');
                if (statusElement) {
                    statusElement.textContent = 'Error: ' + error.message;
                }
            }
        } else {
            console.error('propertyComparisonTool element not found!');

            // List all elements with IDs for debugging
            console.log('All elements with IDs:');
            document.querySelectorAll('[id]').forEach(el => {
                console.log('- ID:', el.id, 'Tag:', el.tagName);
            });

            // Update status
            const statusElement = document.getElementById('statusText');
            if (statusElement) {
                statusElement.textContent = 'Error: Comparison tool element not found';
            }
        }
    }, 500); // Wait 500ms to ensure DOM is fully ready
});
</script>

