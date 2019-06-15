<?php

namespace App\Http\Controllers;

use App\Conference;
use App\Contact;
use App\PhoneNumber;
use App\Scopes\ActiveScope;
use App\Task;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class AuthenticationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        /*
         * Autenticazione usando auth() global helps oppure Auth:: Facade
         */

        return [
            'auth' => auth()->check(), // torna true o false se l'utente è autoenticato o meno,
            'guest' => auth()->guest(), // contrario di auth()->check()
            'user' => auth()->user(), // torna l'utente (instanza del modello) o false
            'userID' => auth()->id(), // torna l'id dell'utente o false
            'viaRemember' => Auth::viaRemember() // indica se l'utente si è autenticato tramite feature "rememberToken" (è necessario settare la conf session->expire_on_close a true affinchè funzioni)
        ];

        /*
         * AUTH CONTROLLERS
         * I controller per gestire tutto il flusso di autenticazione/utente si trovano in App\Http\Controllers\Auth
         */

    }

    public function authenticationManual()
    {

        /*
         * Autenticazione manuale utile in caso di impersonificazione
         */

        $user = User::first();

        auth()->loginUsingId($user->id); // passo l'id dell'utente
        auth()->login($user); // passo l'oggetto che non deve necesssariamente essere di tipo user ma deve essere un oggetto che implementa l'interfaccia lluminate\Contact\Auth\Authenticatable

        return [
            'auth' => auth()->check(), // torna true o false se l'utente è autoenticato o meno,
            'guest' => auth()->guest(), // contrario di auth()->check()
            'user' => auth()->user(), // torna l'utente (instanza del modello) o false
            'userID' => auth()->id(), // torna l'id dell'utente o false
            'viaRemember' => Auth::viaRemember() // indica se l'utente si è autenticato tramite feature "rememberToken" (è necessario settare la conf session->expire_on_close a true affinchè funzioni)
        ];

    }

    public function guards()
    {
        /*
         * Sono la definizione di come il sistema dovrebbe archiviare e recuperare informazioni sugli utenti
         * La definizione delle Guard si trova in auth.defaults.guard (=config)
         * Di default laravel usa la guard web perchè è indicato ciò in auth.defaults.guard (=config)
         */

        return [
            'user' => auth()->user(),
            'apiUser' => auth()->guard('api')->user() //è possibile specificare per una singola richiesta il tipo di guard utilizzato
        ];
    }
}
