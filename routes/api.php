<?php

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
Route::post('sort/sliders', [SortController::class, 'sliders'])->name('api.sort.sliders');



Route::post('search', [MarketplaceController::class, 'search'])->name('api.producto.search');
Route::post('searchsubcategories', [MarketplaceController::class, 'searchsubcategories'])->name('api.producto.subcategories');


Route::get('tipocambio', [HomeController::class, 'tipocambio'])->name('api.tipocambio');
Route::get('consulta-ruc/{ruc}', [HomeController::class, 'consultaruc'])->name('api.ruc');
Route::get('consulta-sunat/{document?}', [HomeController::class, 'consultasunat'])->name('consultasunat');
