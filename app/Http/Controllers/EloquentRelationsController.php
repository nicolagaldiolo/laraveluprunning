<?php

namespace App\Http\Controllers;

use App\Account;
use App\Contact;
use App\Event;
use App\PhoneNumber;
use App\Star;
use App\Tag;
use App\User;
use Faker\Factory as Faker;

class EloquentRelationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function onetoone()
    {
        /*
         * SALVARE UNA RELAZIONE
         * Method 1
         */

        $faker = Faker::create();

        Contact::create([
            'name' => $faker->name,
            'email' => $faker->email,
            'vips' => true,
            'status' => 3,
            'active' => 1,
            'orders' => 6,
            'options' => ['isAdmin' => true],
            'user_id' => 1,
        ])->phoneNumber()->create([ //massAssignement
            'number' => $faker->e164PhoneNumber
        ]);

        /*
         * Method 2
         */

        $contact = Contact::withoutGlobalScopes()->find(82);

        $phoneNumber = new PhoneNumber;
        $phoneNumber->number = $faker->e164PhoneNumber;
        $contact->phoneNumber()->save($phoneNumber);


        /*
         * RECUPERARE DATI CON RELAZIONE
         */

        return [
            Contact::with('phoneNumber')->find(77),
            PhoneNumber::with('contact')->find(2)
        ];
    }

    public function onetomany()
    {

        $user = User::first();
        $faker = Faker::create();

        /*
         * SALVARE UNA RELAZIONE
         * Method 1
         */
        $user->contacts()->create([
            'name' => $faker->name,
            'email' => $faker->email,
            'vips' => true,
            'status' => 3,
            'active' => 1,
            'orders' => 6,
            'options' => ['isAdmin' => true]
        ]);

        /*
         * SALVARE UNA RELAZIONE
         * Method 2
         */

        $contact = new Contact;
        $contact->name = $faker->name;
        $contact->email = $faker->email;
        $contact->vips = true;
        $contact->status = 3;
        $contact->active = 1;
        $contact->orders = 6;
        $contact->options = ['isAdmin' => true];

        $user->contacts()->save($contact);


        /*
         * SALVARE UNA RELAZIONE
         * Method 3
         */

        $contact = new Contact;
        $contact->name = $faker->name;
        $contact->email = $faker->email;
        $contact->vips = true;
        $contact->status = 3;
        $contact->active = 1;
        $contact->orders = 6;
        $contact->options = ['isAdmin' => true];

        $contact2 = new Contact;
        $contact2->name = $faker->name;
        $contact2->email = $faker->email;
        $contact2->vips = true;
        $contact2->status = 3;
        $contact2->active = 1;
        $contact2->orders = 6;
        $contact2->options = ['isAdmin' => true];

        $user->contacts()->saveMany([$contact,$contact2]);

        /*
         * SALVARE UNA RELAZIONE (senza creazione)
         * Method 4 - se al metodo create o save viene passato un record esistente viene aggiornata solo la chiave esterna
         */
        $latestUser = User::orderBy('id', 'desc')->first();
        $contact = Contact::first();
        $latestUser->contacts()->save($contact);


        /*
         * SALVARE ASSOCIAZIONE BELONGS TO
         * Se voglio associare un figlio al padre attraverso il figlio
         * Questo metodo semplicemente aggiorna la chiave esterna del figlio
         */
        $lastContact = Contact::latest()->first();
        $lastContact->user()->associate(User::find(5))->save(); //Aggiungo l'associazione
        //$lastContact->user()->dissociate()->save(); //Rimuovo l'associazione


        /*
         * RECUPERARE DATI CON RELAZIONE
         */
        return [
            'user->contacts' => $user->contacts,
            'contact->user' => $contact->user,
        ];
    }

    public function onetomanyExample()
    {
        $contactsVips = User::find(1)->contacts()->where('vips', 1)->count();
        $contactsVipsScope = User::find(1)->contacts()->vips()->count();

        $userWithContacts = User::has('contacts')->count();
        $userWithManyContacts = User::has('contacts', '>=', 10)->count();

        $userWithPhoneNumbers = User::has('contacts.phoneNumber')->get();

        $jennyIGotYourNumber = Contact::whereHas('phoneNumber', function($query){
            $query->where('number', 'like', '%056%');
        })->get();

        $jennyIGotYourNumber = User::whereHas('contacts.phoneNumber', function($query){
            $query->where('number', 'like', '%056%');
        })->get();

        //return $jennyIGotYourNumber;

        return "Vedi Debug bar";
    }

    public function hasmanythrough()
    {
        User::find(1)->phoneNumbers;

        return "Vedi Debug bar";
    }

    public function manytomany()
    {

        $user = User::first();

        $account = Account::firstOrCreate(
            ['name' => 'Isaiah Brakus'],
            ['email' => 'julianne41@gmail.com']);

        /*
         * SALVARE UNA RELAZIONE
         * METODO 1
         */
        $user->accounts()->save($account, ['status' => 'donor']); // associo un account all'utente e al momento del salvataggio passo altri dati da aggiungere alla tabella PIVOT


        /*
         * SALVARE UNA/PIU RELAZIONI (ATTACH/DETACH) PASSANDO ID/ARRAY DI IDS
         * METODO 2
         * Passo solamente un id o un array di id per accoppiare o disaccoppiare i dati, il secondo parametro lavora sulla tabella pivot
         */
        $user->accounts()->attach(12);
        $user->accounts()->attach(12, ['status' => 'attached']);
        $user->accounts()->attach([12,13,14]);
        $user->accounts()->attach([
            12 => ['status' => 'attached_with_array_1'],
            13 => ['status' => ''],
            14 => ['status' => '']
        ]);

        //$user->accounts()->detach(12);
        //$user->accounts()->detach([12,13]);
        //$user->accounts()->detach(); //all

        /*
         * SALVARE UNA/PIU RELAZIONI (SYNC) PASSANDO ID/ARRAY DI IDS
         * METODO 3
         * Passo solamente un id o un array di id per fare il sync (aggiungo le relazioni passate eliminando tutte le altre
         */
        $user->accounts()->sync([12,13,14]);
        $user->accounts()->sync([
            12 => ['status' => 'attached_with_array_1'],
            13,
            14
        ]);


        /*
         * AGGIORNARE TABELLA PIVOT
         *
         */
        $user->accounts()->updateExistingPivot($account, ['status' => 'inactive']);

        /*
         * ESTRARRE I DATI DA TABELLA PIVOT
         * NB: ho in atiro anche i campi timestamp della tabella pivot perchè nella definizione della relazione ho detto di farmeli tornare
         */
        $user->accounts->each(function($account){
            echo sprintf("Account associated with this user at: %s with status: %s \n", $account->pivot->created_at, $account->pivot->status);
        });
    }

    public function polymorphic()
    {

        $contact = Contact::first();
        $event = Event::firstOrCreate(['name' => 'Evento 1']);
        /*
         * SALVARE UNA RELAZIONE POLIMORFICA
         */
        $contact->stars()->create();
        $event->stars()->create();
        /*
         * ESTRARRE RELAZIONE POLIMORFICA
         */
        $star = Star::first();
        // return $star->starrable // mi viene tornata l'instanza (in questo caso un Contact o un Event)
        $stars = Star::all();
        $stars->each(function($star){
           var_dump($star->starrable); // torna l'istanza del oggetto che potrebbe essere un Contact o un Event
        });

        /*
         * SALVARE UNA RELAZIONE POLIMORFICA CON USER_ID
         * associo anche l'id dell'utente che crea il record
         */
        $user = User::first();
        $event = Event::first();
        $event->stars()->create(['user_id' => $user->id]);
    }

    public function polymorphicMany2Many()
    {
        $faker = Faker::create();
        $tag = Tag::firstOrCreate(['tag' => 'like-cheese']);

        $contact = Contact::first();

        /*
         * SALVARE UNA RELAZIONE POLIMORFICA MANY2MANY
         * dal contatto associo i tags
         */
        $contact->tags()->sync($tag->id);

        // Posso usare tutti i metodi della ralazione belongsTo
        //$contact->tags()->save();
        //$contact->tags()->saveMany();
        //$contact->tags()->attach();
        //$contact->tags()->detach();
        //$contact->tags()->sync();
        //$contact->tags()->updateExistingPivot();

        $newContact = Contact::firstOrcreate(
            ['name' => 'desmond ortiz'],
            ['email' => $faker->email,
            'vips' => true,
            'status' => 3,
            'active' => 1,
            'orders' => 6,
            'options' => ['isAdmin' => true]]);

        /*
         * SALVARE UNA RELAZIONE POLIMORFICA MANY2MANY
         * dal tag associo i contatti
         */
        $tag->contacts()->sync($newContact->id);

        /*
         * RECUPERARE I DATI DA UNA RELAZIONE POLIMORFICA MANY2MANY
         */


        //$contact = Contact::first();
        //$contact->tags;
        //$contact = Contact::has('tags')->get();

        //$tag = Tag::first();
        //$tag->contacts;


        $tags = Tag::has('contacts')->with('contacts')->get();

        $tags->each(function($tag){
            //
        });

        //dd($tags);

        return "Vedi Debug bar";
    }
}
