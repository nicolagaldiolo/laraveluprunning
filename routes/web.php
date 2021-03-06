<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use GuzzleHttp\Client;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/oauth2', function(){
  return view('oauth2');
})->middleware('auth');

Route::get('blade-example', function () {
    $tasks = \App\Task::all();
    return view('bladeExample', compact('tasks'));
});

// RESOURCE CONTROLLER BINDING
// Laravel associa in atuomatica tutte le rotte disponibili per questa risorsa (REST) con i metodi del controller e assegnerà anche i nomi appropriati
Route::resource('tasks', 'TasksController');
Route::resource('contact', 'ContactController');

Route::get('pippo/{param}', function ($param) {
    dd($param);
});

Route::get('get', function () {})->name('get'); // accessibile con verbo GET
Route::post('post', function () {}); // accessibile con verbo POST
Route::put('put', function () {}); // accessibile con verbo PUT
Route::delete('delete', function () {}); // accessibile con verbo DELETE
Route::any('any', function () {}); // accessibile con QUALSIASI verbo
Route::match(['get', 'post'], 'match', function () {}); // accessibile con verbi in array


// CHIAMARE LE ROTTE

// posso usare l'helper url() oppure posso dare un nome alla rotta e chiamarla attraverso il nome, non l'url
//dd(url('get')); //url('get') è un helper che stampa il path completo della rotta
//dd(route('get'))



// PASSARE PARAMETRI ALLE ROTTE

//route('users.comments.show', [1,2]);
//route('users.comments.show', ['userId' => 1, 'commentId' => 2]);
//route('users.comments.show', ['commentId' => 2, 'userId' => 1]);
// http://myapp.com/users/1/comments/2

//route('users.comments.show', ['userId' => 1, 'commentId' => 2, 'opt' => 'a']);
// http://myapp.com/users/1/comments/2?opt=a // tutti i parametri che non matchano vengono aggiunti al querystring


// PATH PREFIXES
Route::group(['prefix' => 'api'], function(){
    Route::get('/', function(){
       // handles the path /api
    });
    Route::get('users', function(){
        // handles the path /api/users
        return \App\User::all();
    });
    Route::get('no_json', function(){
        // handles the path /api/users
        return "string";
    });
});


//SUBDOMAINS ROUTING

// utile per presentare una defferenti sezioni di un applicazione
Route::group(['domain' => 'api.myapp.com'], function(){
    Route::get('/', function(){
        //
    });
});

// utile per parametrizzare il sottodominio come parametro (es: slack)
Route::group(['domain' => '{account}.myapp.com'], function(){
    Route::get('/', function($account){

    });
    Route::get('users/{$id}', function($account, $id){
      //
    });
});


// NAMESPACE PREFIXES
// se uso il raggruppamento per namespace posso evitare di dover specificare il namespace completo
// il risultato è lo stesso

Route::get('api2/', 'API\ControllerB@index'); // rotta con namespace completo
Route::group(['namespace' => 'API'], function(){ //rotta con namespace dichiarato a livello di gruppo
    // App\http\Controllers\API\ControllerB
    Route::get('api/', 'ControllerB@index');
});


// NAME PREFIXES
// anche i nomi delle rotte posso essere prefissati

Route::get('users/comments/{param}', function () {})->name('users.comments.show'); // esempio standard
Route::group(['as' => 'users.', 'prefix' => 'users'], function(){ // esempio con name prefix
    Route::group(['as' => 'comments.', 'prefix' => 'comments'], function(){
        Route::get('{id}', function () {} )->name('show');
    });
});



// ROUTE MODEL BINDING

// IMPLICIT MODEL BINDING
// se il naming del parametro della rotta e quello della variabile passata alla clousure/controller corrispondono
// è possibile fare il model binding così che mi venga tornato l'oggetto e non sono il parametro passato, es: id
Route::get('conferences/{conference}', function(\App\Conference $conference){
    dd($conference);
})->name('conferences.show');

// EXPLICIT MODEL BINDING
// posso anche dichiarare nel Route ServiceProvider (Route::model('event', Conference::class)) che ogni qualvota venga
// passato un determinare parametro (es: event) venga automaticamente fatto il model binding con una modello in particolare
// quindi nella clousure/controller non serve fare il model binding
Route::get('events/{event}', function($event){
    dd($event);
});


// ROUTE CACHING
// se si sta usando rotte controller e resource quindi non clousure è possibile fare cache con le rotte, questo riduce
// drasticamente i tempi di risoluzione delle rotte in quanto laravel serializzerà tutti i files route e renderà
// l'applicazione molto più veloce

// per abilitare la cache: php artisan route:cache
// per cancellare la cache: php artisan route:clear
// nb: da usare solo in produzione altrimenti occorre cancellare la cache ad ogni cambiamento del route


// ABORTING
Route::get('aborting', function(\Illuminate\Http\Request $request){
    abort(403, 'You cannot do that!');
    //abort_unless($request->has('magic_token'), 403, 'abort_unless');
    //abort_if($request->user()->isBanned, 403, 'abort_if');
    dd('La richiesta è andata a buon fine');
});


// Oauth2 Tokens from laravel session authentication (synchronizer tokens)
// per gli altri metodi di autenticazione Oauth vedere rotte API
require __DIR__ . '/partials/ouath2/synchronizer-tokens.php';


/*
 * SIMPLE API AUTH WITH BUILT-IN TOKEN
 * Laravel fornisce un modo built-in per ottenere un token per ogni utente
 * Il token risiede come plain-text salvato a db, e dal momento però che abbiamo settato che il token viene "hashato"
 * in config/auth (scelta facoltativa), non serve più generarlo al momento della registrazione ma abbiamo bisogno che
 * il token passi dalla funzione hash, quindi occorre creare una rotta che prenda il token, lo passi dalla funzione hash
 * e lo torni come json
 */

Route::get('get_my_api_token', function(\Illuminate\Http\Request $request){
    $token = \Illuminate\Support\Str::random(60);

    $request->user()->forceFill([
        'api_token' =>hash('sha256', $token)
    ])->save();

    return ['token' => $token];
})->middleware('auth');


require __DIR__ . '/partials/artisan.php';

require __DIR__ . '/partials/authentication.php';

require __DIR__ . '/partials/cache.php';

require __DIR__ . '/partials/container.php';

require __DIR__ . '/partials/cookie.php';

require __DIR__ . '/partials/eloquent.php';

require __DIR__ . '/partials/eloquentRelations.php';

require __DIR__ . '/partials/emails.php';

require __DIR__ . '/partials/errors.php';

require __DIR__ . '/partials/events.php';

require __DIR__ . '/partials/facade.php';

require __DIR__ . '/partials/gatePolicies.php';

require __DIR__ . '/partials/helpers.php';

require __DIR__ . '/partials/middleware.php';

require __DIR__ . '/partials/notifications.php';

require __DIR__ . '/partials/paginations.php';

require __DIR__ . '/partials/queue.php';

require __DIR__ . '/partials/redirect.php';

require __DIR__ . '/partials/request.php';

require __DIR__ . '/partials/response.php';

require __DIR__ . '/partials/scout.php';

require __DIR__ . '/partials/session.php';

require __DIR__ . '/partials/storage_filesystem.php';

require __DIR__ . '/partials/validation.php';