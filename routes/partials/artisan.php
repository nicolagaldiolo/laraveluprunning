<?php

//https://laravel.com/docs/5.8/artisan#programmatically-executing-commands
//Eseguo in maniera programmatica comandi artisan custom

Route::get('test-artisan', function(){
    \Illuminate\Support\Facades\Artisan::call('password:reset', [
        'userId' => 1, '--sendEmail' => true
    ]);

    // Fa la stessa cosa di quello sopra ma lo appende alla coda, deve quindi essere attivata una coda.
    //\Illuminate\Support\Facades\Artisan::queue('password:reset', [
    //    'userId' => 1, '--sendEmail' => true
    //]);

    dd("Ho chiamato il comando artisan password:reset");
});