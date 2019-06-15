<?php

namespace App\Http\Controllers;

use App\Contact;
use App\User;
use Illuminate\Http\Request;
use Gate;

/*
 * GATE / POLICIES
 * I gate e policy vengono registrate nel AuthServiceProvider nel metodo boot()
 */

class GatePoliciesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Gate $gate)
    {
        //IL GATE PUò ESSERE INIETTATO COME INSTANZA DI Illuminate\Contracts\Auth\Access\Gate OPPURE UTILIZZATE COME FACADE
        $contact = Contact::find(1);
        if(Gate::allows('update-contact', $contact)){
            // update contact
        }

        if($gate::allows('update-contact', $contact)){
            // update contact
        }

        // posso fare un controllo per conto di un altro utente
        $user = User::find(10);
        if(Gate::forUser($user)->denies('create-contact')){
            // create contact
        };
    }

    public function abilitiesWithMultipleParameters()
    {
        //All'abilità posso essere passati dei parametri aggiuntivi oltre all'utente
        //definizione

        $contact = Contact::find(1);
        $group = "";

        Gate::define('add-contact-to-group', function($user, $contact, $group){
            return $user->id == $contact->user_id && $user->id == $group->user_id;
        });

        //utilizzo
        if(Gate::denies('add-contact-to-group', [$contact, $group])){
            abort(403);
        };
    }

    public function controllerAuthorization()
    {

        /*
         * IL CONTROLLER BASE INCLUDE IL TRAITS AuthorizesRequests PER FORNISCE 3 METODI
         * (authorize, authorizeForUser, authorizeResource);
         * che invocano la classe gate e fanno il controllo semplicemente chiamando $this->>autorize('update-contact', $contact);
         */

        $contact = Contact::first();
        $user = User::first();

        // Chiamata esplicita al gate
        if(Gate::cannot('update-contact', $contact)){
            //abort(403)
        }

        // Utilizzo i metodi forniti dal controller
        $this->authorize('update-contact', $contact);
        $this->authorizeForUser($user, 'update-contact', $contact); // autorizzo per conto di altro utente

        // definisco le autorizzazioni per l'intera risorsa (da usare nel costruttore).
        // Vedi GatePoliciesControllerAuthorizeResource controller
        $this->authorizeResource(Contact::class);
    }
}
