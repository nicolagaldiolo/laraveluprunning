<?php

use Faker\Generator as Faker;

// DEFINIZIONE DI UNA FACTORY
$factory->define(App\Task::class, function (Faker $faker) {
    return [
        'title' => $faker->title,
        'options' => json_encode(['isAdmin' => true])
    ];
});

// DEFINIZIONE DI UNA FACTORY CHE NE ESTENDE UN ALTRA
$factory->defineAs(App\Task::class, 'task_with_desc', function (Faker $faker) use($factory){

    $task = $factory->raw(App\Task::class);
    return array_merge($task, ['description' => $faker->text]);
});
