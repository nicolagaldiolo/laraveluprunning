<?php

/*
 * CONTROLLARE LA ABILITA' A LIVELLO DI MIDDLEWARE,
 * lo potrei fare anche nel metodo del controller
 */

// controllo a livello di rotta se l'utente ha questa abilità
Route::get('people/create', function(){
    // Create Person
})->middleware('can:create-person');


// viene passata l'istanza person al gate (viene fatto il model binding)
Route::get('people/{person}/edit', function(){
    // Edit Person
})->middleware('can:edit-person,person');


/*
 * CONTROLLARE LA ABILITA' A LIVELLO DI CONTROLLER,
 */
Route::get('gatePolicies', 'GatePoliciesController@index');
Route::get('gatePoliciesControllerAuthorizeResource', 'GatePoliciesControllerAuthorizeResource@index');

/*
 * CONTROLLARE LA ABILITA' A LIVELLO DI VIEW,
 */
Route::get('gatePoliciesView', function (){
    $contact = \App\Contact::first();
    return view('gatePolicies.index', compact('contact'));
});


/*
 * CONTROLLARE LA ABILITA' DI UNA POLICY,
 */
Route::get('gatePoliciesControllerCheckPolicyAbility', 'GatePoliciesControllerCheckPolicyAbility@index');



/*
 * TEST GATE
 */
Route::post('users', 'UserController@store')->name('users.store');

/*
 * TEST MOCK Facades
 */
Route::get('users', 'UserController@index')->name('users.index');