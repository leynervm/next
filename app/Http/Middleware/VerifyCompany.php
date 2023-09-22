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
        $empresa = Empresa::DefaultEmpresa()->first();
        if (!$empresa) {

            return redirect()->route('admin.administracion.empresa')->with('message', 'Configurar datos de la empresa por favor !');
        }

        return $next($request);
    }
}
