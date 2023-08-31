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

// Route::prefix('caja')->group(function() {
//     Route::get('/', 'CajaController@index');
// });

use Illuminate\Support\Facades\Route;
use Modules\Caja\Http\Controllers\CajaController;

// Route::middleware([
//     'auth:sanctum',
//     config('jetstream.auth_session'),
//     'verified'
// ])->prefix('admin/caja')->group(function () {
//     Route::get('/', [CajaController::class, 'index'])->name('admin.cajas');
//     Route::get('/conceptos', [CajaController::class, 'conceptos'])->name('admin.cajas.conceptos');
//     Route::get('/movimientos', [CajaController::class, 'movimientos'])->name('admin.cajas..movimientos');
//     Route::get('/resumen', [CajaController::class, 'resumen'])->name('admin.cajas.resumen');


// });
