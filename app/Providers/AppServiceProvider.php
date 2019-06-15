<?php

namespace App\Providers;

use App\Contact;
use App\Http\ViewComposers\RecentTasksComposer;
use App\Mail\UserMailer;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Mail\Mailer;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Laravel\Dusk\DuskServiceProvider;
use Symfony\Component\HttpKernel\Log\Logger;


/*
 * Un ServiceProvider è una classe che incapsula la logica che è necessario eseguire varie parti dell'applicazione per poter eseguire il boot delle loro funzionalità principali.
 * Se si ha del codice che necessita di essere lanciato in "preparazione" per far lavorare il codice dell'applicazione vera e propria i service provider sono un buon candidato.

I service Providers sono vari in quanto le varie classi sono suddivide per "tipologia".
Es: AuthServiceProvider definisce tutte le regole legate alle autorizzazioni (es: gate e policy) ed è necessario che siano
definite prima che venga fatto il boot dell'applicazione.
Oppure nell'AppServiceProvider viene definita settata la lingua dell'applicazione e le varie direttive blade.

ServiceProvider ha 2 metodi principali, register() e boot() che vengono lasciati uno dopo l'altro

	"Dopo che tutti i provider sono stati registrati, vengono" avviati ". Questo attiverà il metodo di 	avvio su ciascun provider.
    Un errore comune quando si utilizzano i provider di servizi sta tentando 	di utilizzare i servizi forniti da un altro provider
    nel metodo di registrazione. Poiché, all'interno del 	metodo di registrazione, non abbiamo garanzia che tutti gli altri fornitori
    siano stati caricati, il 	servizio che stai tentando di utilizzare potrebbe non essere ancora disponibile. Quindi, il codice 	del
    fornitore di servizi che utilizza altri servizi dovrebbe sempre vivere nel metodo di avvio. Il 	metodo di registrazione dovrebbe essere
    usato solo per, avete indovinato, registrando i servizi 	con il contenitore. All'interno del metodo di avvio, puoi fare ciò che vuoi:
    registra i listener di 	eventi, includi un file di rotte, filtri di registrazione o qualsiasi altra cosa tu possa immaginare. "

	Quindi il registro uno è solo per il binding. Il boot è in realtà per far scattare qualcosa che accada.


    $defer
	    Se il service provider sta solo registrando legami nel container, es: come risolvere una classe o
        interfaccia ma non eseguire nessun altro bootstrapping si può "differire" la registrazione in modo 	che il
        caricamento di questa classe o classi venga fatto solo se effettivamente utilizzata. Questo migliorerà le performance.
        Perché il $defer funzioni deve essere implementata l'interfaccia: DeferrableProvider e aggiunto tutte le classi
        da caricare in "ritardo" nell'array tornato dal metodo provides().
        PS: PER TESTATE LA FUNZIONALISTA DEFER SVUOTARE LA CACHE CON php srtisan clear-compiled
 */



class AppServiceProvider extends ServiceProvider //implements DeferrableProvider
{


    //public function provides()
    //{
    //    return [
    //        Logger::class,
    //        'myClassObject',

    //    ];
    //}

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // ISTRUIRE LARAVEL SU COME RISOLVERE LA DEPENDENCY INJECTION DI UN PARTICOLARE OGGETTO CHE RICHIEDE PARAMETRI
        // NEL COSTRUTTORE, ALTRIMENTI SE NON NECESSITA DI PARAMETRI NON SERVE FARE QUESTO

        /*
         * POSSO PROCEDERE IN VARI MODI:
         * 1 CLOUSURE BINDING
         * 2 SINGLETONS BINDING
         * 3 ALIAS BINDING
         * 4 INTERFACE (INVERSION OF CONTROL)
         * 5 CONTEXTUAL BINDING
         */

        /*
         * 1 CLOUSURE BINDING
         * passo uno clousure associando la key (primo parametro) con la classe che deve istanziare quando chiamata passando i parametri
         */
        // il primo parametro è la chiave con cui posso richiamare l'istanza dell'oggetto, può essere un alias o un FQCN Fully Qualified class name
        $this->app->bind(Logger::class, function ($app){
            return new Logger("warning", 'param2');
        });

        $this->app->bind('myClassObject', function ($app){
            return new Logger("warning", 'param2');
        });

        /* La clousure riceve un instanza di app perchè potrebbe servirgli al suo interno */
        /*
        $this->app->bind(UserMailer::class, function ($app){
            return new UserMailer(
                $app->make(Mailer::class),
                $app->make(Logger::class),
                $app->make(Slack::class)
            );
        });
        */

        /*
         * 2 SINGLETONS BINDING
         * come clousure binding solo che viene cachata e quindi non viene creata ogni volta una nuova istanza ma viene torna l'istanza cachata.
         */
        $this->app->singleton(Logger::class, function ($app){
            return new Logger("warning", 'param2');
        });

        // oppure se ho già un instanza dell'oggetto
        $logger = new Logger("warning", 'param2');
        $this->app->instance(Logger::class, $logger);

        /*
         * 3 ALIAS BINDING (MOLTO USATO NEL CORE DI LARAVEL)
         * se voglio creare degli alias, chiamando una classe in realtà ne sto chiamando un altra.
         */
        //$this->app->bind(Logger::class, 'myClassObject');
        //$this->app->bind('myClassObject', Logger::class);
        //$this->app->bind(Logger::class, AltraClasse::class);

        /*
         * 4 INTERFACE (INVERSION OF CONTROL)
         * ESEMPIO DI CONTROLLER ED ESEMPIO DI BIND DELLA CLASSE NEL SERVICE PROVIDER
         * Il controller implementa l'interfaccia mailer ma non sa quale oggetto gli arriverà perchè questa decisione viene presa
         * nel service provider bindando la classe interessata
         */

        /*
        use Interfaces\Mailer;

        class UserMailer {
            protected $mailer;

            public function __construct(Mailer $mailer){
                $mailer .......
            }
        }

        $this->app->bind(\Interfaces\Mailer::class, function ($app){
           return new MailGunMailer(....);
        });

        */

        /*
         * 5 CONTEXTUAL BINDING
         * A volte potresti avere due classi che utilizzano la stessa interfaccia, ma desideri inserire diverse
         * implementazioni in ogni classe. Ad esempio, due controller possono dipendere da diverse implementazioni di
         * Illuminate\Contracts\Filesystem\Filesystem. Laravel fornisce un'interfaccia semplice e fluente per definire questo comportamento:
         *
         */

        $this->app->when(PhotoController::class)->needs(Filesystem::class)->give(function () {
            return Storage::disk('local');
        });

        $this->app->when([VideoController::class, UploadController::class])->needs(Filesystem::class)->give(function () {
            return Storage::disk('s3');
        });


        if ($this->app->environment('local', 'testing')) {
            $this->app->register(DuskServiceProvider::class);
        }

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        Gate::define('create-person', function($user){
            return true;
        });


        // CHIAMARE METODI DI CLASSE PASSANDO PARAMETRI
        //app()->call('App\Http\Controllers\Container@callMeByServiceProvider', ['Stringa da stampare']);
        $this->app->call('App\Http\Controllers\Container@callMeByServiceProvider', ['Stringa da stampare']);

        // INIETTARE VARIABILI DENTRO LE VISTE, quando la vista viene chiamata viene eseguita la callback, che può essere
        // una clousure o un riferimento ad una classe, che passa ulteriori variabili alla vista

        // VOLENDO SI AVREBBE POTUTO CREARE UN NUOVO ViewServiceProvider e inserire il codice seguente al suo interno
        // come mostrato qui: https://laravel.com/docs/5.8/views#view-composers

        // La variabile sarà disponibile in tutte le viste
        view()->share('allViewVariable', 'Variabile visibile in tutte le viste');

        // La variabile sarà disponibile nella vista indicata
        view()->composer('bladeExample', function($view){
            $view->with('singleViewVariable', 'Variabile visibile in una vista');
        });

        // La variabile sarà disponibile nelle viste indicate
        view()->composer(['bladeExample', 'home', 'welcome'], function($view){
            $view->with('multipleViewVariable', 'Variabile visibile in varie viste');
        });

        // La variabile sarà disponibile in tutte le viste contenute nella cartella tasks
        view()->composer('tasks.*', function($view){
            $view->with('tasksViewVariable', 'Variabile visibile in varie viste');
        });

        // La variabile sarà disponibile in tutte le viste
        view()->composer('*', function($view){
            $view->with('allViewVariable', 'Variabile visibile in varie viste');
        });


        view()->composer('welcome', RecentTasksComposer::class);


        // CUSTOM DIRETTIVE BLADE

            // ATIPATTERN - // attenzione che tutto ciò che non è una stringa viene valutato al momento della compilazione,
            // non quando la direttiva viene usata, quindi dato che blade viene cashato potrei incorrere in problemi ultizzando questo pattern
            Blade::directive('ifGuestAntipattern', function(){
               $ifGuest = auth()->guest();
                return "<?php if({$ifGuest}): ?>";
            });

            // Pattern corretto
            Blade::directive('ifGuest', function(){
                return "<?php if(auth()->guest()): ?>";
            });

            Blade::directive('newlonesTobar', function($expression){
                return "<?php echo nl2br({$expression}); ?>";
            });

        /*
         * EVENTS
         * IN ALTERNATIVA SI POSSO USARE GLI OBSERVER
         * Laravel automaticamente spara degli eventi ogni volta che accadono le seguenti azioni sul modello:
         * retrieved, creating, created, updating, updated, saving, saved, deleting, deleted, restoring, restored
         * NB: gli eventi SAVED e UPDATED NON VENGONO LANCIATI quando si usa il massassignement
         */

        // Evento che viene scatenato alla creazione sul modello Contact
        Contact::created(function($contact){
            //logger("Contatto creato, id: {$contact->id}");
        });

        // Evento che viene scatenato alla salvataggio/aggiornamento sul modello Contact
        Contact::saved(function($contact){
            //logger("Contatto salvato, id: {$contact->id}");
        });

    }
}
