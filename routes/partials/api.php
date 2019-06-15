<?php

/*
 * JSON API
 * Per vedere la specifica delle api JSON: https://jsonapi.org/
 * Laravel non le rispetta al 100, sono da prendere come ispirazione ma attenersi alla specifica richiede un sacco di lavoro
 * La JSON API da le "regole" per filtrare, ordinare, paginare i dati + autenticazione, embedding, link, metadata, ecc.
 *
 * In genere la struttura di un JSON dovrà essere formata da almeno uno di questi top-level raggruppamenti:
 * - data [{}]
 * - errors [{}]
 * - meta {}
 *
 * la specifica dice che data e errors non devono coesistere
 */

Route::resource('dogs', 'Api\DogController')->except(['create', 'edit']); // create e edit | se ne occupa l'applicazione di frontend.

/*
 * AGGIUNGERE HEADER CUSTOM ALLA RISPOSTA
 * Ha senso farlo per la singola richiesta se vogliamo che ci sia solo per la singola richiesta altrimenti meglio fare un middleware
 * NB: Negli header, i prefissi X- indicano che quel determinato header non è nella specifica HTTP
 */
Route::get('headers_response', function (){
    return response()
        ->json(['data' => 'Aggiungere header custom alla response'])
        ->header('X-APIVersion', 2);
});

/*
 * LEGGERE GLI HEADER DELLA RICHIESTA
 */
Route::get('headers_request', function (\Illuminate\Http\Request $request){
    $all_headers = $request->header();
    $single_header = $request->header('Accept');

    return $all_headers;
});

/*
 * PAGINARE I RISULTATI (Built-in in laravel)
 */
Route::get('dogs_paginated', function (\Illuminate\Http\Request $request){
    // \Illuminate\Support\Facades\DB::table('dogs')->paginate(5); // posso fare la stessa cosa con il query builder
    return \App\Dog::paginate(5);
});

/*
 * ORDINARE I RISULTATI (Non gestita built-in da laravel)
 */


// SEMPLICE /dogs_sorted?sort=name
Route::get('dogs_sorted', function (\Illuminate\Http\Request $request){
    $sort_col = $request->input('sort', 'name');

    return \App\Dog::orderBy($sort_col)->paginate(5);
});

// DIREZIONE /dogs_sorted_direction?sort=-name
Route::get('dogs_sorted_direction', function (\Illuminate\Http\Request $request){
    $sort_col = $request->input('sort', 'name');
    $sort_dir = Str::startsWith($sort_col, '-') ? 'desc' : 'asc';
    $sort_col = ltrim($sort_col, '-');
    return \App\Dog::orderBy($sort_col, $sort_dir)->paginate(5);
});

// DIREZIONE-MULTI PARAMETRI /dogs_sorted_direction_multi?sort=-name,id
Route::get('dogs_sorted_direction_multi', function (\Illuminate\Http\Request $request){
    $sort_col = $request->input('sort', 'name');

    $query = \App\Dog::query();

    $params = explode(',', $sort_col);
    foreach ($params as $param){
        $sort_dir = Str::startsWith($param, '-') ? 'desc' : 'asc';
        $sort_col = ltrim($param, '-');

        $query->orderBy($sort_col, $sort_dir);
    }

    return $query->paginate(5);
});