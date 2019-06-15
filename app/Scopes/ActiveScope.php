<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

/*
 * GLOBAL SCOPE
 * Definisco una classe di Scope globale, così che possa essere utilizzata trasversalmente su vari modelli
 */

class ActiveScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        return $builder->whereActive(true);
    }
}