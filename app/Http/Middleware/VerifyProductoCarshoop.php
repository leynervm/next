<?php

namespace App\Http\Middleware;

use Closure;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;

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
        $count = 0;
        if (Cart::instance('shopping')->count() > 0) {
            foreach (Cart::instance('shopping')->content() as $item) {
                if (is_null($item->model)) {
                    Cart::instance('shopping')->get($item->rowId);
                    Cart::instance('shopping')->remove($item->rowId);
                    $count++;
                }
            }
        }

        $countwish = 0;
        if (Cart::instance('wishlist')->count() > 0) {
            foreach (Cart::instance('wishlist')->content() as $item) {
                if (is_null($item->model)) {
                    Cart::instance('wishlist')->get($item->rowId);
                    Cart::instance('wishlist')->remove($item->rowId);
                    $countwish++;
                }
            }
        }

        if ($count > 0 || $countwish > 0) {
            if (auth()->check()) {
                Cart::instance('shopping')->store(auth()->id());
                Cart::instance('wishlist')->store(auth()->id());
            }

            $mensaje = response()->json([
                'title' => "ALGUNOS PRODUCTOS FUERON REMOVIDOS DEL CARRITO.",
                'text' => 'Carrito de compras actualizado, algunos productos han dejado de estar disponibles en tienda web.',
                'type' => 'warning'
            ])->getData();
            session()->now('message', $mensaje);
        }

        return $next($request);
    }
}
