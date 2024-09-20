<?php

namespace App\Http\Middleware;

use App\Models\Empresa;
use App\Models\Pricetype;
use Closure;
use Illuminate\Http\Request;

class VerifyPricetype
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

        $usepricelist = mi_empresa()->uselistprice;

        if ($usepricelist == 1) {
            $pricetypes = Pricetype::all()->count();

            if ($pricetypes == 0) {
                $mensaje = response()->json([
                    'title' => 'REGISTRAR LISTA DE PRECIOS',
                    'text' => 'Registrar lista de precios a utilizar, cuando la opción de usar lista de precios está activa.',
                    'type' => 'warning'
                ])->getData();
                return redirect()->back()->with('message', $mensaje);
                // return redirect()->route('admin.cajas.methodpayments');
            }

            // if ($pricetypes > 0) {
            //     $default = Pricetype::Default()->get()->count();
            //     if ($default == 0) {
            //         $mensaje = response()->json([
            //             'title' => 'SELECCIONAR LISTA DE PRECIOS PREDETERMINADA',
            //             'text' => 'Seleccione una lista de precio predeterminada a utilizar !',
            //             'type' => 'warning'
            //         ]);
            //         return redirect()->back()->with('message', $mensaje);
            //     }
            // }
        }
        return $next($request);
    }
}
