<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Friend;
use App\User;
use Faker\Generator as Faker;

$gender = ['male', 'female'];

$factory->define(Friend::class, function (Faker $faker) use($gender){
    return [
        'name' => $faker->name,
        'sex' => $gender[array_rand($gender)],
        'user_id' => function() {
            return factory(App\User::class)->create();
        }
    ];
});
