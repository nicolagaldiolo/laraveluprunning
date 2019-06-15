<?php

// REDIRECTS
// Ci sono comuni modi per fare un redirect, utilizzando l'helper oppure facade

// 5 modi per fare la stessa cosa
Route::get('redirect-to', function(){
    return Redirect::to('login'); // Utilizzando lo facade
    //return redirect('login');
    //return redirect()->to('login');
    //return redirect()->route('conferences.show', ['conference' => 1]);
    //return redirect()->action('ContactController@show', ['contact' => 1]);
});


Route::get('redirect-back', function(){ // Utilizzando l'helper globale ridirigo l'utente alla pagina precedente
    return back(); // shortcut di redirect()->back();
});

Route::get('redirect-to-home', function(){ // Altri metodi per il redirect qui: /vendor/laravel/framework/src/Illuminate/Routing/Redirector.php
    return redirect()->home();
});

// redirect with
Route::get('redirect-with-key-value', function(){
    return redirect('login')->with('error', true);
});

Route::get('redirect-with-array', function(){
    return redirect('login')->with(['error' => true, 'message' => 'Whoops!']);
});

// withInput() nel caso voglio ridirigire l'utente ad un form senza che vengano persi i valori precedentemente inseriti (il form però deve utilizzare l'helper old() nel value di ogni campo)
// withErrors() nel caso in cui voglio tornare alla vista una variabile $errors
