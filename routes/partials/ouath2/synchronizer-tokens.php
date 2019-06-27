<?php

/*
 * TOKENS FROM LARAVEL SESSION AUTHENTICATION (SYNCHRONIZER TOKENS) | CUSTOM
 *
 * Questo metodo per ottenere un token valido per consumare le api è molto utile nel caso in cui siamo loggati
 * all'interno dell'app in maniera tradizionale e abbiamo bisogno di un token per interrogare le nostre api via javascript.
 *
 * Passport ci mette a disposizione un middleware CreateFreshApiToken https://laravel.com/docs/5.8/passport#consuming-your-api-with-javascript
 * che allegherà un cookie laravel_token alle risposte in uscita.
 *
 * Questo cookie è un JSON Web Token (JWT) che contiene info crittografate in merito al CSRF token.
 * Ora basta inviare il X-CSRF-TOKEN nell'header della richiesta e passport confronterà il CSRF token dell'header con il cookie in sessione per autenticare la richiesta
 * PS: se utilizzo jquery devo indicare X-CSRF-TOKEN nell'header, ma se uso il setup laravel di VUE mi viene impostato a monte quindi non occorre specificarlo
 *
 */

Route::get('synchronizer-tokens', function(){
    return view('api.index');
});

