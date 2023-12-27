<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifySucursal
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

        $user = auth()->user();
        // if (session('redirect_url')) {
        //     $url = session('redirect_url');
        //     session()->forget('intended_url');
        //     return redirect()->to($url);
        // } else {
        //     return redirect()->route('admin.administracion.empresa');
        // }

        if ($user->sucursals()->exists()) {
            if (!$user->sucursalDefault()->exists()) {
                $mensaje = response()->json([
                    'title' => 'SELECCIONAR SUCURSAL PREDETERMINADO',
                    'text' => 'Seleccione una sucursal predeterminada a usar !',
                    'type' => 'warning'
                ]);

                return redirect()->back()->with('message', $mensaje);
                // return redirect()->route('admin.ventas')->with('message', $mensaje);
            }

            if (!$user->sucursalDefault()->first()->seriecomprobantes()->exists()) {
                $mensaje = response()->json([
                    'title' => 'ASIGNAR SERIES SUCURSAL',
                    'text' => 'Seleccionar series para generar comprobantes de venta !',
                    'type' => 'warning'
                ]);
                return redirect()->back()->with('message', $mensaje);
                // return redirect()->route('admin.ventas')->with('message', $mensaje);
            }
            return $next($request);
        } else {
            $mensaje = response()->json([
                'title' => 'ASIGNAR SUCURSAL',
                'text' => 'Sucursal no asignado, contáctese con el administrador del sistema !',
                'type' => 'warning'
            ]);
            return redirect()->back()->with('message', $mensaje);
            // return redirect()->route('admin.ventas')->with('message', $mensaje);
        }
    }
}
