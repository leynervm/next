<?php

namespace App\Http\Middleware;

use App\Models\Empresa;
use Closure;
use Illuminate\Http\Request;
use Nwidart\Modules\Facades\Module;

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

        $empresa = Empresa::exists();
        // session(['redirect_url' => url()->previous()]);
        if (!$empresa) {
            $mensaje = response()->json([
                'title' => 'CONFIGURAR PERFIL DE LA EMPRESA',
                'text' => 'Configurar los datos de la empresa, requeridos por el sistema, contáctese con su administrador.',
                'type' => 'warning'
            ]);
            return redirect()->back()->with('message', $mensaje);
            // return redirect()->route('admin.administracion.empresa.create')->with('message', 'Configurar datos de la empresa !');
        }

        if (auth()->user()->sucursal_id == null) {
            if (auth()->user()->isAdmin()) {
                $mensaje = response()->json([
                    'title' => 'SELECCIONAR SUCURSAL A USAR DE MANERA PREDETERMINADA',
                    'text' => 'Usuario administrador logueado, seleccionar una sucursal para administrar los datos.',
                    'type' => 'warning'
                ]);
            } else {
                $mensaje = response()->json([
                    'title' => 'PERFIL DE USUARIO NO TIENE ACCESO',
                    'text' => 'Vincular usuario a un personal de trabajo para realizar acciones en el sistema, contáctese con su administrador.',
                    'type' => 'warning'
                ]);
            }
            return redirect()->back()->with('message', $mensaje);
            // return redirect()->route('admin.ventas')->with('message', $mensaje);
        }

        return $next($request);
    }
}
