<?php

// SI PUò VALIDARE I DATI PRINCIPALMENTE IN 3 MODI:
// 1 Utilizzando il metodo validate nel controller reso disponibile dal trait ValidateRequests
// 2 Effettuando una validazione manuale, se non sono in controller o se per qualche motivo voglio forzare una validazione custom
// 3 Utilizzando una formrequest

// sia il metodo validate() sia il metodo withErrors creano un istanza di messageBags e sparano ogni errore in sessione

Route::get('actions/create', 'ActionsController@create');

// 1
Route::post('actions', 'ActionsController@store');

// 2
Route::post('actions_manual', function (\Illuminate\Http\Request $request){
    $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
        'title' => 'required|max:125',
        'body' => 'required'
    ]);

    if($validator->fails()){
        return redirect('actions/create')
            ->withErrors($validator)
            ->withInput();
    }

    /*
    $action = new \App\Action;
    $action->title = $request->input('title');
    $action->body = $request->input('body');
    $action->save();
    */

    \App\Action::create($request->only('title', 'body'));

    //dd("Ok, manual validate, do somethings");
});


// 3
Route::post('tasks/{task}/actions_form_request', function (\App\Http\Requests\CreateActionRequest $request){
    //Entro solo se supero la validazione e autorizzazione definita nella classe \App\Http\Requests\CreateActionRequest
    dd("Ok, validazione e autorizzazione sperata");
})->middleware('auth');