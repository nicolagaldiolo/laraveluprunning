<?php

/*
 * Laravel Scout - Full Text Search
 * Laravel Scout fa da Gateway tra Laravel e i servizi di Indicizzazione Algolia, ElasticSearch per ricerche full-text Search
 * Quando viene lanciata la richiesta viene interrogato il servizio, vengono tornati una serie di ids e poi viene interrogato il db
 * "locale" per l'estrazione dei dati
 */

Route::get('scout', function(){

    // Viene interrogato il servizio esterno per estrarre i dati
    $data = \App\Review::search('Quos deserunt vitae')->get();

    // Posso usare alcuni dei metodi accessibili per eloquent come where() e paginate()
    // non essendo db relazionale la clausula where è limitata: https://laravel.com/docs/5.8/scout#where-clauses
    //$data = \App\Review::search('Quos deserunt vitae')->where('user_id', 140)->paginate(2);
    $data = \App\Review::search('Cumque')->paginate(2);

    return $data;

});

Route::get('scout_without_indexing', function(){

    // se per qualche motivo ha necessità di effettuare delle azioni come creare dei record senza che questi vengano indicizzati
    \App\Review::withoutSyncingToSearch(function (){
        factory(\App\Review::class, 10)->create();
    });

    factory(\App\Review::class, 10)->create();

});

Route::get('scout_index_data', function(){
    // di default viene indicizzato tutto ciò che viene creato/modificato/cancellato grazie agli eventi che vengono lanciati sul modello
    // possiamo comuqnue lanciare la sincronizzazione a mano via codice o CLI

    // INDICIZZAZIONE VIA CODICE
        // Viene lanciata l'indicizzazione di tutto ciò che viene tornato dalla query. Essendo un oggetto eloquent possiamo decidere cosa indicizzare
        // filtrando la query a nostro piacimento anche se Scout sa distingue i nuovi record da quelli già presenti quindi possiamo
        // tranquillamente re-indicizzare tutto
        \App\Review::all()->searchable();

        // possiamo anche lanciare l'indicizzazione su una relazione
        //$user->reviews()->searchable();

    // INDICIZZAZIONE VIA CLI
        // prende tutti i dati del modello e li reindicizza tutti
        //php artisan scout:import App\\Review

    // DE-INDICIZZARE DEI DATI
        // Possiamo anche rimuovere l'indicizzazione dei dati
        \App\Review::all()->unsearchable();
});
