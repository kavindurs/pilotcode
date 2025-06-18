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
            if ($search) {
                $query->where('name', 'like', "%{$search}%");
            }
        }])
        ->when($search, function($query) use ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhereHas('subcategories', function($query) use ($search) {
                      $query->where('name', 'like', "%{$search}%");
                  });
        })
        ->get();

        return view('categories.index', compact('categories', 'search'));
    }
}
