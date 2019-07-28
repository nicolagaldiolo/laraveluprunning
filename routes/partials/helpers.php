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

    // ROUTE | la rotta è costante, ciò che può variare è il metodo del controller invocato, contrario di action()
    $route = route('tasks.show', ['id' => 1]);

    // URL
    $url = url('tasks/3'); //"http://laraveluprunning.test/tasks/3",
    $secure_url = secure_url('tasks/3'); //"https://laraveluprunning.test/tasks/3",

    // senza parametri torna info in base all'url corrente
    $current = url()->current(); //senza querystring
    $full = url()->full(); //con querystring
    $previous = url()->previous();

    return [
        'action()' => $action,
        'route()' => $route,
        'url()' => $url,
        'secure_url()' => $secure_url,
        'current()' => $current,
        'full()' => $full,
        'previous()' => $previous
    ];

});


/*
 * MISC
 */

Route::get('helpers/misc', function(\Illuminate\Support\Facades\Request $request){

    //abort - viene sollevata un eccezzione http
    //abort(403, 'You shall not pass'); // viene sollevata l'eccezzione
    //abort_unless($request->has('magic_token'), 403, 'You shall not pass'); // viene sollevata l'eccezzione solo se la condizione è falsa
    //abort_unless(User::find(1)->isBanned(), 403); // viene sollevata l'eccezzione solo se la condizione è vera

    //auth - come la facade torna info sull'utente loggato
    $user = auth()->user();
    $auth_check = auth()->check();

    if(false){
        return back(); // fa un redirect al referrer
        return redirect('tasks'); // fa un redirect al path passato
    }

    return [
        'auth()->user()' => $user,
        'auth()->check()' => $auth_check
    ];

});


/*
 * COLLECTION
 */

Route::get('helpers/collection', function(){

    // con i metodi classici array
    $users = [
        ['name' => 'Nicola', 'lastname' => 'Galdiolo', 'status' => 'admin'],
        ['name' => 'Chloe', 'lastname' => 'Galdiolo', 'status' => 'user'],
        ['name' => 'Erica', 'lastname' => 'Frigo', 'status' => 'admin'],
    ];

    $admins = array_map(function($user){
        $user['name'] = $user['name'] . ' ' . $user['lastname'];
        return $user;
    }, array_filter($users, function($user){
        return $user['status'] == 'admin';
    }));

    // con i le collection
    $admins_collection = collect([
        ['name' => 'Nicola', 'lastname' => 'Galdiolo', 'status' => 'admin'],
        ['name' => 'Chloe', 'lastname' => 'Galdiolo', 'status' => 'user'],
        ['name' => 'Erica', 'lastname' => 'Frigo', 'status' => 'admin'],
    ])->filter(function($user){
        return $user['status'] == 'admin';
    })->map(function($user){
        $user['name'] = $user['name'] . ' ' . $user['lastname'];
        return $user;
    });


    // METODI DELLE COLLECTION
    // https://laravel.com/docs/5.8/eloquent-collections
    $users = \App\User::take(6)->with('tasks')->get();

    // all() - toArray()
    // la differenza tra all() e toArray() è che toArray() converte in array anche tutti gli oggetti eloquent contenuti nella collaction (collection compresa) mentre all NON converte gli oggetti eloquent in essa contenuto.
    // all() - array di oggetti eloquent
    // toArray() - array di array
    //
    //ps: se torniamo un json entrambi i metodi tornano comunque un array di json perchè viene implicitamente chiamato il metodo to array, mentre viene mantenuto il comportamento se passiamo la collection alla view ad esempio

    // filter() (where() alias di filter()) - reject()
    // accettano una clousure, si girano la collection, se la clousure torna true l'elemento viene tornato altrimenti scartato. Per reject() avviene il contrario

    // first() - last()
    // torna il primo o ultimo elemento da una collection (se passata una closure cone prima parametro come farmi tornare il primo o ultimo che rispetta la condizione)
    $user_first = \App\User::all()->first(function($user){
        return $user->name == \Illuminate\Support\Str::startsWith($user->name, 'Moshe');
    }, 'default value');

    // chunk() raggruppa i risultati in sottoarray, dove in ogni array c'è un numero di elementi pari al parametro passato a chunk
    // take() dalla collection fornita prende semplicimente un numero di elementi pari al parametro passato
    $user_chunk = $users->chunk(3);

    // group_by - mi permette di raggruppare un array tramite un determinato valore che verrà usato come chiave
    // posso passare una stringa che indica il valore di raggruppamento o una callback per farci ulteriore logica
    $group_by = collect([
        ['account_id' => 'account-x10', 'product' => 'Chair'],
        ['account_id' => 'account-x10', 'product' => 'Bookcase'],
        ['account_id' => 'account-x11', 'product' => 'Desk'],
    ]);
    $group_by_simple = $group_by->groupBy('account_id');
    $group_by_callback = $group_by->groupBy(function ($item, $key) {
        if(substr($item['account_id'], -3) == 'x10'){
            return 'new_key_10';
        }
        if(substr($item['account_id'], -3) == 'x11'){
            return 'new_key_11';
        }
    });
    // alla group by passo un array contenente i criteri con cui raggruppare ogni livello
    $group_by_multi = collect([
        10 => ['user' => 1, 'skill' => 1, 'roles' => ['Role_1', 'Role_3']],
        20 => ['user' => 2, 'skill' => 1, 'roles' => ['Role_1', 'Role_2']],
        30 => ['user' => 3, 'skill' => 2, 'roles' => ['Role_1']],
        40 => ['user' => 4, 'skill' => 2, 'roles' => ['Role_2']],
    ])->groupBy(['skill', function($item){
        return $item['roles'];
    }], true);

    dd($group_by_multi);

    $numbers = collect([1,2,3]);
    // reverse() - inverte l'ordine di una collection
    // shuffle() - randomizza l'ordine di una collection

    // sort() ordina array di stringhe e numeri con un ordinamento crescente -
    // sortBy() - ordina array mutlidimensionali tramite specifica chiave o risultato callback
    // sortByDesc() - come sortBy ma con ordine inverso
    // NB: l'ordinamento mantiene le chiavi originali quindi utilizzare il metodo value() per resettarle
    $sort = collect([5, 3, 1, 2, 4]);

    $sort_by_price = collect([
        ['name' => 'Desk', 'price' => 200],
        ['name' => 'Chair', 'price' => 100],
        ['name' => 'Bookcase', 'price' => 150],
    ]);

    $sort_by_callback = collect([
        ['name' => 'Desk', 'colors' => ['Black', 'Mahogany']],
        ['name' => 'Chair', 'colors' => ['Black']],
        ['name' => 'Bookcase', 'colors' => ['Red', 'Beige', 'Brown']],
    ]);

    // avg() - sum() - si comportano con lo stesso criterio applicando la logica rispettiva
    $avg = collect([1, 2, 3, 4, 5])->avg();
    $avg_multi = collect([['foo' => 10], ['foo' => 10], ['foo' => 20], ['foo' => 40]])->avg('foo');

    return [
        'array_method' => $admins,
        'collection_method' => $admins_collection,
        'all()' => $users->all(),
        'toArray()' => $users->toArray(),
        'first()' => $user_first,
        'chunk()' => $user_chunk,
        'group_by()' => $group_by_simple,
        'group_by(callback)' => $group_by_callback,
        'group_by(multi)' => $group_by_multi,
        'reverse()' => $numbers->reverse(),
        'sort()' => $sort->sort()->values(),
        'sortBy()' => $sort_by_price->sortBy('price')->values(),
        'sortBy(callback)' => $sort_by_callback->sortBy(function($product){
            return count($product['colors']);
        })->values(),
        'avg()' => $avg,
        'avg_multi()' => $avg_multi,
    ];



});