<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Nwidart\Modules\Facades\Module;

class VerifySerieventas
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

        $serieventas = auth()->user()->sucursal->seriecomprobantes()->whereHas('typecomprobante', function ($query) {
            if (Module::isDisabled('Facturacion')) {
                $query->default();
            }
            $query->whereNotIn('code', ['07', '09', '13']);
        })->exists();

        if (!$serieventas) {
            $mensaje = response()->json([
                'title' => 'ASIGNAR SERIES DE COMPROBANTES A SUCURSAL',
                'text' => 'Asignar series para generar comprobantes de venta en sucursal asignada.',
                'type' => 'warning'
            ]);
            return redirect()->back()->with('message', $mensaje);
        }
        return $next($request);
    }
}
