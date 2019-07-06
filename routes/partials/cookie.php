<?php

use \Illuminate\Support\Facades\Cookie;
use \Illuminate\Support\Facades\Response;
/*
 * COOKIE
 *
 * I COOKIES VENGONO LETTI DALL'OGGETTO REQUEST E SETTATI DALL'OGGETTO RESPONSE,
 * ABBIAMO UNA FACADE CHE FA DA PROXIES PER GLI OGGETTI REQUEST E RESPONSE
 *
 * ACCEDERE AI COOKIE
 * Si può accedere ai cookie in 3 modi
 * 1- Cookie Facade - la più completa di opzioni (scelta consigliata)
 * 2- Cookie Global Helper - ha alcune limitazioni (mancano i metodi has() e get(), quindi utile solo per settare cookie)
 * 3- Request|Response Object
 *
 */

Route::get('cookie', function(\Illuminate\Http\Request $request, \Illuminate\Cookie\CookieJar $cookieJar){

    // ACCEDERE AI COOKIE
        // Tramite facade o instanza oggetto request
        $userDismissedCookie = $request->cookie('dismissed-popup', false);
        $saw_dashboard = Cookie::get('saw-dashboard', false);

    // VERIFICARE PRESENZA COOKIE
        // Tramite facade o instanza oggetto request
        $checkCookie = $request->hasCookie('dismissed-popup');
        $checkCookie = Cookie::has('dismissed-popup');

    // SETTARE UN COOKIE
        // Parametri: $name,$value,$minutes,$path,$domain,$secure,$httpOnly (tutti i parametri di "default sono settati nel config/session.php)
        // I cookie posso essere settati, tramite, helper, tramite facade, tramite istanza dell'oggetto CookieJar e in ogni caso
        // devono essere aggiunti manualmente all'oggetto response
        $sawDashboard = cookie('saw-dashboard', true, 15);
        $sawDashboard = $cookieJar->make('saw-dashboard', true, 15);
        $sawDashboard = Cookie::make('saw-dashboard', true, 15); // $name,$value,$minutes,$path,$domain,$secure,$httpOnly

        // NB: UTILIZZANDO LA FACADE COOKIE HO A DISPOSIZIONE UN METODO QUEUE CHE TRAMITE IL MIDDLEWARE ADDQUEUEDCOOKIESTORESPONSE
        // AGGIUNGE AUTOMATICAMENTE IL COOKIE ALLA RESPONSE, ALTRIMENTI LO DOVREI FARE MANUALMENTE
        Cookie::queue('dismissed-popup', true, 15);

    return response()->json([
        'userDismissedCookie' => $userDismissedCookie,
        'saw_dashboard' => $saw_dashboard,
        'checkCookie' => $checkCookie
    ])->cookie($sawDashboard);

});

Route::get('cookie_not_crypted', function(\Illuminate\Http\Request $request){
    return $request->cookie('cookie_test_no_cripted');
});