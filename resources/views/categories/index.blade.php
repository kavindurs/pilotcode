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
</style>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-0 py-10">
    <!-- Hero Section -->
    <div class="relative overflow-hidden bg-gradient-to-r from-blue-700 to-blue-900 rounded-2xl shadow-xl mb-12 hero-pattern">
        <div class="absolute inset-0 bg-blue-900 opacity-30"></div>
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

    <!-- Categories Section -->
    <div class="mb-8">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-2xl font-bold text-gray-800">
                {{ isset($search) && $search ? 'Search Results' : 'All Categories' }}
            </h2>
            @if(isset($search) && $search)
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
                             x-collapse.duration.300ms
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
                    <h3 class="text-xl font-bold text-gray-700 mb-3">No categories found</h3>
                    <p class="text-gray-500 mb-6">We couldn't find any categories matching your search. Try adjusting your search terms or browse our complete catalog.</p>
                    <a href="{{ route('categories.index') }}"
                    class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                        <i class="fas fa-list-ul mr-2"></i> View All Categories
                    </a>
                </div>
            </div>
        @endif
    </div>
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

@section('scripts')
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
</script>
@endsection
