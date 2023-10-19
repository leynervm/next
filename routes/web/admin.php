<?php

use App\Http\Controllers\CajaController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProveedorController;
use Illuminate\Support\Facades\Route;

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

// Route::middleware([
//     'auth:sanctum',
//     config('jetstream.auth_session'),
//     'verified'
// ])->prefix('admin/')->group(function () {

Route::get('/', [HomeController::class, 'index'])->name('admin');

Route::get('/administracion', [HomeController::class, 'administracion'])->name('admin.administracion');
Route::get('/areas', [HomeController::class, 'areas'])->name('admin.areas');

Route::get('/administracion/empresa', [EmpresaController::class, 'index'])->name('admin.administracion.empresa');
Route::get('/administracion/empresa/create', [EmpresaController::class, 'create'])->name('admin.administracion.empresa.create');

Route::get('/clientes', [ClientController::class, 'index'])->name('admin.clientes');
Route::get('/clientes/{client:document}', [ClientController::class, 'show'])->name('admin.clientes.show');

Route::get('/proveedores', [ProveedorController::class, 'index'])->name('admin.proveedores');
Route::get('/proveedores/create', [ProveedorController::class, 'create'])->name('admin.proveedores.create');
Route::get('/proveedores/edit/{proveedor:document}', [ProveedorController::class, 'show'])->name('admin.proveedores.show');
Route::get('/proveedores/tipos', [ProveedorController::class, 'proveedortypes'])->name('admin.proveedores.tipos');


Route::get('/lista-precios', [HomeController::class, 'pricetypes'])->name('admin.pricetypes');
Route::get('/canales-venta', [HomeController::class, 'channelsales'])->name('admin.channelsales');



Route::get('/cajas', [CajaController::class, 'index'])->name('admin.cajas');
Route::get('/cajas/aperturas', [CajaController::class, 'aperturas'])->name('admin.cajas.aperturas');
Route::get('/cajas/administrar', [CajaController::class, 'administrar'])->name('admin.cajas.administrar');
Route::get('/cajas/conceptos', [CajaController::class, 'conceptos'])->name('admin.cajas.conceptos');
Route::get('/cajas/movimientos', [CajaController::class, 'movimientos'])->name('admin.cajas.movimientos');
Route::get('/cajas/resumen', [CajaController::class, 'resumen'])->name('admin.cajas.resumen');
Route::get('/cajas/cuentas', [CajaController::class, 'cuentas'])->name('admin.cajas.cuentas');
Route::get('/cajas/forma-pago', [CajaController::class, 'methodpayments'])->name('admin.cajas.methodpayments');







Route::get('tipocambio', [HomeController::class, 'tipocambio'])->name('tipocambio');
Route::get('consulta-sunat/{document?}', [HomeController::class, 'consultasunat'])->name('consultasunat');



// });


// Route::middleware([
//     'auth:sanctum',
//     config('jetstream.auth_session'),
//     'verified'
// ])->group(function () {
// });


// Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
//     Route::get('/dashboard', function () {
//         return view('dashboard');
//     })->name('dashboard');
// });
