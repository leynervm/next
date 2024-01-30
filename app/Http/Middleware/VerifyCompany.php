<?php

namespace App\Http\Middleware;

use App\Models\Empresa;
use Closure;
use Illuminate\Http\Request;

class VerifyCompany
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
        if (!$empresa) {
            $mensaje = response()->json([
                'title' => 'CONFIGURAR PERFIL DE LA EMPRESA',
                'text' => 'Configurar los datos de la empresa, requeridos por el sistema, contáctese con su administrador.',
                'type' => 'warning'
            ]);
            return redirect()->back()->with('message', $mensaje);
        }

        return $next($request);
    }
}
