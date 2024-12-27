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

        $count = 0;
        $cart = Cart::instance('shopping');
        if ($cart->count() > 0) {
            foreach ($cart->content() as $item) {
                if (is_null($item->model)) {
                    $cart->get($item->rowId);
                    $cart->remove($item->rowId);
                    $count++;
                }
            }

            if ($count > 0) {
                if (auth()->check()) {
                    Cart::instance('shopping')->store(auth()->id());
                }

                $mensaje = response()->json([
                    'title' => "ALGUNOS PRODUCTOS FUERON REMOVIDOS DEL CARRITO.",
                    'text' => 'Carrito de compras actualizado, algunos productos han dejado de estar disponibles en tienda web.',
                    'type' => 'warning'
                ])->getData();
                session()->now('message', $mensaje);
            }
        }

        if (Cart::instance('shopping')->count() == 0 && Route::currentRouteName() == 'carshoop.create') {
            $mensaje = response()->json([
                'title' => "NO EXISTEN PRODUCTOS AGREGADOS AL CARRITO",
                'text' => 'Carrito de compras actualizado.',
                'type' => 'warning',
                'timer' => 3000,
            ])->getData();
            return redirect()->to('/')->with('message', $mensaje);
        }

        return $next($request);
    }
}
