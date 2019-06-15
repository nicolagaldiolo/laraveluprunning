<?php

namespace App\Http\Middleware;

use Closure;

class MiddlewareWithParams
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next, $setCookie = false, $fakeParam = '')
    {
        $response = $next($request);

        if ($setCookie) {
            logger("Setto il cookie");
            $response->cookie('visited-our-site', true);
        }

        if (!empty($fakeParam)) {
            logger($fakeParam);
        }

        return $response;
    }
}