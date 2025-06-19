<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $categories = Category::with(['subcategories' => function($query) use ($search) {
            // Only load active subcategories
            $query->where('is_active', 1);
            if ($search) {
                $query->where('name', 'like', "%{$search}%");
            }
        }])
        ->where('is_active', 1) // Only load active categories
        ->when($search, function($query) use ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhereHas('subcategories', function($query) use ($search) {
                      $query->where('name', 'like', "%{$search}%")
                            ->where('is_active', 1); // Ensure subcategories are active in search
                  });
        })
        ->get();

        return view('categories.index', compact('categories', 'search'));
    }
}
