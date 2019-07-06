<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Review extends Model
{
    use Searchable;
    // con il trait Searchable scout si iscrive agli eventi create/delete/update e ai cambiamenti del modello spara i dati
    // al servizio di indicizzazione (Algolia, Elastic Search) e lo può fare in maniera sincrona o attraverso una coda.


    // definisco il nome del modello che apparirà sul search engine, altrimenti viene passato il nome della tabella
    public function searchableAs()
    {
        return 'revisioni';
    }

    // Filtro i dati da passare per l'indicizzazione, altrimenti passerà tutti i campi
    public function toSearchableArray()
    {
        $array = $this->toArray();
        return array_filter($array, function ($k){
            return $k == 'text';
        }, ARRAY_FILTER_USE_KEY);
    }
}
