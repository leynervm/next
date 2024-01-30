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
use Modules\Soporte\Http\Controllers\CenterserviceController;
use Modules\Soporte\Http\Controllers\OrderController;
use Modules\Soporte\Http\Controllers\SoporteController;
use Modules\Soporte\Http\Livewire\Equipos\ShowEquipos;

Route::middleware(['auth:sanctum', config('jetstream.auth_session'),])
    ->prefix('admin/soporte')
    ->group(function () {
        Route::get('/', [SoporteController::class, 'index'])->name('admin.soporte');
        Route::get('/administrar-datos', [SoporteController::class, 'administrar'])->name('administrar');

        Route::get('/equipos', [SoporteController::class, 'equipos'])->name('equipos');
        
        Route::get('/status', [SoporteController::class, 'status'])->name('status');
        Route::get('/atenciones', [SoporteController::class, 'atenciones'])->name('atenciones');
        Route::get('/tipo-atenciones', [SoporteController::class, 'typeatencions'])->name('typeatencions');
        Route::get('/entornos-atencion', [SoporteController::class, 'entornos'])->name('entornos');
        Route::get('/prioridades-atencion', [SoporteController::class, 'priorities'])->name('priorities');
        Route::get('/create-order', [SoporteController::class, 'newOrder'])->name('neworder');
        Route::get('/create-order/{area}', [OrderController::class, 'create'])->name('order.create');
        Route::get('/marcas-autorizadas', [CenterserviceController::class, 'index'])->name('centerservices');
        Route::get('/marcas-autorizadas/create', [CenterserviceController::class, 'create'])->name('centerservice.create');

        // Route::resource('soporte', SoporteController::class)->parameters(['soporte'=> 'soporte'])->names('soporte');
    });
