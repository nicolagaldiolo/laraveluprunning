<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Dog;
use Faker\Generator as Faker;

$factory->define(Dog::class, function (Faker $faker) {
    return [
        'name' => $faker->name
    ];
});
