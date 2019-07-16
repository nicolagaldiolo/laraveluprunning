<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * Indicates whether the XSRF-TOKEN cookie should be set on the response.
     *
     * @var bool
     */
    protected $addHttpCookie = true;

    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */

    // Aggiungo a queste array tutte protette dal mifddleware csrfToken ma che per qualche motivo non voglio che venga fatto il controllo
    protected $except = [
        //'http://laraveluprunning.test/*'
        //'/broadcasting/auth'
    ];
}
