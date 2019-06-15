<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['title', 'description'];

    //protected $casts = [
    //    'options' => 'array'
    //];

    public function actions()
    {
        return $this->hasMany(Action::class);
    }
}
