<?php

namespace App\Http\Controllers;

use Illuminate\Mail\Mailer;

/*
 * Il container di Laravel ha il compito di risolvere le dipendenze e tramite la DEPENDENCY INJECTION inietta gli oggetti ("dipendenti") all'interno del costruttore e o di un particolare metodo.
 * LA DEPENDENCY INJECTION PUà ESSERE FATTA ATTRAVERSO IL METHOD INJECTION OPPURE ATTRAVERSO UN INSTANZA DEL CONTAINER O HELPER APP()
 * Se gli oggetti da iniettare non necessitano di ricevere parametri nel loro costruttore il container si
 * arrangia a risolvere l'iniezione (AUTOWIRING) altrimenti specificare manualmente i parametri e lo si fa nel serviceProvider
 * istruendo laravel su quali parametri passare quando viene richiesta iniezione di un particolare oggetto. Questo ci da il vantaggio
 * di concetrare la logica in un punto solo. VEDERE SERVICE PROVIDER (register method).
 *
 * Il container è anche responsabile di istanziare ogni controller, middleware, queue jobs, event-listener e ogni altra classe generata
 * da Laravel nel ciclo di vita dell'applicazione quindi si preoccupa anche di gestire la dependency injection all'interno di ognuna di queste classe
 */

class Container extends Controller
{
    protected $mailer;

    /*
     * ESEMPIO DI DEPENDENCY INJECTION
     * Un istanza della classe Mailer Viene Iniettata all'interno del costruttore al momento in cui viene instanziata la classe
     *
     */
    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /*
     * MANUAL DEPENDENCYINJECTION EXAMPLE (BAD PRACTICE, FATTO SOLO PER ESEMPIO)
     * IL grosso problema di questo è che ogni volta che devo creare un istanza di UserMailer devo scrivere tutto questo codice
     */
    public function manualDependencyInjection()
    {
        /*
         * Preparo le istanze da iniettare
         */
        //$mailer = new MailgunMailer($mailgunKey, $mailgunSecret, $mailgunOption);
        //$logger = new Logger($loghPath, $minimunLogLevel);
        //$slack = new Slack($slackKey, $slackSecret, $channelName, $channelIcon);

        /*
         * Qui avviene la DependencyInjection manuale, inietto in UserMailer un'istanza di MailgunMailer
         */
        //$userMailer = new UserMailer($mailer, $logger, $slack);

        //$userMailer->welcome($user);
    }

    /*
     * CREAZIONE ISTANZE DI OGGETTI CON L'HELPER GLOBALE app() o con instanza del container
     */
    public function appGlobalHelper()
    {
        //dd(app(Logger::class));
        // OTTENERE ISTANZA DI OGGETTO MANUALMENTE (SCONSIGLIATO)
        //$logger = new Logger($loghPath, $minimunLogLevel);

        // OTTENERE ISTANZA DI OGGETTO CON GLOBAL APP() HELPER (VEDERE SERVICE PROVIDER COME PASSARE PARAMETRI AL COSTRUTTORE)
        //$this->app->make(Logger::class);          // Metodo 1 (se sono in un service provider o ho in tiro un instanza del container)
        //app(Logger::class);                       // Metodo 2 se non ho in tiro un instanza del container
        //$app = app(); $app->make(Logger::class);  // Metodo 3 se non ho in tiro un instanza del container
    }

    /*
     * CHIAMARE METODI DI CLASSE IN MANIERA PROGRAMMATICA
     * Ogni metodo di classe può essere invocato in maniera programmatica così:
     * App è un instanza del container
     * app()->call('App\Http\Controllers\Container@callMeByServiceProvider', ['Stringa da stampare']);
     */
    public function callMeByServiceProvider(string $string)
    {
        return $string;
    }

}
