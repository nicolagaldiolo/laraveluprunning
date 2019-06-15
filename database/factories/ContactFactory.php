<?php

use Faker\Generator as Faker;

$factory->define(App\Contact::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'vips' => true,
        'status' => 1,
        'active' => 1,
        'options' => ['isAdmin' => true],
        'orders' => 4
    ];
});

// DEFINIZIONE DI UNA FACTORY CHE NE ESTENDE UN ALTRA
$factory->defineAs(App\Contact::class, 'inactive', function (Faker $faker) use($factory){
    $contact = $factory->raw(App\Contact::class);
    return array_merge($contact, ['active' => 0]);
});
