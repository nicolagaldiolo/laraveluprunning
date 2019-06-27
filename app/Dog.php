<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dog extends Model
{
    protected $fillable = [
        'name', 'sex', 'color'
    ];

    /*
     * JSON SERIALIZATION
     * Quando viene tornato un json possiamo definire quali campi vogliamo mostrare settando la variabile $ visible (disponibile anche un $hidden per fare il contrario),
     * possiamo usare dei transformer per manipolare i dati (creando delle classi nostre oppure utilizzando librerie come factral: https://github.com/thephpleague/fractal)
     *
     */
    protected $visible = [
        'name', 'sex', 'color'
    ];
}
