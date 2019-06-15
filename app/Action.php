<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Action extends Model
{

    // Le proprietà $fillable & $guarded lavorano solo sul massiveAssignement
    protected $fillable = [
        'title',
        'body'
    ];

    /*protected $guarded = [
        'title',
        'body'
    ];*/
}
