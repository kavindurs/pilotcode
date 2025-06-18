<!-- filepath: c:\xampp\htdocs\pilot\resources\views\search\results.blade.php -->
@extends('layouts.app')

@section('content')
<div class="bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Search Results for "{{ $query }}"</h1>
            <p class="mt-2 text-gray-600">Found {{ $total }} results</p>
        </div>

        <!-- Search form for refining search -->
        <div class="mb-8 bg-white p-4 rounded-lg shadow-sm">
            <form action="{{ route('search') }}" method="GET" class="flex">
                <div class="flex-grow">
                    <input
                        type="text"
                        name="query"
                        value="{{ $query }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Refine your search..."
                    >
                </div>
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-r-md hover:bg-blue-700">
                    Search
                </button>
            </form>
        </div>

        <!-- Business Results -->
        @if($properties->count() > 0)
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Businesses</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
                @foreach($properties as $property)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
                        <a href="{{ route('property.show', $property->id) }}" class="block">
                            <div class="p-4">
                                <div class="flex items-start space-x-4">
                                    <!-- Business logo/image -->
                                    <div class="flex-shrink-0">
                                        @if($property->profile_picture)
                                            <img
                                                src="{{ Storage::url($property->profile_picture) }}"
                                                alt="{{ $property->business_name }}"
                                                class="w-16 h-16 object-cover rounded-md border border-gray-200"
                                                onerror="this.onerror=null; this.src='{{ asset('images/default-business.png') }}';"
                                            />
                                        @else
                                            <div class="w-16 h-16 bg-blue-100 rounded-md border border-gray-200 flex items-center justify-center">
                                                <span class="text-lg font-semibold text-blue-600">{{ substr($property->business_name, 0, 2) }}</span>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Business details -->
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-lg font-semibold text-gray-900 mb-1 truncate">{{ $property->business_name }}</h3>
                                        <p class="text-sm text-gray-500 mb-2 flex flex-wrap items-center">
                                            @php
                                                // Fetch category name from database
                                                $categoryName = DB::table('categories')
                                                    ->where('id', $property->category)
                                                    ->value('name');

                                                // Fetch subcategory name from database
                                                $subcategoryName = DB::table('subcategories')
                                                    ->where('id', $property->subcategory)
                                                    ->value('name');
                                            @endphp



                                            <!-- Subcategory Badge -->
                                            @if($subcategoryName)
                                                <a href="{{ route('search', ['subcategory' => $property->subcategory]) }}"
                                                class="inline-flex items-center bg-indigo-50 hover:bg-indigo-100 transition-colors rounded-md px-2.5 py-1 text-xs font-medium text-indigo-700 mr-2 mb-1">
                                                    <i class="fas fa-bookmark mr-1.5"></i>
                                                    {{ $subcategoryName }}
                                                </a>
                                            @endif

                                            <!-- Property Type Badge -->
                                            @if($property->property_type)
                                                <span class="inline-flex items-center rounded-md px-2.5 py-1 text-xs font-medium mb-1
                                                    {{ $property->property_type == 'Web'
                                                        ? 'bg-purple-50 text-purple-700'
                                                        : 'bg-green-50 text-green-700' }}">
                                                    <i class="fas {{ $property->property_type == 'Web' ? 'fa-globe' : 'fa-store' }} mr-1.5"></i>
                                                    {{ $property->property_type }}
                                                </span>
                                            @endif
                                        </p>
                                        <p class="text-sm text-gray-500">{{ $property->city }}, {{ $property->country }}</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Category Results -->
        @if($subcategories->count() > 0)
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Categories</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mb-8">
                @foreach($subcategories as $subcategory)
                    <a href="{{ route('properties.subcategory', $subcategory->id) }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 hover:shadow-md transition-shadow">
                        <h3 class="text-lg font-semibold text-blue-600">{{ $subcategory->name }}</h3>
                        @if($subcategory->description)
                            <p class="mt-2 text-sm text-gray-600">{{ \Illuminate\Support\Str::limit($subcategory->description, 100) }}</p>
                        @endif
                        <p class="mt-3 text-xs text-gray-500">View all businesses in this category →</p>
                    </a>
                @endforeach
            </div>
        @endif

        @if($properties->count() == 0 && $subcategories->count() == 0)
            <div class="bg-white rounded-lg shadow-sm p-8 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="mt-2 text-lg font-medium text-gray-900">No results found</h3>
                <p class="mt-1 text-gray-500">We couldn't find any businesses or categories matching "{{ $query }}".</p>
                <div class="mt-6">
                    <a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-800">
                        Back to home
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
