<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifyAlmacen
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

        $exists = auth()->user()->sucursal->almacens()->exists();

        if (!$exists) {
            $mensaje = response()->json([
                'title' => 'ASIGNAR ALMACÉN A SUCURSAL',
                'text' => 'Registrar al menos un almacén en sucursal para continuar.',
                'type' => 'warning'
            ])->getData();
            return redirect()->back()->with('message', $mensaje);
        }

        return $next($request);
    }
}
