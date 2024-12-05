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

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Modules\Marketplace\Http\Controllers\ClaimbookController;
use Modules\Marketplace\Http\Controllers\ContactController;
use Modules\Marketplace\Http\Controllers\MarketplaceController;
use Modules\Marketplace\Http\Controllers\NiubizController;
use Modules\Marketplace\Http\Controllers\OrderController;

// AUTH WITH REDES SOCIALES
Route::get('/login/auth/{driver}/redirect', [AuthController::class, 'redirect'])->name('auth.redirect');
Route::get('/login/auth/{driver}/callback', [AuthController::class, 'callback'])->name('auth.callback');


Route::get('/completar-perfil', [UserController::class, 'profilecomplete'])->name('profile.complete')
    ->middleware(['web', 'auth:sanctum', config('jetstream.auth_session')]);
Route::post('/completar-perfil/store', [UserController::class, 'storeprofilecomplete'])->name('profile.complete.save');

Route::get('/perfil', [MarketplaceController::class, 'profile'])->name('profile')
    ->middleware(['web', 'auth:sanctum', config('jetstream.auth_session')]);



Route::middleware(['web', 'verifyproductocarshoop'])->group(function () {
    Route::get('/productos', [MarketplaceController::class, 'productos'])->name('productos');
    Route::get('/productos/{producto:slug}', [MarketplaceController::class, 'showproducto'])->name('productos.show');
    Route::get('/ofertas', [MarketplaceController::class, 'ofertas'])->name('ofertas');
    Route::get('/carshoop', [MarketplaceController::class, 'carshoop'])->name('carshoop');
    Route::get('/mis-deseos', [MarketplaceController::class, 'wishlist'])->name('wishlist');

    Route::get('/libro-reclamaciones/create', [ClaimbookController::class, 'create'])->name('claimbook.create');
    Route::post('/libro-reclamaciones/store', [ClaimbookController::class, 'store'])->name('claimbook.store');
    Route::get('/libro-reclamaciones/{claimbook}/resumen', [ClaimbookController::class, 'resumen'])->name('claimbook.resumen');
    Route::get('/libro-reclamaciones/{claimbook}/print', [ClaimbookController::class, 'print'])->name('claimbook.print.pdf');

    Route::get('/quienes-somos', [MarketplaceController::class, 'quiensomos'])->name('quiensomos');
    Route::get('/contactanos', [MarketplaceController::class, 'contactanos'])->name('contactanos');
    Route::post('/contactanos/store', [ContactController::class, 'store'])->name('contactanos.store');
    Route::get('/centro-autorizado', [MarketplaceController::class, 'centroautorizado'])->name('centroautorizado');
    Route::get('/ubicanos', [MarketplaceController::class, 'ubicanos'])->name('ubicanos');
    Route::get('/trabaja-con-nosotros', [MarketplaceController::class, 'trabaja'])->name('trabaja');

    Route::get('/soluciones-integrales', [MarketplaceController::class, 'tic'])->name('tic');
    Route::get('/servicio-internet', [MarketplaceController::class, 'servicesnetwork'])->name('servicesnetwork');
});





Route::middleware(['web', 'auth'])->group(function () {

    Route::get('/carshoop/registrar-compra', [MarketplaceController::class, 'create'])->name('carshoop.create')
        ->middleware(['verifyproductocarshoop', 'verified', 'verifydatauser']);

    Route::get('/orders', [MarketplaceController::class, 'orders'])->name('orders');
    Route::get('/orders/{order:purchase_number}/resumen', [MarketplaceController::class, 'payment'])->name('orders.payment');
    // Route::post('/orders/{order}/payment/deposito', [MarketplaceController::class, 'deposito'])->name('orders.pay.deposito');


    Route::post('/orders/niubiz/config', [NiubizController::class, 'config_checkout'])->name('orders.niubiz.config')->middleware(['verified']);
    Route::post('/orders/niubiz/checkout', [NiubizController::class, 'checkout'])->name('orders.niubiz.checkout')->middleware(['verified']);


    Route::get('/admin/productos/caracteristicas-especificaciones', [MarketplaceController::class, 'caracteristicas'])->name('admin.almacen.caracteristicas');

    Route::prefix('admin/marketplace')->middleware(['verifysucursal'])->name('admin.marketplace')->group(function () {
        Route::get('/', [MarketplaceController::class, 'index']);
        Route::get('/estados-seguimiento-pedido', [MarketplaceController::class, 'trackingstates'])->name('.trackingstates');
        Route::get('/transacciones-web', [MarketplaceController::class, 'transacciones'])->name('.transacciones');
        Route::get('/tipos-envio', [MarketplaceController::class, 'shipmenttypes'])->name('.shipmenttypes');
        Route::get('/usuarios-web', [MarketplaceController::class, 'usersweb'])->name('.usersweb');
        Route::get('/sliders', [MarketplaceController::class, 'sliders'])->name('.sliders');

        Route::get('/orders', [OrderController::class, 'index'])->name('.orders');
        Route::get('/orders/{order}/show', [OrderController::class, 'show'])->name('.orders.show');

        Route::get('/libro-reclamaciones', [ClaimbookController::class, 'claimbooks'])->name('.claimbooks');
        Route::get('/libro-reclamaciones/{claimbook}/show', [ClaimbookController::class, 'show'])->name('.claimbooks.show');
    });
});
