<?php

namespace App\Http\Middleware;

use Closure;
use CodersFree\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class VerifyProductoCarshoop
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        if (auth()->check()) {
            Cart::instance('shopping')->destroy();
            Cart::instance('shopping')->restore(auth()->id());

            Cart::instance('wishlist')->destroy();
            Cart::instance('wishlist')->restore(auth()->id());
        }

        $carshoop_disponibles = getCartRelations('shopping', true);
        if (count($carshoop_disponibles) == 0 && Route::currentRouteName() == 'carshoop.create') {
            $mensaje = response()->json([
                'title' => "EL CARRITO ESTÁ VACÍO",
                'text' => null,
                'type' => 'warning',
                'timer' => 3000,
            ])->getData();
            return redirect()->to('/')->with('message', $mensaje);
        }

        return $next($request);
    }
}
