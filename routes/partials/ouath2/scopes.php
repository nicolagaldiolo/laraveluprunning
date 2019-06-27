<?php

/*
 * PASSPORT SCOPES
 * Definiamo gli scopes di cui richiedere l'autorizzazione all'accesso
 * Se non definisco alcuno scope posso lavorare come se non esistessero, se li uso però quando mi loggo sull'applicazione devo
 *  esplicitare gli scope che può avere il token generato. In caso di PasswordGrantType posso richiedere tutti gli scope (*)
 */

// loggarsi sull'applicazione
use Illuminate\Http\Request;

Route::get('passport-scopes', function(){
    $query = http_build_query([
        'client_id' => config('api.client_id'),
        'redirect_uri' => config('api.client_callback'),
        'response_type' => 'code',
        'scope' => 'list-clips add-delete-clips'
    ]);

    //http://laraveluprunning.test/oauth/authorize?client_id=6&redirect_uri=http%3A%2F%2Flaraveloauthlogin.test%2Fauth%2Fcallback&response_type=code
    return redirect('http://laraveluprunning.test/oauth/authorize?' . $query);
})->name('log-with-api-with-passport-scopes');


/*
 * VERIFICARE AUTORIZZAZIONI DI UN TOKEN
 * Fare chiamata Ajax alla rotta o via postman passando il bearer token
 *
 * 1- Via method tokenCan()
 */
Route::get('check-passport-scopes', function (Request $request) {
    //return $request->user();

    //$status = (auth()->user()->tokenCan('add-delete-clips')) ?
    $status = ($request->user()->tokenCan('add-delete-clips')) ?
        'Yes, the user has the ability' :
        'No, the user hasn\'t the ability';

    return response()->json([
        'status' => $status
    ]);

})->middleware('auth:api');

/*
 * 2- Via middleware scopes
 * Devono essere soddisfatti tutti gli scopes passati
 */
Route::get('check-passport-all-scopes-via-middleware', function (Request $request) {

    return response()->json(['status' => 'Yes, the user has the ability']);

})->middleware([
    'auth:api',
    'scopes:list-clips,add-delete-clips'
]);

/*
 * Deve essere soddisfatto almeno uno scope
 */
Route::get('check-passport-scopes-via-middleware', function (Request $request) {

    return response()->json(['status' => 'Yes, the user has the ability']);

})->middleware([
    'auth:api',
    'scope:list-clips,add-delete-clips,admin-account'
]);