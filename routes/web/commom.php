<?php

use App\Enums\StatusPayWebEnum;
use App\Http\Controllers\AuthController;
use App\Models\Category;
use App\Models\Moneda;
use App\Models\Slider;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Laravel\Jetstream\Http\Controllers\Livewire\UserProfileController;
use Modules\Marketplace\Entities\Order;

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

Route::middleware([
    'auth:sanctum', config('jetstream.auth_session'), 'dashboard'
])->get('/user/profile', [UserProfileController::class, 'show'])->name('profile.show');


if (Features::enabled(Features::registration())) {
    Route::get('/register', function () {
        return redirect()->route('login')->with('activeForm', 'register');
    })->name('register');
}


// AUTH WITH REDES SOCIALES
Route::get('/login/auth/{driver}/redirect', [AuthController::class, 'redirect'])->name('auth.redirect');
Route::get('/login/auth/{driver}/callback', [AuthController::class, 'callback'])->name('auth.callback');


Route::get('/', function () {
    $empresa = mi_empresa();
    $moneda = Moneda::default()->first();
    $pricetype = getPricetypeAuth($empresa);
    $sliders = Slider::activos()->disponibles()->orderBy('orden', 'asc')->get();

    if (auth()->user()) {
        $status_pendiente = StatusPayWebEnum::PENDIENTE->value;
        $orderspending = Order::where('status', $status_pendiente)->count();
        if ($orderspending) {
            $mensaje = "Usted tiene $orderspending ordenes pendientes. <a class='font-semibold' href='" . route('orders') . "?estado-pago=$status_pendiente' >Ir a pagar</a>";
            session()->flash('flash.banner', $mensaje);
        }
    }

    return view('welcome', compact('sliders', 'empresa', 'moneda', 'pricetype'));
});
