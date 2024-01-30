<?php

namespace App\Http\Middleware;

use App\Models\Opencaja;
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

        // $caja = Opencaja::CajasAbiertas()->CajasUser()->orderBy('startdate', 'desc')->first();
        $opencaja = Opencaja::CajasAbiertas()->CajasUser()
            ->WhereHas('caja', function ($query) {
                $query->where('sucursal_id', auth()->user()->sucursal_id);
            })->first();

        if (!$opencaja) {
            $mensaje = response()->json([
                'title' => 'APERTURAR CAJA DE USUARIO',
                'text' => 'Aperturtar nueva caja para el usuario logueado para registrar movimientos.',
                'type' => 'warning'
            ]);
            return redirect()->route('admin.cajas.aperturas')->with('message', $mensaje);
        }

        if ($opencaja) {
            if ($opencaja->isExpired()) {
                $mensaje = response()->json([
                    'title' => 'CAJA DEL USUARIO EXPIRADO !',
                    'text' => 'Cerrar caja y aperturar nuevamente, o actualize la fecha del cierre de caja.',
                    'type' => 'warning'
                ]);
                return redirect()->route('admin.cajas.aperturas')->with('message', $mensaje);
            }
        }

        return $next($request);
    }
}
