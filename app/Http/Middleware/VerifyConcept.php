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

        $concepts = Concept::all()->count();
        if ($concepts == 0) {
            $mensaje = response()->json([
                'title' => 'REGISTRAR CONCEPTOS DE PAGO',
                'text' => 'Registrar conceptos de pago a utilizar.',
                'type' => 'warning'
            ]);
            return redirect()->back()->with('message', $mensaje);
            // return redirect()->route('admin.cajas.methodpayments');
        }

        if ($concepts > 0) {
            $default = Concept::where('default', Concept::VENTAS)->get()->count();
            if ($default == 0) {
                $mensaje = response()->json([
                    'title' => 'SELECCIONAR FORMA DE PAGO PREDETERMINADA PARA VENTAS',
                    'text' => 'Seleccione una forma de pago predeterminada a utilizar en el modulo ventas.',
                    'type' => 'warning'
                ]);
                return redirect()->back()->with('message', $mensaje);
                // return redirect()->route('admin.cajas.methodpayments');
            }
        }

        return $next($request);
    }
}
