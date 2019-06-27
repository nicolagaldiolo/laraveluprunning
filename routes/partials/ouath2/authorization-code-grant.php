<?php

/*
 * AUTHORIZAZTION CODE GRANT
 * Viene emesso un codice da autorizzazione e utilizzato per ottenere l'access token.
 * Questo codice viene risiede all'applicazione frontend dopo il login
 * L'access token invece viene generato server Side autenticando il client con una password e il codice ottenuto
 * MI LOGGO SUL SERVER DI AUTENTICAZIONE, RICEVO IL CODICE DI AUTORIZZAZIONE
 * CHE MI SERVE PER FARE UNA SECONDA RICHIESTA PER RICHIEDERE IL TOKEN
 *
 * Per creare un client in laravel - php artisan passport:client
 *
 * PS: SOLITAMENTE QUESTE ROTTE RISIEDONO SU UN APP ESTERNA (APP CONSUMER) CHE SI AUTENTICA SUL SERVER OAUTH E RICEVE UN TOKEN, IN QUESTO CASO è TUTTO NELLA STESSA APP PER SEMPLICITà.
 */

// loggarsi sull'applicazione
Route::get('authorization-code-grant', function(){

    $query = http_build_query([
        'client_id' => config('api.client_id'),
        'redirect_uri' => config('api.client_callback'),
        'response_type' => 'code'
    ]);

    //http://laraveluprunning.test/oauth/authorize?client_id=6&redirect_uri=http%3A%2F%2Flaraveloauthlogin.test%2Fauth%2Fcallback&response_type=code
    return redirect('http://laraveluprunning.test/oauth/authorize?' . $query);
})->name('log-with-api');


// ricevo il codice di autorizzazione e faccio una nuova richiesta serverside per il token
Route::get('auth/callback', function(\Illuminate\Http\Request $request){

    if($request->has('error')){
        //handle error condition
    }

    $http = new GuzzleHttp\Client;

    $response = $http->post('http://laraveluprunning.test/oauth/token', [
        'form_params' => [
            'grant_type' => 'authorization_code',
            'client_id' => config('api.client_id'),
            'client_secret' => config('api.client_secret'),
            'redirect_uri' => config('api.client_callback'),
            'code' => $request->code
        ]
    ]);


    return json_decode((string) $response->getBody(), true);
});

// per refreshare il token, ovviamente non funziona perchè il token è un fake,
// in quanto manca l'associazione del token ad un utente

Route::get('/refresh-token', function(){

    $http = new GuzzleHttp\Client;

    $response = $http->post('http://laraveluprunning.test/oauth/token', [
        'form_params' => [
            'grant_type' => 'refresh_token',
            'client_id' => config('api.client_id'),
            'client_secret' => config('api.client_secret'),
            'redirect_uri' => config('api.client_callback'),
            'refresh_token' => 'xxxxxx'
        ]
    ]);


    return json_decode((string) $response->getBody(), true);
});