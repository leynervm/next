<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Nwidart\Modules\Facades\Module;

class VerifyDataUser
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

        if ($user && Module::isEnabled('Marketplace')) {
            if (empty($user->document) || empty($user->password)) {
                $request->session()->put('route.intended', $request->route()->getName());
                // session(['url.intended' => url()->current()]);
                return redirect()->route('profile.complete');
            }
        }

        return $next($request);
    }
}
