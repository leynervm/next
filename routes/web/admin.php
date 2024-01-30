<?php

use App\Http\Controllers\AlmacenController;
use App\Http\Controllers\CajaController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\SucursalController;
use App\Http\Controllers\UserController;
use App\Http\Livewire\Admin\Sucursales\ShowSucursales;
use Illuminate\Support\Facades\Route;
use Nwidart\Modules\Facades\Module;

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


Route::get('/users', [UserController::class, 'index'])->name('admin.users');
Route::get('/users/create', [UserController::class, 'create'])->name('admin.users.create');
Route::post('/users/store', [UserController::class, 'store'])->name('admin.users.store');
Route::get('/users/edit/{user}', [UserController::class, 'edit'])->name('admin.users.edit');
Route::post('/users/update/{user}', [UserController::class, 'update'])->name('admin.users.update');


Route::get('/users/roles', [UserController::class, 'roles'])->name('admin.users.roles');
Route::get('/users/permisos', [UserController::class, 'permisos'])->name('admin.users.permisos');
Route::get('/users/historial', [UserController::class, 'history'])->name('admin.users.history');
Route::get('/users/historial-reset-password', [UserController::class, 'historypassword'])->name('admin.users.historypassword');



Route::get('/administracion/empresa/edit/', [EmpresaController::class, 'show'])->name('admin.administracion.empresa')->middleware(['verifycompany']);
Route::get('/administracion/empresa/create', [EmpresaController::class, 'create'])->name('admin.administracion.empresa.create')->middleware(['registercompany']);



Route::get('/administracion/employers', [HomeController::class, 'employers'])->name('admin.administracion.employers')->middleware(['verifycompany']);





Route::get('/administracion/sucursales', [SucursalController::class, 'index'])->name('admin.administracion.sucursales')->middleware(['verifycompany']);
Route::get('/administracion/sucursales/{sucursal:id}/edit/', [SucursalController::class, 'edit'])->name('admin.administracion.sucursales.edit')->middleware(['verifycompany']);
// Route::get('/administracion/sucursales', ShowSucursales::class)->name('admin.administracion.sucursales')->middleware(['registercompany']);

Route::get('/unidades-medida', [AlmacenController::class, 'units'])->name('admin.units');


Route::get('/clientes', [ClientController::class, 'index'])->name('admin.clientes')->middleware(['verifycompany']);
Route::get('/clientes/{client:document}/edit', [ClientController::class, 'show'])->name('admin.clientes.show')->middleware(['verifycompany']);
Route::get('/clientes/{client:document}/historial-ventas', [ClientController::class, 'show'])->name('admin.clientes.historial')->middleware(['verifycompany']);

Route::get('/proveedores', [ProveedorController::class, 'index'])->name('admin.proveedores');
Route::get('/proveedores/create', [ProveedorController::class, 'create'])->name('admin.proveedores.create');
Route::get('/proveedores/{proveedor:document}/edit', [ProveedorController::class, 'show'])->name('admin.proveedores.show');
Route::get('/proveedores/tipos', [ProveedorController::class, 'proveedortypes'])->name('admin.proveedores.tipos');
Route::get('/proveedores/{proveedor:document}/historial-compras', [ProveedorController::class, 'history'])->name('admin.proveedores.historial');
Route::get('/proveedores/{proveedor:document}/historial-pedidos', [ProveedorController::class, 'pedidos'])->name('admin.proveedores.pedidos');


Route::get('/lista-precios', [HomeController::class, 'pricetypes'])->name('admin.pricetypes');
Route::get('/canales-venta', [HomeController::class, 'channelsales'])->name('admin.channelsales');

Route::get('/administracion/tipo-comprobantes', [HomeController::class, 'typecomprobantes'])->name('admin.administracion.typecomprobantes')->middleware(['verifycompany']);




Route::get('/cajas', [CajaController::class, 'index'])->name('admin.cajas');
Route::get('/cajas/aperturas', [CajaController::class, 'aperturas'])->name('admin.cajas.aperturas')->middleware(['verifysucursal']);
Route::get('/cajas/administrar', [CajaController::class, 'administrar'])->name('admin.cajas.administrar');
Route::get('/cajas/conceptos', [CajaController::class, 'conceptos'])->name('admin.cajas.conceptos');
Route::get('/cajas/movimientos', [CajaController::class, 'movimientos'])->name('admin.cajas.movimientos');
Route::get('/cajas/resumen', [CajaController::class, 'resumen'])->name('admin.cajas.resumen');
Route::get('/cajas/cuentas', [CajaController::class, 'cuentas'])->name('admin.cajas.cuentas');
Route::get('/cajas/forma-pago', [CajaController::class, 'methodpayments'])->name('admin.cajas.methodpayments');


if (Module::isEnabled('Almacen') || Module::isEnabled('Ventas')) {
    Route::get('/almacen/productos', [ProductoController::class, 'index'])->name('admin.almacen.productos')->middleware(['verifysucursal']);
    Route::get('/almacen/productos/create', [ProductoController::class, 'create'])->name('admin.almacen.productos.create')->middleware(['verifysucursal', 'verifyalmacen']);
    Route::get('/almacen/productos/{producto:slug}/edit', [ProductoController::class, 'edit'])->name('admin.almacen.productos.show')->middleware(['verifysucursal']);

    Route::get('/almacen/productos/promociones', [HomeController::class, 'promociones'])->name('admin.almacen.promociones')->middleware(['verifysucursal']);
}

Route::get('/marcas', [HomeController::class, 'marcas'])->name('admin.almacen.marcas');
Route::get('/categorias', [AlmacenController::class, 'categorias'])->name('admin.almacen.categorias');
Route::get('/subcategorias', [AlmacenController::class, 'subcategorias'])->name('admin.almacen.subcategorias');
Route::get('/almacen/productos/ofertas', [AlmacenController::class, 'ofertas'])->name('admin.almacen.ofertas');
Route::get('/especificaciones', [AlmacenController::class, 'especificaciones'])->name('admin.almacen.especificaciones');


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
