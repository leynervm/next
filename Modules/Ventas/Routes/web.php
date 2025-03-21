<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use Modules\Ventas\Http\Controllers\VentaController;
use Nwidart\Modules\Facades\Module;

Route::prefix('admin')->middleware(['web', 'auth:sanctum', config('jetstream.auth_session'), 'verifysucursal'])->group(function () {
    Route::prefix('/ventas')->group(function () {


        Route::post('/addcarshoop', [VentaController::class, 'addcarshoop'])->name('admin.ventas.addcarshoop');
        Route::post('/getproductobyserie', [VentaController::class, 'getproductobyserie'])->name('admin.ventas.getproductobyserie');



        Route::get('/', [VentaController::class, 'index'])->name('admin.ventas');
        Route::get('/create', [VentaController::class, 'create'])->name('admin.ventas.create')->middleware(['verifyserieventas', 'openbox', 'verifymethodpayment', 'verifyconcept', 'verifypricetype']);
        Route::get('/{venta:seriecompleta}/show', [VentaController::class, 'show'])->name('admin.ventas.edit')->middleware(['verifymethodpayment']);
        Route::get('/{venta:seriecompleta}/imprimir-ticket', [VentaController::class, 'imprimirticket'])->name('admin.ventas.print.ticket');

        if (Module::isEnabled('Facturacion')) {
            Route::get('/cobranzas', [VentaController::class, 'cobranzas'])->name('admin.ventas.cobranzas');
        }


        // Route::post('data', [ApiController::class, 'data'])->name('api.data');
        Route::post('/seriecomprobantes/list', [VentaController::class, 'seriecomprobantes'])->name('admin.ventas.seriecomprobantes.list');
        Route::post('/marcas/list', [VentaController::class, 'marcas'])->name('admin.ventas.marcas.list');
        Route::post('/categories/list', [VentaController::class, 'categories'])->name('admin.ventas.categories.list');
        Route::post('/subcategories/list', [VentaController::class, 'subcategories'])->name('admin.ventas.subcategories.list');
        Route::post('/ubigeos/list', [VentaController::class, 'ubigeos'])->name('admin.ventas.ubigeos.list');
        Route::post('/pricetypes/list', [VentaController::class, 'pricetypes'])->name('admin.ventas.pricetypes.list');
        Route::post('/almacens/list', [VentaController::class, 'almacens'])->name('admin.ventas.almacens.list');
        Route::post('/typepayments/list', [VentaController::class, 'typepayments'])->name('admin.ventas.typepayments.list');
        Route::post('/methodpayments/list', [VentaController::class, 'methodpayments'])->name('admin.ventas.methodpayments.list');
    });

    Route::get('/promociones', [HomeController::class, 'promociones'])->name('admin.promociones')->middleware(['verifypricetype']);
    Route::get('/administracion/lista-precios', [HomeController::class, 'pricetypes'])->name('admin.administracion.pricetypes');
});
