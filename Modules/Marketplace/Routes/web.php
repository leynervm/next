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

use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Route;
use Modules\Marketplace\Http\Controllers\ClaimbookController;
use Modules\Marketplace\Http\Controllers\ContactController;
use Modules\Marketplace\Http\Controllers\MarketplaceController;
use Modules\Marketplace\Http\Controllers\NiubizController;
use Modules\Marketplace\Http\Controllers\OrderController;

// Route::middleware([
//     'auth:sanctum', config('jetstream.auth_session'),
// ])->middleware(['auth', 'verifysucursal'])
//     ->prefix('admin/marketplace')->name('admin.marketplace')->group(function () {
//         Route::get('/', [MarketplaceController::class, 'index']);
//         Route::get('/{venta:seriecompleta}/edit', [MarketplaceController::class, 'edit'])->name('.edit');
//         Route::get('/{venta:seriecompleta}/show', [MarketplaceController::class, 'show'])->name('.show');
//     });


Route::middleware('verifyproductocarshoop')->group(function () {
    Route::get('/productos', [MarketplaceController::class, 'productos'])->name('productos');
    Route::get('/productos/{producto:slug}', [MarketplaceController::class, 'showproducto'])->name('productos.show');
    Route::get('/ofertas', [MarketplaceController::class, 'ofertas'])->name('ofertas');
    Route::get('/carshoop', [MarketplaceController::class, 'carshoop'])->name('carshoop');
    Route::get('/mis-deseos', [MarketplaceController::class, 'wishlist'])->name('wishlist');
    Route::get('/perfil', [MarketplaceController::class, 'profile'])->name('profile');

    Route::get('/libro-reclamaciones/create', [ClaimbookController::class, 'create'])->name('claimbook.create');
    Route::post('/libro-reclamaciones/store', [ClaimbookController::class, 'store'])->name('claimbook.store');
    Route::get('/libro-reclamaciones/{claimbook}/resumen', [ClaimbookController::class, 'resumen'])->name('claimbook.resumen');
    Route::get('/libro-reclamaciones/{claimbook}/print', [ClaimbookController::class, 'print'])->name('claimbook.print.pdf');

    Route::get('/nosotros', [MarketplaceController::class, 'nosotros'])->name('nosotros');
    Route::get('/contactanos', [MarketplaceController::class, 'contactanos'])->name('contactanos');
    Route::post('/contactanos/store', [ContactController::class, 'store'])->name('contactanos.store');
    Route::get('/centro-autorizado', [MarketplaceController::class, 'centroautorizado'])->name('centroautorizado');
    Route::get('/ubicanos', [MarketplaceController::class, 'ubicanos'])->name('ubicanos');
    Route::get('/trabaja-con-nosotros', [MarketplaceController::class, 'trabaja'])->name('trabaja');
});


Route::middleware([
    'auth:sanctum', config('jetstream.auth_session'),
])->group(function () {

    Route::get('/carshoop/registrar-compra', [MarketplaceController::class, 'create'])->name('carshoop.create')->middleware(['verifyproductocarshoop', 'carshoop', 'verified']);
    
    Route::get('/orders', [MarketplaceController::class, 'orders'])->name('orders');
    Route::get('/orders/{order}/payment', [MarketplaceController::class, 'payment'])->name('orders.payment');
    // Route::post('/orders/{order}/payment/deposito', [MarketplaceController::class, 'deposito'])->name('orders.pay.deposito');
    Route::post('/orders/niubiz/checkout', [NiubizController::class, 'checkout'])->name('orders.niubiz.checkout')->middleware(['verified']);
    




    Route::get('/admin/productos/caracteristicas-especificaciones', [MarketplaceController::class, 'caracteristicas'])->name('admin.almacen.caracteristicas');

    Route::middleware(['verifysucursal'])->name('admin.marketplace')->prefix('admin/marketplace')->group(function () {
        Route::get('/', [MarketplaceController::class, 'index']);
        Route::get('/estados-seguimiento-pedido', [MarketplaceController::class, 'trackingstates'])->name('.trackingstates');
        Route::get('/transacciones-web', [MarketplaceController::class, 'transacciones'])->name('.transacciones');
        Route::get('/tipos-envio', [MarketplaceController::class, 'shipmenttypes'])->name('.shipmenttypes');
        Route::get('/usuarios-web', [MarketplaceController::class, 'usersweb'])->name('.usersweb');
        Route::get('/sliders', [MarketplaceController::class, 'sliders'])->name('.sliders');

        Route::get('/orders', [OrderController::class, 'index'])->name('.orders');
        Route::get('/orders/{order}/show', [OrderController::class, 'show'])->name('.orders.show');
    });

    Route::middleware(['verifysucursal'])->name('admin.administracion')->prefix('admin/administracion')->group(function () {
        Route::get('/libro-reclamaciones', [ClaimbookController::class, 'claimbooks'])->name('.claimbooks');
    });
});

// Route::get('/orders/{order}/show', [OrderController::class, 'show'])->name('order.show');

// Route::get('/prueba', function () {
//     return Cart::instance('shopping')->content();

    // $producto = Producto::with(['promocions' => function ($query) {
    //     $query->with(['itempromos'])->disponibles();
    // }])->find(14);
    // $pricetype = Pricetype::find(1);
    // $precioVenta = $producto->calcularPrecioVentaLista($pricetype, 0);

    // return [
    //     $producto,
    //     'precio_real_compra' => $producto->precio_real_compra,
    //     'precio_venta_lista' => $precioVenta,
    //     'precio_manual_lista' => $producto->calcularPrecioManualLista($pricetype),
    //     'ganancia_lista' => $producto->obtenerPorcentajeGananciaLista($pricetype->id),
    //     'precio_con_descuento' => $producto->calcularPrecioDescuento($precioVenta, 30, 0, null)
    // ];
// });
