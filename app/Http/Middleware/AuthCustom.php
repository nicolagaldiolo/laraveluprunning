<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

/*
 * Creo un middleware custom per utilizzare una guard personalizzata.
 * dal momento che ho vari tipi di autenticazione (User,Trainee) devo usare delle guardie personalizzata.
 */

class AuthCustom
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null, $redirectTo = '/login')
    {
        if(!Auth::guard($guard)->check()){ //Se NON viene specificata una guarda specializzata vado avanti
            return redirect($redirectTo);
        }
        Auth::shouldUse($guard); // Altrimenti informo laravel di usare la guardia passata
        return $next($request);
    }
}
