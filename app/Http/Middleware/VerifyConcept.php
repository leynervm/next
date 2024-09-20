<?php

namespace App\Http\Middleware;

use App\Models\Concept;
use Closure;
use Illuminate\Http\Request;

class VerifyConcept
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

        $concepts = Concept::exists();
        if (!$concepts) {
            $mensaje = response()->json([
                'title' => 'REGISTRAR CONCEPTOS DE PAGO',
                'text' => 'Registrar conceptos de pago a utilizar.',
                'type' => 'warning'
            ])->getData();
            return redirect()->back()->with('message', $mensaje);
            // return redirect()->route('admin.cajas.methodpayments');
        }

        $exists = Concept::ventas()->exists();
        if (!$exists) {
            $mensaje = response()->json([
                'title' => 'SELECCIONAR FORMA DE PAGO PREDETERMINADA PARA VENTAS',
                'text' => 'Seleccione una forma de pago predeterminada a utilizar en el modulo ventas.',
                'type' => 'warning'
            ])->getData();
            return redirect()->back()->with('message', $mensaje);
            // return redirect()->route('admin.cajas.methodpayments');
        }

        return $next($request);
    }
}
