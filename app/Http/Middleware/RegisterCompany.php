<?php

namespace App\Http\Middleware;

use App\Models\Empresa;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;

class RegisterCompany
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
        // dd($request->session()->previousUrl());
        // dd(url()->previous());
        // dd(URL::previous());


        if ($empresa) {
            // session(['redirect_url' => url()->previous()]);
            // Session::put('redirect_url', $request->fullUrl());
            // session(['redirect_url' => $request->url()]);
            // $mensaje = response()->json([
            //     'title' => 'CONFIGURAR PERFIL DE LA EMPRESA',
            //     'text' => 'Configurar los datos de la empresa, requeridos por el sistema, contáctese con su administrador.',
            //     'type' => 'warning'
            // ]);
            // return redirect()->back()->with('message', $mensaje);
            return redirect()->route('admin.administracion.empresa');
        }

        return $next($request);
    }
}
