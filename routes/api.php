<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SortController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Marketplace\Http\Controllers\MarketplaceController;

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


Route::post('sort/categories', [SortController::class, 'categories'])->name('api.sort.categories');
Route::post('sort/subcategories', [SortController::class, 'subcategories'])->name('api.sort.subcategories');
Route::post('sort/caracteristicas', [SortController::class, 'caracteristicas'])->name('api.sort.caracteristicas');
Route::post('sort/especificacions', [SortController::class, 'especificacions'])->name('api.sort.especificacions');
Route::post('sort/sliders', [SortController::class, 'sliders'])->name('api.sort.sliders');
Route::post('sort/producto/images', [SortController::class, 'imagesproducto'])->name('api.sort.producto.images');

Route::post('search', [MarketplaceController::class, 'search'])->name('api.producto.search');
Route::post('searchsubcategories', [MarketplaceController::class, 'searchsubcategories'])->name('api.producto.subcategories');
Route::post('searchsubcategories', [MarketplaceController::class, 'searchsubcategories'])->name('api.producto.subcategories');

Route::get('tipocambio', [ApiController::class, 'tipocambio'])->name('api.tipocambio');