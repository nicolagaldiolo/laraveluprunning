<?php

// i dati della sessione posso essere salvati su diversi formati, file, cookies, database, menmcached, redis, ecc
// laravel fornisce i driver per lavorare con ognuno di essi, vedi config->session.php per dettagli

/*
 * ACCEDERE ALLA SESSIONE
 * $request->session()->get()   // tramite il metodo session di ogni instanza dell'oggetto request
 * Session::get()               // attraverso la facade \Illuminate\Support\Facades\Session
 * $session->get()              // attraverso un istanza di Illuminate\Session\Store
 * session()->get()             // attraverso l'helper globale (SCELTA CONSIGLIATA)
 */

Route::get('session', function(\Illuminate\Http\Request $request){


    // AGGIUNGERE INFO IN SESSIONE
    $request->session()->put('user_id', 1);
    session(['test' => 10]);
    session()->put('test2',20);
    session()->put('friends',['Saul', 'Quang', 'Mecheld']);
    session()->push('friends', 'Javier');
    session()->put('keyEmpty', '');
    session()->put('keyNull', null);


    // ACCEDERE ALLA SESSIONE
    // il 2° parametro è il parametro di default, che può essere anche una clousure
    $sessione_get           = $request->session()->get('user_id', '');
    $sessione_get_global    = session('test', '');
    $sessione_get_array     = session('friends');
    $sessione_has_key       = session()->has('friends');
    $session_all            = session()->all();
    //session()->flush();


    // RIMUOVERE ELEMENTI DALLA SESSIONE
    //session()->forget('test');     // elimina la chiave passata
    //session()->pull('user_id');     // ritorna la chiave passata e la rimuove subito dopo
    //session()->flush();                  // svuota la sessione
    //session()->regenerate();             // rigenera l'id di sessione


    // MESSAGGI FLASH IN SESSIONE
    // si usa solitamente con i redirect dove i dati persistono in sessione solo per la richiesta successiva e al
    // ricaricare della pagina la chiave foo non esiste più. Comodo per scambiare messaggi tra le richieste
    $request->session()->flash('foo', 'bar');
    $request->session()->flash('baz', 'qux');
    $request->session()->flash('foo2', 'bar2');
    $request->session()->flash('foo3', 'bar3');
    $request->session()->reflash(); // rendo disponibili TUTTE le chiavi "flashate" in precedenza anche per la richiesta successiva
    $request->session()->keep(['foo', 'foo2']); // come reflash ma solo per chiavi passate al metodo

    return [
        'user_id'           => $sessione_get,
        'test'              => $sessione_get_global,
        'friends'           => $sessione_get_array,
        'friends_exists'    => $sessione_has_key,
        'all'               => $session_all
    ];

});
