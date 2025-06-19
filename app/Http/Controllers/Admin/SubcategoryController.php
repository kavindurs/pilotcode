<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subcategory;
use App\Models\Category;
use Illuminate\Http\Request;

class SubcategoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $categoryFilter = $request->input('category');

        $subcategories = Subcategory::with('category')
            ->when($search, function($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhereHas('category', function($q) use ($search) {
                          $q->where('name', 'like', "%{$search}%");
                      });
            })
            ->when($categoryFilter, function($query) use ($categoryFilter) {
                $query->where('category_id', $categoryFilter);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $categories = Category::where('is_active', 1)->orderBy('name')->get();

        return view('admin.subcategories.index', compact('subcategories', 'search', 'categories', 'categoryFilter'));
    }

    public function create()
    {
        $categories = Category::where('is_active', 1)->orderBy('name')->get();
        return view('admin.subcategories.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:subcategories,slug',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $validatedData['is_active'] = $request->has('is_active') ? 1 : 0;

        Subcategory::create($validatedData);

        return redirect()->route('admin.subcategories.index')->with('success', 'Subcategory created successfully.');
    }

    public function show($id)
    {
        $subcategory = Subcategory::with('category')->findOrFail($id);
        return view('admin.subcategories.show', compact('subcategory'));
    }

    public function edit($id)
    {
        $subcategory = Subcategory::with('category')->findOrFail($id);
        $categories = Category::where('is_active', 1)->orderBy('name')->get();
        return view('admin.subcategories.edit', compact('subcategory', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $subcategory = Subcategory::findOrFail($id);

        $validatedData = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:subcategories,slug,' . $id,
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $validatedData['is_active'] = $request->has('is_active') ? 1 : 0;

        $subcategory->update($validatedData);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Subcategory updated successfully.']);
        }

        return redirect()->route('admin.subcategories.index')->with('success', 'Subcategory updated successfully.');
    }

    public function approve($id)
    {
        $subcategory = Subcategory::findOrFail($id);
        $subcategory->is_active = 1;
        $subcategory->save();

        return redirect()->route('admin.subcategories.index')->with('success', 'Subcategory approved successfully.');
    }

    public function reject($id)
    {
        $subcategory = Subcategory::findOrFail($id);
        $subcategory->is_active = 0;
        $subcategory->save();

        return redirect()->route('admin.subcategories.index')->with('success', 'Subcategory rejected successfully.');
    }

    public function destroy($id)
    {
        $subcategory = Subcategory::findOrFail($id);

        // Check if subcategory is being used by properties
        $propertiesCount = \App\Models\Property::where('subcategory', $subcategory->name)->count();

        if ($propertiesCount > 0) {
            return redirect()->route('admin.subcategories.index')
                ->with('error', "Cannot delete subcategory '{$subcategory->name}' as it is being used by {$propertiesCount} properties.");
        }

        $subcategory->delete();

        return redirect()->route('admin.subcategories.index')->with('success', 'Subcategory deleted successfully.');
    }
}
