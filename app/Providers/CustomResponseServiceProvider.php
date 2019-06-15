<?php


namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;

/*
 * CREO UN SERVICE PROVIDERS PER REGISTRARE DEI METODI CUSTOM PER TORANRE UNA RESPONSE PERSONALIZZATA
 * IN QUESTO CASO ABBIAMO SOLO REPLICATO IL METODO response->json() A TITOLO DIMOSTRATIVO
 * NB: non ero obbligato a fare un service providers personalizzato
 */

class CustomResponseServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        Response::macro('myJson', function($content){
            //return response(json_encode($content))
            //    ->headers(['Content-Type' => 'application/json']);
            return Response::make(json_encode($content), 200, ['Content-Type' => 'application/json']);
        });
    }
}