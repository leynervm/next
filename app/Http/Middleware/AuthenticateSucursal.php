<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthenticateSucursal
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


        dd($request->all());

        if (auth()->user()->sucursal_id == $request->sucursal_id) {

            $mensaje = response()->json([
                'title' => 'SUCURSAL ES DISTINTO A SUCURSAL LOGUEADO',
                'text' => 'Solo puede visualizar información de la sucursal asignada.',
                'type' => 'warning'
            ]);
            return redirect()->back()->with('message', $mensaje);
        }
        return $next($request);
    }
}
