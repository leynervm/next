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
use Modules\Ventas\Http\Controllers\VentasController;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->prefix('admin/ventas')->group(function () {
    // Route::get('/', 'VentasController@index');
    Route::get('/', [VentasController::class, 'index'])->name('admin.ventas');
    Route::get('/create', [VentasController::class, 'create'])->name('admin.ventas.create')->middleware('opencaja');
    Route::get('/show/{venta}', [VentasController::class, 'show'])->name('admin.ventas.show');
    Route::get('/cobranzas', [VentasController::class, 'cobranzas'])->name('admin.ventas.cobranzas');

    
});