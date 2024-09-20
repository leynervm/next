<?php

namespace App\Http\Middleware;

use Closure;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;

class Carshoop
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

        if (Cart::instance('shopping')->count() > 0) {
            return $next($request);
        } else {
            $mensaje = response()->json([
                'title' => 'NO EXISTEN PRODUCTOS AGREGADOS EN EL CARRITO DE COMPRAS',
                'text' => 'No se puede registrtar pedido sin items en el carrito.',
                'type' => 'warning'
            ])->getData();
            return redirect()->back()->with('message', $mensaje);
        }
    }
}
