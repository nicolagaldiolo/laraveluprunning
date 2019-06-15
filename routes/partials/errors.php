<?php

// in laravel i messaggi all'interno dell'applicazione sono gestiti dalla classe \Illuminate\Support\MessageBag
// MessageBag si aspetta una struttura chiave valore, dove la chiave indetifica il "tipo di messaggio" mentre i valori sono un array di "messaggi"
// possiamo manualmente creare un istanza della classe e passarla alla view

// MessageBag lavora a stretto contatto con le validazioni quindi quando ci sono degli errori di validazione laravel crea un'istanza della classe MessageBag.
// laravel passa implicitamente ad ogni view un istanza vuota di MessageBag assegnandola alla variabile $errors mentre lo possiamo
// fare noi in maniera esplicità con il metodo ->withErrors() sovrascrivendo quella passata automaticamente da laravel vedi sotto

Route::get('errors', function(){

    $error_messages = [
        'errors' => [
            'Something went wrong with edit 1!'
        ],
        'messages' => [
            'Edit 2 was successful.'
        ]
    ];

    $messages = new \Illuminate\Support\MessageBag($error_messages);

    return view('errors.errors', compact('messages'));
});

Route::get('generate_errors', function(){
    $error_messages = [
        'errors' => [
            'Something went wrong with edit 1!'
        ],
        'messages' => [
            'Edit 2 was successful.'
        ],
        'pluto' => [
            'ax asxsasa xsa'
        ]
    ];

    return redirect('errors')->withErrors($error_messages);
    //return redirect('errors')->withErrors($error_messages, 'components'); possiamo passare un secondo parametro che identifica una chiave ulteriore
    //così ad esempio se all'interno della stessa vista abbiamo due componenti es: login,register possiamo identificare se si tratta di errori legati alla login o alla registrazione

});