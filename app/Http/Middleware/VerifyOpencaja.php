<?php

namespace App\Http\Middleware;

use App\Models\Box;
use App\Models\Monthbox;
use App\Models\Openbox;
use Closure;
use Illuminate\Http\Request;

class VerifyOpencaja
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

        if (Monthbox::usando(auth()->user()->sucursal_id)->where('sucursal_id', auth()->user()->sucursal_id)->exists() == false) {
            $mensaje = response()->json([
                'title' => 'SUCURSAL NO DISPONE DE CAJAS MENSUALES ACTIVAS !',
                'text' => 'No existen cajas mensuales registradas, contáctese con su administrador.',
                'type' => 'warning'
            ])->getData();
            return redirect()->back()->with('message', $mensaje);
        }

        $openbox = Openbox::mybox(auth()->user()->sucursal_id)->first();

        if ($openbox) {
            if ($openbox->isExpired()) {
                $mensaje = response()->json([
                    'title' => 'CAJA APERTURADA DEL DÍA HA EXPIRADO !',
                    'text' => 'Cerrar caja y aperturar nueva, para registrar movimientos diarios.',
                    'type' => 'warning'
                ])->getData();
                return redirect()->back()->with('message', $mensaje);
            }
        } else {
            $mensaje = response()->json([
                'title' => 'APERTURAR CAJA DIARIA PARA GENERAR MOVIMIENTOS !',
                'text' => 'Aperturtar nueva caja del usuario logueado para registrar movimientos.',
                'type' => 'warning'
            ])->getData();
            return redirect()->back()->with('message', $mensaje);
        }

        return $next($request);
    }
}
