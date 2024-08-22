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



Route::post('search', [MarketplaceController::class, 'search'])->name('api.producto.search');
Route::post('searchsubcategories', [MarketplaceController::class, 'searchsubcategories'])->name('api.producto.subcategories');


Route::get('tipocambio', [HomeController::class, 'tipocambio'])->name('api.tipocambio');
Route::get('consulta-ruc/{ruc}', [HomeController::class, 'consultaruc'])->name('api.ruc');
Route::get('consulta-sunat/{document?}', [HomeController::class, 'consultasunat'])->name('consultasunat');


Route::post('productos', [ApiController::class, 'productos'])->name('api.producto.all');

Route::post('data', [ApiController::class, 'data'])->name('api.data');
Route::post('ventas/create/marcas', [ApiController::class, 'marcas'])->name('api.ventas.create.marcas');
Route::post('ventas/create/categories', [ApiController::class, 'categories'])->name('api.ventas.create.categories');
Route::post('ventas/create/subcategories', [ApiController::class, 'subcategories'])->name('api.ventas.create.subcategories');
Route::post('ventas/create/ubigeos', [ApiController::class, 'ubigeos'])->name('api.ventas.create.ubigeos');
Route::post('ventas/create/pricetypes', [ApiController::class, 'pricetypes'])->name('api.ventas.create.pricetypes');
Route::post('ventas/create/almacens', [ApiController::class, 'almacens'])->name('api.ventas.create.almacens');
Route::post('ventas/create/typepayments', [ApiController::class, 'typepayments'])->name('api.ventas.create.typepayments');
Route::post('ventas/create/methodpayments', [ApiController::class, 'methodpayments'])->name('api.ventas.create.methodpayments');
