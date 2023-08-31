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

use Modules\Almacen\Http\Controllers\AlmacenController;
use Modules\Almacen\Http\Controllers\ProductoController;



Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->prefix('admin/almacen')->group(function () {
    Route::get('/', [AlmacenController::class, 'index'])->name('admin.almacen');
    Route::get('/tipo-garantias', [AlmacenController::class, 'typegarantias'])->name('admin.almacen.typegarantias');
    Route::get('/productos', [ProductoController::class, 'index'])->name('admin.almacen.productos');
    Route::get('/productos/create', [ProductoController::class, 'create'])->name('admin.almacen.productos.create');
    // Route::get('/productos/{producto:slug}', [ProductoController::class, 'show'])->name('admin.almacen.productos.show');

    Route::get('/productos/{producto:slug}', [ProductoController::class, 'edit'])->name('admin.almacen.productos.show');




    Route::get('/ofertas', [AlmacenController::class, 'ofertas'])->name('admin.almacen.ofertas');

    Route::get('/especificaciones', [AlmacenController::class, 'especificaciones'])->name('admin.almacen.especificaciones');


    Route::get('/areas-&-estantes', [AlmacenController::class, 'almacenareas'])->name('admin.almacen.almacenareas');
    Route::get('/categorias', [AlmacenController::class, 'categorias'])->name('admin.almacen.categorias');
    Route::get('/subcategorias', [AlmacenController::class, 'subcategorias'])->name('admin.almacen.subcategorias');
    Route::get('/unidades-medida', [AlmacenController::class, 'units'])->name('admin.almacen.units');
});
