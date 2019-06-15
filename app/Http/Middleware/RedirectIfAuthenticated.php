<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

/*
 * MIDDLEWARE GUEST, CONTROLLO SE L'UTENTE è GIà LOGGATO LO RIMANDO ALLA "HOMEPAGE" DELL'UTENTE
 * EVITO QUINDI DI PRESENTARE AD ESEMPIO LA PAGINA DI LOGIN SE SONO GIà LOGGATO
 *
 * PARAMETRIZZATO REDIRECTTO RISPETTO ALL'ORIGINALE, IN QUANTO AVENDO + AUTENTICAZIONI (USERS,TRAINEES) NON POSSO REDIRIGERE
 * L'UTENTE AD UNA PAGINA DEFINITA (=/HOME) PERCHè OGNI TIPOLOGIA DI UTENTE HA UNA PAGINA "HOME" DEDICATA
 */

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    /*public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            return redirect('/home');
        }

        return $next($request);
    }
    */

    public function handle($request, Closure $next, $guard = null, $redirectTo = '/home')
    {
        if (Auth::guard($guard)->check()) {
            return redirect($redirectTo);
        }

        return $next($request);
    }

}
