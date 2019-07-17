<?php

/*
 * HELPERS
 * https://laravel.com/docs/5.8/helpers
 */

/*
 * ARRAY
 */

Route::get('helpers/array', function(){

    // Arr::first | torna il primo elemento dell'array che matcha
    $array = [100, 200, 300];
    $first = Arr::first($array, function ($value, $key) {
        return $value >= 150;
    });

    // Arr::get | permette di accedere ad elementi innestati all'interno di array mutidimensionali con dot notation
    // non solleva errori in caso l'elemento non esiste
    $array = ['products' => ['desk' => ['price' => 100]]];
    $price = Arr::get($array, 'products.desk.price');

    // Arr::has | permette di verificare la presenza di elementi innestati all'interno di array mutidimensionali con dot notation
    $array = ['product' => ['name' => 'Desk', 'price' => 100]];
    $contains = Arr::has($array, 'product.name');
    $contains = Arr::has($array, ['product.price', 'product.name']);

    // Arr::pluck | ritorna un array con solo i valori di una determinata chiave
    $array = [
        ['developer' => ['id' => 1, 'name' => 'Taylor']],
        ['developer' => ['id' => 2, 'name' => 'Nicola']],
    ];

    $pluck = Arr::pluck($array, 'developer.name', 'developer.id');
    // il terzo parametro facoltativo identifica la chiave con valorizzare i valori, con terzo parametro array multidimensionale altrimenti piatto

    return [
        'Arr::first' => $first,
        'Arr::get' => $price,
        'Arr::has' => $contains,
        'Arr::pluck' => $pluck

    ];

});

/*
 * STRING
 */

Route::get('helpers/string', function(){

    // e() | alias di htmlentities
    $e = e('<html>foo</html>');

    //Str::start() | aggiunge un determinato carattere alla stringa se essa non inizia con quel carattere
    $start = Str::start("this/string", '/');

    //Str::startsWith() | aggiunge un determinato carattere alla stringa se essa non inizia con quel carattere
    $startsWith = Str::startsWith('This is my name', 'This');
    $contains = Str::contains('This is my name', 'my');
    $truncated = Str::limit('The quick brown fox jumps over the lazy dog', 20);
    $matches = Str::is('foo*', 'foobar');
    $matches = Str::is('baz*', 'foobar');
    $random = Str::random(40);
    $slug = Str::slug('Laravel 5 Framework', '-');

    return [
        'e()' => $e,
        'Str::start' => $start,
        'Str::startsWith' => $startsWith,
        'Str::contains' => $contains,
        'Str::limit' => $truncated,
        'Str::is' => $matches,
        'Str::random' => $random,
        'Str::slug' => $slug
    ];

});

/*
 * APPLICATION_PATH
 * se passo un parametro alle funzioni al path verrà aggiunto il path passato come parametro
 */
Route::get('helpers/path', function(){

    $app_path = app_path();
    $app_path = app_path('Http/Controllers/Controller.php');

    $base_path = base_path();
    $base_path = base_path('vendor/bin');

    $config_path = config_path();
    $config_path = config_path('app.php');

    $db_path = database_path();
    $db_path = database_path('factories/UserFactory.php');

    $storage_path = storage_path();
    $storage_path = storage_path('app/file.txt');

    return [
        'app_path()' => $app_path,
        'base_path()' => $base_path,
        'config_path()' => $config_path,
        'db_path()' => $db_path,
        'storage_path()' => $storage_path
    ];
});

/*
 * URL
 */

Route::get('helpers/url', function(){

    // ACTION | la rotta o l'url può variare ma il metodo del controller deve essere la costante
    // affinchè funzioni una rotta deve essere definita, attualmente funziona perchè una rotta sta puntando a questo metodo di controller
    // non importa quale sia la rotta. l'importante è che sia definita
    $action = action('HomeController@index', '', true);
    $action = action('TasksController@show', ['id' => 1], true);
    $action = action('TasksController@show', [1], true);
    $action = action([\App\Http\Controllers\HomeController::class, 'index'], ['id' => 1], true);

    // ROUTE | la rotta è costante, ciò che può variare è il metodo del controller invocato
    //$route = ""; route('pippo/param', ['param' => 1]);
    //$route = route('tasks.show', ['id' => 1]);
    //return [
    //    'action()' => $action,
    //    'route()' => $route,
    //];
});