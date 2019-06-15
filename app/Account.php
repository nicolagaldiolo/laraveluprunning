<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{

    protected $fillable = [
        'name',
        'email',
        'status'
    ];

    public function users()
    {
        // Laravel si aspetta che la tabella pivot si chiami account_user (nomi al singolare, in ordine alfabetico, separato da _)
        // Laravel si aspetta che la tabella pivot abbia le colonne account_id e user_id
        return $this->belongsToMany(User::class);
    }
}
