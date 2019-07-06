<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Assignment extends Mailable
{
    // I traits Queueable, SerializesModels servono per utilizzare le code e per serializzare ogni modello che viene
    // passato al costruttore
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $trainee;
    public $txt;

    public function __construct($trainee)
    {
        $this->trainee = $trainee;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        // la View può accedere a tutte le variabili pubbliche contenute in questa classe + alle variabili include
        // nell'array che viene passato al metodo with()

        /*
         * EMAIL TEMPLATES (view,text,markdown)
         * Ogni email può essere inviata con template differenti usando i respettivi metodi: view() text() markdown()
         * Il fatto di usare markdown è solo un modo come un altro (sembrerebbe che con markdown hai dei template messi a disposizione da laravel)
         */

        /*
         * METODI DISPONIBILI
         * ->from()
         * ->subject()
         * ->priority() - Impostare una priorità di invio (1 alta - 5 bassa)
         * ->withSwiftMessage() - accetta una callback che mi permette di modificare il messaggio/aggiungere info prima
         * che venga consegnato
         */

        /*
         * ALLEGARE FILE (posso allegare file anche dentro la view come inline image (sia come attach che attachData))
         * Si possono allegare file o crearli runtime
         * ->attach() - possiblità di allegare un file
         * ->attachData() - allegare un file da una stringa
         */

        return $this->subject('New assignment For ' . $this->trainee->name)
            ->attach(
                storage_path('app/public/images/customName.png'),
                ['mime' => 'image/png', 'as' => 'file.png'] // optional
            )
            //->attachData(file_get_contents(storage_path('app/public/file.txt')), 'file.txt', ['mime' => 'text/plain'])
            ->attachData(
                "Ciao, bella ciao",
                'file.txt',
                ['mime' => 'text/plain'] // optional
            )
            ->view('emails.assignment')
            ->withSwiftMessage(function ($swift){
                $swift->setReplyTo('email@email.com');
            })
            ->with(['event' => "Nome dell'evento"]);
    }
}
