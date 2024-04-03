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
use Modules\Employer\Http\Controllers\EmployerController;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    // 'verified',
    'verifycompany'
])->prefix('admin/administracion/personal')->name('admin.administracion.employers')->middleware(['auth', 'verifysucursal'])->group(function () {
    Route::get('/', [EmployerController::class, 'index']);
    Route::get('/{employer:document}/pagos', [EmployerController::class, 'payments'])->name('.payments');
    Route::get('/turnos', [EmployerController::class, 'turnos'])->name('.turnos');
});
