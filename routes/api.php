<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// se chiamo questa rotta passando un token valido ottengo le info che voglio, altrimenti ottengo il form di login
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

require __DIR__ . '/partials/api.php';

/*
 * AUTENTICAZIONE OAUTH2 STANDARD: 4 versioni differenti di autenticazione: https://itnext.io/an-oauth-2-0-introduction-for-beginners-6e386b19f7a9
 * 1 - Authorization Code Grant (Client + server)
 * 2 - Implicit Grant (solo client)
 * 3 - Client Credential Grant (solo server)
 * 4 - Password Grant (Client + server)
 *
 * PASSPORT API
 * AUTENTICAZIONE OAUTH2 LARAVEL PASSPORT: 4 versioni differenti di autenticazione (2 standard|2 custom)
 */

// 1 - Authorization Code Grant (Client + server) | STANDARD
require __DIR__ . '/partials/ouath2/authorization-code-grant.php';

// 2 Personal Access Token | CUSTOM
require __DIR__ . '/partials/ouath2/personal-access-token.php';

// 3 Tokens from laravel session authentication (synchronizer tokens) | CUSTOM
// per questo tipo di autenticazione vedere rotte web

// 4 - Password Grant (Client + server) | STANDARD
require __DIR__ . '/partials/ouath2/password-grant-auth.php';

require __DIR__ . '/partials/ouath2/scopes.php';


/*
 * AUTENTICAZIONE LARAVEL BUILT-IN
 */

// ottenere il token: http://laraveluprunning.test/get_my_api_token (rotta web)

// METODO 1
// chiamare la rotta passando il parametro api_token via get
// es: http://laraveluprunning.test/api/user_api_auth_simple?api_token=mH2ulfEAVxy1GvhznCoNEnXq76GzrUcETBaYBP5iURQ7c959EXOu0ghTOKMt
// se il token non è più valido rigenerarlo
Route::get('user_api_auth_simple', function(Request $request){
    return $request->user();
})->middleware('auth:api_token');

// METODO 2
// Effettuare una chiamata con postman passando il Bearer Token chiamando prima la rotta per ottenere il token


