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

use Illuminate\Support\Facades\Route;
use Modules\Almacen\Http\Controllers\AlmacenController;
use Modules\Almacen\Http\Controllers\CompraController;
use Modules\Almacen\Http\Controllers\KardexController;
use Modules\Almacen\Http\Controllers\ProductoController;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    // 'verified'
])->prefix('admin/almacen')->middleware(['auth', 'verifycompany'])->group(function () {
    Route::get('/', [AlmacenController::class, 'index'])->name('admin.almacen');
    Route::get('/almacenes', [AlmacenController::class, 'almacenes'])->name('admin.almacen.almacenes');
    Route::get('/tipo-garantias', [AlmacenController::class, 'typegarantias'])->name('admin.almacen.typegarantias');

    Route::get('/compras', [CompraController::class, 'index'])->name('admin.almacen.compras');
    Route::get('/compras/create', [CompraController::class, 'create'])->name('admin.almacen.compras.create')->middleware(['verifysucursal', 'verifyalmacen']);
    Route::get('/compras/{compra:id}/edit', [CompraController::class, 'show'])->name('admin.almacen.compras.show')->middleware(['opencaja', 'verifymethodpayment']);

    Route::get('/areas-&-estantes', [AlmacenController::class, 'almacenareas'])->name('admin.almacen.almacenareas');

    Route::get('/kardex', [KardexController::class, 'index'])->name('admin.almacen.kardex');
    Route::get('/kardex/series', [KardexController::class, 'series'])->name('admin.almacen.kardex.series');
    Route::get('/kardex/series/{serie:serie}/show', [KardexController::class, 'show'])->name('admin.almacen.kardex.series.show');
});
