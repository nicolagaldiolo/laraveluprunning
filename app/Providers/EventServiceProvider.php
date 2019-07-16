<?php

namespace App\Providers;

use App\Events\UserSubscribed;
use App\Listeners\EmailOwnerAboutSubscription;
use App\Listeners\UserEventSubscriber;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */

    /*
     * ASSOCIAZIONE EVENTO-LISTENER - METODO 1
     * Associo uno o più listener per ogni evento, ogni listener è una classe a sè stante
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
            // Posso associare altri listener a questo evento
        ],
        UserSubscribed::class => [
            EmailOwnerAboutSubscription::class,
            // Posso associare altri listener a questo evento
        ],
    ];


    /*
     * ASSOCIAZIONE EVENTO-LISTENER - METODO 2
     * Il listener è una classe che riceve l'evento scatenato e all'interno di esso ha vari metodi ognuno dei quali funge da listener,
     * è un modo per organizzare meglio i vari listener ad esempio per funzionalità
     */
    protected $subscribe = [
        UserEventSubscriber::class
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
