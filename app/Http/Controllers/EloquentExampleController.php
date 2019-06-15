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
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class EloquentExampleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Contact::all();
        // ELENCO RISULTATI
        // fanno esattamente la stessa cosa, la differenza è che all() si può usare solo senza alcun altro metodo per filtrare la query
        // quindi è meglio preferire get()

        Task::all();
        Task::get();

        // ELENCO RISULTATI (Chunking Results)
        // Vengono effettuate diverse query e per ogni chunk viene eseguita la clousure passata,
        // in questo modo non si ha tutto in memoria ma la memoria viene svuotata ad ogni chunk
        Task::chunk(1, function($tasks){
            foreach ($tasks as $task){
                echo '<pre>', $task, '</pre>';
            }
        });

        $data = Task::whereNull('options')->count(); //dd($data);
        $data = Task::max('priority'); //dd($data);
        $data = Task::sum('priority'); //dd($data);
        $data = Task::avg('priority'); //dd($data);


        return "Eloquent Example";
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //METHOD 1
        $contacts = new Contact;
        $contacts->name = "Nicola";
        $contacts->email = "galdiolo.nicola@gmail.com";
        $contacts->vips = true;
        $contacts->status = 1;
        $contacts->active = 1;
        $contacts->options = ['isAdmin' => true];
        $contacts->orders = 4;
        $contacts->save();

        //METHOD 2 (MASS ASSIGNEMENT)
        // Quanto passo un array a Model(), Model::create(), Model::update() sto facendo un massassignement e richiede che vengano settati
        // i campi fillable nel modello, il massassignement viene applicato quando si lavora con un istanza del modello, non su un query builder
        $contacts = new Contact([
            'name' => 'Erica',
            'email' => 'erica.frigo@gmail.com',
            'vips' => true,
            'status' => 2,
            'active' => 1,
            'orders' => 3,
            'options' => ['isAdmin' => true]
        ]);
        $contacts->save();

        //METHOD 3 (MASS ASSIGNEMENT)
        Contact::create([
            'name' => 'Chloe',
            'email' => 'chloe.galdiolo@gmail.com',
            'vips' => false,
            'status' => 3,
            'active' => 0,
            'orders' => 6,
            'options' => ['isAdmin' => true]
        ]);

        return "Record Aggiunto";
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        // Cerca il record e in caso non lo trovasse crea un istanza senza salvarla,
        // il secondo parametro è un array di valori con cui il record deve essere popolato in caso di creazione, ma non viene usato per la ricerca
        //$contact = Contact::firstOrNew(['email' => 'pippo.pluto@gmail.com'], ['name' => 'Nicola', 'longevity' => 'new']);

        // Cerca il record e in caso non lo trovasse crea un istanza e la salva
        // il secondo parametro è un array di valori con cui il record deve essere popolato in caso di creazione, ma non viene usato per la ricerca
        $contact = Contact::firstOrCreate(['email' => 'pippo.pluto@gmail.com'], ['name' => 'Nicola', 'longevity' => 'new']);
        return $contact;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update()
    {
        //METHOD 1
        $contacts = Contact::first();
        $contacts->name = "Nicola edited";
        $contacts->email = "galdiolo.nicola+20@gmail.com";
        $contacts->save();

        //METHOD 2 (NO MASS ASSIGNEMENT)
        //non ho massassignment in quanto non sto lavorando su un instanza del modello ma su querybuilder
        Contact::take(1)->update(['longevity' => '']);

        //METHOD 3 (MASS ASSIGNEMENT)
        //ho massassignment in quanto sto lavorando su un instanza del modello e non sul query builder
        $contact = Contact::Where('longevity', '')->first();
        $contact->update(['longevity' => 'new']);
        //$contact->update($request->all()); // prende tutto ciò che arriva dalla request
        //$contact->update($request->only(['key' => 'value'])); // prende solo ciò che indichiamo in array
        //$contact->update($request->except(['key' => 'value'])); // prende tutto dalla requeste tranne ciò che indichiamo in array

        return "Record Aggiornato";
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        //Contact::findOrFail(4)->delete(); //non serve recuperare l'istanza per cancellarla

        // posso chiamare direttamente il metodo destroy passando un id o un array di id
        //Contact::destroy(5);
        //Contact::destroy([6,7,8]);
        Contact::whereNull('longevity')->orWhere('longevity', '')->delete();

        return "Record Cancellato";
    }

    public function softdelete()
    {
        $contacts = Contact::get(); // restituisce tutti i record senza i softdelete
        $allHistoricContacts = Contact::withTrashed()->get(); // restituisce tutti i record compresi i softdelete
        $onlyTrashedContacts = Contact::onlyTrashed()->get(); // restituisce tutti i record eliminati tramite softdelete

        /*
         * CICLO I DATI E CON IL METODO trashed() SO QUALE è TRASHED
        $allHistoricContacts->each(function ($item, $key){
           if($item->trashed()){
               echo $item->name . '<br>';
           }
        }); */

        // RIPRISTINARE I RECORD ELIMINATI
        //Contact::onlyTrashed()->first()->restore();

        // ELIMINAZIONE FORZATA RECORD
        //Contact::onlyTrashed()->first()->forceDelete();

        return "Softdelete";
    }

    public function scope()
    {
        /*
         * LOCAL SCOPE
         * QUERY CON LOCAL SCOPE, DEFINITO NEL MODELLO
         */

        $contacts = Contact::vips()->get();
        $contacts = Contact::status(2)->get();

        /*
         * GLOBAL SCOPE
         * TUTTE LE QUERY IMPLEMENTANO DI DEFAULT LO SCOPE GLOBALE SALVO ESPLICITA DICHIARAZIONE
         */

        // NON APPLICA LO SCOPE GLOBALE INDICATO
        // (COME STRINGA SE SI TRATTA DI CLOUSURE GLOBAL SCOPE O INDICANDO LA CLASSE SE IL GLOBAL SCOPE è UNA CLASSE)
        $contacts = Contact::withoutGlobalScope('status')->get();
        $contacts = Contact::withoutGlobalScope(ActiveScope::class)->get();

        // NON APPLICA GLI SCOPE GLOBALI INDICATI
        // (COME STRINGA SE SI TRATTA DI CLOUSURE GLOBAL SCOPE O INDICANDO LA CLASSE SE IL GLOBAL SCOPE è UNA CLASSE)
        $contacts = Contact::withoutGlobalScopes(['status', ActiveScope::class])->get();

        // NON APPLICA NESSUN GLOBAL SCOPE (ANCHE IL SOFTDELETE è UN GLOBAL SCOPE QUINDI FARE ATTENZIONE)
        $contacts = Contact::withoutGlobalScopes()->get();



        return $contacts;
    }

    public function accessors()
    {
        $contact = Contact::first();

        $data = [
            'obj' => $contact,
            'accessors' => [
                'name' => $contact->name,
                'name_plus_mail' => $contact->name_plus_mail,
            ]
        ];

        return $data;
    }

    public function attributeCasting()
    {
        $contact = Contact::first();
        dd($contact->options->isAdmin); //il campo options è castato a stdClass nel modello così mi viene tornato sottoforma di oggetto

    }

    public function collections()
    {

        /*
         * ELOQUENT COLLECTION
         * Le eloquent collection sono una classe che estende l'oggetto collection aggiungendo funzionalità per gestire i records del db.
         */

        // alcuni metodi
        $contacts = Contact::all();
        return [
            'modelKeys' => $contacts->modelKeys(), // restituisce tutte le chiavi primarie estratte
            'find id' => $contacts->find(60), // restituisce l'istanza con la chiave primaria 60

            // il modello contact utilizza una classe collection custom che mette a disposizione il metodo subOrderTotal(),
            // vedi il metodo newCollection() del modello Contact
            'subOrderTotal' => $contacts->subOrderTotal()
        ];
    }

    public function serialization()
    {
        /*
         * SERIALIZZAZIONE
         * Quando dobbiamo tornare dei dati abbiamo a disposizione 2 metodi applicabili sia ad un oggetto Eloquent
         * sia ad una eloquentCollection
         *
         * ->toArray = torna i dati sottoforma di array
         * ->toJson = torna i dati sottoforma di Json
         */

        $contactArray = Contact::with('user')->first()->toArray();
        $contactJson = Contact::first()->toJson();
        $contactsArray = Contact::all()->toArray();
        $contactsJson = Contact::all()->toJson();

        dd($contactArray, $contactJson, $contactsArray, $contactsJson);

        /*
         * SERIALIZZAZIONE E ROUTE
         * Il ruoting di laravel converte in automatico tutto ciò che viene tornato in un Json
         */

        return Contact::first(); // automaticamente convertito in json
        return Contact::first()->toJson(); // NON NECESSARIO
    }

    public function updateParentTimestamp()
    {

        /*
         * AGGIORNARE UPDATED_AT DEL PADRE SULL'AUPDATE DEL FIGLIO
         * è possibile aggiornare il timestamp di una relazione padre all'aggiornamento del figlio
         * è sufficiente settare la proprietà: protected $touches = ['user']; nel modello
         * funziona anche con mass-assignment
         */

        $user = User::latest()->first();
        $conference = $user->conferences()->first();

        $faker = Faker::create();
        $conference->update(['name' => $faker->name]);

        return [
            'conference_name' => $conference->name,
            'conference_update_at' => $conference->updated_at,
            'user_update_at' => $user->updated_at,
        ];

    }

    public function eagerLoading()
    {
        /*
         * NON FACCIO EAGER LOADING
         * ad ogni ciclo faccio una query per recuperare il phoneNumber
         */
        $contacts = Contact::get();
        $contacts->each(function($contact){
            $var = $contact->phoneNumber;
            $var2 = $contact->user;
        });

        /*
         * FACCIO EAGER LOADING
         * recupero tutto subito così evito di fare query inutili
         */
        $contacts = Contact::with('phoneNumber', 'user.accounts')->get();
        $contacts->each(function($contact){
            $var = $contact->phoneNumber;
        });

        /*
         * EAGER LOADING FILTRATO
         * carico le relazioni filtrandole
         */
        $contacts = Contact::with(['phoneNumber', 'user'=> function($query){
            $query->whereNotNull('email_verified_at');
        }])->get();

        dd($contacts);
        $contacts->each(function($contact){
            $var = $contact->phoneNumber;
        });

        /*
         * LAZY EAGER LOADING
         * utilizzo il eager loading solo se effettivamente mi serve
         */

        $contacts = Contact::all();
        $loadAllData = false;

        if($loadAllData){
            $contacts->load('phoneNumber', 'user.accounts');
        }

        $contacts->each(function($contact){
            $var = $contact->phoneNumber;
        });

        /*
         * LAZY EAGER LOADING (WITH() VS LOAD())
         * la differenza tra with() e load() è che with viene utilizzato al momento della query mentre load può essere usato
         * in un secondo momento
         */

        return 'View Debugbar';
    }
}
