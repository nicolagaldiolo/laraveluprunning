<?php

namespace App\Providers;

use App\MyClasses\LoremIpsum;

use App\MyClasses\LoremIpsumInterface;
use App\MyClasses\TestLoremIpsum;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class MyClassesServiceProvider extends ServiceProvider implements DeferrableProvider
{

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // stiamo istruendo il container su come risovere questa dipendenza,
        // In questo caso non è necessario questa dichiarazione se non per il fatto che possiamo fare riferimento alla
        // classe LoremIpsum con l'alias altrimenti occorre fare riferimento al FQCN (Fully Qualified Classname) LoremIpsum::Class
        // solitamente si registra la classe se loremIpsum necessita di qualche parametro nel costruttore così informiamo il
        // container su come risolvere la dipendenza altrimenti in mancanza di parametri sa come risolvere la dipendenza automaticamente.

        // singletone significa che l'istanza viene cachata e non viene tornata ogni volta una nuova instanza della classe, per risparmiare memoria
        $this->app->singleton('loremIpsum', function($app){
            return new LoremIpsum();
        });

        $this->app->bind(LoremIpsumInterface::class, function (){
            return new LoremIpsum();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
           //LoremIpsum::class
            'loremIpsum',
            LoremIpsumInterface::class
        ];
    }
}
