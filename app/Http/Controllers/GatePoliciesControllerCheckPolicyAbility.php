<?php

namespace App\Http\Controllers;

use App\Contact;
use App\User;
use Illuminate\Http\Request;
use Gate;

class GatePoliciesControllerCheckPolicyAbility extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $contact = Contact::first();
        $user = User::first();

        // CONTOLLARE SE UN UTENTE HA UN ABILITà
        // METODO 1 (Utente viene automaticamente passato)
        if(Gate::denies('update', $contact)){
            //
        }
        // METODO 2 (Utente viene automaticamente passato)
        //$this->authorize('update', $contact);


        if(!Gate::check('create', Contact::class)){
            //abort(403);
        }

        // METODO 3 Utilizzando l'helper can associato all'utente
        if($user->can('update', $contact)){
            //
        }

        // In blade
        //@can('update', $contact)
        //@endcan

    }
}
