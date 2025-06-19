<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subcategory;
use App\Models\Property;

class SubcategoryController extends Controller
{
    public function showBusinesses($slug)
    {
        // Only show active subcategories
        $subcategory = Subcategory::where('slug', $slug)
            ->where('is_active', 1)
            ->firstOrFail();

        $businesses = Property::where('subcategory', $subcategory->id)
            ->where('status', 'Approved')
            ->paginate(12);

        return view('subcategory.businesses', [
            'subcategory' => $subcategory,
            'businesses' => $businesses
        ]);
    }
}
