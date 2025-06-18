<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\PropertyComparisonController;
use App\Models\Subcategory;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Search API endpoint - don't use any middleware that would block the request
Route::get('/search', [SearchController::class, 'search']);

// Property comparison API routes
Route::prefix('property-comparison')->group(function () {
    Route::get('/properties', [PropertyComparisonController::class, 'getProperties']);
    Route::get('/categories', [PropertyComparisonController::class, 'getCategories']);
    Route::post('/compare', [PropertyComparisonController::class, 'compare']);
});

Route::get('/subcategories/{categoryId}', function ($categoryId) {
    // Cast to integer to ensure proper query behavior
    $categoryId = (int) $categoryId;

    // Return actual subcategories from the database
    return Subcategory::where('category_id', $categoryId)
        ->where('is_active', 1)
        ->select('id', 'name')
        ->orderBy('name')
        ->get();
});
