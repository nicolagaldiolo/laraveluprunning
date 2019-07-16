<?php

namespace App\Jobs;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Log\Logger;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

/*
 * JOB
 * il costruttore mi permette di aggiungere dati al job (Dati su cui lavorare)
 * handle() è dove risiede la logica e dove vengono iniettate le dipendenze (logica)
 *
 * o traits e interfacce aggiungono alla classe l'abilità di lavorare con le code
 * Queueable - specifica come l'app deve accodare i job
 * InteractsWithQueue - permette a ogni job di controllare la relazione con la coda, la cancellazione e la risottomissione sulla stessa in caso di task non completatto/fallito
 * SerializesModels - permette ad ogni job di serializzare i modelli utilizzati
 */

class CrunchReports implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    public $user;



    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Logger $logger)
    {
        // Rimettere in coda/eliminare un job manualmente
        // in certi casi si può creare la circostanza che il job venga rimesso in coda o eliminato
        $someCondition = false;
        if($someCondition){ $this->release(10); } // rimetto in coda il job
        if($someCondition){ return; } // istruisco laravel che il job è stato completato e implicitamente lo cancello

        $logger->info("Tentativi di esecuzione job: " . $this->attempts()); // ottengo il numero di tentativi che ha raggiunto questo job
        $logger->info("Job per l'utente: " . $this->user->name);
    }

    /*
     * JOB FALLITI
     * se il job fallisce posso dichiarare per singolo job quanto aspettare prima di ritentare
     * (lo posso definire globalmente per tutti i job quando lancio la coda con la proprietà --delay)
     */
        //public $retryAfter = 3;

    public function failed(Exception $exception)
    {
        // LAVORO A LIVELLO ATOMICO
        // Quando un job fallisce viene lanciato questo metodo al quale viene passata l'eccezzione lanciata
        // e posso avvisare lo user o effettuare qualche operazione di rollback

        // SE VOGLIO LAVORARE A LIVELLO GLOBALE POSSO METTERE UN LISTENER IN APPSERVICEPROVIDER es per notificare il team
        // dei job falliti
    }
}
