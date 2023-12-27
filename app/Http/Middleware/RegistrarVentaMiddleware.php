<?php

namespace App\Http\Middleware;

use App\Models\Opencaja;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegistrarVentaMiddleware
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
        $caja = Opencaja::CajasAbiertas()->CajasUser()->orderBy('startdate', 'desc')->first();
        if (!$caja) {

            $mensaje = response()->json([
                'title' => 'APERTURAR CAJA DE USUARIO',
                'text' => 'Aperturtar nueva caja para el usuario logueado para registrar movimientos !',
                'type' => 'warning'
            ]);
            return redirect()->route('admin.cajas.aperturas')->with('message', $mensaje);
        }

        return $next($request);
    }
}
