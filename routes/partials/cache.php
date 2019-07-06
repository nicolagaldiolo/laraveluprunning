<?php

/*
 * CACHE
 * la cache lavora molto similmente alla sessione, la differenza principale è che la sessione lavora a livello di utente/sessione attiva
 * mentre la cache lavora a livello di applicazione indipendentemente dalle istanze
 * Solitamente si cacha chiamate a api, lente query, o comunque dati che possono anche non essere aggiornati al momento
 *
 * i dati della cache posso essere salvati su diversi formati, file, database, menmcached, redis, ecc
 * laravel fornisce i driver per lavorare con ognuno di essi, vedi config->cache.php per dettagli
 */

/*
 * ACCEDERE ALLA CACHE
 * Cache::get()               // attraverso la facade \Illuminate\Support\Facades\Session
 * $cache->get()              // attraverso un istanza di Illuminate\Contract\Cache\Repository
 * cache()->get()             // attraverso l'helper globale (SCELTA CONSIGLIATA)
 */

Route::get('cache', function(){


    // AGGIUNGERE INFO IN CACHE, DEFINISCO ANCHE UN TTL DELLA CACHE (FACOLTATIVO)
    // NB: VEDI VETODO cache()->remember(), molto interessante
    cache()->put('test', 5, 10); // ttl in minuti
    cache()->put('test', 5, 10); // ttl in minuti
    $value_added = cache()->add('test3', 50); // simile a put solo che aggiunge solo se chiave non già settata, torna true/false di conseguenza
    $value_readded = cache()->add('test3', 50); // simile a put solo che aggiunge solo se chiave non già settata, torna true/false di conseguenza
    cache(['test4' => 10], 10);
    cache()->forever('test5', 10); // aggiungo alla cache con ttl illimitato

    // INCREMENTARE/DECREMENTARE ELEMENTI DELLA CACHE
    cache()->increment('online_users'); //incrementa il valore di 1 (parte da zero se non trova valore)
    cache()->increment('online_users', 10); //incrementa il valore di 10 (parte da zero se non trova valore)
    //cache()->decrement('online_users');

    // ACCEDERE ALLA CACHE
    // NB: con i metodi remember e rememberForever salvo in cash i dati per un periodo di tempo e li torno,
    // se alla prossima richiesta li ho ancora li torno altrimenti la clousure sa come andare a ri-recuperarli
    $test_cache = cache('test', 'default_value');
    $test2_cache = cache('test2', 'default_value');
    $cache_has_key = cache()->has('test2');
    $users = cache()->remember('users', 10, function (){ // imposto un ttl
        return \App\User::take(3)->get();
    });
    $users_forever = cache()->rememberForever('users', function (){ // ttl illimitato
        return \App\User::take(3)->get();
    });
    $online_users = cache('online_users');


    // RIMUOVERE ELEMENTI DALLA CACHE
    //cache()->forget('test');     // elimina la chiave passata
    //cache()->flush();                  // svuota la cache

    return [
        'test_cache' => $test_cache,
        'test2_cache' => $test2_cache,
        'value_added' => $value_added,
        'value_readded' => $value_readded,
        'cache_has_key' => $cache_has_key,
        'users_cached' => $users,
        'users_cached_forever' => $users_forever,
        'online_users' => $online_users,
    ];

});
