<?php

/*
 * RESPONSE
 *
 */

$xmlData = file_get_contents(public_path() . '/storage/file.xml');
$fileData = public_path() . '/storage/images/customName.png';

/*
 * RESPONSE AUTOMATICA GESTITA DA LARAVEL
 * Tutte le rotte e i controllers restituiscono una risposta al browser che in automatico laravel converte in una
 * risposta http il cui contenuto può essere una stringa o un json in caso che venga tornata un array o un oggetto eloquent.
 */

Route::get('simple_response', function(){
    return 'Hello World';
});

Route::get('simple_response_json', function(){
    //return [1,2,3];
    return \App\Conference::all();
});


/*
 * RESPONSE CUSTOM GESTITA MANUALMENTE
 * Laravel ci permette di personalizzare la response modificato header/content manualmente mettendoci a disposizione:
 *  - Oggetto Response : instanza di Illuminate\Http\Response
 *  - Helper response()
 *  - Helper view()
 *  - redirect helpers() | vedere file redirect.php
 */

Route::get('response', function(){
    return response('Hello World');
});

// HELPER o ISTANZA DI Illuminate\Http\Response | Ogni richiesta può essere fatta con l'helper response() o con un instanza di Illuminate\Http\Response
Route::get('response_error', function(){

    // con helper
    return response('Error', 400)
        ->header('X-Header-Name', 'header-value')
        ->cookie('cookie-name', 'cookie-value')
        ->cookie('signup_dismissed', true);

    // con istanza
    //$response = new Illuminate\Http\Response('Error', '400');
    //return $response->header('X-Header-Name', 'header-value')
    //    ->cookie('cookie-name', 'cookie-value')
    //    ->cookie('signup_dismissed', true);
});

// mi faccio tornare un xml
Route::get('response_xml', function() use($xmlData){
    return response($xmlData)
        ->header('Content-Type', 'text/xml');
});


/*
 * SPECIALIZED RESPONSE TYPES
 *
 * l'helper response() mette a disposizione dei metodi view() | download() | files() | json(), ossia delle "macro"
 * che hanno un template standardizzato, adattato per quel tipo di richiesta ma è cmq possibile modificare header e content
 *
 */

// VIEW | potrei usare direttamente l'helper view ma se voglio personalizzare l'header o il content della richiesta devo usare questo helper dove mi faccio tornare un xml alla view
Route::get('view_response_xml', function() use($xmlData){
    return response()->view('response.xml-structure', compact('xmlData'))
        ->header('Content-Type', 'text/xml');
});

// DOWNLOAD | faccio tornare direttamente il file, quindi parte il download
Route::get('download_file', function() use($fileData){
    return response()->download($fileData, 'newfilename.zip', ['header' => 'value']);
});

// FILE | mostro il contenuto del file nel browser
Route::get('view_file_in_browser', function() use($fileData){
    return response()->file($fileData, ['header' => 'value']);
});

// JSON | torno un json (viene fatto un json_encode() del contenuto e viene settato application/json come Content-Type) viene tornato un json in tutti e 3 i casi
Route::get('response_json', function(){
    //return response()->json(['Tom', 'Jerry']);
    //return \App\Conference::all();
    return response()->json(\App\Conference::all());
});

// JSONP
Route::get('response_jsonp', function(){
    return response()->jsonp('callback', \App\Conference::all());
    //return response()->json(\App\Conference::all())->setCallback($request->input('callback'));
});

// REDIRECT() | posso usare l'helper response ma solitamente si usa direttamente l'helper redirect() | vedere file redirect.php
Route::get('response-redirect', function(){
    return response()->redirectTo('/');
});

// CUSTOM RESPONSE TYPE | Ho creato un metodo CUSTOM per avere una response personalizzata (registrato in CustomResponseServiceProvider)
Route::get('custom_response_type', function (){
    return response()->myJson(['name', 'Sangeetha']);
});


/*
 * HELPER VIEW()
 * Posso usare l'helper view se devo renderizzare una view lasciando gestire la response a laravel
 *
 */

Route::get('helper_view', function(){
    $var = "Contenuto da renderizzare";
    return view('response.view_response', compact('var'));
});
