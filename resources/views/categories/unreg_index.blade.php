<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Categories - Scoreness</title>
    @vite('resources/css/app.css')
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50">
    @include('navigation_bars.unreg_user_nav')

    <div class="container mx-auto px-4 py-8">
        <!-- Hero Section with Search -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-2xl shadow-xl mb-8 p-8 text-white">
            <h1 class="text-3xl md:text-4xl font-bold mb-4">Browse Categories</h1>
            <p class="text-blue-100 mb-6">Discover services and businesses across various categories</p>

            <!-- Enhanced Search Form -->
            <div x-data="{ searchFocused: false }">
                <form action="{{ route('all-categories') }}" method="GET"
                      class="relative max-w-2xl transition-all duration-300"
                      :class="{ 'scale-105': searchFocused }">
                    <div class="relative">
                        <input
                            type="text"
                            name="search"
                            value="{{ $search ?? '' }}"
                            placeholder="Search categories or subcategories..."
                            class="w-full pl-12 pr-4 py-3 rounded-lg bg-white/10 backdrop-blur-md border border-white/20 text-white placeholder-blue-100 focus:outline-none focus:ring-2 focus:ring-white/50"
                            @focus="searchFocused = true"
                            @blur="searchFocused = false"
                        >
                        <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-blue-100"></i>
                    </div>
                </form>
            </div>
        </div>

        <!-- Categories Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($categories as $category)
                <div x-data="{ expanded: false }"
                     class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 h-fit">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-3">
                                <!-- Category Icons -->
                                <div class="w-10 h-10 flex items-center justify-center rounded-lg bg-blue-100 text-blue-600">
                                    <i class="fas {{ getCategoryIcon($category->name) }} text-xl"></i>
                                </div>
                                <h2 class="text-xl font-bold text-gray-900">{{ $category->name }}</h2>
                            </div>
                            <button @click="expanded = !expanded"
                                    class="text-blue-600 hover:text-blue-800 focus:outline-none transition-transform duration-300"
                                    :class="{ 'transform rotate-180': expanded }">
                                <i class="fas fa-chevron-down"></i>
                            </button>
                        </div>

                        <div x-show="expanded"
                             x-cloak
                             x-collapse.duration.300ms
                             class="overflow-hidden">
                            @if($category->subcategories->count() > 0)
                                <ul class="space-y-2 mt-4">
                                    @foreach($category->subcategories as $subcategory)
                                        <li class="flex items-center text-gray-600 hover:text-blue-600 group">
                                            <span class="w-2 h-2 bg-blue-600 rounded-full mr-3 group-hover:scale-125 transition-transform duration-300"></span>
                                            <a href="{{ route('subcategories.show', $subcategory->slug) }}"
                                               class="hover:underline flex-1 transition-colors duration-300">
                                                {{ $subcategory->name }}
                                            </a>
                                            <i class="fas fa-arrow-right opacity-0 group-hover:opacity-100 transition-opacity duration-300"></i>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-gray-500 italic mt-4">No subcategories found</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- No Results Message -->
        @if($categories->isEmpty())
            <div class="text-center py-12">
                <div class="bg-white rounded-lg shadow-md p-8 max-w-lg mx-auto">
                    <i class="fas fa-search text-4xl text-gray-400 mb-4"></i>
                    <h3 class="text-xl text-gray-600 mb-2">No categories found</h3>
                    <p class="text-gray-500">Try adjusting your search terms or browse all categories</p>
                    <a href="{{ route('all-categories') }}"
                       class="inline-block mt-4 px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        View All Categories
                    </a>
                </div>
            </div>
        @endif
    </div>

    @include('footer.footer')

    @if(session('success'))
        <!-- Success Toast -->
        <div x-data="{ show: true }"
             x-show="show"
             x-init="setTimeout(() => show = false, 3000)"
             class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg">
            {{ session('success') }}
        </div>
    @endif
</body>
</html>
