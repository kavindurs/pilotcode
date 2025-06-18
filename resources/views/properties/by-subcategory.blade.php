@extends('layouts.app')

@section('title', $subcategory . ' | Businesses and Services')

@section('styles')
<style>
    /* Core styles */
    [x-cloak] { display: none !important; }

    /* Card hover effects */
    .business-card {
        transition: all 0.3s ease;
        border-left: 3px solid transparent;
    }
    .business-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        border-left: 3px solid #3b82f6;
    }

    /* Filter styles */
    .group:hover .border-gray-300 {
        border-color: rgb(96 165 250);
    }

    /* Form element styling */
    select {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        background-image: none;
    }

    /* Custom rating stars */
    .star-rating {
        display: inline-flex;
        position: relative;
    }
    .star-rating .stars-bg {
        color: rgba(250, 204, 21, 0.3);
    }
    .star-rating .stars-fg {
        color: #FACC15;
        position: absolute;
        top: 0;
        left: 0;
        overflow: hidden;
        white-space: nowrap;
    }

    /* Animations */
    .rotate-180 {
        transform: rotate(180deg);
    }
    .transition-transform {
        transition-property: transform;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        transition-duration: 200ms;
    }

    /* Scroll transitions */
    .sticky-filter {
        transition: top 0.3s ease;
    }

    /* Custom checkboxes and radio buttons */
    .custom-control input:checked + .custom-control-label {
        background-color: #EFF6FF;
        border-color: #2563EB;
        color: #1E40AF;
    }

    /* Badge animations */
    .badge {
        transition: all 0.2s ease;
    }
    .badge:hover {
        transform: scale(1.05);
    }
</style>
@endsection

@section('content')
<!-- Page Header -->
<div class="bg-gradient-to-r from-blue-700 to-blue-900 py-6 mb-6 rounded-lg shadow-lg">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between">
            <div>
                <nav class="flex mb-2" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-2 text-sm text-blue-100">
                        <li class="inline-flex items-center">
                            <a href="{{ url('/') }}" class="hover:text-white">
                                <i class="fas fa-home mr-1"></i> Home
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-blue-300 mx-1 text-xs"></i>
                                <a href="{{ route('categories.index') }}" class="hover:text-white">
                                    Categories
                                </a>
                            </div>
                        </li>

                        <!-- filepath: c:\xampp\htdocs\pilot\resources\views\properties\by-subcategory.blade.php -->

                        <li aria-current="page">
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-blue-300 mx-1 text-xs"></i>
                                <!-- Get subcategory name from subcategories table using the ID -->
                                @php
                                    $subcategoryName = DB::table('subcategories')
                                        ->where('id', $subcategory)
                                        ->value('name');
                                @endphp
                                <span class="text-white font-medium">{{ $subcategoryName ?? 'Uncategorized' }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>

                @php
                // This query might already exist in your breadcrumb section
                $subcategoryName = DB::table('subcategories')
                    ->where('id', $subcategory)
                    ->value('name');
                @endphp

                <!-- Update the heading to use the name -->
                <h1 class="text-2xl md:text-3xl font-bold text-white mb-1 mt-5">{{ $subcategoryName ?? 'Uncategorized' }}</h1>
                <p class="text-blue-100">
                    Showing {{ $properties->count() }} {{ Str::plural('business', $properties->count()) }} in this category
                </p>
            </div>
            @auth
            <div class="mt-4 md:mt-0">
                <a href="{{ route('property.create.step1') }}" class="inline-flex items-center px-4 py-2 bg-white text-blue-700 rounded-lg font-medium hover:bg-blue-50 transition-colors shadow-md">
                    <i class="fas fa-plus-circle mr-2"></i>
                    Add Your Business
                </a>
            </div>
            @endauth
        </div>
    </div>
</div>

<div class="container mx-auto pb-10">
    <!-- Mobile Filter Toggle -->
    <div class="lg:hidden mb-4">
        <button type="button"
                id="mobileFilterToggle"
                onclick="document.getElementById('filterModal').classList.toggle('hidden')"
                class="w-full bg-white rounded-lg shadow-md p-4 flex items-center justify-between border border-gray-100">
            <div class="flex items-center">
                <i class="fas fa-sliders-h text-blue-600 mr-2"></i>
                <span class="font-medium text-gray-800">Filters</span>
            </div>
            <i class="fas fa-chevron-down text-blue-600 transition-transform duration-200" id="filterIcon"></i>
        </button>
    </div>

    <div class="flex flex-col lg:flex-row gap-6">
        <!-- Desktop Filter Sidebar -->
        <div class="hidden lg:block w-72 flex-shrink-0">
            <div class="bg-white rounded-lg shadow-md sticky-filter top-4 border border-gray-100">
                <form action="{{ route('properties.subcategory', $subcategory) }}" method="GET">
                    <!-- Header -->
                    <div class="bg-gray-50 rounded-t-lg px-5 py-4 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-bold text-gray-800">Refine Results</h2>
                            <button type="button"
                                    id="clearFilters"
                                    class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                <i class="fas fa-undo-alt mr-1"></i> Clear all
                            </button>
                        </div>
                    </div>

                    <div class="p-5 space-y-6">
                        <!-- Rating Filter -->
                        <div>
                            <h3 class="text-sm font-bold text-gray-800 mb-3 flex items-center">
                                <i class="fas fa-star text-yellow-400 mr-2"></i>Rating
                            </h3>
                            <div class="flex flex-wrap gap-2">
                                <label class="relative inline-flex custom-control">
                                    <input type="radio"
                                           name="rating"
                                           value="any"
                                           class="absolute w-full h-full opacity-0 cursor-pointer rating-input"
                                           {{ request('rating', 'any') == 'any' ? 'checked' : '' }}>
                                    <span class="custom-control-label px-4 py-2 rounded-full text-sm border border-gray-300 transition-colors bg-white hover:border-blue-400">
                                        Any
                                    </span>
                                </label>
                                @foreach(['3.0+', '4.0+', '4.5+'] as $value)
                                <label class="relative inline-flex custom-control">
                                    <input type="radio"
                                           name="rating"
                                           value="{{ $value }}"
                                           class="absolute w-full h-full opacity-0 cursor-pointer rating-input"
                                           {{ request('rating') == $value ? 'checked' : '' }}>
                                    <span class="custom-control-label px-4 py-2 rounded-full text-sm border border-gray-300 transition-colors bg-white hover:border-blue-400">
                                        <i class="fas fa-star text-yellow-400 mr-1"></i>{{ $value }}
                                    </span>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Location Filter -->
                        <div>
                            <h3 class="text-sm font-bold text-gray-800 mb-3 flex items-center">
                                <i class="fas fa-map-marker-alt text-blue-600 mr-2"></i>Location
                            </h3>
                            <div class="space-y-3">
                                <div class="relative rounded-lg shadow-sm">
                                    <select name="country"
                                            class="w-full rounded-lg border border-gray-300 pr-10 pl-4 py-2.5 text-sm focus:ring-blue-500 bg-white appearance-none">
                                        <option value="">All Countries</option>
                                        @foreach($countries ?? [] as $country)
                                            <option value="{{ $country }}" {{ request('country') == $country ? 'selected' : '' }}>
                                                {{ $country }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none">
                                        <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                                    </div>
                                </div>
                                <div class="relative rounded-lg shadow-sm">
                                    <input type="text"
                                           name="zip_code"
                                           placeholder="City or ZIP code"
                                           value="{{ request('zip_code') }}"
                                           class="w-full rounded-lg border border-gray-300 pl-10 pr-4 py-2.5 text-sm focus:ring-blue-500 focus:border-blue-500">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <i class="fas fa-search text-gray-400 text-sm"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Property Type Filter -->
                        <div>
                            <h3 class="text-sm font-bold text-gray-800 mb-3 flex items-center">
                                <i class="fas fa-building text-blue-600 mr-2"></i>Business Type
                            </h3>
                            <div class="space-y-2 bg-gray-50 rounded-lg p-4 border border-gray-200">
                                @foreach(['physical' => 'Physical Business', 'web' => 'Web Business'] as $value => $label)
                                <label class="flex items-center py-1.5 px-1 rounded hover:bg-gray-100 transition-colors cursor-pointer">
                                    <div class="flex items-center h-5">
                                        <input type="checkbox"
                                               name="property_type[]"
                                               value="{{ $value }}"
                                               {{ in_array($value, request('property_type', [])) ? 'checked' : '' }}
                                               class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <span class="text-gray-700 font-medium">{{ $label }}</span>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Apply Filters Button -->
                        <button type="submit"
                                class="w-full bg-blue-600 text-white rounded-lg py-3 font-medium hover:bg-blue-700 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 flex items-center justify-center">
                            <i class="fas fa-filter mr-2"></i>
                            Apply Filters
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Results Container -->
        <div class="flex-grow">
            @if($properties->isEmpty())
                <div class="bg-white rounded-lg shadow-md p-8 text-center border border-gray-100">
                    <div class="w-16 h-16 bg-blue-100 rounded-full mx-auto flex items-center justify-center mb-4">
                        <i class="fas fa-search text-blue-500 text-2xl"></i>
                    </div>
                    <h2 class="text-xl font-bold text-gray-800 mb-2">No businesses found</h2>
                    <p class="text-gray-600 mb-6">
                        We couldn't find any businesses matching your criteria. Try adjusting your filters or check back later.
                    </p>
                    <button type="button"
                            id="clearAllFilters"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors">
                        <i class="fas fa-undo-alt mr-2"></i>
                        Reset Filters
                    </button>
                </div>
            @else
                <!-- Sort/Filter Bar -->
                <div class="bg-white rounded-lg shadow-sm mb-4 px-4 py-3 flex justify-between items-center border border-gray-100">
                    <div class="text-sm text-gray-600">
                        <strong>{{ $properties->count() }}</strong> {{ Str::plural('result', $properties->count()) }} found
                    </div>
                    <!-- Could add sorting options here in the future -->
                </div>

                <!-- Enhanced Business Card Design -->
                <div class="space-y-4">
                    @foreach($properties as $property)
                    <div class="business-card bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden hover:shadow-lg transition-all duration-300">
                        <div class="p-4">
                            <!-- Mobile Layout with Reduced Spacing -->
                            <div class="flex flex-col sm:hidden">
                                <!-- Combined Business Name and Property Type Badge with Less Margin -->
                                <div class="flex items-center mb-2">
                                    <h3 class="text-lg font-bold text-gray-800 truncate mr-2">
                                        <a href="{{ route('property.show', $property) }}" class="hover:text-blue-600 transition-colors">
                                            {{ $property->business_name }}
                                        </a>
                                    </h3>
                                </div>

                                <!-- Reduced Spacing in Logo and Info Area -->
                                <div class="flex items-start space-x-3 mb-3">
                                    <!-- Smaller Logo Container -->
                                    <div class="w-16 h-16 flex-shrink-0 bg-white rounded-lg overflow-hidden shadow-md border border-gray-200 relative">
                                        @if($property->profile_picture)
                                            <img src="{{ asset('storage/' . $property->profile_picture) }}"
                                                 alt="{{ $property->business_name }}"
                                                 class="w-full h-full object-cover">
                                        @elseif($property->document_path)
                                            <img src="{{ asset('storage/' . $property->document_path) }}"
                                                 alt="{{ $property->business_name }}"
                                                 class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center bg-gray-50">
                                                <i class="fas fa-building text-xl text-blue-300"></i>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Reduced Spacing in Rating and Location -->
                                    <div class="flex-grow">
                                        <p class="text-xs text-gray-600 flex items-center mb-1.5">
                                            <span class="flex-shrink-0 w-4 h-4 flex items-center justify-center text-blue-500 mr-1">
                                                <i class="fas fa-map-marker-alt"></i>
                                            </span>
                                            <span>{{ $property->city }}, {{ $property->country }}</span>
                                        </p>

                                        @if($property->rates_count > 0)
                                            <div class="flex items-center">
                                                <span class="text-sm font-semibold text-blue-700 mr-1.5">
                                                    {{ number_format($property->rates_avg_rate ?? 0, 1) }}
                                                </span>
                                                <div class="star-rating text-sm">

                                                    <div class="stars-fg" style="width: {{ ($property->rates_avg_rate / 5) * 100 }}%">★★★★★</div>
                                                </div>
                                                <span class="ml-1 text-xs text-gray-500">({{ $property->rates_count }})</span>
                                            </div>
                                        @else
                                            <div class="flex items-center text-gray-400 italic text-xs">
                                                <i class="fas fa-star mr-1"></i> No reviews yet
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- More Compact Mobile Details Grid -->
                                <div class="grid grid-cols-2 gap-2 mb-3">
                                    <div class="bg-gray-50 rounded-md p-2 flex items-center">
                                        <span class="w-5 h-5 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 mr-2">
                                            <i class="fas fa-envelope text-xs"></i>
                                        </span>
                                        <span class="text-xs text-gray-700 truncate">{{ $property->business_email }}</span>
                                    </div>

                                    @if($property->domain)
                                    <div class="bg-gray-50 rounded-md p-2 flex items-center">
                                        <span class="w-5 h-5 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 mr-2">
                                            <i class="fas fa-globe text-xs"></i>
                                        </span>
                                        <a href="{{ $property->domain }}" target="_blank"
                                           class="text-xs text-blue-600 hover:text-blue-800 hover:underline truncate">
                                            Website
                                        </a>
                                    </div>
                                    @endif

                                    <div class="bg-gray-50 rounded-md p-2 flex items-center">
                                        <span class="w-5 h-5 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 mr-2">
                                            <i class="fas fa-users text-xs"></i>
                                        </span>
                                        <span class="text-xs text-gray-700">{{ $property->employee_count }}</span>
                                    </div>

                                    <div class="bg-gray-50 rounded-md p-2 flex items-center">
                                        <span class="w-5 h-5 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 mr-2">
                                            <i class="fas fa-chart-line text-xs"></i>
                                        </span>
                                        <span class="text-xs text-gray-700">{{ $property->annual_revenue }}</span>
                                    </div>
                                </div>

                                <!-- Condensed Category/Review Section -->
                                <div class="border-t border-gray-100 pt-3">
                                    <div class="flex flex-wrap gap-1.5 mb-2">
                                        <span class="inline-flex items-center px-2 py-0.5 bg-blue-50 text-blue-700 rounded-md text-xs font-medium border border-blue-100">
                                            <i class="fas fa-tag mr-1 text-blue-400"></i>
                                            {{ $property->category }}
                                        </span>
                                        <span class="inline-flex items-center px-2 py-0.5 bg-indigo-50 text-indigo-700 rounded-md text-xs font-medium border border-indigo-100">
                                            <i class="fas fa-bookmark mr-1 text-indigo-400"></i>
                                            {{ $property->subcategory }}
                                        </span>
                                        <span class="inline-flex items-center px-2 py-0.5 ml-1.5 rounded-md text-xs font-medium border {{ strtolower($property->property_type) == 'web' ? 'bg-purple-50 text-purple-700 border-purple-100' : 'bg-green-50 text-green-700 border-green-100' }}">
                                            <i class="fas {{ strtolower($property->property_type) == 'web' ? 'fa-globe' : 'fa-store' }} mr-1 {{ strtolower($property->property_type) == 'web' ? 'text-purple-400' : 'text-green-400' }}"></i>
                                            {{ $property->property_type }}
                                        </span>
                                    </div>

                                    @auth
                                    <a href="{{ route('rate.create', $property) }}"
                                       class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-blue-600 text-white text-xs rounded-md hover:bg-blue-700 transition-colors shadow-sm">
                                        <i class="fas fa-star mr-1.5"></i>
                                        Write Review
                                    </a>
                                    @endauth
                                </div>
                            </div>

                            <!-- Desktop Layout with Reduced Height -->
                            <div class="hidden sm:flex items-start">
                                <!-- Smaller Logo -->
                                <div class="w-20 h-20 flex-shrink-0 bg-white rounded-lg overflow-hidden mr-5 shadow-md border border-gray-200 relative group">
                                    @if($property->profile_picture)
                                        <img src="{{ asset('storage/' . $property->profile_picture) }}"
                                             alt="{{ $property->business_name }}"
                                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    @elseif($property->document_path)
                                        <img src="{{ asset('storage/' . $property->document_path) }}"
                                             alt="{{ $property->business_name }}"
                                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-gray-50">
                                            <i class="fas fa-building text-2xl text-blue-300"></i>
                                        </div>
                                    @endif

                                    <!-- Property Type Badge - Smaller -->

                                </div>

                                <!-- Main Content with Reduced Spacing -->
                                <div class="flex-grow min-w-0">
                                    <!-- More Compact Header Section -->
                                    <div class="flex justify-between items-start mb-2">
                                        <div>
                                            <h3 class="text-lg font-bold text-gray-800 mb-1 group">
                                                <a href="{{ route('property.show', $property) }}" class="hover:text-blue-600 transition-colors inline-flex items-center">
                                                    <span class="truncate">{{ $property->business_name }}</span>
                                                    <i class="fas fa-external-link-alt ml-2 text-xs text-gray-400 group-hover:text-blue-500 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                                                </a>
                                            </h3>

                                            <!-- Location with Smaller Icon -->
                                            <p class="text-xs text-gray-600 flex items-center">
                                                <span class="flex-shrink-0 w-4 h-4 rounded-full bg-blue-50 flex items-center justify-center text-blue-500 mr-1.5">
                                                    <i class="fas fa-map-marker-alt text-xs"></i>
                                                </span>
                                                <span>{{ $property->city }}, {{ $property->country }}</span>
                                            </p>
                                        </div>

                                        <!-- Condensed Rating Section -->
                                        <div class="text-right">
                                            @if($property->rates_count > 0)
                                                <div class="flex items-center justify-end">
                                                    <span class="text-xl font-bold text-blue-700 mr-1.5">
                                                        {{ number_format($property->rates_avg_rate ?? 0, 1) }}
                                                    </span>
                                                    <div class="star-rating text-lg">

                                                        <div class="stars-fg" style="width: {{ ($property->rates_avg_rate / 5) * 100 }}%">★★★★★</div>
                                                    </div>
                                                </div>
                                                <span class="text-xs text-gray-500">{{ $property->rates_count }} {{ Str::plural('review', $property->rates_count) }}</span>
                                            @else
                                                <div class="bg-gray-50 px-2 py-1 rounded-md border border-gray-200">
                                                    <span class="text-xs text-gray-500 italic">No reviews yet</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Flatter Details Grid -->
                                    <div class="grid grid-cols-4 gap-2 mb-3">
                                        <div class="bg-gray-50 rounded-md p-2 flex items-center border border-gray-100">
                                            <span class="w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 mr-2">
                                                <i class="fas fa-envelope text-xs"></i>
                                            </span>
                                            <div class="truncate">
                                                <div class="text-xs text-gray-500 leading-tight">Email</div>
                                                <div class="text-xs font-medium text-gray-700 truncate">{{ $property->business_email }}</div>
                                            </div>
                                        </div>

                                        @if($property->domain)
                                        <div class="bg-gray-50 rounded-md p-2 flex items-center border border-gray-100">
                                            <span class="w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 mr-2">
                                                <i class="fas fa-globe text-xs"></i>
                                            </span>
                                            <div class="truncate">
                                                <div class="text-xs text-gray-500 leading-tight">Website</div>
                                                <a href="{{ $property->domain }}" target="_blank"
                                                   class="text-xs font-medium text-blue-600 hover:text-blue-800 hover:underline truncate block">
                                                    {{ str_replace(['http://', 'https://'], '', $property->domain) }}
                                                </a>
                                            </div>
                                        </div>
                                        @endif

                                        <div class="bg-gray-50 rounded-md p-2 flex items-center border border-gray-100">
                                            <span class="w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 mr-2">
                                                <i class="fas fa-users text-xs"></i>
                                            </span>
                                            <div>
                                                <div class="text-xs text-gray-500 leading-tight">Team</div>
                                                <div class="text-xs font-medium text-gray-700">{{ $property->employee_count }}</div>
                                            </div>
                                        </div>

                                        <div class="bg-gray-50 rounded-md p-2 flex items-center border border-gray-100">
                                            <span class="w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 mr-2">
                                                <i class="fas fa-chart-line text-xs"></i>
                                            </span>
                                            <div>
                                                <div class="text-xs text-gray-500 leading-tight">Revenue</div>
                                                <div class="text-xs font-medium text-gray-700">{{ $property->annual_revenue }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Compact Footer Section -->
                                    <div class="flex items-center justify-between border-t border-gray-100 pt-2">
                                        <div class="flex items-center gap-1.5">
                                            <span class="inline-flex items-center px-2 py-0.5 bg-blue-50 text-blue-700 rounded-md text-xs font-medium border border-blue-100">
                                                <i class="fas fa-tag mr-1 text-blue-400"></i>
                                                {{ $property->category }}
                                            </span>
                                            <i class="fas fa-chevron-right text-gray-300 text-xs"></i>
                                            <span class="inline-flex items-center px-2 py-0.5 bg-indigo-50 text-indigo-700 rounded-md text-xs font-medium border border-indigo-100">
                                                <i class="fas fa-bookmark mr-1 text-indigo-400"></i>
                                                {{ $property->subcategory }}
                                            </span>
                                            <span class="inline-flex items-center px-2 py-0.5 ml-1.5 rounded-md text-xs font-medium border {{ strtolower($property->property_type) == 'web' ? 'bg-purple-50 text-purple-700 border-purple-100' : 'bg-green-50 text-green-700 border-green-100' }}">
                                                <i class="fas {{ strtolower($property->property_type) == 'web' ? 'fa-globe' : 'fa-store' }} mr-1 {{ strtolower($property->property_type) == 'web' ? 'text-purple-400' : 'text-green-400' }}"></i>
                                                {{ $property->property_type }}
                                            </span>
                                        </div>

                                        @auth
                                        <a href="{{ route('rate.create', $property) }}"
                                           class="inline-flex items-center px-3 py-2.5 bg-blue-600 text-white text-xs rounded-md hover:bg-blue-700 transition-colors shadow-sm">
                                            <i class="fas fa-star mr-1.5"></i>
                                            Write Review
                                        </a>
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Mobile Filter Toggle Button -->
<div class="fixed bottom-4 right-4 lg:hidden z-20">
    <button type="button"
            onclick="document.getElementById('filterModal').classList.toggle('hidden')"
            class="bg-blue-600 text-white rounded-full p-4 shadow-lg hover:bg-blue-700 transition-colors">
        <i class="fas fa-filter"></i>
    </button>
</div>

<!-- Mobile Filter Modal -->
<div id="filterModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden lg:hidden">
    <div class="absolute inset-x-0 bottom-0 bg-white rounded-t-xl max-h-[90vh] overflow-y-auto shadow-xl">
        <div class="relative p-5">
            <div class="flex justify-between items-center mb-5 pb-2 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-800">Filter Results</h3>
                <button type="button"
                        onclick="document.getElementById('filterModal').classList.add('hidden')"
                        class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <!-- Filter form content will be cloned here via JavaScript -->
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Clone filter form for mobile modal
    const filterForm = document.querySelector('#filterModal .p-5');
    const originalForm = document.querySelector('.sticky-filter form').cloneNode(true);
    filterForm.appendChild(originalForm);

    // Update mobile form title
    const mobileFormHeader = filterForm.querySelector('.flex.items-center.justify-between');
    if (mobileFormHeader) {
        mobileFormHeader.remove(); // Remove the cloned header
    }

    // Clear filters functionality
    const clearButtons = document.querySelectorAll('#clearFilters, #clearAllFilters');
    clearButtons.forEach(button => {
        button.addEventListener('click', function() {
            const form = this.closest('form') || document.querySelector('.sticky-filter form');

            // Clear radio buttons
            form.querySelectorAll('input[type="radio"]').forEach(radio => {
                radio.checked = radio.value === 'any';
            });

            // Clear checkboxes
            form.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
                checkbox.checked = false;
            });

            // Clear select dropdowns
            form.querySelectorAll('select').forEach(select => {
                select.value = '';
            });

            // Clear text inputs
            form.querySelectorAll('input[type="text"]').forEach(input => {
                input.value = '';
            });

            // Submit the form to update results
            form.submit();
        });
    });

    // Rating inputs styling and interaction
    const ratingInputs = document.querySelectorAll('.rating-input');
    ratingInputs.forEach(input => {
        // Set initial state
        if (input.checked) {
            input.nextElementSibling.classList.add('bg-blue-50', 'border-blue-600', 'text-blue-700');
            input.nextElementSibling.classList.remove('border-gray-300', 'text-gray-600');
        } else {
            input.nextElementSibling.classList.add('border-gray-300', 'text-gray-600');
            input.nextElementSibling.classList.remove('bg-blue-50', 'border-blue-600', 'text-blue-700');
        }

        // Add change event listener
        input.addEventListener('change', function() {
            // Remove active classes from all labels
            ratingInputs.forEach(radio => {
                radio.nextElementSibling.classList.remove('bg-blue-50', 'border-blue-600', 'text-blue-700');
                radio.nextElementSibling.classList.add('border-gray-300', 'text-gray-600');
            });

            // Add active classes to selected label
            if (this.checked) {
                this.nextElementSibling.classList.add('bg-blue-50', 'border-blue-600', 'text-blue-700');
                this.nextElementSibling.classList.remove('border-gray-300', 'text-gray-600');
            }
        });
    });

    // Make sure the mobile clone also works
    const mobileForm = document.querySelector('#filterModal form');
    if (mobileForm) {
        const mobileRatingInputs = mobileForm.querySelectorAll('.rating-input');
        mobileRatingInputs.forEach(input => {
            // Set initial state for mobile
            if (input.checked) {
                input.nextElementSibling.classList.add('bg-blue-50', 'border-blue-600', 'text-blue-700');
                input.nextElementSibling.classList.remove('border-gray-300', 'text-gray-600');
            } else {
                input.nextElementSibling.classList.add('border-gray-300', 'text-gray-600');
                input.nextElementSibling.classList.remove('bg-blue-50', 'border-blue-600', 'text-blue-700');
            }

            // Add change event listener for mobile
            input.addEventListener('change', function() {
                mobileRatingInputs.forEach(radio => {
                    radio.nextElementSibling.classList.remove('bg-blue-50', 'border-blue-600', 'text-blue-700');
                    radio.nextElementSibling.classList.add('border-gray-300', 'text-gray-600');
                });

                if (this.checked) {
                    this.nextElementSibling.classList.add('bg-blue-50', 'border-blue-600', 'text-blue-700');
                    this.nextElementSibling.classList.remove('border-gray-300', 'text-gray-600');
                }
            });
        });
    }

    // Mobile filter toggle functionality
    const mobileFilterToggle = document.getElementById('mobileFilterToggle');
    const filterIcon = document.getElementById('filterIcon');
    const filterModal = document.getElementById('filterModal');

    if (mobileFilterToggle && filterModal) {
        mobileFilterToggle.addEventListener('click', function() {
            filterIcon.classList.toggle('rotate-180');

            // Update filter count
            const activeFilters = getActiveFilterCount();
            updateFilterCount(activeFilters);
        });
    }

    // Close modal button functionality
    const closeModalButton = document.querySelector('#filterModal button[type="button"]');
    if (closeModalButton) {
        closeModalButton.addEventListener('click', function() {
            filterIcon.classList.remove('rotate-180');
        });
    }

    // Function to count active filters
    function getActiveFilterCount() {
        const form = filterModal.querySelector('form');
        let count = 0;

        // Count checked radio buttons (rating)
        const checkedRating = form.querySelector('input[name="rating"]:checked');
        if (checkedRating && checkedRating.value !== 'any') count++;

        // Count selected country
        if (form.querySelector('select[name="country"]').value) count++;

        // Count entered zip code
        if (form.querySelector('input[name="zip_code"]').value) count++;

        // Count checked property types
        const checkedPropertyTypes = form.querySelectorAll('input[name="property_type[]"]:checked');
        if (checkedPropertyTypes.length) count++;

        return count;
    }

    // Function to update filter count badge
    function updateFilterCount(count) {
        let badge = mobileFilterToggle.querySelector('.filter-count');
        if (count > 0) {
            if (!badge) {
                badge = document.createElement('span');
                badge.className = 'filter-count ml-2 px-2 py-0.5 bg-blue-100 text-blue-700 rounded-full text-xs font-medium';
                mobileFilterToggle.querySelector('.flex').appendChild(badge);
            }
            badge.textContent = count;
        } else if (badge) {
            badge.remove();
        }
    }

    // Initialize filter count on page load
    const activeFilters = getActiveFilterCount();
    updateFilterCount(activeFilters);

    // Handle form changes
    filterModal.querySelector('form').addEventListener('change', function() {
        const activeFilters = getActiveFilterCount();
        updateFilterCount(activeFilters);
    });

    // Scroll behavior for sticky sidebar
    let lastScrollTop = 0;
    const stickyFilter = document.querySelector('.sticky-filter');
    if (stickyFilter) {
        window.addEventListener('scroll', function() {
            const st = window.pageYOffset || document.documentElement.scrollTop;
            if (st > lastScrollTop) {
                // Scrolling down
                stickyFilter.style.top = '4px';
            } else {
                // Scrolling up
                stickyFilter.style.top = '20px';
            }
            lastScrollTop = st <= 0 ? 0 : st;
        }, false);
    }
});
</script>
@endpush
