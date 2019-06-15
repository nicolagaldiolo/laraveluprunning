<?php

namespace App\Providers;

use App\Contact;
use App\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

/*
 * GATE E POLICIES
 * I GATE SONO IL MODO IN LARAVEL CON CUI PUOI GESTIRE LE AUTORIZZAZIONI/ABILITA DI UN UTENTE
 * IL GATE HA UN CHIAVE CHE IDENTIFICA L'ABILITA E UNA FUNZIONE CHE CONTIENE LA LOGICA CON CUI DEVE ESSERE ASSEGNATA O MENO UNA ABILITà
 * LE POLICY SONO DELLE CLASSI CHE RAGGRUPPANO LA LOGICA DI AUTORIZZAZIONE DI UN DETERMINATO MODELLO,
 * Quando una policy viene registrata vengono letti tutti i metodi della policy e per ogni metodo vengono definiti dei
 * gate in automatico associati a quel modello. Le policy fondamentalmente vengono utilizzare per raggruppare le autorizzazioni
 *
 *
 */

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */

    protected $policies = [
        'App\Contact' => 'App\Policies\ContactPolicy',
        'App\User' => 'App\Policies\UsersPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot(GateContract $gate)
    {
        //All'interno del metodo boot è il luogo dove vengono definiti Gate e Policy

        $this->registerPolicies();

        /*
         * INTERCETTARE CHIAMATE AI GATE (HOOK CHE VIENE CHIAMATO OGNI VOLTA CHE SI CHIAMAT UN GATE)
         * Utile per permettere ad esempio ad un admin di fare qualsiasi cosa
         */
        $gate->before(function ($user, $ability){
            // NB: $ability è l'abilità che stiamo chiamando così posso fare dei controlli personalizzati in base alla abilità

            //if($user->isAdmin()){
            //    return true;
            //}
        });

        /*
         * DEFINIRE UN ABILITA'
         * IL GATE PUò ESSERE INIETTATO COME ISTANZA DI Illuminate\Contracts\Auth\Access\Gate OPPURE UTILIZZATE COME FACADE
         */

        // il primo argomento passato alla clousure è l'istanza dell'utente, mentre il secondo è l'istanza su cui confrontare l'abilità
        $gate->define('update-contact', function($user, $contact){
            return $user->id === $contact->user_id;
        });

        Gate::define('delete-contact', function($user, $contact){
            return $user->id === $contact->user_id;
        });

        // Come per le rotte posso usare una classe e un metodo da chiamare
        //$gate->define('create-contact', 'ContactChecker@createContact');



        /*
         * CONTROLLARE UN ABILITA' PER CONTO DI UN ALTRO UTENTE
         *
         */

        $user = new User(); //User::findOrFail(10);
        if(Gate::forUser($user)->denies('create-contact')){
            // create contact
        };


        /*
         * CONTROLLARE UN ABILITA' DIRETTAMENTE SU UN MODELLO authenticatable
         * grazie al traits authorizable che viene implementato in qualsiasi modello che implementa la classe authenticatable
         * abbiamo a disposizione 3 metodi da chiamare sull'instanza
         *
         * dietro le quinte questi metodi chiamano la facade Gate::forUser($user)->can('create-contact') come sopra
         */

        //$user->can('create-contact');
        //$user->cant('create-contact');
        //$user->cannot('create-contact');


        /*
         * DEFINIRE UN ABILITA' PASSANDO PARAMETRI AGGIUNTIVI
         * All'abilità posso essere passati dei parametri aggiuntivi oltre all'utente
         */

        $contact = new Contact(); //Contact::findOrFail(1);
        $group = "";

        //definizione
        Gate::define('add-contact-to-group', function($user, $contact, $group){
            return $user->id == $contact->user_id && $user->id == $group->user_id;
        });

        //utilizzo
        if(Gate::denies('add-contact-to-group', [$contact, $group])){
            //abort(403);
        };
    }
}
