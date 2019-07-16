<?php

/*
 * EVENTS
 * Gli eventi ragionano come un broadcast, vengono lanciati dall'applicazione incuranti di chi li ascolta
 *
 *
 */

Route::get('events', function(){

    $user = \App\User::first();
    $plan = 1;

    // METODO 1 - FACADE (SEMBRA NON FUNZIONARE)
    //\Illuminate\Support\Facades\Event::fire(new \App\Events\UserSubscribed($user, $plan));

    // METODO 2 - DISPATCHER (SEMBRA NON FUNZIONARE)
    //$dispatcher = app(Illuminate\Contracts\Events\Dispatcher);
    //$dispatcher->fire(new \App\Events\UserSubscribed($user, $plan));

    // METODO 3 - GLOBAL HELPER | CONSIGLIATA
    event(new \App\Events\UserSubscribed($user, $plan));


    event(new \App\Events\UserSubscribed($user, $plan));
    event(new \App\Events\UserCancelled($user));

    return "Sparato eventi";
});

Route::get('events_broadcasted', function(){


    $user = Auth::user();

    //Verranno avvisati tutti gli utenti che sono iscritti a questo evento, COMPRESO io stesso
    $plan = 'Evento da global event()';
    //event(new \App\Events\UserSuscribedBroadcast($user, $plan));

    //Verranno avvisati tutti gli utenti che sono iscritti a questo evento, ESCLUSO io stesso
    $plan = 'Evento da global broadcast()->toOthers()';
    broadcast(new \App\Events\UserSuscribedBroadcast($user, $plan))->toOthers();

})->middleware('auth');