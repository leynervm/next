<?php

namespace App\Http\Middleware;

use App\Models\Box;
use Closure;
use Illuminate\Http\Request;

class RegisterBoxes
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

        if (Box::where('sucursal_id', auth()->user()->sucursal_id)->exists() == false) {
            $mensaje = response()->json([
                'title' => 'NO EXISTEN CAJAS DE PAGO REGISTRADAS !',
                'text' => 'No existen cajas registradas, contáctese con su administrador.',
                'type' => 'warning'
            ]);
            return redirect()->back()->with('message', $mensaje);
        }

        return $next($request);
    }
}
