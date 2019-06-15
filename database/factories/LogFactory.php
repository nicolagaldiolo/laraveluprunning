<?php

use Faker\Generator as Faker;

$factory->define(App\Log::class, function (Faker $faker) {
    return [
        'message' => $faker->sentence
    ];
});
