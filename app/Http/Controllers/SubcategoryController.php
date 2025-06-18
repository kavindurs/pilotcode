<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subcategory;
use App\Models\Property;

class SubcategoryController extends Controller
{
    public function showBusinesses($slug)
    {
        $subcategory = Subcategory::where('slug', $slug)->firstOrFail();

        $businesses = Property::where('subcategory', $subcategory->name)
            ->where('status', 'Approved')
            ->paginate(12);

        return view('subcategory.businesses', [
            'subcategory' => $subcategory,
            'businesses' => $businesses
        ]);
    }
}
