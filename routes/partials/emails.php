<?php

/*
 * PER CREARE UNA CLASSE MAIL
 * php artisan make:mail Assignment
 *
 * SET GLOBAL EMAIL "TO" RECIPIENT
 * Per ogni email si può impostare un destinatario persoanlizzato ma se vogliamo sovrascrivere questa cosa e impostare
 * un destinatario "GLOBALE" lo possiamo fare in config/email settando la chiave to:
 * //'to' => [
   //    'address' => 'email@email.com',
   //    'name' => 'Name Surname',
   //],
 */

Route::get('emails', function(){

    $trainee = \App\Trainee::first();

    \Illuminate\Support\Facades\Mail::to('me@app.com')
        ->cc(\App\User::take(3)->get()) // nei campi cc, bcc posso anche passare una collections anzichè un indirizzo email secco
        ->send(new \App\Mail\Assignment($trainee));

});

/*
 * EMAIL QUEUED
 * Le code in laravel lavorano con i jobs ma le email possono essere messe in coda anche senza creare dei jobs,
 * infatti hanno i metodi queue() e later() adatti allo scopo
 * ->queue() - mette le email in coda (con una queue, quindi un tread asincrono)
 * ->later() - come queue solo che puoi aggiungere anche un ritardo
 */

Route::get('emails_queued', function(){

    $trainee = \App\Trainee::first();

    \Illuminate\Support\Facades\Mail::to('me@app.com')
        //->later(\Carbon\Carbon::now()->addMinutes(10), new \App\Mail\Assignment($trainee))
        ->queue(new \App\Mail\Assignment($trainee));


    /*
     * EMAIL QUEUED SU CODA E CONNESSIONE SPECIFICA
     * se utilizzo delle code particolari e connessioni di code particolari posso specificare su quali lavorare
     */
    $message = (new \App\Mail\Assignment($trainee))
        ->onConnection('sqs')
        ->onQueue('emails');
    //\Illuminate\Support\Facades\Mail::to('me@app.com')->later(\Carbon\Carbon::now()->addMinutes(10), $message);
    \Illuminate\Support\Facades\Mail::to('me@app.com')->queue($message);

});