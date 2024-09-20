<?php

namespace App\Http\Middleware;

use App\Models\Methodpayment;
use Closure;
use Illuminate\Http\Request;

class VerifyMethodpayment
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
        $methodpayments = Methodpayment::all()->count();
        if ($methodpayments == 0) {
            $mensaje = response()->json([
                'title' => 'REGISTRAR FORMAS DE PAGO',
                'text' => 'Registrar formas de pago a utilizar.',
                'type' => 'warning'
            ])->getData();
            return redirect()->back()->with('message', $mensaje);
            // return redirect()->route('admin.cajas.methodpayments');
        }

        // if ($methodpayments > 0) {

        //     $default = Methodpayment::Default()->get()->count();
        //     if ($default == 0) {
        //         $mensaje = response()->json([
        //             'title' => 'SELECCIONAR FORMA DE PAGO PREDETERMINADA',
        //             'text' => 'Seleccione una forma de pago predeterminada a utilizar !',
        //             'type' => 'warning'
        //         ]);
        //         return redirect()->back()->with('message', $mensaje);
        //     }
        // }

        return $next($request);
    }
}
