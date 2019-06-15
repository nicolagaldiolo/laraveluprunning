<?php

// POSSO ACCEDERE ALLA REQUEST IN VARI MODI:
// Request Object: \Illuminate\Http\Request $request->all()
// Facade Request: \Illuminate\Support\Facades\Request::all()
// helper: request()->all()
// global app: app(\Illuminate\Http\Request:class)->all() oppure app('request')->all()

Route::get('show_form', function(){
    return view('request.form');
});

Route::post('post-route', function(\Illuminate\Http\Request $request){

    // Metodi che lavorano sull'oggetto request
    var_dump($request->all()); // torna tutto ciò che viene passato dalla request (querystring|post data| file)
    var_dump($request->except(['_token', 'utm'])); // torna tutto trannne ciò che vogliamo escludere
    var_dump($request->only(['firstname', 'utm'])); // torna solo ciò che vogliamo includere (uso array solo per > 1)

    // Metodi che controllano l'esistenza di determinati elementi nella request
    var_dump($request->has('firstname')); // torna true anche se la chiave è null
    var_dump($request->exists('firstname')); // alias di has
    var_dump($request->filled('firstname')); // torna true solo se chiave è valorizzata

    // Metodi che estraggono un singolo elemento dalla request
    // la differenza tra input e get è che input gestiche anche gi arrayInput
    var_dump($request->input('lastname', '(not provided)'));
    var_dump($request->get('lastname', '(not provided)'));

    // Modi per estrarre dati dagli array input
    var_dump($request->input('employed.0.firstname'));
    var_dump($request->input('employed.*.lastname'));
    var_dump($request->input('employed.1'));
    var_dump($request->input('employed'));

    // Estrarre il json
    var_dump($request->json());
    var_dump($request->json('keyname'));

    // Estrarre il json (parameterBag)
    // quando recupero i dati in json mi viene fornito un oggetto parameterBag, una sorta di array associativo da cui posso chiamare una serie di metodi
    $bag = $request->json();
    var_dump($bag->get('name'));
    var_dump($bag->all());
    var_dump($bag->count());
    var_dump($bag->keys());

    // Metodi "che non lavorano sui dati" ma sulla richiesta, mi vengono forniti gratis da Laravel
    var_dump($request->method());
    var_dump($request->path()); // torna il path (nome definito nella rotta)
    var_dump($request->url()); // torna l'url completo senza query string
    var_dump($request->is('*-route*')); // usa uns regex per matchare la request con la stringa passata
    var_dump($request->ip());
    var_dump($request->header()); // torna l'header della richiesta
    var_dump($request->header('user-agent')); // torna lo user-agent della richiesta
    var_dump($request->server()); // torna un array contentente le varibili di solito incluse in $_SERVER
    var_dump($request->secure()); // torna un booleano se sono in https o meno
    var_dump($request->pjax()); // torna un booleano se la richiesta di questa questa è stata caricata via pjax
    var_dump($request->wantsJson()); // alias: $request->expectsJson() // torna un booleano se la richiesta accetta json come response
    var_dump($request->isJson()); // torna un booleano se la richiesta contiene json
    var_dump($request->accepts('application/json')); // torna un booleano se la richiesta accetta il content type passato come response.

});

// PERSISTENZA DEI DATI
// salvare momentaneamente in sessione i dati contenuti nella request
Route::get('show_form_persistence', function(\Illuminate\Http\Request $request){
    var_dump(old()); //torna tutti i dati precedentemente flashati
    var_dump(old('firstname')); //torna i dati precedentemente flashati (solo la chiave indicata)
    var_dump($request->cookie()); // recupera dalla richiesta i cookie o un eventuale chiave fornita
    //if($request->hasCookie('laravel_session')) // torna un booleano se la richiesta ha un cookie per la chiave fornita
    return view('request.form_persistence');
});

Route::post('post-route-persistence', function(\Illuminate\Http\Request $request){
    $request->flash(); //salva tutti gli user input della request in sessione, per renderli disponibili solo per la richiesta successiva
    //$request->flashOnly(['firstname', 'lastname']); //come flash() ma solo per le chiavi passate come parametro
    //$request->flashExcept(['firstname', 'lastname']); //come flash() ma solo che esclude le chiavi passate come parametro
    //$request->flush(); //rimuove tutti i dati precedentemente flashati
    return redirect()->back();
});


// ROUTE DATA - RECUPERARE DATI DAL ROUTE
Route::get('route_data/id/{id}/name/{name}', function($id, $name, \Illuminate\Http\Request $request){

    var_dump(request()->segments()); // mi torna tutti i segmenti di cui è compsoto l'url
    var_dump($request->segment(1)); // mi torna uno specifico segmento
    var_dump($id, $name); // i vari parametri del route vengono anche iniettati da Laravel all'interno della clousure o del controller

});

// FILES
Route::get('upload_file', function(){
    return view('request.file');
});

Route::post('upload_file', function(\Illuminate\Http\Request $request){

    // Metodi che lavorano sui file dell'oggetto request

    var_dump($request->file()); // torna un array con tutti i file presenti nella request che posso ciclare e trattare individualmente
    var_dump($request->file('picture')); // torna il file la cui chiave è indicata come parametro

    // controllo se ho il file picture e se è stato correttamente caricato
    // faccio entrambi i controlli perchè is valid viene lanciato direttamente sul file quindi
    // se lancio il metodo isValid su un NON file ottengo un errore
    if($request->hasFile('picture') && $request->file('picture')->isValid()){

        $path = $request->file('picture')->store('images'); //salvo il file e mi faccio tornare il path per salvarlo a db
        $customName = $request->file('picture')->storeAs('images', 'customName.png');
        //\Illuminate\Support\Facades\Auth::user()->picture = $path;
        //\Illuminate\Support\Facades\Auth::user()->save();
        var_dump($request->file('picture'));
    }

    // tutti i metodi disponibili dell'upladed file: //vendor/symfony/http-foundation/File/UploadedFile.php

});