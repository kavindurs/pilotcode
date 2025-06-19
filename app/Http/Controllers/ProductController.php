<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of products.
     */
    public function index()
    {
        // Get the property ID from the session
        $propertyId = session('property_id');

        if (!$propertyId) {
            return redirect()->route('property.login')
                ->with('error', 'Please login to access your products.');
        }

        // Retrieve the property
        $property = \App\Models\Property::find($propertyId);

        if (!$property) {
            return redirect()->route('property.dashboard')
                ->with('error', 'Property not found.');
        }        // Get active plan
        $activePlan = $property->getActivePlan();
        $productLimit = $property->getProductLimit();
        $currentProductCount = $property->products()->count();

        // Get products for this property with pagination
        $products = \App\Models\Product::where('property_id', $propertyId)
                          ->orderBy('created_at', 'desc')
                          ->paginate(9);

        return view('property.products', compact('products', 'property', 'activePlan', 'productLimit', 'currentProductCount'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $propertyId = session('property_id');

        if (!$propertyId) {
            return redirect()->route('property.login')
                ->with('error', 'Please login to add products.');
        }

        $property = \App\Models\Property::find($propertyId);

        if (!$property) {
            return redirect()->route('property.dashboard')
                ->with('error', 'Property not found.');
        }

        // Check if property has reached its product limit
        if (!$property->canAddMoreProducts()) {
            $activePlan = $property->getActivePlan();

            if ($activePlan && $activePlan->name === 'Free') {
                return redirect()->route('property.products')
                    ->with('error', 'Free plan does not allow adding products. Please upgrade your plan.');
            } else {
                return redirect()->route('property.products')
                    ->with('error', 'You have reached your product limit. Please upgrade your plan to add more products.');
            }
        }

        // Get recent products for sidebar
        $recentProducts = \App\Models\Product::where('property_id', $propertyId)
                            ->orderBy('created_at', 'desc')
                            ->limit(3)
                            ->get();

        // Calculate stats
        $allProducts = \App\Models\Product::where('property_id', $propertyId)->get();
        $totalProducts = $allProducts->count();
        $totalStock = $allProducts->sum('stock_quantity');
        $averagePrice = $allProducts->avg('price') ?? 0;
        $totalCategories = $allProducts->pluck('category')->filter()->unique()->count();

        return view('property.product-form', compact(
            'recentProducts',
            'totalProducts',
            'totalStock',
            'averagePrice',
            'totalCategories'
        ));
    }

    /**
     * Store a newly created product.
     */
    public function store(Request $request)
    {
        $propertyId = session('property_id');

        if (!$propertyId) {
            return redirect()->route('property.login')
                ->with('error', 'Please login to add products.');
        }

        $property = \App\Models\Property::find($propertyId);

        if (!$property) {
            return redirect()->route('property.dashboard')
                ->with('error', 'Property not found.');
        }

        // Check if property has reached its product limit
        if (!$property->canAddMoreProducts()) {
            $activePlan = $property->getActivePlan();

            if ($activePlan && $activePlan->name === 'Free') {
                return redirect()->route('property.products')
                    ->with('error', 'Free plan does not allow adding products. Please upgrade your plan.');
            } else {
                return redirect()->route('property.products')
                    ->with('error', 'You have reached your product limit. Please upgrade your plan to add more products.');
            }
        }

        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'nullable|integer|min:0',
            'category' => 'nullable|string|max:100',
            'is_featured' => 'nullable|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Create the new product
        $product = new \App\Models\Product();
        $product->name = $validated['name'];
        $product->description = $validated['description'] ?? null;
        $product->price = $validated['price'];
        $product->stock_quantity = $validated['stock_quantity'] ?? 0;
        $product->category = $validated['category'] ?? null;
        $product->is_featured = isset($validated['is_featured']) ? 1 : 0;
        $product->property_id = $propertyId;

        // Handle image upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $product->image_path = $path;
        }

        $product->save();

        return redirect()->route('property.products')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit($id)
    {
        $propertyId = session('property_id');

        if (!$propertyId) {
            return redirect()->route('property.login')
                ->with('error', 'Please login to edit products.');
        }

        $property = \App\Models\Property::find($propertyId);

        if (!$property) {
            return redirect()->route('property.dashboard')
                ->with('error', 'Property not found.');
        }

        $product = \App\Models\Product::find($id);

        if (!$product) {
            return redirect()->route('property.products')
                ->with('error', 'Product not found.');
        }

        // Check if this product belongs to the property
        if ($product->property_id !== $propertyId) {
            return redirect()->route('property.products')
                ->with('error', 'You do not have permission to edit this product.');
        }

        // Check if this property can add/edit products based on plan
        $activePlan = $property->getActivePlan();

        if ($activePlan && $activePlan->name === 'Free') {
            return redirect()->route('property.products')
                ->with('error', 'Free plan does not allow editing products. Please upgrade your plan.');
        }

        // Get recent products for sidebar (excluding current product)
        $recentProducts = \App\Models\Product::where('property_id', $propertyId)
                            ->where('id', '!=', $product->id)
                            ->orderBy('created_at', 'desc')
                            ->limit(3)
                            ->get();

        // Calculate stats
        $allProducts = \App\Models\Product::where('property_id', $propertyId)->get();
        $totalProducts = $allProducts->count();
        $totalStock = $allProducts->sum('stock_quantity');
        $averagePrice = $allProducts->avg('price') ?? 0;
        $totalCategories = $allProducts->pluck('category')->filter()->unique()->count();

        return view('property.product-form', compact(
            'product',
            'recentProducts',
            'totalProducts',
            'totalStock',
            'averagePrice',
            'totalCategories'
        ));
    }

    /**
     * Update the specified product.
     */
    public function update(Request $request, Product $product)
    {
        // Check if the product belongs to the user's property
        $property = Property::find(session('property_id'));

        if (!$property || $product->property_id !== $property->id) {
            return redirect()->route('property.products')
                ->with('error', 'You do not have permission to update this product.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'nullable|integer|min:0',
            'category' => 'nullable|string|max:100',
            'is_featured' => 'nullable|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Update product
        $product->name = $validated['name'];
        $product->description = $validated['description'] ?? null;
        $product->price = $validated['price'];
        $product->stock_quantity = $validated['stock_quantity'] ?? 0;
        $product->category = $validated['category'] ?? null;
        $product->is_featured = isset($validated['is_featured']) ? 1 : 0;

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }

            $path = $request->file('image')->store('products', 'public');
            $product->image_path = $path;
        }

        $product->save();

        return redirect()->route('property.products')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified product.
     */
    public function destroy(Product $product)
    {
        // Check if the product belongs to the user's property
        $property = Property::find(session('property_id'));

        if (!$property || $product->property_id !== $property->id) {
            return redirect()->route('property.products')
                ->with('error', 'You do not have permission to delete this product.');
        }

        // Delete product image if exists
        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }

        $product->delete();

        return redirect()->route('property.products')
            ->with('success', 'Product deleted successfully.');
    }
}
