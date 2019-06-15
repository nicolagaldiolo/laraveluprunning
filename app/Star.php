<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Star extends Model
{

    protected $fillable = [
        'user_id'
    ];

    public function starrable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
