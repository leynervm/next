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
use Modules\Ventas\Http\Controllers\VentaController;
use Modules\Ventas\Http\Controllers\VentasController;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    // 'verified'
])->prefix('admin/ventas')->middleware(['auth', 'verifysucursal'])->group(function () {
    // Route::get('/', 'VentasController@index');
    Route::get('/', [VentasController::class, 'index'])->name('admin.ventas');
    Route::get('/cobranzas', [VentasController::class, 'cobranzas'])->name('admin.ventas.cobranzas');


    Route::get('/create', [VentaController::class, 'create'])->name('admin.ventas.create')->middleware(['verifyserieventas', 'opencaja', 'verifymethodpayment', 'verifyconcept', 'verifypricetype']);
    Route::get('/{venta}/show', [VentaController::class, 'show'])->name('admin.ventas.show')->middleware(['opencaja', 'verifymethodpayment', 'verifyconcept']);
});
