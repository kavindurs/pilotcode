@extends('layouts.business')

@section('active-products', 'bg-blue-600')

@section('title', isset($product) ? 'Edit Product' : 'Add Product')
@section('page-title', isset($product) ? 'Edit Product' : 'Add New Product')
@section('page-subtitle', isset($product) ? 'Update product information' : 'Add a new product to your inventory')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Form Section -->
        <div class="lg:col-span-2">
            <div class="bg-gray-800 border border-gray-700 rounded-lg shadow-lg p-6">
        <form action="{{ isset($product) ? route('property.products.update', $product->id) : route('property.products.store') }}"
              method="POST"
              enctype="multipart/form-data">
            @csrf
            @if(isset($product))
                @method('PUT')
            @endif

            <div class="space-y-6">
                <!-- Product Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-300 mb-1">Product Name *</label>
                    <input type="text" name="name" id="name"
                           value="{{ old('name', isset($product) ? $product->name : '') }}"
                           class="w-full px-3 py-2 bg-gray-700 border border-gray-600 text-white rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                           required>
                    @error('name')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-300 mb-1">Description</label>
                    <textarea name="description" id="description" rows="4"
                              class="w-full px-3 py-2 bg-gray-700 border border-gray-600 text-white rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('description', isset($product) ? $product->description : '') }}</textarea>
                    @error('description')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Price and Stock -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-300 mb-1">Price *</label>
                        <div class="relative rounded-md">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-400">$</span>
                            </div>
                            <input type="number" step="0.01" min="0" name="price" id="price"
                                   value="{{ old('price', isset($product) ? $product->price : '') }}"
                                   class="w-full pl-7 pr-3 py-2 bg-gray-700 border border-gray-600 text-white rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                   required>
                        </div>
                        @error('price')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="stock_quantity" class="block text-sm font-medium text-gray-300 mb-1">Stock Quantity</label>
                        <input type="number" min="0" name="stock_quantity" id="stock_quantity"
                               value="{{ old('stock_quantity', isset($product) ? $product->stock_quantity : 0) }}"
                               class="w-full px-3 py-2 bg-gray-700 border border-gray-600 text-white rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        @error('stock_quantity')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Category -->
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-300 mb-1">Category</label>
                    <input type="text" name="category" id="category"
                            value="{{ old('category', isset($product) ? $product->category : '') }}"
                            class="w-full px-3 py-2 bg-gray-700 border border-gray-600 text-white rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    @error('category')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Featured checkbox -->
                <div class="flex items-center">
                    <input type="checkbox" name="is_featured" id="is_featured" value="1"
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-600 bg-gray-700 rounded"
                            {{ old('is_featured', isset($product) && $product->is_featured ? 'checked' : '') }}>
                    <label for="is_featured" class="ml-2 block text-sm font-medium text-gray-300">
                        Feature this product
                    </label>
                </div>

                <!-- Product Image -->
                <div>
                    <label for="image" class="block text-sm font-medium text-gray-300 mb-1">Product Image</label>
                    <input type="file" name="image" id="image"
                            class="w-full px-3 py-2 bg-gray-700 border border-gray-600 text-white rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700"
                            accept="image/*">
                    @error('image')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror

                    @if(isset($product) && $product->image_path)
                        <div class="mt-2">
                            <p class="text-sm text-gray-400 mb-1">Current image:</p>
                            <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}"
                                class="h-24 w-auto object-contain border border-gray-600 rounded bg-gray-700">
                        </div>
                    @endif
                </div>

                <!-- Submit -->
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('property.products') }}" class="px-4 py-2 border border-gray-600 rounded-md text-gray-300 hover:bg-gray-700 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors shadow-lg">
                        {{ isset($product) ? 'Update Product' : 'Add Product' }}
                    </button>
                </div>                </div>
            </form>
        </div>
    </div>

    <!-- Products Sidebar -->
    <div class="lg:col-span-1">
        <div class="bg-gray-800 border border-gray-700 rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                <i class="fas fa-box mr-2 text-blue-400"></i>
                Recent Products
            </h3>

            @if(isset($recentProducts) && count($recentProducts) > 0)
                <div class="space-y-4">
                    @foreach($recentProducts as $recentProduct)
                        <div class="bg-gray-700 rounded-lg p-4 border border-gray-600">
                            <div class="flex items-start space-x-3">
                                <div class="w-12 h-12 flex-shrink-0">
                                    @if($recentProduct->image_path)
                                        <img src="{{ asset('storage/' . $recentProduct->image_path) }}"
                                             alt="{{ $recentProduct->name }}"
                                             class="w-12 h-12 rounded-lg object-cover">
                                    @else
                                        <div class="w-12 h-12 bg-gray-600 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-image text-gray-400"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-white font-medium text-sm truncate">{{ $recentProduct->name }}</h4>
                                    <p class="text-gray-400 text-xs truncate mb-1">{{ $recentProduct->description }}</p>
                                    <div class="flex items-center justify-between">
                                        <span class="text-yellow-400 font-semibold text-sm">${{ number_format($recentProduct->price, 2) }}</span>
                                        <span class="text-gray-500 text-xs">Stock: {{ $recentProduct->stock_quantity }}</span>
                                    </div>
                                    @if($recentProduct->category)
                                        <span class="inline-block px-2 py-1 bg-gray-600 text-gray-200 rounded-full text-xs mt-1">
                                            {{ $recentProduct->category }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4 pt-4 border-t border-gray-600">
                    <a href="{{ route('property.products') }}"
                       class="block w-full text-center px-4 py-2 bg-gray-700 hover:bg-gray-600 text-gray-300 rounded-lg transition-colors text-sm">
                        <i class="fas fa-eye mr-2"></i>
                        View All Products
                    </a>
                </div>
            @else
                <div class="text-center py-8">
                    <div class="text-gray-500 mb-4">
                        <i class="fas fa-box-open text-4xl"></i>
                    </div>
                    <p class="text-gray-400 text-sm mb-4">No products added yet</p>
                    <a href="{{ route('property.products') }}"
                       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors text-sm">
                        <i class="fas fa-plus mr-2"></i>
                        Add First Product
                    </a>
                </div>
            @endif
        </div>

        <!-- Quick Stats Card -->
        <div class="bg-gray-800 border border-gray-700 rounded-lg shadow-lg p-6 mt-6">
            <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                <i class="fas fa-chart-bar mr-2 text-green-400"></i>
                Quick Stats
            </h3>

            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-gray-400 text-sm">Total Products</span>
                    <span class="text-white font-semibold">{{ $totalProducts ?? 0 }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-400 text-sm">Total Stock</span>
                    <span class="text-white font-semibold">{{ $totalStock ?? 0 }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-400 text-sm">Avg. Price</span>
                    <span class="text-yellow-400 font-semibold">${{ number_format($averagePrice ?? 0, 2) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-400 text-sm">Categories</span>
                    <span class="text-white font-semibold">{{ $totalCategories ?? 0 }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
