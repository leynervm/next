<?php

use App\Http\Controllers\AlmacenController;
use App\Http\Controllers\CajaController;
use App\Http\Controllers\CarshoopController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PrintController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SucursalController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Laravel\Jetstream\Http\Controllers\Livewire\UserProfileController;
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
// ])->prefix('admin/')->group(unction () {
Route::get('/perfil/user', function () {
    return redirect()->route('admin.profile');
})->name('profile.show');
Route::get('/', [HomeController::class, 'dashboard'])->name('admin')->middleware('dashboard');
Route::get('/perfil', [UserController::class, 'profile'])->name('admin.profile')->middleware(['verifycompany']);

Route::middleware(['verifycompany'])->prefix('users')->name('admin.users')->group(function () {
    Route::get('/', [UserController::class, 'index']);
    Route::get('/create', [UserController::class, 'create'])->name('.create');
    Route::get('/{user}/edit', [UserController::class, 'edit'])->name('.edit');
    Route::get('/historial', [UserController::class, 'history'])->name('.history');
    Route::get('/historial-reset-password', [UserController::class, 'historypassword'])->name('.historypassword');
    // Route::post('/users/store', [UserController::class, 'store'])->name('admin.users.store');
    // Route::post('/users/update/{user}', [UserController::class, 'update'])->name('admin.users.update');
});

Route::get('/administracion/roles', [RoleController::class, 'index'])->name('admin.roles');
Route::get('/administracion/roles/create', [RoleController::class, 'create'])->name('admin.roles.create');
Route::get('/administracion/roles/{role}/edit', [RoleController::class, 'edit'])->name('admin.roles.edit');
Route::get('/administracion/roles/permisos', [RoleController::class, 'permisos'])->name('admin.roles.permisos');


Route::prefix('clientes')->name('admin.clientes')->group(function () {
    Route::get('/', [ClientController::class, 'index'])->middleware(['verifycompany']);
    Route::get('/{client:document}/edit', [ClientController::class, 'edit'])->name('.edit')->middleware(['verifycompany']);
    Route::get('/{client:document}/historial-ventas', [ClientController::class, 'history'])->name('.historial')->middleware(['verifycompany']);
});


Route::prefix('proveedores')->name('admin.proveedores')->group(function () {
    Route::get('/', [ProveedorController::class, 'index'])->middleware(['verifycompany']);
    Route::get('/create', [ProveedorController::class, 'create'])->name('.create')->middleware(['verifycompany']);
    Route::get('/{proveedor:document}/edit', [ProveedorController::class, 'edit'])->name('.edit')->middleware(['verifycompany']);
    Route::get('/tipos', [ProveedorController::class, 'proveedortypes'])->name('.tipos')->middleware(['verifycompany']);
    Route::get('/{proveedor:document}/historial-compras', [ProveedorController::class, 'history'])->name('.historial')->middleware(['verifycompany']);
    Route::get('/{proveedor:document}/historial-pedidos', [ProveedorController::class, 'pedidos'])->name('.pedidos')->middleware(['verifycompany']);
});


// Se quito middleware 'box'  de admin.cajas
Route::prefix('cajas')->name('admin.cajas')->group(function () {
    Route::get('/', [CajaController::class, 'index'])->middleware(['verifysucursal']);
    Route::get('/aperturas', [CajaController::class, 'aperturas'])->name('.aperturas')->middleware(['verifysucursal', 'box']);
    Route::get('/conceptos', [CajaController::class, 'conceptos'])->name('.conceptos');
    Route::get('/movimientos', [CajaController::class, 'movimientos'])->name('.movimientos');
    Route::get('/forma-pago', [CajaController::class, 'methodpayments'])->name('.methodpayments');
    Route::get('/mensuales', [CajaController::class, 'mensuales'])->name('.mensuales')->middleware(['verifysucursal']);
});

Route::prefix('administracion')->name('admin.administracion')->group(function () {
    Route::get('/', [HomeController::class, 'administracion']);
    Route::get('/empresa/create', [EmpresaController::class, 'create'])->name('.empresa.create')->middleware(['registercompany']);
    Route::get('/empresa/edit/', [EmpresaController::class, 'edit'])->name('.empresa')->middleware(['verifycompany']);
    Route::get('/sucursales', [SucursalController::class, 'index'])->name('.sucursales')->middleware(['verifycompany']);
    Route::get('/sucursales/{sucursal:id}/edit/', [SucursalController::class, 'edit'])->name('.sucursales.edit')->middleware(['verifycompany']);
    Route::get('/tipo-comprobantes', [HomeController::class, 'typecomprobantes'])->name('.typecomprobantes')->middleware(['verifycompany']);
    Route::get('/unidades-medida', [AlmacenController::class, 'units'])->name('.units')->middleware(['verifycompany']);
    Route::get('/areas-trabajo', [HomeController::class, 'areaswork'])->name('.areaswork')->middleware(['verifycompany']);
});



Route::get('/marcas', [HomeController::class, 'marcas'])->name('admin.almacen.marcas');
Route::get('/categorias', [AlmacenController::class, 'categorias'])->name('admin.almacen.categorias');
Route::get('/subcategorias', [AlmacenController::class, 'subcategorias'])->name('admin.almacen.subcategorias');


if (Module::isEnabled('Almacen') || Module::isEnabled('Ventas')) {
    Route::get('/almacen/productos', [ProductoController::class, 'index'])->name('admin.almacen.productos');
    Route::get('/almacen/productos/create', [ProductoController::class, 'create'])->name('admin.almacen.productos.create')->middleware(['verifysucursal', 'verifyalmacen']);
    Route::get('/almacen/productos/{producto:slug}/edit', [ProductoController::class, 'edit'])->name('admin.almacen.productos.edit')->middleware(['verifysucursal']);
}

Route::get('/payments/{cajamovimiento}/imprimir-ticket', [PrintController::class, 'imprimirticket'])->name('admin.payments.print');




Route::post('admin/carshoops/{carshoop}/delete', [CarshoopController::class, 'delete'])->name('admin.carshoop.delete');
Route::post('admin/carshoops/delete/all', [CarshoopController::class, 'deleteall'])->name('admin.carshoop.delete.all');
Route::post('admin/carshoops/moneda/{moneda_id}/update', [CarshoopController::class, 'updatemoneda'])->name('admin.carshoop.updatemoneda');



// Route::get('/email/{comprobante:seriecompleta}/enviar-xml', [EmailController::class, 'enviarxml'])->name('admin.email.enviarxml');
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
