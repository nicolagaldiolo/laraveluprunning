<?php


namespace App\CustomAPITransformer;

/*
 * JSON SERIALIZATION
 * Quando viene tornato un json possiamo definire quali campi vogliamo mostrare settando la variabile $ visible (disponibile anche un $hidden per fare il contrario) nel modello,
 * possiamo usare dei transformer per manipolare i dati (creando delle classi nostre oppure utilizzando librerie come factral: https://github.com/thephpleague/fractal)
 *
 */

use App\User;

class UserTransformer
{
    protected $user;
    protected $embed;

    public function __construct(User $user, array $embed)
    {
        $this->user = $user;
        $this->embed = $embed;
    }

    public function toArray()
    {

        $appends = [];

        if(in_array('friends', $this->embed)){
            // QUI POTREI FARE UN ALTRO TRANSFORMER PER FRIEND
            $appends['friends'] = $this->user->friends;
            $appends['friendsCount'] = $this->user->friends->count();
        }

        return array_merge([
            'id' => $this->user->id,
            'name' => sprintf("%s %s", $this->user->name, $this->user->email),
        ], $appends);
    }

    public function toJson()
    {
        return json_encode($this->toArray());
    }

    public function __toString()
    {
        return $this->toJson();
    }
}