<?php

use Illuminate\Support\Facades\Route;
use Modules\Facturacion\Http\Controllers\FacturacionController;
use Modules\Facturacion\Http\Controllers\GuiaController;

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

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    // 'verified'
])->prefix('admin/facturacion')->name('admin.')->middleware(['auth', 'verifysucursal'])->group(function () {
    Route::get('/', [FacturacionController::class, 'index'])->name('facturacion');
    Route::get('/{comprobante}/show', [FacturacionController::class, 'show'])->name('facturacion.show');

    Route::get('/guias', [GuiaController::class, 'index'])->name('facturacion.guias');
    Route::get('/guias/create', [GuiaController::class, 'create'])->name('facturacion.guias.create')->middleware(['verifyserieguias']);
    Route::get('/guias/{guia:seriecompleta}/edit', [GuiaController::class, 'show'])->name('facturacion.guias.show');

    Route::get('/guias/motivos-traslado', [GuiaController::class, 'motivos'])->name('facturacion.guias.motivos')->middleware(['verifyserieguias']);
});
