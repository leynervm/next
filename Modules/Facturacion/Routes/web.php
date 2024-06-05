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


Route::get('/download/{comprobante:seriecompleta}/file/{type}/', [FacturacionController::class, 'downloadXML'])->name('download.xml');
// Route::get('/cdr/{comprobante:seriecompleta}/download', [FacturacionController::class, 'downloadCDR'])->name('download.cdr');


Route::middleware([
    'auth:sanctum', config('jetstream.auth_session'), 'auth', 'verifysucursal'
    // 'verified'
])->prefix('admin/facturacion')->name('admin.')->group(function () {
    Route::get('/', [FacturacionController::class, 'index'])->name('facturacion');
    Route::get('/{comprobante}/show', [FacturacionController::class, 'show'])->name('facturacion.edit');

    Route::get('/guias', [GuiaController::class, 'index'])->name('facturacion.guias');
    Route::get('/guias/create', [GuiaController::class, 'create'])->name('facturacion.guias.create')->middleware(['verifyserieguias']);
    Route::get('/guias/{guia:seriecompleta}/edit', [GuiaController::class, 'show'])->name('facturacion.guias.edit');

    Route::get('/guias/motivos-traslado', [GuiaController::class, 'motivos'])->name('facturacion.guias.motivos')->middleware(['verifyserieguias']);
});


Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'auth'])
    ->prefix('admin/facturacion')->name('admin.facturacion')->group(function () {
        Route::get('/{comprobante:seriecompleta}/imprimir-A4', [FacturacionController::class, 'imprimirA4'])->name('.print.a4');
        Route::get('/{comprobante:seriecompleta}/imprimir-ticket', [FacturacionController::class, 'imprimirticket'])->name('.print.ticket');
        Route::get('/guias/{guia:seriecompleta}/imprimir-A4', [GuiaController::class, 'imprimirA4'])->name('.guias.print');
    });
