<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Conference extends Model
{

    /*
     * AGGIORNARE UPDATED_AT DEL PADRE SULL'AUPDATE DEL FIGLIO
     * è possibile aggiornare il timestamp di una relazione padre all'aggiornamento del figlio.
     * funziona anche con mass-assignment
     */
    protected $touches = ['user'];

    protected $fillable = [
        'name'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
