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
        $empresa = Empresa::all()->count();

        // dd($request->session()->previousUrl());
        // dd(url()->previous());
        // dd(URL::previous());
        session(['redirect_url' => url()->previous()]);
        if (!$empresa) {
            // Session::put('redirect_url', $request->fullUrl());
            // session(['redirect_url' => $request->url()]);
            return redirect()->route('admin.administracion.empresa.create')->with('message', 'Configurar datos de la empresa por favor !');
        }

        return $next($request);
    }
}
