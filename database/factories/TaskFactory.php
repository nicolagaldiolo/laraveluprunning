<?php

use Faker\Generator as Faker;

$factory->define(App\Task::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence,
        'description' => $faker->paragraph,
        'options' => json_encode(['isAdmin' => true]),
        'user_id' => function() {
            return factory(App\User::class)->create();
        }
    ];
});
