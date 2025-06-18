@extends('layouts.business')

@section('active-products', 'bg-blue-600')

@section('title', 'Manage Products')
@section('page-title', 'Product Management')
@section('page-subtitle', 'Add, edit, and manage your product inventory')

@section('content')
    <!-- Plan Information Section -->
    <div class="bg-gray-900 border-l-4 border-yellow-500 text-gray-200 p-4 mb-6 rounded-r-lg shadow-md" role="alert">
        <div class="flex items-center">
            <div class="py-1">
                <i class="fas fa-info-circle text-2xl text-yellow-400 mr-4"></i>
            </div>
            <div>
                @if(isset($activePlan))
                    <p class="font-bold text-white">Current Plan: {{ $activePlan->name }}</p>
                    <p class="text-gray-300">
                        Product Limit: {{ $productLimit }} |
                        Currently Using: {{ $currentProductCount }} |
                        Remaining: {{ max(0, $productLimit - $currentProductCount) }}
                    </p>
                    @if($activePlan->name === 'Free')
                        <p class="mt-2"><a href="{{ route('plans.index') }}" class="underline text-yellow-400">Upgrade your plan</a> to add products to your inventory!</p>
                    @elseif($currentProductCount >= $productLimit)
                        <p class="mt-2">You've reached your product limit. <a href="{{ route('plans.index') }}" class="underline text-yellow-400">Upgrade your plan</a> for more!</p>
                    @endif
                @else
                    <p>Unable to determine your current plan. Please contact support.</p>
                @endif
            </div>
        </div>
    </div>

    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-2xl font-semibold text-white">Your Products</h2>

        @if(isset($activePlan) && $activePlan->name !== 'Free' && $currentProductCount < $productLimit)
            <a href="{{ route('property.products.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-lg">
                <i class="fas fa-plus mr-2"></i> Add New Product
            </a>
        @elseif(isset($activePlan) && $activePlan->name === 'Free')
            <a href="{{ route('plans.index') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors shadow-lg">
                <i class="fas fa-arrow-up mr-2"></i> Upgrade Plan
            </a>
        @elseif(isset($activePlan) && $currentProductCount >= $productLimit)
            <a href="{{ route('plans.index') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors shadow-lg">
                <i class="fas fa-arrow-up mr-2"></i> Upgrade for More Products
            </a>
        @endif
    </div>

    @if(session('success'))
        <div class="bg-green-900 border-l-4 border-green-400 text-green-100 p-4 mb-6 rounded-r-lg shadow-md" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-900 border-l-4 border-red-400 text-red-100 p-4 mb-6 rounded-r-lg shadow-md" role="alert">
            <p>{{ session('error') }}</p>
        </div>
    @endif

    @if(isset($products) && count($products) > 0)
        <!-- Products Table View -->
        <div class="bg-gray-800 rounded-lg shadow-lg border border-gray-700 overflow-hidden mb-8">
            <!-- Table Header -->
            <div class="bg-gray-700 px-6 py-4 border-b border-gray-600">
                <div class="grid grid-cols-12 gap-4 items-center text-sm font-medium text-gray-300">
                    <div class="col-span-4">Product</div>
                    <div class="col-span-2 text-center">Price</div>
                    <div class="col-span-2 text-center">Stock</div>
                    <div class="col-span-2 text-center">Category</div>
                    <div class="col-span-2 text-center">Actions</div>
                </div>
            </div>

            <!-- Table Body -->
            <div class="divide-y divide-gray-700">
                @foreach($products as $product)
                    <div class="px-6 py-4 hover:bg-gray-750 transition-colors">
                        <div class="grid grid-cols-12 gap-4 items-center">
                            <!-- Product Info -->
                            <div class="col-span-4 flex items-center space-x-4">
                                <div class="w-12 h-12 flex-shrink-0">
                                    @if($product->image_path)
                                        <img src="{{ asset('storage/' . $product->image_path) }}"
                                             alt="{{ $product->name }}"
                                             class="w-12 h-12 rounded-lg object-cover">
                                    @else
                                        <div class="w-12 h-12 bg-gray-600 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-image text-gray-400 text-lg"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h3 class="text-white font-medium truncate">{{ $product->name }}</h3>
                                    <p class="text-gray-400 text-sm truncate">{{ $product->description }}</p>
                                    <div class="flex items-center text-xs text-gray-500 mt-1">
                                        <span>ID: #{{ $product->id }}</span>
                                        <span class="mx-2">•</span>
                                        <span>{{ $product->created_at ? $product->created_at->format('M j, Y') : 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Price -->
                            <div class="col-span-2 text-center">
                                <span class="text-yellow-400 font-semibold text-lg">${{ number_format($product->price, 2) }}</span>
                            </div>

                            <!-- Stock -->
                            <div class="col-span-2 text-center">
                                <span class="text-white font-medium">{{ $product->stock_quantity }}</span>
                                <div class="text-xs text-gray-400">units</div>
                            </div>

                            <!-- Category -->
                            <div class="col-span-2 text-center">
                                <span class="px-2 py-1 bg-gray-600 text-gray-200 rounded-full text-xs font-medium">
                                    {{ $product->category }}
                                </span>
                            </div>

                            <!-- Actions -->
                            <div class="col-span-2 flex justify-center space-x-2">
                                @if(isset($activePlan) && $activePlan->name !== 'Free')
                                    <a href="{{ route('property.products.edit', $product->id) }}"
                                       class="p-2 text-blue-400 hover:text-blue-300 hover:bg-gray-700 rounded-lg transition-colors"
                                       title="Edit Product">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('property.products.destroy', $product->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="p-2 text-red-400 hover:text-red-300 hover:bg-gray-700 rounded-lg transition-colors"
                                                title="Delete Product"
                                                onclick="return confirm('Are you sure you want to delete this product?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @else
                                    <span class="p-2 text-gray-600 cursor-not-allowed" title="Upgrade to edit products">
                                        <i class="fas fa-edit"></i>
                                    </span>
                                    <span class="p-2 text-gray-600 cursor-not-allowed" title="Upgrade to delete products">
                                        <i class="fas fa-trash"></i>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $products->links() }}
        </div>
    @else
        <div class="bg-gray-800 border border-gray-700 rounded-lg shadow-lg p-8 text-center">
            <div class="text-gray-500 mb-4">
                <i class="fas fa-box-open text-6xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-white mb-2">No Products Yet</h3>

            @if(isset($activePlan) && $activePlan->name === 'Free')
                <p class="text-gray-400 mb-6">Upgrade your plan to start adding products to your inventory.</p>
                <a href="{{ route('plans.index') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-lg">
                    Upgrade Plan
                </a>
            @else
                <p class="text-gray-400 mb-6">You haven't added any products to your inventory.</p>
                <a href="{{ route('property.products.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-lg">
                    Add Your First Product
                </a>
            @endif
        </div>
    @endif
@endsection
