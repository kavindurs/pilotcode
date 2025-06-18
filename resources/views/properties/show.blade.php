<!-- resources/views/properties/show.blade.php -->
@extends('layouts.app')

@section('title', $property->business_name . ' | Property Details')

@section('styles')
<style>
    /* Star rating styles */
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

    /* Business card animations */
    .property-header {
        transition: all 0.3s ease;
    }

    /* Review animations */
    .review-card {
        transition: all 0.2s ease;
        border-left: 3px solid transparent;
    }
    .review-card:hover {
        transform: translateY(-2px);
        border-left: 3px solid #3b82f6;
    }


</style>
@endsection



@section('content')
<!-- Page Header -->
<div class="bg-gradient-to-r from-blue-700 to-blue-900 py-6 mb-6 rounded-lg">
    <div class="container mx-auto px-4">
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
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-blue-300 mx-1 text-xs"></i>
                        <!-- Fix the category route to display name from categories table -->
                        @php
                            $categoryName = DB::table('categories')
                                ->where('id', $property->category)
                                ->value('name');
                        @endphp
                        <a href="{{ route('categories.index', $property->category) }}" class="hover:text-white">
                            {{ $categoryName ?? 'Uncategorized' }}
                        </a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-blue-300 mx-1 text-xs"></i>
                        <!-- Fix the subcategory route to display the name from subcategories table -->
                        @php
                            $subcategoryName = DB::table('subcategories')
                                ->where('id', $property->subcategory)
                                ->value('name');
                        @endphp
                        <a href="{{ route('properties.subcategory', $property->subcategory) }}" class="hover:text-white">
                            {{ $subcategoryName ?? 'Uncategorized' }}
                        </a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-blue-300 mx-1 text-xs"></i>
                        <span class="text-white font-medium truncate">{{ $property->business_name }}</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>
</div>

<div class="container mx-auto pb-10">
    <div class="flex flex-col lg:flex-row gap-6">
        <!-- Main Content -->
        <div class="flex-grow">
            <!-- Business Header Card -->
            <div class="property-header bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden mb-6">
                <!-- Business Header -->
                <div class="p-6">
                    <div class="flex flex-col md:flex-row md:items-start">
                        <!-- Logo/Image Section -->
                        <div class="w-full md:w-48 h-48 bg-white rounded-lg overflow-hidden mb-6 md:mb-0 md:mr-6 shadow-md border border-gray-200 relative flex-shrink-0">
                            @if($property->profile_picture)
                                <img src="{{ asset('storage/' . $property->profile_picture) }}"
                                     alt="{{ $property->business_name }}"
                                     class="w-full h-full object-cover">
                            @elseif($property->document_path)
                                <img src="{{ asset('storage/' . $property->document_path) }}"
                                     alt="{{ $property->business_name }}"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-50 to-blue-100">
                                    <i class="fas fa-building text-5xl text-blue-300"></i>
                                </div>
                            @endif

                        </div>

                        <!-- Business Details -->
                        <div class="flex-grow">
                            <div class="flex flex-wrap justify-between items-start">
                                <div class="flex-grow max-w-2xl">
                                    <!-- Business Name and Verified Badge -->
                                    <div class="flex items-center mb-2">
                                        <h1 class="text-2xl md:text-3xl font-bold text-gray-800">{{ $property->business_name }}</h1>
                                        @if($property->status == 'Approved')
                                            <span class="ml-2 px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full font-medium flex items-center">
                                                <i class="fas fa-check-circle mr-1"></i> Verified
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Category Tags -->
                                    <div class="flex flex-wrap gap-2 mb-3">
                                        @php
                                            $categoryName = DB::table('categories')
                                                ->where('id', $property->category)
                                                ->value('name');

                                            $subcategoryName = DB::table('subcategories')
                                                ->where('id', $property->subcategory)
                                                ->value('name');
                                        @endphp

                                        <!-- Property Type Badge (moved from image section) -->
                                        <span class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-md shadow-sm {{ strtolower($property->property_type) == 'web' ? 'bg-purple-100 text-purple-800 border border-purple-200' : 'bg-green-100 text-green-800 border border-green-200' }}">
                                            <i class="fas {{ strtolower($property->property_type) == 'web' ? 'fa-globe' : 'fa-store' }} mr-2"></i>
                                            {{ $property->property_type }}
                                        </span>

                                        <span class="inline-flex items-center px-3 py-1 bg-blue-50 text-blue-700 rounded-md text-sm font-medium border border-blue-100">
                                            <i class="fas fa-tag mr-2 text-blue-400"></i>
                                            {{ $categoryName ?? 'Uncategorized' }}
                                        </span>

                                        <span class="inline-flex items-center px-3 py-1 bg-indigo-50 text-indigo-700 rounded-md text-sm font-medium border border-indigo-100">
                                            <i class="fas fa-bookmark mr-2 text-indigo-400"></i>
                                            {{ $subcategoryName ?? 'Uncategorized' }}
                                        </span>
                                    </div>

                                    <!-- Location with Map Link -->
                                    <p class="text-gray-600 flex items-center mb-4">
                                        <span class="flex-shrink-0 w-5 h-5 rounded-full bg-blue-50 flex items-center justify-center text-blue-500 mr-2">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </span>
                                        <span>{{ $property->city }}, {{ $property->country }} {{ $property->zip_code }}</span>
                                        <a href="https://maps.google.com/?q={{ urlencode($property->city . ', ' . $property->country) }}"
                                           target="_blank"
                                           class="ml-2 text-blue-600 hover:text-blue-800 text-xs underline">
                                            <i class="fas fa-map"></i> View on map
                                        </a>
                                    </p>

                                    <!-- HTML Integration Content -->
                                    @php
                                        $activeIntegration = DB::table('html_integrations')
                                            ->where('property_id', $property->id)
                                            ->where('is_active', 1)
                                            ->first();
                                    @endphp

                                    @if($activeIntegration)
                                        <div class="mt-3 mb-4 p-3 bg-gray-50 rounded-lg border border-gray-100">
                                            <div class="text-xs text-gray-500 mb-2 flex items-center">
                                                <i class="fas fa-puzzle-piece mr-1 text-blue-400"></i>
                                                {{ $activeIntegration->title }}
                                            </div>
                                            <div class="html-integration-content">
                                                {!! $activeIntegration->html_content !!}
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <!-- Rating Section -->
                                <div class="bg-gradient-to-br from-blue-50 to-blue-100 px-5 py-4 rounded-lg border border-blue-200 text-center shadow-sm ml-0 md:ml-4 mt-4 md:mt-0 w-full md:w-auto">
                                    @if($property->rates_count > 0)
                                        <div class="text-3xl font-bold text-blue-700 mb-1">
                                            {{ number_format($property->rates_avg_rate ?? 0, 1) }}
                                        </div>
                                        <div class="star-rating text-xl mb-1">

                                            <div class="stars-fg" style="width: {{ ($property->rates_avg_rate / 5) * 100 }}%">★★★★★</div>
                                        </div>
                                        <div class="text-sm text-gray-600">
                                            Based on {{ $property->rates_count }} {{ Str::plural('review', $property->rates_count) }}
                                        </div>
                                    @else
                                        <div class="text-lg text-gray-500 italic">
                                            <i class="fas fa-star text-gray-400 mr-1"></i> No reviews yet
                                        </div>
                                        <div class="text-sm text-gray-500 mt-1">Be the first to review</div>
                                    @endif

                                    @auth
                                    <a href="{{ route('rate.create', $property) }}"
                                       class="mt-3 inline-block w-full px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700 transition-colors shadow-sm">
                                        <i class="fas fa-star mr-2"></i>
                                        Write a Review
                                    </a>
                                    @endauth
                                </div>
                            </div>

                            <!-- Business Details Grid -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mt-6">
                                <div class="bg-gradient-to-r from-gray-50 to-white rounded-md p-3 flex items-center border border-gray-100 hover:shadow-sm transition-shadow">
                                    <span class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 mr-3 shadow-sm">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                    <div>
                                        <div class="text-xs text-gray-500 mb-0.5">Email</div>
                                        <div class="text-sm font-medium text-gray-700 truncate max-w-[140px]">{{ $property->business_email }}</div>
                                    </div>
                                </div>

                                @if($property->domain)
                                <div class="bg-gradient-to-r from-gray-50 to-white rounded-md p-3 flex items-center border border-gray-100 hover:shadow-sm transition-shadow">
                                    <span class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 mr-3 shadow-sm">
                                        <i class="fas fa-globe"></i>
                                    </span>
                                    <div>
                                        <div class="text-xs text-gray-500 mb-0.5">Website</div>
                                        <a href="{{ $property->domain }}" target="_blank"
                                           class="text-sm font-medium text-blue-600 hover:text-blue-800 hover:underline block truncate max-w-[140px]">
                                            {{ str_replace(['http://', 'https://'], '', $property->domain) }}
                                        </a>
                                    </div>
                                </div>
                                @endif

                                <div class="bg-gradient-to-r from-gray-50 to-white rounded-md p-3 flex items-center border border-gray-100 hover:shadow-sm transition-shadow">
                                    <span class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 mr-3 shadow-sm">
                                        <i class="fas fa-users"></i>
                                    </span>
                                    <div>
                                        <div class="text-xs text-gray-500 mb-0.5">Team Size</div>
                                        <div class="text-sm font-medium text-gray-700">{{ $property->employee_count }} {{ Str::plural('employee', (int)$property->employee_count) }}</div>
                                    </div>
                                </div>

                                <div class="bg-gradient-to-r from-gray-50 to-white rounded-md p-3 flex items-center border border-gray-100 hover:shadow-sm transition-shadow">
                                    <span class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 mr-3 shadow-sm">
                                        <i class="fas fa-chart-line"></i>
                                    </span>
                                    <div>
                                        <div class="text-xs text-gray-500 mb-0.5">Annual Revenue</div>
                                        <div class="text-sm font-medium text-gray-700">{{ $property->annual_revenue }}</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Call-to-Action Buttons -->
                            <div class="flex flex-wrap gap-3 mt-6 pt-6 border-t border-gray-100">

                                <a href="{{ $property->domain }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                                    <i class="fas {{ strtolower($property->property_type) == 'web' ? 'fa-external-link-alt' : 'fa-globe' }} mr-2"></i>
                                    Visit {{ strtolower($property->property_type) == 'web' ? 'Website' : 'Website' }}
                                </a>

                                <button class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors">
                                    <i class="fas fa-share-alt mr-2"></i> Share
                                </button>
                                <button class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors">
                                    <i class="far fa-bookmark mr-2"></i> Save
                                </button>
                            </div>

                            <!-- Social Media Links -->
                            @php
                                $socialMediaWidgets = DB::table('widgets')
                                    ->where('property_id', $property->id)
                                    ->where('is_active', 1)
                                    ->whereIn('widget_type', ['instagram', 'linkedin', 'youtube', 'facebook'])
                                    ->get();

                                // Better quality icons for each platform
                                $socialIconUrls = [
                                    'instagram' => 'https://cdn-icons-png.flaticon.com/16/2111/2111463.png',
                                    'linkedin' => 'https://cdn-icons-png.flaticon.com/16/3536/3536505.png',
                                    'youtube' => 'https://cdn-icons-png.flaticon.com/16/2504/2504965.png',
                                    'facebook' => 'https://cdn-icons-png.flaticon.com/16/733/733547.png'
                                ];
                            @endphp

                            @if($socialMediaWidgets->count() > 0)
                                <div class="mt-4 flex flex-wrap items-center">
                                    <div class="text-sm text-gray-500 mr-3">
                                        <i class="fas fa-globe mr-1.5"></i> Follow on:
                                    </div>
                                    <div class="flex items-center gap-2">
                                        @foreach($socialMediaWidgets as $widget)
                                            <a href="{{ $widget->link_url }}"
                                            target="_blank"
                                            class="social-icon-link"
                                            title="Follow on {{ ucfirst($widget->widget_type) }}">
                                                <img src="{{ $socialIconUrls[$widget->widget_type] }}"
                                                    alt="{{ ucfirst($widget->widget_type) }}"/>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Text Widget Section -->
            <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden mt-6 mb-6">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-amber-50 to-white">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-bold text-gray-800 flex items-center">
                            <span class="w-8 h-8 flex items-center justify-center bg-amber-100 text-amber-600 rounded-full mr-2">
                                <i class="fas fa-file-alt"></i>
                            </span>
                            About This Business
                        </h2>

                        @auth
                            @if(auth()->user()->id == $property->user_id || auth()->user()->user_type == 'admin')
                            <a href="{{ route('property.widgets.create', ['type' => 'text']) }}"
                                class="inline-flex items-center px-3 py-1.5 bg-amber-600 text-white text-sm rounded-md hover:bg-amber-700 transition-colors shadow-sm">
                                <i class="fas fa-plus mr-1.5"></i>
                                Add Content
                            </a>
                            @endif
                        @endauth
                    </div>
                </div>

                <div class="p-6">
                    @php
                        $textWidgets = DB::table('widgets')
                            ->where('property_id', $property->id)
                            ->where('widget_type', 'text')
                            ->where('is_active', 1)
                            ->orderBy('order')
                            ->get();
                    @endphp

                    @if($textWidgets->count() > 0)
                        <div class="space-y-8">
                            @foreach($textWidgets as $widget)
                                <div class="text-widget">
                                    @if($widget->title)
                                        <h3 class="text-lg font-semibold text-gray-800 mb-3">{{ $widget->title }}</h3>
                                    @endif

                                    <div class="prose max-w-none">
                                        {!! $widget->content !!}
                                    </div>

                                    @if($widget->image_path)
                                        <div class="mt-4">
                                            <img src="{{ asset('storage/' . $widget->image_path) }}"
                                                    alt="{{ $widget->title ?? 'Business information' }}"
                                                    class="rounded-lg max-h-80 object-cover">
                                        </div>
                                    @endif

                                    @if($widget->link_url)
                                        <div class="mt-4">
                                            <a href="{{ $widget->link_url }}" target="_blank"
                                                class="inline-flex items-center text-amber-600 hover:text-amber-800">
                                                <span>{{ $widget->settings && isset(json_decode($widget->settings, true)['link_text']) ? json_decode($widget->settings, true)['link_text'] : 'Learn More' }}</span>
                                                <i class="fas fa-external-link-alt ml-1 text-xs"></i>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-10">
                            <div class="w-20 h-20 bg-amber-50 rounded-full mx-auto flex items-center justify-center mb-4 shadow-inner">
                                <i class="fas fa-file-alt text-amber-300 text-3xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 mb-2">No information added</h3>
                            <p class="text-gray-600 mb-6 max-w-md mx-auto">
                                This business hasn't added detailed information about their services yet.
                            </p>

                            @auth
                                @if(auth()->user()->id == $property->user_id)
                                <a href="{{ route('property.widgets.create', ['type' => 'text']) }}"
                                    class="inline-flex items-center px-5 py-2.5 bg-amber-600 text-white rounded-md hover:bg-amber-700 transition-colors shadow-sm">
                                    <i class="fas fa-plus mr-2"></i>
                                    Add Information
                                </a>
                                @endif
                            @endauth
                        </div>
                    @endif
                </div>
            </div>



            <!-- Reviews Section with Professional Design and Three-Column Layout -->
            <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-white reviews-section-header">
                    <div class="flex items-center justify-between reviews-section-header">
                        <h2 class="text-xl font-bold text-gray-800 flex items-center">
                            <span class="w-8 h-8 flex items-center justify-center bg-blue-100 text-blue-600 rounded-full mr-2">
                                <i class="fas fa-star"></i>
                            </span>
                            Customer Reviews
                        </h2>
                        @auth
                        <a href="{{ route('rate.create', $property) }}"
                           class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700 transition-colors shadow-sm">
                            <i class="fas fa-edit mr-1.5"></i>
                            Write a Review
                        </a>
                        @endauth
                    </div>
                </div>

                <div class="p-6">
                    @if(isset($property->rates) && $property->rates->count() > 0)
                        <!-- Review Summary -->
                        <div class="flex items-center justify-between mb-6 pb-6 border-b border-gray-100">
                            <div class="flex items-end gap-3">
                                <div class="text-center">
                                    <div class="text-4xl font-bold text-blue-700 mb-0.5">
                                        {{ number_format($property->rates_avg_rate ?? 0, 1) }}
                                    </div>
                                    <div class="star-rating text-2xl">

                                        <div class="stars-fg" style="width: {{ ($property->rates_avg_rate / 5) * 100 }}%">★★★★★</div>
                                    </div>
                                    <div class="text-sm text-gray-500 mt-1">
                                        {{ $property->rates_count }} {{ Str::plural('review', $property->rates_count) }}
                                    </div>
                                </div>

                                <div class="h-12 border-r border-gray-200 mx-2"></div>

                                <div class="flex-grow">
                                    <div class="grid grid-cols-5 gap-1 items-center text-sm">
                                        <div class="col-span-1 text-right pr-2">5 stars</div>
                                        <div class="col-span-3">
                                            <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                                                <div class="h-full bg-blue-600 rounded-full" style="width: {{ $property->rates->where('rate', 5)->count() / max($property->rates_count, 1) * 100 }}%"></div>
                                            </div>
                                        </div>
                                        <div class="col-span-1 text-left pl-2">{{ $property->rates->where('rate', 5)->count() }}</div>

                                        <div class="col-span-1 text-right pr-2">4 stars</div>
                                        <div class="col-span-3">
                                            <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                                                <div class="h-full bg-blue-600 rounded-full" style="width: {{ $property->rates->where('rate', 4)->count() / max($property->rates_count, 1) * 100 }}%"></div>
                                            </div>
                                        </div>
                                        <div class="col-span-1 text-left pl-2">{{ $property->rates->where('rate', 4)->count() }}</div>

                                        <div class="col-span-1 text-right pr-2">3 stars</div>
                                        <div class="col-span-3">
                                            <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                                                <div class="h-full bg-blue-600 rounded-full" style="width: {{ $property->rates->where('rate', 3)->count() / max($property->rates_count, 1) * 100 }}%"></div>
                                            </div>
                                        </div>
                                        <div class="col-span-1 text-left pl-2">{{ $property->rates->where('rate', 3)->count() }}</div>

                                        <div class="col-span-1 text-right pr-2">2 stars</div>
                                        <div class="col-span-3">
                                            <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                                                <div class="h-full bg-blue-600 rounded-full" style="width: {{ $property->rates->where('rate', 2)->count() / max($property->rates_count, 1) * 100 }}%"></div>
                                            </div>
                                        </div>
                                        <div class="col-span-1 text-left pl-2">{{ $property->rates->where('rate', 2)->count() }}</div>

                                        <div class="col-span-1 text-right pr-2">1 star</div>
                                        <div class="col-span-3">
                                            <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                                                <div class="h-full bg-blue-600 rounded-full" style="width: {{ $property->rates->where('rate', 1)->count() / max($property->rates_count, 1) * 100 }}%"></div>
                                            </div>
                                        </div>
                                        <div class="col-span-1 text-left pl-2">{{ $property->rates->where('rate', 1)->count() }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Three Reviews Per Row Layout - Limited to 6 initially -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($property->rates->take(6) as $review)
                                <div class="review-card bg-white rounded-lg shadow-sm border border-gray-200 p-4 h-full flex flex-col">
                                    <div class="flex items-start justify-between mb-3 pb-3 border-b border-gray-50">
                                        <div class="flex items-center">
                                            <!-- User profile picture - dynamically loads from users table -->
                                            <div class="w-10 h-10 rounded-full overflow-hidden bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center text-blue-600 mr-3 shadow-sm">
                                                @if($review->user && $review->user->profile_picture)
                                                    <img src="{{ asset('storage/' . $review->user->profile_picture) }}"
                                                         alt="{{ $review->user->name ?? 'User' }}"
                                                         class="w-full h-full object-cover">
                                                @else
                                                    <i class="fas fa-user"></i>
                                                @endif
                                            </div>
                                            <div>
                                                <h3 class="font-bold text-gray-800">{{ $review->user->name ?? 'Anonymous' }}</h3>
                                                <p class="text-xs text-gray-500">{{ $review->created_at->format('M d, Y') }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center border border-gray-100 px-2 py-1 rounded-md bg-gray-50">
                                            <span class="text-sm font-bold text-blue-700 mr-1.5">{{ number_format($review->rate, 1) }}</span>
                                            <div class="star-rating text-sm">

                                                <div class="stars-fg" style="width: {{ ($review->rate / 5) * 100 }}%">★★★★★</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex-grow">
                                        <!-- Show review text from the review column in rates table -->
                                        @if($review->review)
                                            <p class="text-gray-700 text-sm leading-relaxed">
                                                {{ Str::limit($review->review, 150) }}
                                            </p>
                                            @if(strlen($review->review) > 150)
                                                <button class="text-blue-600 hover:text-blue-800 text-xs mt-1 focus:outline-none"
                                                        onclick="toggleReviewText(this)">
                                                    Read more
                                                </button>
                                                <p class="text-gray-700 text-sm leading-relaxed hidden full-review">
                                                    {{ $review->review }}
                                                </p>
                                            @endif
                                        @else
                                            <p class="text-gray-500 italic text-sm">No comment provided</p>
                                        @endif
                                    </div>

                                    <!-- Review Footer with Helpful Button -->
                                    <div class="mt-3 pt-3 border-t border-gray-50 flex justify-between items-center">
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
                                        <button class="text-xs text-gray-500 hover:text-blue-600 flex items-center focus:outline-none">
                                            <i class="far fa-thumbs-up mr-1"></i> Helpful
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Hidden reviews section (initially hidden) -->
                        <div id="all-reviews" class="hidden grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
                            @foreach($property->rates->skip(6) as $review)
                                <div class="review-card bg-white rounded-lg shadow-sm border border-gray-200 p-4 h-full flex flex-col">
                                    <div class="flex items-start justify-between mb-3 pb-3 border-b border-gray-50">
                                        <div class="flex items-center">
                                            <!-- User profile picture - dynamically loads from users table -->
                                            <div class="w-10 h-10 rounded-full overflow-hidden bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center text-blue-600 mr-3 shadow-sm">
                                                @if($review->user && $review->user->profile_picture)
                                                    <img src="{{ asset('storage/' . $review->user->profile_picture) }}"
                                                         alt="{{ $review->user->name ?? 'User' }}"
                                                         class="w-full h-full object-cover">
                                                @else
                                                    <i class="fas fa-user"></i>
                                                @endif
                                            </div>
                                            <div>
                                                <h3 class="font-bold text-gray-800">{{ $review->user->name ?? 'Anonymous' }}</h3>
                                                <p class="text-xs text-gray-500">{{ $review->created_at->format('M d, Y') }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center border border-gray-100 px-2 py-1 rounded-md bg-gray-50">
                                            <span class="text-sm font-bold text-blue-700 mr-1.5">{{ number_format($review->rate, 1) }}</span>
                                            <div class="star-rating text-sm">

                                                <div class="stars-fg" style="width: {{ ($review->rate / 5) * 100 }}%">★★★★★</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex-grow">
                                        <!-- Show review text from the review column in rates table -->
                                        @if($review->review)
                                            <p class="text-gray-700 text-sm leading-relaxed">
                                                {{ Str::limit($review->review, 150) }}
                                            </p>
                                            @if(strlen($review->review) > 150)
                                                <button class="text-blue-600 hover:text-blue-800 text-xs mt-1 focus:outline-none"
                                                        onclick="toggleReviewText(this)">
                                                    Read more
                                                </button>
                                                <p class="text-gray-700 text-sm leading-relaxed hidden full-review">
                                                    {{ $review->review }}
                                                </p>
                                            @endif
                                        @else
                                            <p class="text-gray-500 italic text-sm">No comment provided</p>
                                        @endif
                                    </div>

                                    <!-- Review Footer with Helpful Button -->
                                    <div class="mt-3 pt-3 border-t border-gray-50 flex justify-between items-center">
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
                                        <button class="text-xs text-gray-500 hover:text-blue-600 flex items-center focus:outline-none">
                                            <i class="far fa-thumbs-up mr-1"></i> Helpful
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- View All Reviews Button - show only if there are more than 6 reviews -->
                        @if($property->rates->count() > 6)
                            <div class="mt-6 flex justify-center">
                                <button id="view-all-btn" onclick="toggleAllReviews()"
                                        class="px-4 py-2 bg-blue-50 text-blue-600 rounded-md hover:bg-blue-100 transition-colors text-sm">
                                    View all {{ $property->rates_count }} reviews
                                </button>
                            </div>
                        @endif

                    @else
                        <div class="text-center py-10">
                            <div class="w-20 h-20 bg-blue-50 rounded-full mx-auto flex items-center justify-center mb-4 shadow-inner">
                                <i class="fas fa-comment-alt text-blue-500 text-3xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 mb-2">No reviews yet</h3>
                            <p class="text-gray-600 mb-6 max-w-md mx-auto">This business hasn't received any reviews yet. Be the first to share your experience!</p>

                            @auth
                            <a href="{{ route('rate.create', $property) }}"
                               class="inline-flex items-center px-5 py-2.5 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors shadow-sm">
                                <i class="fas fa-star mr-2"></i>
                                Be the first to write a review
                            </a>
                            @else
                            <a href="{{ route('login') }}"
                               class="inline-flex items-center px-5 py-2.5 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors shadow-sm">
                                <i class="fas fa-sign-in-alt mr-2"></i>
                                Log in to write a review
                            </a>
                            @endauth
                        </div>
                    @endif
                </div>
            </div>

                        <!-- Products Section -->
                        <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden mt-6">
                            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-white">
                                <div class="flex items-center justify-between">
                                    <h2 class="text-xl font-bold text-gray-800 flex items-center">
                                        <span class="w-8 h-8 flex items-center justify-center bg-green-100 text-green-600 rounded-full mr-2">
                                            <i class="fas fa-shopping-bag"></i>
                                        </span>
                                        Products & Services
                                    </h2>

                                    @auth
                                        @if(auth()->user()->id == $property->user_id || auth()->user()->user_type == 'admin')
                                        <a href="{{ route('property.products.create') }}"
                                           class="inline-flex items-center px-3 py-1.5 bg-green-600 text-white text-sm rounded-md hover:bg-green-700 transition-colors shadow-sm">
                                            <i class="fas fa-plus mr-1.5"></i>
                                            Add Product
                                        </a>
                                        @endif
                                    @endauth
                                </div>
                            </div>

                            <div class="p-6">
                                @php
                                    $products = DB::table('products')
                                        ->where('property_id', $property->id)
                                        ->where('is_active', 1)
                                        ->orderBy('is_featured', 'desc')
                                        ->orderBy('created_at', 'desc')
                                        ->get();
                                @endphp

                                @if($products->count() > 0)
                                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                                        @foreach($products as $product)
                                            <div class="product-card bg-white rounded-lg border border-gray-200 overflow-hidden hover:shadow-md transition-shadow flex flex-col h-full">
                                                <!-- Product Image -->
                                                <div class="h-48 overflow-hidden bg-gray-100 relative">
                                                    @if($product->image_path)
                                                        <img src="{{ asset('storage/' . $product->image_path) }}"
                                                             alt="{{ $product->name }}"
                                                             class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                                                    @else
                                                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-50 to-gray-100">
                                                            <i class="fas fa-box text-3xl text-gray-300"></i>
                                                        </div>
                                                    @endif

                                                    @if($product->is_featured)
                                                        <div class="absolute top-2 left-2">
                                                            <span class="bg-yellow-500 text-white text-xs font-bold px-2 py-1 rounded shadow-sm">
                                                                Featured
                                                            </span>
                                                        </div>
                                                    @endif

                                                    @if($product->stock_quantity <= 0)
                                                        <div class="absolute top-2 right-2">
                                                            <span class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded shadow-sm">
                                                                Out of Stock
                                                            </span>
                                                        </div>
                                                    @elseif($product->stock_quantity < 10)
                                                        <div class="absolute top-2 right-2">
                                                            <span class="bg-orange-500 text-white text-xs font-bold px-2 py-1 rounded shadow-sm">
                                                                Low Stock
                                                            </span>
                                                        </div>
                                                    @endif
                                                </div>

                                                <!-- Product Details -->
                                                <div class="p-4 flex-grow">
                                                    <div class="flex justify-between items-start">
                                                        <h3 class="font-bold text-gray-800 mb-1">{{ $product->name }}</h3>
                                                        <span class="text-green-600 font-bold">
                                                            ${{ number_format($product->price, 2) }}
                                                        </span>
                                                    </div>

                                                    @if($product->category)
                                                        <div class="mb-2">
                                                            <span class="inline-block bg-blue-50 text-blue-700 rounded-full px-2 py-0.5 text-xs">
                                                                {{ $product->category }}
                                                            </span>
                                                        </div>
                                                    @endif

                                                    <p class="text-gray-600 text-sm line-clamp-3 mb-3">
                                                        {{ $product->description ?? 'No description available' }}
                                                    </p>

                                                    <div class="flex items-center text-xs text-gray-500 mt-2">
                                                        <i class="fas fa-box-open mr-1"></i>
                                                        <span>Stock: {{ $product->stock_quantity > 0 ? $product->stock_quantity : 'Out of stock' }}</span>
                                                    </div>
                                                </div>

                                                <!-- Product Footer -->
                                                <div class="p-4 pt-0 mt-auto">
                                                    <a href="#" onclick="showProductDetails({{ $product->id }})"
                                                       class="w-full inline-block text-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded transition-colors text-sm">
                                                        View Details
                                                    </a>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    @if($products->count() > 8)
                                        <div class="mt-6 text-center">
                                            <a href="{{ route('property.products.all', $property) }}"
                                               class="inline-flex items-center px-4 py-2 bg-blue-50 text-blue-600 rounded-md hover:bg-blue-100 transition-colors text-sm">
                                                <i class="fas fa-shopping-basket mr-2"></i>
                                                View All Products ({{ DB::table('products')->where('property_id', $property->id)->where('is_active', 1)->count() }})
                                            </a>
                                        </div>
                                    @endif
                                @else
                                    <div class="text-center py-10">
                                        <div class="w-20 h-20 bg-gray-50 rounded-full mx-auto flex items-center justify-center mb-4 shadow-inner">
                                            <i class="fas fa-box-open text-gray-300 text-3xl"></i>
                                        </div>
                                        <h3 class="text-xl font-bold text-gray-800 mb-2">No products listed</h3>
                                        <p class="text-gray-600 mb-6 max-w-md mx-auto">
                                            This business hasn't added any products or services to their listing yet.
                                        </p>

                                        @auth
                                            @if(auth()->user()->id == $property->user_id)
                                            <a href="{{ route('property.products.create') }}"
                                               class="inline-flex items-center px-5 py-2.5 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors shadow-sm">
                                                <i class="fas fa-plus mr-2"></i>
                                                Add Your First Product
                                            </a>
                                            @endif
                                        @endauth
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- FAQ Section -->
                        <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden mt-6">
                            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-white">
                                <div class="flex items-center justify-between">
                                    <h2 class="text-xl font-bold text-gray-800 flex items-center">
                                        <span class="w-8 h-8 flex items-center justify-center bg-indigo-100 text-indigo-600 rounded-full mr-2">
                                            <i class="fas fa-question-circle"></i>
                                        </span>
                                        Frequently Asked Questions
                                    </h2>

                                    @auth
                                        @if(auth()->user()->id == $property->user_id || auth()->user()->user_type == 'admin')
                                        <a href="{{ route('property.widgets.create', ['type' => 'faq']) }}"
                                           class="inline-flex items-center px-3 py-1.5 bg-indigo-600 text-white text-sm rounded-md hover:bg-indigo-700 transition-colors shadow-sm">
                                            <i class="fas fa-plus mr-1.5"></i>
                                            Add FAQ
                                        </a>
                                        @endif
                                    @endauth
                                </div>
                            </div>

                            <div class="p-6">
                                @php
                                    $faqWidget = DB::table('widgets')
                                        ->where('property_id', $property->id)
                                        ->where('widget_type', 'faq')
                                        ->where('is_active', 1)
                                        ->orderBy('order')
                                        ->first();

                                    $faqs = $faqWidget ? json_decode($faqWidget->content, true) : [];
                                @endphp

                                @if($faqWidget && count($faqs) > 0)
                                    <div class="divide-y divide-gray-100" x-data="{active: null}">
                                        @foreach($faqs as $index => $faq)
                                            <div class="py-4 first:pt-0 last:pb-0" x-data="{id: {{ $index }}}">
                                                <button
                                                    @click="active = active === id ? null : id"
                                                    class="flex w-full text-left justify-between items-center font-medium focus:outline-none"
                                                >
                                                    <span class="text-gray-800 font-semibold">{{ $faq['question'] }}</span>
                                                    <span class="ml-6 flex-shrink-0">
                                                        <i class="fas fa-chevron-down text-indigo-500 transition-transform duration-300"
                                                           :class="{'rotate-180': active === id}"></i>
                                                    </span>
                                                </button>
                                                <div
                                                    x-show="active === id"
                                                    x-transition:enter="transition ease-out duration-200"
                                                    x-transition:enter-start="opacity-0 transform -translate-y-2"
                                                    x-transition:enter-end="opacity-100 transform translate-y-0"
                                                    x-transition:leave="transition ease-in duration-150"
                                                    x-transition:leave-start="opacity-100 transform translate-y-0"
                                                    x-transition:leave-end="opacity-0 transform -translate-y-2"
                                                    class="mt-3 text-gray-600 text-sm"
                                                >
                                                    <p class="whitespace-pre-line">{{ $faq['answer'] }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    @if($faqWidget->title)
                                        <div class="mt-6 pt-4 border-t border-gray-100 text-center">

                                        </div>
                                    @endif

                                    @if($faqWidget->link_url)
                                        <div class="mt-4 text-center">
                                            <a href="{{ $faqWidget->link_url }}"
                                               class="inline-flex items-center px-4 py-2 bg-indigo-50 text-indigo-600 rounded-md hover:bg-indigo-100 transition-colors text-sm">
                                                <i class="fas fa-external-link-alt mr-2"></i>
                                                View more FAQs
                                            </a>
                                        </div>
                                    @endif
                                @else
                                    <div class="text-center py-10">
                                        <div class="w-20 h-20 bg-indigo-50 rounded-full mx-auto flex items-center justify-center mb-4 shadow-inner">
                                            <i class="fas fa-question text-indigo-300 text-3xl"></i>
                                        </div>
                                        <h3 class="text-xl font-bold text-gray-800 mb-2">No FAQs available</h3>
                                        <p class="text-gray-600 mb-6 max-w-md mx-auto">
                                            This business hasn't added any frequently asked questions yet.
                                        </p>

                                        @auth
                                            @if(auth()->user()->id == $property->user_id)
                                            <a href="{{ route('property.widgets.create', ['type' => 'faq']) }}"
                                               class="inline-flex items-center px-5 py-2.5 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors shadow-sm">
                                                <i class="fas fa-plus mr-2"></i>
                                                Add Your First FAQ
                                            </a>
                                            @endif
                                        @endauth
                                    </div>
                                @endif
                            </div>
                        </div>
        </div>
    </div>
</div>

<!-- Add this script to handle "Read more" functionality -->
<script>
function toggleReviewText(button) {
    const card = button.closest('.review-card');
    const fullReview = card.querySelector('.full-review');

    if (fullReview.classList.contains('hidden')) {
        fullReview.classList.remove('hidden');
        button.innerText = 'Read less';
    } else {
        fullReview.classList.add('hidden');
        button.innerText = 'Read more';
    }
}

function toggleAllReviews() {
    const allReviews = document.getElementById('all-reviews');
    const viewAllBtn = document.getElementById('view-all-btn');

    if (allReviews.classList.contains('hidden')) {
        // Show all reviews
        allReviews.classList.remove('hidden');
        viewAllBtn.textContent = 'Show less';
    } else {
        // Hide additional reviews
        allReviews.classList.add('hidden');
        viewAllBtn.textContent = 'View all {{ $property->rates_count }} reviews';

        // Scroll back to reviews section header
        document.querySelector('.reviews-section-header').scrollIntoView({ behavior: 'smooth' });
    }
}
</script>

<!-- Add this script for product modal functionality -->
<script>
function showProductDetails(productId) {
    // Show modal and loading state
    document.getElementById('productModal').classList.remove('hidden');
    document.body.classList.add('overflow-hidden');

    // Fetch product details
    fetch(`/api/products/${productId}`)
        .then(response => response.json())
        .then(product => {
            document.getElementById('modalProductName').textContent = product.name;

            let imageHtml = product.image_path
                ? `<img src="${product.image_path}" alt="${product.name}" class="w-full h-auto rounded-lg">`
                : `<div class="w-full h-64 flex items-center justify-center bg-gray-100 rounded-lg"><i class="fas fa-box text-4xl text-gray-300"></i></div>`;

            let stockStatus = '';
            if (product.stock_quantity <= 0) {
                stockStatus = '<span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded">Out of Stock</span>';
            } else if (product.stock_quantity < 10) {
                stockStatus = `<span class="bg-orange-100 text-orange-800 text-xs font-medium px-2.5 py-0.5 rounded">Low Stock: ${product.stock_quantity} left</span>`;
            } else {
                stockStatus = `<span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">In Stock: ${product.stock_quantity} available</span>`;
            }

            const formattedPrice = new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'USD'
            }).format(product.price);

            let html = `
                <div class="flex flex-col md:flex-row md:space-x-6">
                    <div class="md:w-1/3 mb-4 md:mb-0">
                        ${imageHtml}
                    </div>
                    <div class="md:w-2/3">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                ${product.category ? `<span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">${product.category}</span>` : ''}
                                ${product.is_featured ? `<span class="ml-2 bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded">Featured</span>` : ''}
                            </div>
                            <div class="text-2xl font-bold text-green-600">${formattedPrice}</div>
                        </div>

                        <div class="mb-4">
                            ${stockStatus}
                        </div>

                        <h4 class="text-sm font-medium text-gray-500 mb-2">Product Description</h4>
                        <p class="text-gray-700 mb-6">${product.description || 'No description available.'}</p>

                    </div>
                </div>
            `;

            document.getElementById('modalContent').innerHTML = html;
            document.getElementById('contactSellerBtn').href = `mailto:${product.property_email}?subject=Inquiry about ${product.name}`;
        })
        .catch(error => {
            document.getElementById('modalContent').innerHTML = `
                <div class="text-center py-8">
                    <div class="text-red-500 mb-2"><i class="fas fa-exclamation-circle text-2xl"></i></div>
                    <h3 class="text-lg font-bold text-gray-800 mb-1">Error Loading Product</h3>
                    <p class="text-gray-600">There was a problem loading the product details. Please try again.</p>
                </div>
            `;
        });
}

function closeProductModal() {
    document.getElementById('productModal').classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

// Close modal when clicking outside of it
document.getElementById('productModal').addEventListener('click', function(event) {
    if (event.target === this) {
        closeProductModal();
    }
});
</script>
@endsection
