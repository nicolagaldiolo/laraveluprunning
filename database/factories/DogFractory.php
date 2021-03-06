<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Dog;
use Faker\Generator as Faker;

$gender = ['male', 'female'];
$color = ['brown','black','white'];

$factory->define(Dog::class, function (Faker $faker) use($gender, $color){
    return [
        'name' => $faker->name,
        'sex' => $gender[array_rand($gender)],
        'color' => $color[array_rand($color)]
    ];
});
