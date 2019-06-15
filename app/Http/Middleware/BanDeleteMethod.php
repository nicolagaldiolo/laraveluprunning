<?php

namespace App\Http\Middleware;

use Closure;

class BanDeleteMethod
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if($request->method() === 'DELETE'){
            return response('Method Not Allowed', 405);
        }

        $response = $next($request);
        $response->cookie('visited-our-site', true);

        return $response;
    }
}
