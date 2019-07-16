<?php

/*
 * QUEUE
 * Per lanciare l’elaborazione dei job dalla coda:
 * php artisan queue:work
 *
 * // Opzioni
    php artisan queue:work redid --timeout=60 --sleep=15 --tries=3 --queue=high,medium

	redis 						| definisco la connessione
	--timeout=60 				| il tempo massimo di esecuzione di un job prima del suo timeout
	--sleep=15					| il tempo in cui la coda dovrebbe "dormire" quando non c'è nulla da 								fare, prima di risvegliarsi
	--tries=3 					| tentativi che dovrebbe fare un job prima di essere considerato 								fallito (molto importante definire un numero massimo di tentativi)
	--queue=high,medium		    | definisco le cose da ascoltare e la priorità con cui eseguirle
	--delay=3					| definisco quanto secondi aspettare prima di riprovare un job fallito 								(in alternativa può essere specificato per singolo job)
 */


/*
 *  JOBS
 */

Route::get('jobs', function(){

    $user = \App\User::first();

    //DISPACCIARE UN EVENTO (2 metodi):

    // 1 - Se in un controller (grazie al DispatchesJobs traits sul controller base)
    //$this->dispatch(new \App\Jobs\CrunchReports($user));

    // 2 - In qualsiasi altro punto
    dispatch(new \App\Jobs\CrunchReports($user));

    //OPZIONI DI DISPATCH (connessione, queue, delay):
    dispatch(new \App\Jobs\CrunchReports($user))
        ->onConnection('redis') // utile in caso di connessione multiple, altrimenti default
        ->onQueue('high') // utile in caso di queue multiple (es: high, low), altrimenti default
        ->delay(20); // utile in caso vogliamo aggiungere un ritardo (in secondi). NB: AmazonSQS non permette delay > 15m

    return "Jobs dispacciati";
});


/*
 * JOBS FALLITI
 * I job falliti risiedono su una tabella del db (config.queue.failed) e la tabella necessita di essere creata:
 * php artisan queue:failed-table
 * php artisan migrate
 *
 * è possibile eseguire delle azioni per ogni job fallito o a livello atomico all'interno del job o globale inserendo un listener nell'app service provider
 *
 * I job falliti possono essere gestiti da artisan:
 * php artisan queue:failed // torna elenco job falliti
 * php artisan queue:retry 9 // rilancia job fallito con id passato
 * php artisan queue:retry all // rilancia tutti i job falliti
 * php artisan queue:forget 5 // elimina il job con i 5
 * php artisan queue:flush // elimina tutti i job
 */