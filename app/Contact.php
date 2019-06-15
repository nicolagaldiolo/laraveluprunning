<?php

namespace App;

use App\CustomCollection\ContactCollection;
use App\Scopes\ActiveScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{

    use SoftDeletes; // aggiunto il traits softdeleted per aggiungere funzionalità al modello

    /*
     * $dates
     * informo laravel che questi campi devono essere trattati come date
     * da laravel 5.2 questa è un operazione abbastanza inutile perchè potrei utilizzare direttamente la variabile $casts
     */
    //
    protected $dates = [
        'deleted_at'
    ];

    protected $casts = [
        'options' => 'object', // dichiaro come deve essere trattato questo campo all'interno dell'applicazione
        //'deleted_at' => 'timestamp'
    ];

    protected $table = 'contacts'; // Definisce la tabella a cui si riferisce (non necessario in questo caso perchè rappresenta valore default).
    protected $primaryKey = 'id'; // Definisce la chiave primaria (non necessario in questo caso perchè rappresenta valore default).
    public $incrementing = true; // Definisce che la chiave primaria si autoincrementa (non necessario in questo caso perchè rappresenta valore default).

    public $timestamps = true; // Definisce che la classe gestirà i campi created_at|updated_at (la migrations deve avere i campi $table->timestamps()) (non necessario in questo caso perchè rappresenta valore default).
    protected $dateFormat = 'U'; // Definisce il formato con cui vengono salvati i campi timestamps (Settato: Unix timestamp | Default: 2019-04-11 04:49:53)

    // Hanno effetto solo sul massAssignement, posso comunque popolare questi campi con un assegnazione diretta
    protected $fillable = [
        'name',
        'email',
        'longevity',
        'vips',
        'status',
        'active',
        'options',
        'orders',
        'user_id'
    ];

    // Hanno effetto solo sul massAssignement, posso comunque popolare questi campi con un assegnazione diretta
    //protected $guarded = [
    //    'owner_id'
    //];

    /*
     * SERIALIZZAZIONE
     * Quando viene applicata la serializzazione dei dati (quindi un modello eloquent o collections viene tornato al routing
     * oppure vengono applicati i metodi ->toArray() o ->toJson()) è possibile nascondere o mostrare solo alcuni campi
     * vale anche per le relazioni (es: user)
     */
    public $hidden = ['created_at', 'updated_at', 'deleted_at'];
    //public $visible = ['name', 'email', 'vips', 'status', 'user', 'name_plus_mail'];
    // è cmq possibile in determinati casi fare delle eccezioni:
    // $contactJson = Contact::makeVisibile('created_at')->first()->toJson();
    // $contactJson = Contact::makeHidden('email')->first()->toJson();
    protected $appends = ['name_plus_mail']; // possiamo aggiungere alla serializzazione altri campi che non esistono ma di cui esiste un accessors


    /*
     * LOCAL SCOPE - Filtrare le query
     * Definisce uno scope locale che può essere utilizzata onDemand dal query builder
     */

    public function scopeVips($query){

        //laravel crea "metodi magici" per ogni colonna es: whereVips(),
        // altrimenti avrei dovuto usare: $query->where('vips', true);
        return $query->whereVips(true);
    }

    public function scopeStatus($query, $status){
        return $query->where('status', $status);
    }


    /*
     * GLOBAL SCOPE - Filtrare le query
     * Definisce uno scope globale che viene utilizzato implicitamente in ogni query,
     * salvo che non venga specificato il contrario con Model::withoutGlobalScope(...)
     */

    protected static function boot()
    {
        // dal momento che ridefinisco il metodo boot chiamo il metodo boot()
        // definito nella classe che sto estendendo
        parent::boot();

        // UTILIZZO DELLO SCOPE GLOBALE
        // METHOD 1
        static::addGlobalScope('status', function(Builder $builder){
           $builder->where('status', '>=', 1);
        });

        // METHOD 2
        // Definisco una classe di Scope globale,
        // così che possa essere utilizzata trasversalmente su vari modelli
        static::addGlobalScope(new ActiveScope);

    }

    /*
     * ACCESSORS | MUTATORS | ATTRIBUTE CASTING
     * permettono di manipolare i dati o creare nuovi attributi non presenti a db
     */

    public function getNameAttribute($value)
    {
        return strtoupper($value);
    }

    public function getNamePlusMailAttribute() // attributo non presente a db
    {
        return $this->name . ' - ' . $this->email;
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = strtolower($value);
    }

    public function setWorkinggroupNameAttribute($workgroupName) // attributo non presente a db
    {
        $this->attributes['email'] = "{$workgroupName}@ourcompany.com";
    }

    /*
     * ELOQUENT COLLECTION
     * Ogni modello di default utilizza la classe Illuminate\Database\Eloquent\Collection per tornare un result Sets
     * Se abbiamo necessità di aggiungere funzionalità all'oggetto collection possiamo creare una classe collection custom
     * che estende quella di default e informare il modello quale classe dovrà usare utilizzando il metodo new Collection
     */
    public function newCollection(array $models = [])
    {
        return new ContactCollection($models); //$models è un array di instanze Contact
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function phoneNumber(){
        return $this->hasOne(PhoneNumber::class);
    }

    public function stars()
    {
        return $this->morphMany(Star::class, 'starrable');
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
}
