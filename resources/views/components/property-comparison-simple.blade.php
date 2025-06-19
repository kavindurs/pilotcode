<div class="property-comparison-tool" id="propertyComparisonTool">
    <div class="max-w-7xl mx-auto px-6">
        <!-- Section heading -->
        <div class="text-center mb-12 mt-12">
            <h2 class="text-3xl font-bold leading-tight text-gray-900 sm:text-4xl xl:text-5xl font-pj">Property Comparison Tool</h2>
            <p class="mt-4 text-base leading-7 text-gray-600 sm:mt-8 font-pj">Compare two properties side by side to make informed decisions</p>
        </div>

        <!-- Property Selection -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Category and Subcategory Selection -->
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
<div id="comparisonModal" class="fixed inset-0 bg-black bg-opacity-60 hidden z-50 overflow-y-auto backdrop-blur-sm">
    <div class="flex items-center justify-center min-h-screen p-2 sm:p-4">
        <div class="bg-white rounded-2xl sm:rounded-3xl shadow-2xl w-full max-w-xs sm:max-w-7xl max-h-[98vh] sm:max-h-[95vh] overflow-hidden transform transition-all duration-300 modal-content">
            <!-- Modal Header -->
            <div class="sticky top-0 bg-gradient-to-r from-indigo-600 via-purple-600 to-blue-600 text-white px-4 sm:px-8 py-4 sm:py-6 flex justify-between items-center shadow-lg">
                <div class="flex items-center space-x-2 sm:space-x-4 min-w-0 flex-1">
                    <div class="bg-white bg-opacity-20 rounded-full p-2 sm:p-3 flex-shrink-0">
                        <svg class="w-5 h-5 sm:w-8 sm:h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <h2 class="text-lg sm:text-3xl font-bold text-white truncate">Property Comparison</h2>
                        <p class="text-indigo-100 text-xs sm:text-sm mt-1 hidden sm:flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            Detailed side-by-side analysis
                        </p>
                    </div>
                </div>
                <button id="closeModal" class="text-white hover:text-gray-200 transition-all duration-200 p-2 sm:p-3 rounded-full hover:bg-white hover:bg-opacity-20 hover:scale-110 focus:outline-none focus:ring-2 focus:ring-white focus:ring-opacity-50 flex-shrink-0">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Modal Content -->
            <div id="modalContent" class="overflow-y-auto max-h-[calc(98vh-80px)] sm:max-h-[calc(95vh-120px)] bg-gradient-to-br from-gray-50 to-blue-50">
                <!-- Comparison Results -->
                <div id="comparisonResults" class="p-4 sm:p-8">
                    <!-- Properties Grid -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-8 mb-8 sm:mb-12">
                        <!-- Property 1 Details -->
                        <div id="property1Details" class="space-y-4 sm:space-y-8">
                            <!-- Content will be populated by JavaScript -->
                        </div>

                        <!-- Property 2 Details -->
                        <div id="property2Details" class="space-y-4 sm:space-y-8">
                            <!-- Content will be populated by JavaScript -->
                        </div>
                    </div>

                    <!-- Divider -->
                    <div class="flex items-center justify-center mb-8 sm:mb-12">
                        <div class="flex-1 h-px bg-gradient-to-r from-transparent via-gray-300 to-transparent"></div>
                        <div class="mx-4 sm:mx-6 bg-white rounded-full p-3 sm:p-4 shadow-lg border border-gray-200">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="flex-1 h-px bg-gradient-to-r from-transparent via-gray-300 to-transparent"></div>
                    </div>

                    <!-- Comparison Summary -->
                    <div id="comparisonSummary" class="bg-white rounded-2xl sm:rounded-3xl p-6 sm:p-10 shadow-xl border border-gray-200 backdrop-blur-md bg-opacity-95">
                        <div class="text-center mb-6 sm:mb-10">
                            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-full p-3 sm:p-4 w-16 h-16 sm:w-20 sm:h-20 mx-auto mb-4 sm:mb-6 shadow-lg">
                                <svg class="w-10 h-10 sm:w-12 sm:h-12 text-white mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <h3 class="text-2xl sm:text-3xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent mb-2 sm:mb-3">
                                Comparison Summary
                            </h3>
                            <p class="text-gray-600 text-base sm:text-lg">Key metrics and performance indicators at a glance</p>
                            <div class="w-16 sm:w-24 h-1 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-full mx-auto mt-3 sm:mt-4"></div>
                        </div>
                        <div id="summaryContent" class="space-y-6 sm:space-y-8">
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

/* Custom animations */
@keyframes fadeInLeft {
    from {
        opacity: 0;
        transform: translateX(-50px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes fadeInRight {
    from {
        opacity: 0;
        transform: translateX(50px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes modalEnter {
    from {
        opacity: 0;
        transform: scale(0.9) translateY(-20px);
    }
    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

@keyframes spinSlow {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

.animate-fade-in-left {
    animation: fadeInLeft 0.6s ease-out forwards;
}

.animate-fade-in-right {
    animation: fadeInRight 0.6s ease-out forwards;
}

.animate-fade-in-up {
    animation: fadeInUp 0.8s ease-out forwards;
}

.modal-enter {
    animation: modalEnter 0.4s ease-out forwards;
}

.animate-spin-slow {
    animation: spinSlow 3s linear infinite;
}

/* Modal entrance effect */
#comparisonModal:not(.hidden) .modal-enter {
    opacity: 1;
    transform: scale(1);
}

/* Glass morphism effect */
.backdrop-blur-md {
    backdrop-filter: blur(12px);
}

/* Hover effects */
.hover-lift:hover {
    transform: translateY(-4px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
}

/* Border animations */
.border-animated {
    position: relative;
    overflow: hidden;
}

.border-animated::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
    transition: left 0.5s;
}

.border-animated:hover::before {
    left: 100%;
}

/* Custom gradient text */
.text-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    background-clip: text;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

/* Pulse animation for winner badges */
@keyframes pulseGlow {
    0%, 100% {
        box-shadow: 0 0 5px rgba(34, 197, 94, 0.5);
    }
    50% {
        box-shadow: 0 0 20px rgba(34, 197, 94, 0.8);
    }
}

.winner-glow {
    animation: pulseGlow 2s infinite;
}

/* Improved scrollbar */
.modal-content::-webkit-scrollbar {
    width: 8px;
}

.modal-content::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 4px;
}

.modal-content::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 4px;
}

.modal-content::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(135deg, #5a67d8, #6b46c1);
}
</style>

<script>
// Global variables
let selectedCategory = null;
let selectedSubcategory = null;
let selectedProperty1 = null;
let selectedProperty2 = null;

// Simple function to update status
function updateStatus(message) {
    console.log('Status:', message);
    const statusElement = document.getElementById('statusText');
    if (statusElement) {
        statusElement.textContent = message;
    }
}

// Test modal function
function testModal() {
    console.log('Testing modal...');

    // Add some test data to the modal
    const property1Details = document.getElementById('property1Details');
    const property2Details = document.getElementById('property2Details');
    const summaryContent = document.getElementById('summaryContent');

    if (property1Details) {
        property1Details.innerHTML = '<div class="bg-white p-4 rounded">Test Property 1</div>';
    }
    if (property2Details) {
        property2Details.innerHTML = '<div class="bg-white p-4 rounded">Test Property 2</div>';
    }
    if (summaryContent) {
        summaryContent.innerHTML = '<div class="bg-white p-4 rounded">Test Summary</div>';
    }

    showResults();
}

// Load categories
async function loadCategories() {
    updateStatus('Loading categories...');
    console.log('Loading categories...');

    try {
        const response = await fetch('/api/property-comparison/categories');
        console.log('Response received:', response.status, response.ok);

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const categories = await response.json();
        console.log('Categories loaded:', categories);

        const categorySelect = document.getElementById('categoryFilter');
        if (!categorySelect) {
            console.error('Category select element not found!');
            updateStatus('Error: Category dropdown not found');
            return;
        }

        categorySelect.innerHTML = '<option value="">Select a category</option>';

        let count = 0;
        for (const [id, name] of Object.entries(categories)) {
            const option = document.createElement('option');
            option.value = id;
            option.textContent = name;
            categorySelect.appendChild(option);
            count++;
        }

        updateStatus(`${count} categories loaded successfully!`);
        console.log(`Successfully loaded ${count} categories`);

        // Hide debug status after 3 seconds
        setTimeout(() => {
            const debugDiv = document.getElementById('debugStatus');
            if (debugDiv) {
                debugDiv.style.display = 'none';
            }
        }, 3000);

    } catch (error) {
        console.error('Error loading categories:', error);
        updateStatus('Error: ' + error.message);
    }
}

// Load subcategories
async function loadSubcategories(categoryId) {
    console.log('Loading subcategories for category:', categoryId);

    try {
        const response = await fetch(`/api/subcategories/${categoryId}`);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const subcategories = await response.json();
        console.log('Subcategories loaded:', subcategories);

        const subcategorySelect = document.getElementById('subcategoryFilter');
        if (!subcategorySelect) {
            console.error('Subcategory select element not found');
            return;
        }

        subcategorySelect.innerHTML = '<option value="">Select a subcategory</option>';

        if (subcategories.length === 0) {
            subcategorySelect.disabled = true;
            return;
        }

        subcategorySelect.disabled = false;

        subcategories.forEach(subcategory => {
            const option = document.createElement('option');
            option.value = subcategory.id;
            option.textContent = subcategory.name;
            subcategorySelect.appendChild(option);
        });

    } catch (error) {
        console.error('Error loading subcategories:', error);
        const subcategorySelect = document.getElementById('subcategoryFilter');
        if (subcategorySelect) {
            subcategorySelect.innerHTML = '<option value="">Error loading subcategories</option>';
            subcategorySelect.disabled = true;
        }
    }
}

// Load properties
async function loadProperties(subcategoryId) {
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

        populatePropertySelects(properties);

    } catch (error) {
        console.error('Error loading properties:', error);
    }
}

// Populate property select dropdowns
function populatePropertySelects(properties) {
    const property1Select = document.getElementById('property1Select');
    const property2Select = document.getElementById('property2Select');

    if (!property1Select || !property2Select) {
        console.error('Property select elements not found');
        return;
    }

    // Clear existing options
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

// Toggle compare button
function toggleCompareButton() {
    const compareBtn = document.getElementById('compareBtn');
    if (!compareBtn) return;

    const canCompare = selectedProperty1 && selectedProperty2 && selectedProperty1 !== selectedProperty2;
    compareBtn.disabled = !canCompare;
    console.log('Compare button enabled:', canCompare);
}

// Compare properties
async function compareProperties() {
    console.log('Compare button clicked!');
    console.log('Property 1:', selectedProperty1);
    console.log('Property 2:', selectedProperty2);

    if (!selectedProperty1 || !selectedProperty2) {
        console.log('Missing property selections');
        return;
    }

    showLoading(true);

    // Only hide results if modal is currently shown
    const modal = document.getElementById('comparisonModal');
    if (modal && !modal.classList.contains('hidden')) {
        hideResults();
    }

    try {
        console.log('Sending comparison request...');

        const response = await fetch('/api/property-comparison/compare', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            body: JSON.stringify({
                property1_id: selectedProperty1,
                property2_id: selectedProperty2
            })
        });

        console.log('Response status:', response.status);
        const data = await response.json();
        console.log('Response data:', data);

        if (response.ok) {
            console.log('Displaying comparison results...');
            displayComparison(data.property1, data.property2);
        } else {
            console.error('API error:', data.error);
            alert('Error: ' + (data.error || 'Failed to compare properties'));
        }
    } catch (error) {
        console.error('Error comparing properties:', error);
        alert('An error occurred while comparing properties');
    } finally {
        showLoading(false);
    }
}

// Display comparison results
function displayComparison(property1, property2) {
    console.log('displayComparison called with:', property1, property2);
    displayPropertyDetails('property1Details', property1);
    displayPropertyDetails('property2Details', property2);
    displayComparisonSummary(property1, property2);
    console.log('About to call showResults()');
    showResults();
}

// Display individual property details
function displayPropertyDetails(containerId, property) {
    const container = document.getElementById(containerId);
    if (!container) return;

    const stats = property.review_stats;
    const avgRating = parseFloat(stats.average_rating || 0);

    container.innerHTML = `
        <!-- Property Header Card -->
        <div class="relative bg-gradient-to-br from-white via-blue-50 to-indigo-100 rounded-2xl p-8 mb-8 border border-blue-200 shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-400 to-indigo-600 rounded-full opacity-10 transform translate-x-16 -translate-y-16"></div>
            <div class="relative flex items-center space-x-6">
                ${property.profile_picture ?
                    `<div class="relative">
                        <img src="/storage/${property.profile_picture}" alt="${property.business_name}" class="w-20 h-20 rounded-2xl object-cover border-4 border-white shadow-lg">
                        <div class="absolute -bottom-2 -right-2 bg-green-500 rounded-full w-6 h-6 border-2 border-white flex items-center justify-center">
                            <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>` :
                    `<div class="relative">
                        <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center border-4 border-white shadow-lg">
                            <span class="text-white text-3xl">🏢</span>
                        </div>
                        <div class="absolute -bottom-2 -right-2 bg-blue-500 rounded-full w-6 h-6 border-2 border-white flex items-center justify-center">
                            <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm2 3a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 4a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>`
                }
                <div class="flex-1">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2 leading-tight">${property.business_name}</h3>
                    <div class="flex items-center space-x-3 mb-3">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gradient-to-r from-blue-100 to-indigo-100 text-indigo-800 border border-indigo-200">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            ${property.category_name || property.category}
                        </span>
                    </div>
                    <p class="text-gray-600 text-base flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                        </svg>
                        ${property.city}, ${property.country}
                    </p>
                </div>
            </div>
        </div>

        <!-- Rating Overview Card -->
        <div class="bg-white rounded-2xl p-8 mb-8 border border-gray-200 shadow-lg hover:shadow-xl transition-all duration-300 group">
            <div class="flex items-center justify-between mb-6">
                <h4 class="text-xl font-bold text-gray-900 flex items-center">
                    <div class="bg-gradient-to-r from-yellow-400 to-orange-500 rounded-full p-2 mr-3 group-hover:scale-110 transition-transform duration-200">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                    </div>
                    Rating Overview
                </h4>
                <div class="text-right">
                    <div class="text-4xl font-bold bg-gradient-to-r from-yellow-500 to-orange-500 bg-clip-text text-transparent">${avgRating.toFixed(1)}</div>
                    <div class="text-sm text-gray-500 font-medium">${stats.total_reviews} reviews</div>
                </div>
            </div>
            <div class="flex items-center justify-center mb-6">
                <div class="star-rating text-3xl">
                    ${generateStars(avgRating)}
                </div>
            </div>
            <div class="bg-gradient-to-r from-gray-50 to-blue-50 rounded-xl p-6 border border-gray-100">
                <div class="grid grid-cols-5 gap-4 text-sm">
                    ${[5,4,3,2,1].map(star => {
                        const count = stats[getStarProperty(star)] || 0;
                        const percentage = calculatePercentage(count, stats.total_reviews);
                        return `
                            <div class="text-center">
                                <div class="text-gray-700 mb-2 font-medium">${star}★</div>
                                <div class="bg-gray-200 rounded-full h-3 mb-2 overflow-hidden">
                                    <div class="bg-gradient-to-r from-yellow-400 to-orange-500 h-3 rounded-full transition-all duration-500 ease-out" style="width: ${percentage}%"></div>
                                </div>
                                <div class="text-gray-600 font-semibold">${count}</div>
                            </div>
                        `;
                    }).join('')}
                </div>
            </div>
        </div>

        <!-- Review Sentiment Card -->
        <div class="bg-white rounded-2xl p-8 mb-8 border border-gray-200 shadow-lg hover:shadow-xl transition-all duration-300">
            <h4 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-full p-2 mr-3">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                        <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                    </svg>
                </div>
                Review Sentiment
            </h4>
            <div class="grid grid-cols-3 gap-6">
                <div class="relative text-center p-6 bg-gradient-to-br from-green-50 to-emerald-100 rounded-xl border-2 border-green-200 hover:border-green-300 transition-all duration-200 group">
                    <div class="absolute top-2 right-2 opacity-20 group-hover:opacity-30 transition-opacity">
                        <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="text-3xl font-bold text-green-600 mb-2">${stats.positive_reviews || 0}</div>
                    <div class="text-sm text-green-700 font-semibold mb-1">Positive</div>
                    <div class="text-xs text-green-600 bg-green-100 px-2 py-1 rounded-full">(4-5★)</div>
                </div>
                <div class="relative text-center p-6 bg-gradient-to-br from-yellow-50 to-amber-100 rounded-xl border-2 border-yellow-200 hover:border-yellow-300 transition-all duration-200 group">
                    <div class="absolute top-2 right-2 opacity-20 group-hover:opacity-30 transition-opacity">
                        <svg class="w-6 h-6 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="text-3xl font-bold text-yellow-600 mb-2">${stats.neutral_reviews || 0}</div>
                    <div class="text-sm text-yellow-700 font-semibold mb-1">Neutral</div>
                    <div class="text-xs text-yellow-600 bg-yellow-100 px-2 py-1 rounded-full">(3★)</div>
                </div>
                <div class="relative text-center p-6 bg-gradient-to-br from-red-50 to-rose-100 rounded-xl border-2 border-red-200 hover:border-red-300 transition-all duration-200 group">
                    <div class="absolute top-2 right-2 opacity-20 group-hover:opacity-30 transition-opacity">
                        <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="text-3xl font-bold text-red-600 mb-2">${stats.negative_reviews || 0}</div>
                    <div class="text-sm text-red-700 font-semibold mb-1">Negative</div>
                    <div class="text-xs text-red-600 bg-red-100 px-2 py-1 rounded-full">(1-2★)</div>
                </div>
            </div>
            <div class="mt-6 text-center">
                <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold shadow-md ${
                    calculatePercentage(stats.positive_reviews || 0, stats.total_reviews) >= 70
                        ? 'bg-gradient-to-r from-green-500 to-emerald-600 text-white'
                        : calculatePercentage(stats.positive_reviews || 0, stats.total_reviews) >= 50
                        ? 'bg-gradient-to-r from-yellow-500 to-amber-600 text-white'
                        : 'bg-gradient-to-r from-red-500 to-rose-600 text-white'
                }">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z" clip-rule="evenodd"></path>
                    </svg>
                    ${calculatePercentage(stats.positive_reviews || 0, stats.total_reviews).toFixed(1)}% Positive Rate
                </div>
            </div>
        </div>

        ${property.products && property.products.length > 0 ? `
            <!-- Products & Services Card -->
            <div class="bg-white rounded-2xl p-8 mb-8 border border-gray-200 shadow-lg hover:shadow-xl transition-all duration-300">
                <h4 class="text-xl font-bold text-gray-900 mb-6 flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="bg-gradient-to-r from-purple-500 to-pink-600 rounded-full p-2 mr-3">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 2L3 7v11a1 1 0 001 1h3a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1h3a1 1 0 001-1V7l-7-5z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        Products & Services
                    </div>
                    <span class="bg-gradient-to-r from-purple-100 to-pink-100 text-purple-800 text-sm font-bold px-4 py-2 rounded-full border border-purple-200 shadow-sm">${property.products.length} items</span>
                </h4>
                <div class="space-y-4">
                    ${property.products.slice(0, 4).map((product, index) => `
                        <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-purple-50 rounded-xl border border-gray-200 hover:border-purple-200 hover:shadow-md transition-all duration-200 group transform hover:-translate-y-1">
                            <div class="flex items-center space-x-4 flex-1">
                                <div class="w-3 h-3 bg-gradient-to-r from-purple-400 to-pink-500 rounded-full opacity-60 group-hover:opacity-100 transition-opacity"></div>
                                <div class="flex-1">
                                    <div class="font-semibold text-gray-900 text-base group-hover:text-purple-800 transition-colors">${product.name}</div>
                                    ${product.category ? `<div class="text-sm text-gray-500 mt-1">${product.category}</div>` : ''}
                                </div>
                            </div>
                            ${product.price ? `
                                <div class="text-right ml-4">
                                    <div class="font-bold text-green-600 text-lg">$${product.price}</div>
                                    ${product.stock_quantity !== undefined ? `<div class="text-xs text-gray-500">${product.stock_quantity} in stock</div>` : ''}
                                </div>
                            ` : ''}
                        </div>
                    `).join('')}
                    ${property.products.length > 4 ? `
                        <div class="text-center py-4">
                            <span class="text-sm text-gray-600 bg-gradient-to-r from-gray-100 to-purple-100 px-6 py-3 rounded-full border border-gray-200 shadow-sm font-medium">+ ${property.products.length - 4} more products available</span>
                        </div>
                    ` : ''}
                </div>
            </div>
        ` : ''}
    `;
}

// Display comparison summary
function displayComparisonSummary(property1, property2) {
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

    // Get winner badges
    function getWinnerBadge(winner, propertyKey) {
        if (winner === 'tie') return '<span class="inline-flex items-center bg-gradient-to-r from-yellow-500 to-amber-600 text-white text-xs sm:text-sm font-bold px-2 sm:px-4 py-1 sm:py-2 rounded-full shadow-lg animate-pulse"><svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd"></path></svg><span class="hidden sm:inline">TIE</span><span class="sm:hidden">T</span></span>';
        if (winner === propertyKey) return '<span class="inline-flex items-center bg-gradient-to-r from-green-500 to-emerald-600 text-white text-xs sm:text-sm font-bold px-2 sm:px-4 py-1 sm:py-2 rounded-full shadow-lg animate-bounce"><svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg><span class="hidden sm:inline">WINNER</span><span class="sm:hidden">W</span></span>';
        return '';
    }

    container.innerHTML = `
        <!-- Rating Comparison -->
        <div class="bg-white rounded-xl sm:rounded-2xl p-4 sm:p-8 border border-gray-200 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 mb-6 sm:mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 sm:mb-8">
                <h4 class="text-lg sm:text-xl font-bold text-gray-900 flex items-center mb-4 sm:mb-0">
                    <div class="bg-gradient-to-r from-yellow-400 to-orange-500 rounded-full p-2 sm:p-3 mr-3 sm:mr-4 shadow-md flex-shrink-0">
                        <svg class="w-4 h-4 sm:w-6 sm:h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                    </div>
                    <span class="break-words">Average Rating</span>
                </h4>
                <div class="flex flex-wrap items-center gap-2">
                    ${getWinnerBadge(ratingWinner, 'property1')}
                    ${ratingWinner === 'property2' ? getWinnerBadge(ratingWinner, 'property2') : ''}
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-8">
                <div class="relative text-center p-4 sm:p-8 ${ratingWinner === 'property1' ? 'bg-gradient-to-br from-green-50 to-emerald-100 border-2 sm:border-3 border-green-300 rounded-xl sm:rounded-2xl shadow-lg sm:scale-105' : 'bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl sm:rounded-2xl border border-gray-200'} transition-all duration-300">
                    ${ratingWinner === 'property1' ? `
                        <div class="absolute -top-2 -right-2 sm:-top-4 sm:-right-4 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-full p-2 sm:p-3 shadow-lg animate-bounce">
                            <svg class="w-4 h-4 sm:w-6 sm:h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    ` : ''}
                    <div class="text-3xl sm:text-5xl font-bold ${ratingWinner === 'property1' ? 'text-green-600' : 'text-yellow-500'} mb-2 sm:mb-4">${avgRating1.toFixed(1)}</div>
                    <div class="text-sm sm:text-lg text-gray-700 font-semibold mb-2 sm:mb-3 truncate px-2">${property1.business_name}</div>
                    <div class="text-lg sm:text-3xl mb-2">${generateStars(avgRating1)}</div>
                    <div class="text-xs sm:text-sm text-gray-500 font-medium">Based on ${totalReviews1} reviews</div>
                </div>
                <div class="relative text-center p-4 sm:p-8 ${ratingWinner === 'property2' ? 'bg-gradient-to-br from-green-50 to-emerald-100 border-2 sm:border-3 border-green-300 rounded-xl sm:rounded-2xl shadow-lg sm:scale-105' : 'bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl sm:rounded-2xl border border-gray-200'} transition-all duration-300">
                    ${ratingWinner === 'property2' ? `
                        <div class="absolute -top-2 -right-2 sm:-top-4 sm:-right-4 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-full p-2 sm:p-3 shadow-lg animate-bounce">
                            <svg class="w-4 h-4 sm:w-6 sm:h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    ` : ''}
                    <div class="text-3xl sm:text-5xl font-bold ${ratingWinner === 'property2' ? 'text-green-600' : 'text-yellow-500'} mb-2 sm:mb-4">${avgRating2.toFixed(1)}</div>
                    <div class="text-sm sm:text-lg text-gray-700 font-semibold mb-2 sm:mb-3 truncate px-2">${property2.business_name}</div>
                    <div class="text-lg sm:text-3xl mb-2">${generateStars(avgRating2)}</div>
                    <div class="text-xs sm:text-sm text-gray-500 font-medium">Based on ${totalReviews2} reviews</div>
                </div>
            </div>
        </div>

        <!-- Review Count Comparison -->
        <div class="bg-white rounded-xl sm:rounded-2xl p-4 sm:p-8 border border-gray-200 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 mb-6 sm:mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 sm:mb-8">
                <h4 class="text-lg sm:text-xl font-bold text-gray-900 flex items-center mb-4 sm:mb-0">
                    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full p-2 sm:p-3 mr-3 sm:mr-4 shadow-md flex-shrink-0">
                        <svg class="w-4 h-4 sm:w-6 sm:h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zM7 8H5v2h2V8zm2 0h2v2H9V8zm6 0h-2v2h2V8z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <span class="break-words">Total Reviews</span>
                </h4>
                <div class="flex flex-wrap items-center gap-2">
                    ${getWinnerBadge(reviewCountWinner, 'property1')}
                    ${reviewCountWinner === 'property2' ? getWinnerBadge(reviewCountWinner, 'property2') : ''}
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-8">
                <div class="relative text-center p-4 sm:p-8 ${reviewCountWinner === 'property1' ? 'bg-gradient-to-br from-blue-50 to-indigo-100 border-2 sm:border-3 border-blue-300 rounded-xl sm:rounded-2xl shadow-lg sm:scale-105' : 'bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl sm:rounded-2xl border border-gray-200'} transition-all duration-300">
                    ${reviewCountWinner === 'property1' ? `
                        <div class="absolute -top-2 -right-2 sm:-top-4 sm:-right-4 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-full p-2 sm:p-3 shadow-lg animate-pulse">
                            <svg class="w-4 h-4 sm:w-6 sm:h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    ` : ''}
                    <div class="text-3xl sm:text-5xl font-bold ${reviewCountWinner === 'property1' ? 'text-blue-600' : 'text-blue-500'} mb-2 sm:mb-4">${totalReviews1.toLocaleString()}</div>
                    <div class="text-sm sm:text-lg text-gray-700 font-semibold mb-2 truncate px-2">${property1.business_name}</div>
                    <div class="text-xs sm:text-sm text-gray-500 font-medium bg-gray-100 px-3 py-2 rounded-full inline-block">Total Reviews</div>
                </div>
                <div class="relative text-center p-4 sm:p-8 ${reviewCountWinner === 'property2' ? 'bg-gradient-to-br from-blue-50 to-indigo-100 border-2 sm:border-3 border-blue-300 rounded-xl sm:rounded-2xl shadow-lg sm:scale-105' : 'bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl sm:rounded-2xl border border-gray-200'} transition-all duration-300">
                    ${reviewCountWinner === 'property2' ? `
                        <div class="absolute -top-2 -right-2 sm:-top-4 sm:-right-4 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-full p-2 sm:p-3 shadow-lg animate-pulse">
                            <svg class="w-4 h-4 sm:w-6 sm:h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    ` : ''}
                    <div class="text-3xl sm:text-5xl font-bold ${reviewCountWinner === 'property2' ? 'text-blue-600' : 'text-blue-500'} mb-2 sm:mb-4">${totalReviews2.toLocaleString()}</div>
                    <div class="text-sm sm:text-lg text-gray-700 font-semibold mb-2 truncate px-2">${property2.business_name}</div>
                    <div class="text-xs sm:text-sm text-gray-500 font-medium bg-gray-100 px-3 py-2 rounded-full inline-block">Total Reviews</div>
                </div>
            </div>
        </div>

        <!-- Products Comparison -->
        <div class="bg-white rounded-xl sm:rounded-2xl p-4 sm:p-8 border border-gray-200 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 mb-6 sm:mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 sm:mb-8">
                <h4 class="text-lg sm:text-xl font-bold text-gray-900 flex items-center mb-4 sm:mb-0">
                    <div class="bg-gradient-to-r from-purple-500 to-pink-600 rounded-full p-2 sm:p-3 mr-3 sm:mr-4 shadow-md flex-shrink-0">
                        <svg class="w-4 h-4 sm:w-6 sm:h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 2L3 7v11a1 1 0 001 1h3a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1h3a1 1 0 001-1V7l-7-5z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <span class="break-words">Products & Services</span>
                </h4>
                <div class="flex flex-wrap items-center gap-2">
                    ${getWinnerBadge(productWinner, 'property1')}
                    ${productWinner === 'property2' ? getWinnerBadge(productWinner, 'property2') : ''}
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-8">
                <div class="relative text-center p-4 sm:p-8 ${productWinner === 'property1' ? 'bg-gradient-to-br from-purple-50 to-pink-100 border-2 sm:border-3 border-purple-300 rounded-xl sm:rounded-2xl shadow-lg sm:scale-105' : 'bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl sm:rounded-2xl border border-gray-200'} transition-all duration-300">
                    ${productWinner === 'property1' ? `
                        <div class="absolute -top-2 -right-2 sm:-top-4 sm:-right-4 bg-gradient-to-r from-purple-500 to-pink-600 text-white rounded-full p-2 sm:p-3 shadow-lg animate-spin-slow">
                            <svg class="w-4 h-4 sm:w-6 sm:h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 2L3 7v11a1 1 0 001 1h3a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1h3a1 1 0 001-1V7l-7-5z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    ` : ''}
                    <div class="text-3xl sm:text-5xl font-bold ${productWinner === 'property1' ? 'text-purple-600' : 'text-purple-500'} mb-2 sm:mb-4">${products1}</div>
                    <div class="text-sm sm:text-lg text-gray-700 font-semibold mb-2 truncate px-2">${property1.business_name}</div>
                    <div class="text-xs sm:text-sm text-gray-500 font-medium bg-gray-100 px-3 py-2 rounded-full inline-block">Available Products</div>
                </div>
                <div class="relative text-center p-4 sm:p-8 ${productWinner === 'property2' ? 'bg-gradient-to-br from-purple-50 to-pink-100 border-2 sm:border-3 border-purple-300 rounded-xl sm:rounded-2xl shadow-lg sm:scale-105' : 'bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl sm:rounded-2xl border border-gray-200'} transition-all duration-300">
                    ${productWinner === 'property2' ? `
                        <div class="absolute -top-2 -right-2 sm:-top-4 sm:-right-4 bg-gradient-to-r from-purple-500 to-pink-600 text-white rounded-full p-2 sm:p-3 shadow-lg animate-spin-slow">
                            <svg class="w-4 h-4 sm:w-6 sm:h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 2L3 7v11a1 1 0 001 1h3a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1h3a1 1 0 001-1V7l-7-5z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    ` : ''}
                    <div class="text-3xl sm:text-5xl font-bold ${productWinner === 'property2' ? 'text-purple-600' : 'text-purple-500'} mb-2 sm:mb-4">${products2}</div>
                    <div class="text-sm sm:text-lg text-gray-700 font-semibold mb-2 truncate px-2">${property2.business_name}</div>
                    <div class="text-xs sm:text-sm text-gray-500 font-medium bg-gray-100 px-3 py-2 rounded-full inline-block">Available Products</div>
                </div>
            </div>
        </div>

        <!-- Sentiment Analysis -->
        <div class="bg-white rounded-xl sm:rounded-2xl p-4 sm:p-8 border border-gray-200 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 sm:mb-10">
                <h4 class="text-lg sm:text-xl font-bold text-gray-900 flex items-center mb-4 sm:mb-0">
                    <div class="bg-gradient-to-r from-indigo-500 to-blue-600 rounded-full p-2 sm:p-3 mr-3 sm:mr-4 shadow-md flex-shrink-0">
                        <svg class="w-4 h-4 sm:w-6 sm:h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                            <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                        </svg>
                    </div>
                    <span class="break-words">Review Sentiment Analysis</span>
                </h4>
                <div class="flex flex-wrap items-center gap-2">
                    ${getWinnerBadge(positiveWinner, 'property1')}
                    ${positiveWinner === 'property2' ? getWinnerBadge(positiveWinner, 'property2') : ''}
                </div>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-12">
                <div class="space-y-4 sm:space-y-6">
                    <div class="text-center p-4 sm:p-6 ${positiveWinner === 'property1' ? 'bg-gradient-to-br from-blue-50 to-indigo-100 border-2 sm:border-3 border-blue-300 rounded-xl sm:rounded-2xl shadow-lg' : 'bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl sm:rounded-2xl border border-blue-200'} transition-all duration-300">
                        <div class="text-lg sm:text-2xl font-bold text-blue-800 mb-2 truncate px-2">${property1.business_name}</div>
                        ${positiveWinner === 'property1' ? '<div class="text-xs sm:text-sm text-blue-600 font-semibold">🏆 Best Sentiment Score</div>' : ''}
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4">
                        <div class="text-center p-3 sm:p-6 bg-gradient-to-br from-green-50 to-emerald-100 rounded-xl sm:rounded-2xl border-2 border-green-200 hover:border-green-300 transition-all duration-200 group">
                            <div class="text-xl sm:text-3xl font-bold text-green-600 mb-1 sm:mb-2">${positive1}</div>
                            <div class="text-xs sm:text-sm text-green-700 font-semibold mb-1">Positive</div>
                            <div class="text-xs text-green-600 bg-green-100 px-2 py-1 rounded-full font-medium">${calculatePercentage(positive1, totalReviews1).toFixed(1)}%</div>
                        </div>
                        <div class="text-center p-3 sm:p-6 bg-gradient-to-br from-yellow-50 to-amber-100 rounded-xl sm:rounded-2xl border-2 border-yellow-200 hover:border-yellow-300 transition-all duration-200 group">
                            <div class="text-xl sm:text-3xl font-bold text-yellow-600 mb-1 sm:mb-2">${stats1.neutral_reviews || 0}</div>
                            <div class="text-xs sm:text-sm text-yellow-700 font-semibold mb-1">Neutral</div>
                            <div class="text-xs text-yellow-600 bg-yellow-100 px-2 py-1 rounded-full font-medium">${calculatePercentage(stats1.neutral_reviews || 0, totalReviews1).toFixed(1)}%</div>
                        </div>
                        <div class="text-center p-3 sm:p-6 bg-gradient-to-br from-red-50 to-rose-100 rounded-xl sm:rounded-2xl border-2 border-red-200 hover:border-red-300 transition-all duration-200 group">
                            <div class="text-xl sm:text-3xl font-bold text-red-600 mb-1 sm:mb-2">${stats1.negative_reviews || 0}</div>
                            <div class="text-xs sm:text-sm text-red-700 font-semibold mb-1">Negative</div>
                            <div class="text-xs text-red-600 bg-red-100 px-2 py-1 rounded-full font-medium">${calculatePercentage(stats1.negative_reviews || 0, totalReviews1).toFixed(1)}%</div>
                        </div>
                    </div>
                </div>
                <div class="space-y-4 sm:space-y-6">
                    <div class="text-center p-4 sm:p-6 ${positiveWinner === 'property2' ? 'bg-gradient-to-br from-blue-50 to-indigo-100 border-2 sm:border-3 border-blue-300 rounded-xl sm:rounded-2xl shadow-lg' : 'bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl sm:rounded-2xl border border-blue-200'} transition-all duration-300">
                        <div class="text-lg sm:text-2xl font-bold text-blue-800 mb-2 truncate px-2">${property2.business_name}</div>
                        ${positiveWinner === 'property2' ? '<div class="text-xs sm:text-sm text-blue-600 font-semibold">🏆 Best Sentiment Score</div>' : ''}
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4">
                        <div class="text-center p-3 sm:p-6 bg-gradient-to-br from-green-50 to-emerald-100 rounded-xl sm:rounded-2xl border-2 border-green-200 hover:border-green-300 transition-all duration-200 group">
                            <div class="text-xl sm:text-3xl font-bold text-green-600 mb-1 sm:mb-2">${positive2}</div>
                            <div class="text-xs sm:text-sm text-green-700 font-semibold mb-1">Positive</div>
                            <div class="text-xs text-green-600 bg-green-100 px-2 py-1 rounded-full font-medium">${calculatePercentage(positive2, totalReviews2).toFixed(1)}%</div>
                        </div>
                        <div class="text-center p-3 sm:p-6 bg-gradient-to-br from-yellow-50 to-amber-100 rounded-xl sm:rounded-2xl border-2 border-yellow-200 hover:border-yellow-300 transition-all duration-200 group">
                            <div class="text-xl sm:text-3xl font-bold text-yellow-600 mb-1 sm:mb-2">${stats2.neutral_reviews || 0}</div>
                            <div class="text-xs sm:text-sm text-yellow-700 font-semibold mb-1">Neutral</div>
                            <div class="text-xs text-yellow-600 bg-yellow-100 px-2 py-1 rounded-full font-medium">${calculatePercentage(stats2.neutral_reviews || 0, totalReviews2).toFixed(1)}%</div>
                        </div>
                        <div class="text-center p-3 sm:p-6 bg-gradient-to-br from-red-50 to-rose-100 rounded-xl sm:rounded-2xl border-2 border-red-200 hover:border-red-300 transition-all duration-200 group">
                            <div class="text-xl sm:text-3xl font-bold text-red-600 mb-1 sm:mb-2">${stats2.negative_reviews || 0}</div>
                            <div class="text-xs sm:text-sm text-red-700 font-semibold mb-1">Negative</div>
                            <div class="text-xs text-red-600 bg-red-100 px-2 py-1 rounded-full font-medium">${calculatePercentage(stats2.negative_reviews || 0, totalReviews2).toFixed(1)}%</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
}

// Helper functions
function generateStars(rating) {
    const fullStars = Math.floor(rating);
    const hasHalfStar = rating % 1 >= 0.5;
    const emptyStars = 5 - fullStars - (hasHalfStar ? 1 : 0);

    return '★'.repeat(fullStars) + (hasHalfStar ? '☆' : '') + '☆'.repeat(emptyStars);
}

function getStarProperty(star) {
    const map = { 5: 'five_star', 4: 'four_star', 3: 'three_star', 2: 'two_star', 1: 'one_star' };
    return map[star];
}

function calculatePercentage(value, total) {
    return total > 0 ? (value / total) * 100 : 0;
}

function showLoading(show) {
    const spinner = document.getElementById('loadingSpinner');
    if (spinner) {
        spinner.classList.toggle('hidden', !show);
    }
}

function showResults() {
    console.log('showResults() called');
    const modal = document.getElementById('comparisonModal');
    console.log('Modal element found:', !!modal);

    if (modal) {
        // Small delay to prevent immediate closing from event bubbling
        setTimeout(() => {
            // Show modal immediately
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            console.log('Modal shown, classes:', modal.className);

            // Find the modal content and apply entrance animation
            const modalContent = modal.querySelector('.modal-content');
            console.log('Modal content found:', !!modalContent);

            if (modalContent) {
                // Start with scaled down and invisible
                modalContent.style.transform = 'scale(0.95)';
                modalContent.style.opacity = '0';

                // Animate to full size and visible
                requestAnimationFrame(() => {
                    modalContent.style.transform = 'scale(1)';
                    modalContent.style.opacity = '1';
                    console.log('Animation applied');
                });
            }
        }, 100); // Small delay to prevent event conflicts
    }
}

function hideResults() {
    console.log('hideResults() called');
    const modal = document.getElementById('comparisonModal');
    if (modal) {
        console.log('Hiding modal...');
        const modalContent = modal.querySelector('.modal-content');
        if (modalContent) {
            // Animate out
            modalContent.style.transform = 'scale(0.95)';
            modalContent.style.opacity = '0';
        }

        // Hide modal after animation
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
            console.log('Modal hidden');
        }, 300);
    }
}

// Reset functions
function resetSubcategoryAndProperties() {
    const subcategorySelect = document.getElementById('subcategoryFilter');
    if (subcategorySelect) {
        subcategorySelect.innerHTML = '<option value="">Select a subcategory</option>';
        subcategorySelect.disabled = true;
    }
    resetProperties();
}

function resetProperties() {
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

    selectedProperty1 = null;
    selectedProperty2 = null;
    toggleCompareButton();
}

// Event listeners setup
function setupEventListeners() {
    // Prevent double attachment
    if (window.eventListenersAttached) {
        console.log('Event listeners already attached, skipping...');
        return;
    }

    console.log('Setting up event listeners...');

    // Category filter
    const categoryFilter = document.getElementById('categoryFilter');
    if (categoryFilter) {
        categoryFilter.addEventListener('change', (e) => {
            selectedCategory = e.target.value;
            if (e.target.value) {
                loadSubcategories(e.target.value);
            } else {
                resetSubcategoryAndProperties();
            }
        });
    }

    // Subcategory filter
    const subcategoryFilter = document.getElementById('subcategoryFilter');
    if (subcategoryFilter) {
        subcategoryFilter.addEventListener('change', (e) => {
            selectedSubcategory = e.target.value;
            if (e.target.value) {
                loadProperties(e.target.value);
            } else {
                resetProperties();
            }
        });
    }

    // Property selections
    const property1Select = document.getElementById('property1Select');
    const property2Select = document.getElementById('property2Select');

    if (property1Select) {
        property1Select.addEventListener('change', (e) => {
            selectedProperty1 = e.target.value;
            toggleCompareButton();
        });
    }

    if (property2Select) {
        property2Select.addEventListener('change', (e) => {
            selectedProperty2 = e.target.value;
            toggleCompareButton();
        });
    }

    // Compare button
    const compareBtn = document.getElementById('compareBtn');
    if (compareBtn) {
        compareBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            compareProperties();
        });
    }

    // Close modal
    const closeModal = document.getElementById('closeModal');
    if (closeModal) {
        closeModal.addEventListener('click', hideResults);
    }

    // Close modal when clicking outside
    const modal = document.getElementById('comparisonModal');
    if (modal) {
        modal.addEventListener('click', (e) => {
            // Only close if clicking directly on the modal backdrop, not on its children
            if (e.target === modal && !modal.classList.contains('hidden')) {
                console.log('Clicking outside modal, closing...');
                hideResults();
            }
        });

        // Prevent modal content clicks from closing the modal
        const modalContent = modal.querySelector('.modal-content');
        if (modalContent) {
            modalContent.addEventListener('click', (e) => {
                e.stopPropagation();
            });
        }
    }

    // Mark event listeners as attached
    window.eventListenersAttached = true;
    console.log('Event listeners setup complete');
}

// Initialize when page loads
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, starting initialization...');
    updateStatus('DOM loaded, checking elements...');

    const toolElement = document.getElementById('propertyComparisonTool');
    const categorySelect = document.getElementById('categoryFilter');

    console.log('Tool element found:', !!toolElement);
    console.log('Category select found:', !!categorySelect);

    if (toolElement && categorySelect) {
        updateStatus('Elements found, loading categories...');
        loadCategories();
        setupEventListeners();
    } else {
        updateStatus('Error: Required elements not found');
        console.error('Required elements not found');
    }
});

// Backup initialization
window.addEventListener('load', function() {
    console.log('Window loaded - backup check');
    const categorySelect = document.getElementById('categoryFilter');
    if (categorySelect && categorySelect.options.length <= 1) {
        console.log('Categories not loaded yet, trying again...');
        setTimeout(function() {
            loadCategories();
            setupEventListeners();
        }, 100);
    }
});
</script>
