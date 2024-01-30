<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Nwidart\Modules\Facades\Module;

class VerifySerieguias
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

        $serieguias = auth()->user()->sucursal->seriecomprobantes()->whereHas('typecomprobante', function ($query) {
            if (Module::isDisabled('Facturacion')) {
                $query->default();
            }
            $query->whereIn('code', ['09', '13']);
        })->exists();

        if (!$serieguias) {
            $mensaje = response()->json([
                'title' => 'ASIGNAR SERIES DE GUIAS A SUCURSAL',
                'text' => 'Asignar series para generar guías de remisión en sucursal asignada.',
                'type' => 'warning'
            ]);
            return redirect()->back()->with('message', $mensaje);
        }
        return $next($request);
    }
}
