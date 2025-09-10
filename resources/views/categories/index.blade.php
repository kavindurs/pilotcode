@extends('layouts.app')

@section('title', 'Browse Categories - Scoreness')

@section('styles')
<style>
    [x-cloak] { display: none !important; }
    .category-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .category-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    .hero-pattern {
        background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }
    /* Smooth max-height transitions */
    .max-h-0 { max-height: 0; }
    .max-h-96 { max-height: 24rem; }
    /* Text truncation utilities */
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-0 py-10">
    <!-- Hero Section -->
    <div class="relative overflow-hidden bg-gradient-to-r from-blue-700 to-blue-900 rounded-2xl shadow-xl mb-12 hero-pattern">
        <div class="absolute inset-0 bg-blue-800 opacity-100"></div>
        <div class="relative z-10 px-8 py-12 sm:py-16 sm:px-12">
            <div class="max-w-3xl">
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-extrabold mb-4 text-white leading-tight">
                    Browse Categories
                </h1>
                <p class="text-xl text-blue-100 mb-8 max-w-2xl">
                    Discover top-rated services and businesses across various industries and specialties
                </p>

                <!-- Enhanced Search Form -->
                <div x-data="{ searchFocused: false }" class="relative z-10">
                    <form action="{{ route('categories.index') }}" method="GET"
                        class="transition-all duration-300 max-w-2xl"
                        :class="{ 'scale-105': searchFocused }">
                        <div class="relative flex">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-search text-blue-100"></i>
                            </div>
                            <input
                                type="text"
                                name="search"
                                value="{{ $search ?? '' }}"
                                placeholder="Search categories or subcategories..."
                                class="block w-full pl-12 pr-12 py-4 rounded-lg bg-white/15 backdrop-blur-md border border-white/30 text-white placeholder-blue-100 focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-transparent shadow-lg text-base"
                                @focus="searchFocused = true"
                                @blur="searchFocused = false"
                            >
                            <button type="submit" class="absolute inset-y-0 right-0 px-4 flex items-center text-white/70 hover:text-white focus:outline-none transition-colors">
                                <span class="sr-only">Search</span>
                                <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Dropdowns Section -->
    <div class="mb-8" x-data="window.categoryFilter()">
        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-filter mr-2 text-blue-600"></i>
                Filter by Category & Subcategory
            </h3>

            <form action="{{ route('categories.index') }}" method="GET" class="space-y-4" id="filterForm">
                <!-- Preserve search term if it exists -->
                @if($search)
                    <input type="hidden" name="search" value="{{ $search }}">
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Category Dropdown -->
                    <div class="relative">
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Select Category
                        </label>
                        <select name="category_id" id="category_id"
                                x-model="selectedCategory"
                                @change="onCategoryChange()"
                                class="block w-full px-4 py-3 bg-white border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">All Categories</option>
                            @foreach($allCategories as $category)
                                <option value="{{ $category->id }}" {{ $selectedCategoryId == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Subcategory Dropdown -->
                    <div class="relative">
                        <label for="subcategory_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Select Subcategory
                        </label>
                        <select name="subcategory_id" id="subcategory_id"
                                x-model="selectedSubcategory"
                                :disabled="!selectedCategory"
                                class="block w-full px-4 py-3 bg-white border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors disabled:bg-gray-100 disabled:text-gray-500">
                            <option value="">All Subcategories</option>
                            <template x-for="subcategory in subcategories" :key="subcategory.id">
                                <option :value="subcategory.id"
                                        :selected="selectedSubcategory == subcategory.id"
                                        x-text="subcategory.name">
                                </option>
                            </template>
                        </select>
                    </div>

                    <!-- Filter Button -->
                    <div class="flex items-end">
                        <button type="submit"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 flex items-center justify-center">
                            <i class="fas fa-search mr-2"></i>
                            Apply Filter
                        </button>
                    </div>

                    <!-- Clear Filter Button -->
                    <div class="flex items-end">
                        <a href="{{ route('categories.index') }}"
                           class="w-full bg-gray-600 hover:bg-gray-700 text-white font-medium py-3 px-6 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 flex items-center justify-center">
                            <i class="fas fa-times mr-2"></i>
                            Clear All
                        </a>
                    </div>
                </div>

                <!-- Active Filters Display -->
                <div class="flex flex-wrap gap-2 mt-4" x-show="hasActiveFilters()">
                    <span class="text-sm text-gray-600">Active filters:</span>

                    @if($selectedCategoryId)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            Category: {{ $allCategories->find($selectedCategoryId)?->name }}
                            <a href="{{ request()->fullUrlWithQuery(['category_id' => null, 'subcategory_id' => null]) }}"
                               class="ml-1 text-blue-600 hover:text-blue-800">
                                <i class="fas fa-times"></i>
                            </a>
                        </span>
                    @endif

                    @if($selectedSubcategoryId)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Subcategory: {{ $allSubcategories->find($selectedSubcategoryId)?->name }}
                            <a href="{{ request()->fullUrlWithQuery(['subcategory_id' => null]) }}"
                               class="ml-1 text-green-600 hover:text-green-800">
                                <i class="fas fa-times"></i>
                            </a>
                        </span>
                    @endif

                    @if($search)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            Search: "{{ $search }}"
                            <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}"
                               class="ml-1 text-yellow-600 hover:text-yellow-800">
                                <i class="fas fa-times"></i>
                            </a>
                        </span>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Categories Section -->
    <div class="mb-8">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-2xl font-bold text-gray-800">
                @if($selectedCategoryId || $selectedSubcategoryId || $search)
                    Filtered Results
                    @if($selectedCategoryId)
                        <span class="text-lg text-blue-600">({{ $allCategories->find($selectedCategoryId)?->name }}{{ $selectedSubcategoryId ? ' → ' . $allSubcategories->find($selectedSubcategoryId)?->name : '' }})</span>
                    @elseif($search)
                        <span class="text-lg text-gray-600">for "{{ $search }}"</span>
                    @endif
                @else
                    All Categories
                @endif
            </h2>
            @if($selectedCategoryId || $selectedSubcategoryId || $search)
                <a href="{{ route('categories.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium">
                    <i class="fas fa-arrow-left mr-2"></i> View All Categories
                </a>
            @endif
        </div>

        <!-- Categories Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($categories as $category)
                <div x-data="{ expanded: false }"
                    class="category-card bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden h-fit">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-5">
                            <div class="flex items-center space-x-4">
                                <!-- Category Icon -->
                                <div class="w-12 h-12 flex items-center justify-center rounded-full bg-blue-50 text-blue-600 shadow-sm">
                                    <i class="fas {{ getCategoryIcon($category->name) }} text-xl"></i>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 tracking-tight">
                                    {{ $category->name }}
                                </h3>
                            </div>

                            <!-- Expand Button -->
                            <button @click="expanded = !expanded"
                                    class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 text-gray-500 hover:bg-blue-50 hover:text-blue-600 focus:outline-none transition-colors duration-200"
                                    :class="{ 'bg-blue-50 text-blue-600': expanded }">
                                <i class="fas fa-chevron-down transition-transform duration-300"
                                   :class="{ 'transform rotate-180': expanded }"></i>
                            </button>
                        </div>

                        <!-- Subcategories List -->
                        <div x-show="expanded"
                             x-cloak
                             x-transition:enter="transition-all ease-out duration-300"
                             x-transition:enter-start="opacity-0 max-h-0"
                             x-transition:enter-end="opacity-100 max-h-96"
                             x-transition:leave="transition-all ease-in duration-300"
                             x-transition:leave-start="opacity-100 max-h-96"
                             x-transition:leave-end="opacity-0 max-h-0"
                             class="overflow-hidden border-t border-gray-100 pt-4 mt-4">
                            @if($category->subcategories->count() > 0)
                                <ul class="space-y-3">
                                    @foreach($category->subcategories as $subcategory)
                                        <li class="group">
                                            <a href="{{ route('properties.subcategory', $subcategory->id) }}"
                                               class="flex items-center justify-between p-2 -mx-2 rounded-lg hover:bg-blue-50 group-hover:text-blue-700 transition-colors duration-200">
                                                <div class="flex items-center">
                                                    <span class="w-2 h-2 bg-blue-600 rounded-full mr-3 group-hover:w-3 group-hover:h-3 transition-all duration-200"></span>
                                                    <span class="font-medium">{{ $subcategory->name }}</span>
                                                </div>
                                                <i class="fas fa-arrow-right opacity-0 group-hover:opacity-100 transform translate-x-0 group-hover:translate-x-1 transition-all duration-200"></i>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <div class="py-4 text-center text-gray-500 italic">
                                    <i class="fas fa-info-circle mr-2"></i> No subcategories available
                                </div>
                            @endif
                        </div>

                        <!-- Preview Count -->
                        <div x-show="!expanded" class="mt-4 text-sm text-gray-500 flex items-center">
                            <i class="fas fa-layer-group mr-2 text-gray-400"></i>
                            <span>{{ $category->subcategories->count() }} subcategories available</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- No Results Message -->
        @if($categories->isEmpty())
            <div class="text-center py-16">
                <div class="bg-white rounded-xl shadow-md p-10 max-w-lg mx-auto border border-gray-200">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-search text-3xl text-gray-400"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-700 mb-3">
                        @if($selectedCategoryId || $selectedSubcategoryId)
                            No results found
                        @else
                            No categories found
                        @endif
                    </h3>
                    <p class="text-gray-500 mb-6">
                        @if($selectedCategoryId || $selectedSubcategoryId || $search)
                            We couldn't find any categories matching your filters. Try adjusting your selection or browse our complete catalog.
                        @else
                            We couldn't find any categories matching your search. Try adjusting your search terms or browse our complete catalog.
                        @endif
                    </p>
                    <a href="{{ route('categories.index') }}"
                    class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                        <i class="fas fa-list-ul mr-2"></i> View All Categories
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Businesses Section (shown when subcategory is selected) -->
    @if($selectedSubcategoryId && $businesses->count() > 0)
    <div class="mb-8">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-2xl font-bold text-gray-800">
                Businesses in {{ $allSubcategories->find($selectedSubcategoryId)?->name }}
                <span class="text-lg text-gray-600">({{ $businesses->total() }} found)</span>
            </h2>
        </div>

        <!-- Businesses Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($businesses as $business)
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-lg transition-shadow duration-300 overflow-hidden">
                    <!-- Business Header -->
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <h3 class="text-xl font-bold text-gray-900 mb-2 line-clamp-2">
                                    {{ $business->business_name }}
                                </h3>
                                <div class="flex items-center text-sm text-gray-600 mb-2">
                                    <i class="fas fa-map-marker-alt mr-2 text-gray-400"></i>
                                    <span>{{ $business->city }}, {{ $business->country }}</span>
                                </div>
                            </div>
                            <!-- Business Type Badge -->
                            <div class="flex-shrink-0 ml-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ ucfirst($business->property_type ?? 'Business') }}
                                </span>
                            </div>
                        </div>

                        <!-- Business Details -->
                        <div class="space-y-2 mb-4">
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-folder mr-2 text-gray-400"></i>
                                <span>
                                    @if($business->category)
                                        {{ $allCategories->find($business->category)?->name ?? $business->category }}
                                    @else
                                        N/A
                                    @endif
                                </span>
                                @if($business->subcategory)
                                    <i class="fas fa-chevron-right mx-2 text-gray-300"></i>
                                    <span class="text-blue-600">
                                        {{ $allSubcategories->find($business->subcategory)?->name ??
                                           \App\Models\Subcategory::find($business->subcategory)?->name ??
                                           $business->subcategory }}
                                    </span>
                                @endif
                            </div>

                            @if($business->employee_count)
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-users mr-2 text-gray-400"></i>
                                    <span>{{ $business->employee_count }} employees</span>
                                </div>
                            @endif

                            @if($business->annual_revenue)
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-chart-line mr-2 text-gray-400"></i>
                                    <span>${{ $business->annual_revenue }} annual revenue</span>
                                </div>
                            @endif

                            @if($business->domain)
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-globe mr-2 text-gray-400"></i>
                                    <a href="{{ $business->domain }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                        Visit Website
                                    </a>
                                </div>
                            @endif

                            @if($business->business_email)
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-envelope mr-2 text-gray-400"></i>
                                    <a href="mailto:{{ $business->business_email }}" class="text-blue-600 hover:text-blue-800">
                                        {{ $business->business_email }}
                                    </a>
                                </div>
                            @endif
                        </div>                        <!-- Action Button -->
                        <div class="pt-4 border-t border-gray-100">
                            <a href="{{ url('/property/' . $business->id) }}"
                               class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-4 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 flex items-center justify-center">
                                <i class="fas fa-eye mr-2"></i>
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($businesses->hasPages())
            <div class="mt-8 flex justify-center">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                    {{ $businesses->links() }}
                </div>
            </div>
        @endif
    </div>
    @elseif($selectedSubcategoryId && $businesses->count() == 0)
    <!-- No Businesses Message -->
    <div class="mb-8">
        <div class="text-center py-12">
            <div class="bg-white rounded-xl shadow-md p-8 max-w-lg mx-auto border border-gray-200">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-building text-3xl text-gray-400"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-700 mb-3">No Businesses Found</h3>
                <p class="text-gray-500 mb-6">
                    We couldn't find any businesses in the selected subcategory yet.
                    Try selecting a different subcategory or browse our complete catalog.
                </p>
                <a href="{{ route('categories.index') }}"
                   class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                    <i class="fas fa-list-ul mr-2"></i> Browse All Categories
                </a>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Success Toast -->
@if(session('success'))
    <div x-data="{ show: true }"
        x-show="show"
        x-init="setTimeout(() => show = false, 4000)"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform translate-y-2"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform translate-y-0"
        x-transition:leave-end="opacity-0 transform translate-y-2"
        class="fixed bottom-4 right-4 bg-green-600 text-white px-6 py-4 rounded-lg shadow-xl flex items-center z-50">
        <i class="fas fa-check-circle mr-3 text-xl"></i>
        <div>
            <p class="font-medium">{{ session('success') }}</p>
        </div>
        <button @click="show = false" class="ml-6 text-white hover:text-white/80 focus:outline-none">
            <i class="fas fa-times"></i>
        </button>
    </div>
@endif
@endsection

@push('scripts')
<script>
    // Helper function to determine the appropriate icon for each category
    function getCategoryIcon(name) {
        const icons = {
            'Restaurants': 'fa-utensils',
            'Hotels': 'fa-hotel',
            'Shopping': 'fa-shopping-bag',
            'Automotive': 'fa-car',
            'Beauty': 'fa-spa',
            'Health': 'fa-heartbeat',
            'Home Services': 'fa-home',
            'Financial Services': 'fa-dollar-sign',
            'Education': 'fa-graduation-cap',
            'Technology': 'fa-laptop',
            'Entertainment': 'fa-film',
            'Travel': 'fa-plane',
            // Add more mappings as needed
        };

        return icons[name] || 'fa-folder';
    }

    // Alpine.js component for category filtering - must be defined before Alpine loads
    window.categoryFilter = function() {
        return {
            selectedCategory: '{{ $selectedCategoryId ?? '' }}',
            selectedSubcategory: '{{ $selectedSubcategoryId ?? '' }}',
            subcategories: [],

            init() {
                console.log('CategoryFilter initialized with category:', this.selectedCategory);
                // Initialize subcategories if category is already selected
                if (this.selectedCategory) {
                    this.onCategoryChange();
                }
            },

            async onCategoryChange() {
                console.log('Category changed to:', this.selectedCategory);

                if (!this.selectedCategory) {
                    this.subcategories = [];
                    this.selectedSubcategory = '';
                    return;
                }

                try {
                    console.log('Fetching subcategories for category:', this.selectedCategory);
                    const response = await fetch(`{{ route('categories.subcategories') }}?category_id=${this.selectedCategory}`);

                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }

                    const data = await response.json();
                    console.log('Received subcategories:', data);
                    this.subcategories = data;

                    // Clear subcategory selection if the previously selected one is not in the new list
                    if (this.selectedSubcategory && !data.find(sub => sub.id == this.selectedSubcategory)) {
                        this.selectedSubcategory = '';
                    }
                } catch (error) {
                    console.error('Error loading subcategories:', error);
                    this.subcategories = [];
                    this.selectedSubcategory = '';
                }
            },

            hasActiveFilters() {
                return this.selectedCategory || this.selectedSubcategory || '{{ $search ?? '' }}';
            }
        }
    }
</script>
@endpush
