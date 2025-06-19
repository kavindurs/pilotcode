<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $categories = Category::with('subcategories')
            ->when($search, function($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.categories.index', compact('categories', 'search'));
    }

    public function show($id)
    {
        $category = Category::with(['subcategories' => function($query) {
            $query->orderBy('name');
        }])->findOrFail($id);

        return view('admin.categories.show', compact('category'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:categories,slug',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $validatedData['is_active'] = $request->has('is_active') ? 1 : 0;

        Category::create($validatedData);

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
    }

    public function edit($id)
    {
        $category = Category::with('subcategories')->findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:categories,slug,' . $id,
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $validatedData['is_active'] = $request->has('is_active') ? 1 : 0;

        $category->update($validatedData);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Category updated successfully.']);
        }

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }

    public function approve($id)
    {
        $category = Category::findOrFail($id);
        $category->is_active = 1;
        $category->save();

        return redirect()->route('admin.categories.index')->with('success', 'Category approved successfully.');
    }

    public function reject($id)
    {
        $category = Category::findOrFail($id);
        $category->is_active = 0;
        $category->save();

        return redirect()->route('admin.categories.index')->with('success', 'Category rejected successfully.');
    }

    public function destroy($id)
    {
        // Add some debugging
        \Log::info("Category delete attempt for ID: " . $id);

        $category = Category::findOrFail($id);

        // Check if category has subcategories
        $subcategoriesCount = $category->subcategories()->count();
        \Log::info("Category {$id} has {$subcategoriesCount} subcategories");

        if ($subcategoriesCount > 0) {
            \Log::info("Delete blocked - category has subcategories");
            return redirect()->route('admin.categories.index')
                ->with('error', 'Cannot delete category with subcategories. Please delete subcategories first.');
        }

        \Log::info("Attempting to delete category {$id}");
        $category->delete();
        \Log::info("Category {$id} deleted successfully");

        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
    }
}
