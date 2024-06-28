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

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    // 'verified'
])->middleware(['auth', 'verifysucursal'])->group(function () {
    // Route::get('/', 'VentasController@index');

    Route::prefix('admin/ventas')->group(function () {
        Route::get('/', [VentaController::class, 'index'])->name('admin.ventas');
        Route::get('/create', [VentaController::class, 'create'])->name('admin.ventas.create')->middleware(['verifyserieventas', 'openbox', 'verifymethodpayment', 'verifyconcept', 'verifypricetype']);
        Route::get('/{venta:seriecompleta}/show', [VentaController::class, 'show'])->name('admin.ventas.edit')->middleware(['verifymethodpayment']);

        if (Module::isEnabled('Facturacion')) {
            Route::get('/cobranzas', [VentaController::class, 'cobranzas'])->name('admin.ventas.cobranzas');
        }
    });

    Route::prefix('admin/promociones')->middleware(['verifypricetype'])->group(function () {
        Route::get('/', [HomeController::class, 'promociones'])->name('admin.promociones');
    });

    Route::get('/administracion/lista-precios', [HomeController::class, 'pricetypes'])->name('admin.administracion.pricetypes');
});

Route::middleware(['auth:sanctum', config('jetstream.auth_session')])
    ->middleware(['auth'])->group(function () {
        Route::get('admin/ventas/{venta:seriecompleta}/imprimir-ticket', [VentaController::class, 'imprimirticket'])->name('admin.ventas.print.ticket');
    });
