<?php

/*
 * PASSWORD GRANT
 * L'access token viene emesso subito con una singola richiesta contenente tutte le info,
 * username, password,client_id,client secret.
 * Il concetto principale di questo flusso, è che gli utenti inseriscono il nome utente e la
 * password nell'applicazione client e non nel server di autorizzazione. Una cosa evidente è che
 * le credenziali "appartengono" al server di autorizzazione, non al client,
 * Più facile da implementare ma ha alcune complicazioni.
 * Una volta inserite le credenziali viene fatta una chiamata server Side per ottenere token
 *
 * Per creare un client in laravel - php artisan passport:client --password
 */


Route::get('password-grant-auth', function(){

    $http = new GuzzleHttp\Client;
    $response = $http->post('http://laraveluprunning.test/oauth/token', [
        'form_params' => [
            'grant_type' => 'password',
            'client_id' => config('api.client_id'),
            'client_secret' => config('api.client_secret'),
            'username' => 'galdiolo.nicola@gmail.com',
            'password' => 'Nicola392@;'
        ]
    ]);

    return json_decode((string) $response->getBody(), true);



});

