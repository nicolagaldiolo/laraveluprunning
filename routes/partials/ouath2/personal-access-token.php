<?php

/*
 * PERSONAL ACCESS TOKEN
 * Il personal access token è utile in fase di sviluppo per generare token al volo, quindi non richiede che sia messo in piedi un flusso Oauth,
 * infatti non è un vero e proprio tipo di concessione/autorizzazione. è utile quando non abbiamo un sistema oauth ma vogliamo
 * comunque laravorare/testare con i token. Necessitano solamente di un client
 *
 * Per creare un client in laravel - php artisan passport:client --personal
 */


Route::get('personal-access-token', function(){
    $user = \App\User::whereEmail('galdiolo.nicola@gmail.com')->firstOrFail();

    // Crea un token senza scope
    $token = $user->createToken('Personal Access Token')->accessToken;

    // Crea un token con uno o più scope
    //$token = $user->createToken('Personal Access Token with scope', ['place-orders', 'xxxx'])->accessToken;

    dd($token);
});

