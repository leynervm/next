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
            $mensaje = response()->json([
                'title' => 'PERFIL DE USUARIO NO TIENE ACCESO',
                'text' => 'Usuario de acceso debe estar vinculado al personal de empresa, contáctese con su administrador.',
                'type' => 'warning'
            ]);
            return redirect()->back()->with('message', $mensaje);
            // return redirect()->route('admin.ventas')->with('message', $mensaje);
        }

        return $next($request);
    }
}
