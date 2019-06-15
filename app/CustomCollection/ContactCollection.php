<?php


namespace App\CustomCollection;

use Illuminate\Database\Eloquent\Collection;

/*
 * ELOQUENT COLLECTION
 * Ogni modello di default utilizza la classe Illuminate\Database\Eloquent\Collection per tornare un result Sets
 * Se abbiamo necessità di aggiungere funzionalità all'oggetto collection possiamo creare una classe collection custom
 * che estende quella di default e informare il modello quale classe dovrà usare utilizzando il metodo new Collection
 *
 */

class ContactCollection extends Collection
{

    public function subOrderTotal() // aggiungiamo il metodo per la contactCollection
    {
        return $this->reduce(function($orders, $item){
            return $orders + ($item->orders) ?: 0;
        }, 0);
    }
}