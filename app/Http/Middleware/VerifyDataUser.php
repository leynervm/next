<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
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
            if (empty($user->document)) {
                $request->session()->put('route.intended', $request->route()->getName());
                // session(['url.intended' => url()->current()]);
                return redirect()->route('profile.complete');
            }
        }

        return $next($request);
    }
}
