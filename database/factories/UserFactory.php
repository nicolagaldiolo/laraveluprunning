<?php

use App\User;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token' => Str::random(10),

        //'api_token' => Str::random(60)

        // aggiungo il campo api_token per l'autenticazione con token "semplice",
        // dal momento però che abbiamo settato che il token viene "hashato" in config/auth (scelta facoltativa),
        // non serve più generarlo al momento della registrazione ma abbiamo bisogno che il token passi dalla funzione hash,
        // quindi occorre creare una rotta che prenda il token, lo passi dalla funzione hash e lo torni come json http://laraveluprunning.test/get_my_api_token
    ];
});
