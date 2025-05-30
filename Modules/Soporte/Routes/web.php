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
use Modules\Soporte\Http\Controllers\SoporteController;
use Modules\Soporte\Http\Controllers\TicketController;

Route::middleware(['auth:sanctum', config('jetstream.auth_session'),])
    ->prefix('admin/soporte')->name('admin.soporte')
    ->group(function () {

        Route::get('/', [SoporteController::class, 'index'])->name('');
        Route::get('/tickets', [TicketController::class, 'index'])->name('.tickets');
        Route::get('/create-ticket', [TicketController::class, 'selectarea'])->name('.tickets.selectarea');
        Route::get('/{areawork:slug}/create-ticket', [TicketController::class, 'create'])->name('.tickets.create');

        Route::get('/marcas-autorizadas', [CenterserviceController::class, 'index'])->name('.centerservices');

        Route::get('/tipos-equipos', [SoporteController::class, 'typeequipos'])->name('.typeequipos');

        Route::get('/status', [SoporteController::class, 'status'])->name('.status');
        
        Route::get('/tipos-atencion', [SoporteController::class, 'atenciones'])->name('.atenciones');
        Route::get('/condiciones-atencion', [SoporteController::class, 'conditions'])->name('.conditions');
        
        Route::get('/tipo-atenciones', [SoporteController::class, 'typeatencions'])->name('.typeatencions');
        Route::get('/entornos-atencion', [SoporteController::class, 'entornos'])->name('.entornos');
        Route::get('/prioridades-atencion', [SoporteController::class, 'priorities'])->name('.priorities');
        Route::get('/marcas-autorizadas/create', [CenterserviceController::class, 'create'])->name('.centerservice.create');

        // Route::resource('soporte', SoporteController::class)->parameters(['soporte'=> 'soporte'])->names('soporte');
    });
