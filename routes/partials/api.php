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
 * PAGINARE I RISULTATI
 * (Built-in in laravel)
 */
    Route::get('dogs_paginated', function (\Illuminate\Http\Request $request){
        // \Illuminate\Support\Facades\DB::table('dogs')->paginate(5); // posso fare la stessa cosa con il query builder
        return \App\Dog::paginate(5);
    });

/*
 * ORDINARE I RISULTATI
 * (Non gestita built-in da laravel)
 */


    // SEMPLICE
    // dogs_sorted?sort=name
    Route::get('dogs_sorted', function (\Illuminate\Http\Request $request){
        $sort_col = $request->input('sort', 'name');

        return \App\Dog::orderBy($sort_col)->paginate(5);
    });

    // DIREZIONE
    // dogs_sorted_direction?sort=-name
    Route::get('dogs_sorted_direction', function (\Illuminate\Http\Request $request){
        $sort_col = $request->input('sort', 'name');
        $sort_dir = Str::startsWith($sort_col, '-') ? 'desc' : 'asc';
        $sort_col = ltrim($sort_col, '-');
        return \App\Dog::orderBy($sort_col, $sort_dir)->paginate(5);
    });

    // DIREZIONE-MULTI PARAMETRI
    // dogs_sorted_direction_multi?sort=-name,id
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

/*
 * FILTRARE I RISULTATI (Non gestita built-in da laravel)
 */


    // SEMPLICE
    // dogs_filtered?filter=sex:male
    Route::get('dogs_filtered', function (\Illuminate\Http\Request $request){

        $results = \App\Dog::query();
        if($request->has('filter')){
            list($criteria,$value) = explode(':', $request->input('filter'));
            $results->where($criteria, $value);
        }

        return $results->paginate(5);
    });

    // MULTI PARAMETRI
    // dogs_filtered?filter=sex:male,color:brown
    Route::get('dogs_filtered_multi', function (\Illuminate\Http\Request $request){
        $results = \App\Dog::query();
        if($request->has('filter')){

            $filters = explode(',', $request->input('filter'));
            foreach ($filters as $filter){
                list($criteria,$value) = explode(':', $filter);
                $results->where($criteria, $value);
            }
        }

        return $results->paginate(5);
    });


/*
 * FILTRARE/CREARE LE NOSTRE API
 * Utilizziamo un oggetto Custom API per manipolare i dati che il modello ci torna, avremmo potuto tranquillamente usare le proprietà $visible $hidden del modello
 * ma questo layer ci da molta più versatibilità.
 */

    Route::get('api_transforms/{id}', function (\App\User $id){
        return new \App\CustomAPITransformer\UserTransformer($id);
    });

/*
* API NESTING & RELATIONSHIP
* ci sono veri modi per innestare la api:
*   1 - Embeddare le relazioni figlie direttamente nella risorsa principale (ce la da gratis laravel) es: user/5
*   2 - Embeddare solamente le chiavi esterne in un array e con un latra chiamata andare a prendere le relazioni figlie. es: user/5
*   3 - Permettere all'utente di interrogare le risorse innestare passando l'id del padre, (conseguenza punto 2), es: friends?user=5
*   4 - Creare delle sottorisorse, es: /user/5/friends
*   5 - Permettere all'utente di embeddare ciò che desidera, es: /user/5 (non embedda nulla) /user/5?embed=friends (embedda friends) /user/5?embed=friends,dogs (embedda friends e dogs)
*/

    Route::get('api_nested/{id}', function (\App\User $id, \Illuminate\Http\Request $request){
        $embed = ($request->has('embed')) ? explode(',', $request->input('embed')) : [];
        return new \App\CustomAPITransformer\UserTransformer($id, $embed);
    });


    Route::get('testApiDogs', function(){
        if(\App\Dog::count() <= 0){
            factory(\App\Dog::class)->create();
        }
        return \App\Dog::all();
    })->middleware('auth:api');