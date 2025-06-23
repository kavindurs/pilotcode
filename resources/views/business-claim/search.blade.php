<!doctype html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@if($query)Search Results for "{{ $query }}" @else Search Claimable Businesses @endif | Scoreness</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-white text-text-body">

@php
    use Illuminate\Support\Facades\Storage;
@endphp

    @include('navigation_bars.business_home_nav')

    <!-- Hero Search Section -->
    <div class="relative bg-gradient-to-br from-blue-600 via-purple-600 to-indigo-700 py-20">
        <div class="absolute inset-0 bg-black opacity-20"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-blue-500/30 to-purple-500/30"></div>

        <!-- Decorative elements -->
        <div class="absolute top-10 right-10 w-20 h-20 bg-white/10 rounded-full"></div>
        <div class="absolute bottom-10 left-10 w-32 h-32 bg-white/5 rounded-full"></div>
        <div class="absolute top-1/2 left-1/4 w-16 h-16 bg-white/10 rounded-full"></div>

        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-white/20 backdrop-blur-sm rounded-full mb-8 shadow-lg">
                <i class="fas fa-search text-white text-3xl"></i>
            </div>
            <h1 class="text-5xl font-bold text-white mb-6 leading-tight">
                Find & Claim Your
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 to-orange-300">Business</span>
            </h1>
            <p class="text-xl text-blue-100 mb-12 max-w-2xl mx-auto leading-relaxed">
                Take control of your online presence. Search for your business and start managing customer reviews today.
            </p>

            <!-- Enhanced Search Form -->
            <form method="GET" action="{{ route('business-claim.search') }}" class="max-w-2xl mx-auto">
                <div class="relative group">
                    <div class="absolute inset-0 bg-gradient-to-r from-pink-500 to-violet-500 rounded-2xl blur opacity-75 group-hover:opacity-100 transition duration-300"></div>
                    <div class="relative flex bg-white rounded-2xl shadow-2xl overflow-hidden">
                        <div class="flex-1 relative">
                            <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400 text-lg"></i>
                            </div>
                            <input
                                type="text"
                                name="search"
                                value="{{ $query }}"
                                placeholder="Enter your business name or keyword..."
                                class="w-full pl-14 pr-6 py-5 text-lg text-gray-900 placeholder-gray-500 bg-transparent border-0 focus:outline-none focus:ring-0"
                            />
                        </div>
                        <button
                            type="submit"
                            class="px-8 py-5 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold hover:from-blue-700 hover:to-purple-700 transition-all duration-200 flex items-center space-x-2"
                        >
                            <i class="fas fa-search"></i>
                            <span class="hidden sm:inline">Search</span>
                        </button>
                    </div>
                </div>
            </form>

            <!-- Search stats -->
            <div class="mt-12 grid grid-cols-3 gap-8 text-center">
                <div class="text-white/90">
                    <div class="text-3xl font-bold text-white">10K+</div>
                    <div class="text-sm text-blue-200">Claimable Businesses</div>
                </div>
                <div class="text-white/90">
                    <div class="text-3xl font-bold text-white">2-3 Days</div>
                    <div class="text-sm text-blue-200">Average Review Time</div>
                </div>
                <div class="text-white/90">
                    <div class="text-3xl font-bold text-white">24/7</div>
                    <div class="text-sm text-blue-200">Support Available</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="bg-gradient-to-br from-gray-50 to-blue-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">

            @if($query)
                <!-- Search Results Header -->
                <div class="text-center mb-12">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-4">
                        <i class="fas fa-search-plus text-blue-600 text-xl"></i>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">
                        Search Results for "<span class="text-blue-600">{{ $query }}</span>"
                    </h2>
                    <p class="text-lg text-gray-600">
                        @if($properties->total() > 0)
                            Found <span class="font-semibold text-blue-600">{{ $properties->total() }}</span> claimable {{ Str::plural('business', $properties->total()) }}
                        @else
                            <span class="text-orange-600">No claimable businesses found</span>
                        @endif
                    </p>
                </div>

                @if($properties->count() > 0)
                    <!-- Business Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8 mb-12">
                        @foreach($properties as $property)
                            <div class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100 overflow-hidden">
                                <!-- Business Image -->
                                <div class="relative overflow-hidden">
                                    @if($property->profile_picture)
                                        <img src="{{ Storage::url($property->profile_picture) }}"
                                             alt="{{ $property->business_name }}"
                                             class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                                    @else
                                        <div class="w-full h-48 bg-gradient-to-br from-blue-400 via-purple-500 to-pink-500 flex items-center justify-center relative overflow-hidden">
                                            <div class="absolute inset-0 bg-black/10"></div>
                                            <span class="relative text-white font-bold text-3xl">{{ substr($property->business_name, 0, 2) }}</span>
                                        </div>
                                    @endif

                                    <!-- Claimable Badge -->
                                    <div class="absolute top-4 right-4">
                                        <div class="bg-green-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg flex items-center space-x-1">
                                            <i class="fas fa-hand-holding text-xs"></i>
                                            <span>Claimable</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Business Details -->
                                <div class="p-6 space-y-4">
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2 group-hover:text-blue-600 transition-colors">
                                            {{ $property->business_name }}
                                        </h3>
                                        <div class="flex items-center text-gray-600 text-sm mb-3">
                                            <i class="fas fa-map-marker-alt text-red-500 mr-2"></i>
                                            <span>{{ $property->city }}, {{ $property->country }}</span>
                                        </div>
                                    </div>

                                    <!-- Category Tags -->
                                    <div class="flex flex-wrap gap-2">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200">
                                            <i class="fas fa-tag mr-1"></i>
                                            {{ $property->category }}
                                        </span>
                                        @if($property->subcategory)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 border border-purple-200">
                                                {{ $property->subcategory }}
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Status -->
                                    <div class="flex items-center justify-between">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-700 border border-orange-200">
                                            <i class="fas fa-clock mr-1"></i>
                                            {{ $property->status }}
                                        </span>
                                    </div>

                                    <!-- Claim Button -->
                                    <a href="{{ route('business-claim.create', $property->id) }}"
                                       class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white text-center py-3 px-4 rounded-xl font-semibold hover:from-blue-700 hover:to-purple-700 transition-all duration-200 inline-block shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex items-center justify-center space-x-2">
                                        <i class="fas fa-hand-holding"></i>
                                        <span>Claim Business</span>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                <!-- Pagination -->
                @if($properties->hasPages())
                    <div class="flex justify-center">
                        {{ $properties->appends(request()->query())->links() }}
                    </div>
                @endif
            @else
                <!-- No Results -->
                <div class="text-center py-12">
                    <div class="w-24 h-24 mx-auto mb-4 text-gray-300">
                        <i class="fas fa-search text-6xl"></i>
                    </div>
                    <h3 class="text-xl font-medium text-gray-900 mb-2">No businesses found</h3>
                    <p class="text-gray-600 mb-6">Try adjusting your search terms or browse all claimable businesses.</p>
                    <a href="{{ route('business-claim.search') }}"
                       class="bg-blue-600 text-white px-6 py-3 rounded-md font-medium hover:bg-blue-700 transition-colors">
                        Browse All Claimable Businesses
                    </a>
                </div>
            @endif
        @else
            <!-- Browse All Businesses -->
            <div class="text-center py-12">
                <div class="w-24 h-24 mx-auto mb-4 text-blue-600">
                    <i class="fas fa-building text-6xl"></i>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-4">Claim Your Business</h1>
                <p class="text-gray-600 mb-8 max-w-2xl mx-auto">
                    Search for your business in our directory and claim it to start managing your online presence and customer reviews.
                </p>
                <div class="max-w-md mx-auto">
                    <form method="GET" action="{{ route('business-claim.search') }}">
                        <div class="relative">
                            <input
                                type="text"
                                name="search"
                                placeholder="Enter your business name..."
                                class="w-full py-3 px-4 pl-12 text-base text-gray-900 placeholder-gray-500 bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                required
                            />
                            <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                                <i class="fas fa-search"></i>
                            </div>
                            <button
                                type="submit"
                                class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-blue-600 text-white px-4 py-2 rounded-md text-sm hover:bg-blue-700 transition-colors"
                            >
                                Search
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        <!-- Help Section -->
        <div class="mt-16 bg-blue-50 rounded-lg p-8">
            <div class="text-center">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Can't Find Your Business?</h2>
                <p class="text-gray-600 mb-6">
                    If your business isn't listed in our directory yet, you can still get started by contacting our support team.
                </p>
                <a href="mailto:support@scoreness.com"
                   class="bg-white text-blue-600 px-6 py-3 rounded-md font-medium border border-blue-600 hover:bg-blue-50 transition-colors">
                    <i class="fas fa-envelope mr-2"></i>
                    Contact Support
                </a>
            </div>
        </div>
    </div>

    @include('footer.footer')

</body>
</html>
