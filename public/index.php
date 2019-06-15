<?php

/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @package  Laravel
 * @author   Taylor Otwell <taylor@laravel.com>
 */


/*
 * ENTRY POINT DI UN APPLICAZIONE LARAVEL
 * Ogni richiesta fatta all'interno di Laravel (sia via http sia command line) viene immediatamente convertita in oggetto
 * Illuminate -> Request che attraversa vari livelli e poi viene analizzato dall'applicazione stessa. Poi l'applicazione
 * genera un oggetto Illuminate -> Response che viene mandato indietro attraverso i livelli precedenti e viene ritornato all'utente finale
 *
 * Ogni applicazione ha un webserver (.htaccess, Nginx, ecc) che riceve la richiesta indipendentemente dall'url e lo dirotta verso la public/index.php
 *
 * QUI VENGONO FATTE 3 AZIONI PRINCIPALI:
 *
 */



define('LARAVEL_START', microtime(true));

/*
 *  1 CARICAMENTO DELLE CLASSI
 */
/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our application. We just need to utilize it! We'll simply require it
| into the script here so that we don't have to worry about manual
| loading any of our classes later on. It feels great to relax.
|
*/

require __DIR__.'/../vendor/autoload.php';

/*
 *  2 CREAZIONE DEL CONTAINER
 */
/*
|--------------------------------------------------------------------------
| Turn On The Lights
|--------------------------------------------------------------------------
|
| We need to illuminate PHP development, so let us turn on the lights.
| This bootstraps the framework and gets it ready for use, then it
| will load up this application so that we can run it and send
| the responses back to the browser and delight our users.
|
*/

$app = require_once __DIR__.'/../bootstrap/app.php';

/*
 *  3 CREAZIONE DI UN ISTANZA DEL KERNEL,
 *      CREAZIONE DELL'OGGETTO REQUEST E PASSATO AL KERNEL PER LA SUCCESSIVA GESTIONE,
 *      IL KERNEL TORNA UN OGGETTO RESPONSE CHE INDEX.PHP TORNA ALL'UTENTE FINALE E TERMINA LA RICHIESTA
 *
 */
/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request
| through the kernel, and send the associated response back to
| the client's browser allowing them to enjoy the creative
| and wonderful application we have prepared for them.
|
*/

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);
