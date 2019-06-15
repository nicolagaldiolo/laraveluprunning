<?php

namespace Tests\Feature;

use App\User;
use Faker\Factory as Faker;
use Faker\Factory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class TestVisitingRouteTest extends DuskTestCase
{
    /*
     * METODI PER NAVIGARE LE ROTTE (GENERARE REQUEST)
     * Quando usiamo metodi di questo tipo viene creata una richiesta http(quindi viene generato un oggetto request e un oggetto response
     * che laravel ci mette a disposizione) e di fatto è come se chiamassimo una rotta tramite browser.
     *
     * METODI PRINCIPALI:
     *                      $browser->visit() - crea un crawler | necessita di DUSK.
     *                      $this->call() - ci permettono di fare richieste http passando come primo parametro il verso http

     * ALTRI METODI CHE WRAPPANO call():
     *                      $this->get() | ->post() | ->put() | ->delete() | ->patch() - sono dei wrapper di call, che setta no un automatico il metodo
     *                      $this->json() - è anche questo un wrap di call e serve per fare richieste http passando un json, infatto converte un json i parametri passati e imposta gli header adatti per fare una richiesta passando json
     *
     */
    public function testExampleRequest()
    {
        $response = $this->get("register");
        $response->assertStatus(200);
    }

    /*
     * METODI PER LAVORARE SULL'OGGETTO RESPONSE (ASSERT...)
     *
     * https://laravel.com/docs/5.8/http-tests#available-assertions | HTTP
     * https://laravel.com/docs/5.8/dusk#available-assertions | BROWSER
     *
     * $this->followRedirects() - permette di seguire un redirect e ricevere la response della pagina di atterraggio
     * $this->assertStatus(200) - verifica che la pagine viene correttamente caricata e torna un codice 200
     * $this->assertSee() | ->assertDontSee() - verifica che la stringa o regex passata sia o non sia presente nella pagina
     * $browser->assertSeeLink() | ->assertDontSeeLink() - come see()
     * $this->assertHeader() | ->assertHeaderMissing - verifica che la response contenga un determinato header
     * $this->assertCookie() | ->assertCookieMissing - verifica che la response contenga un determinato cookie
     * $browser->assertInputValue() | ->assertInputValueIsNot() - un determinato campo contiene un determinato valore
     * $browser->assertChecked() | ->assertNotChecked() - un determinato campo è checkato
     * $browser->assertSelected() | assertNotSelected() - un determinato campo è selezionato
     * $browser->assertPathIs() | assertPathIsNot() - Assert that the current path matches the given path
     * $this->assertSeeInDatabase() - assertDontSeeInDatabase() Assert that the current path matches the given path
     */


    public function testExampleRouteVisit()
    {

        $user = factory(User::class)->create([
            'email' => 'taylor@laravel20.com',
        ]);

        $this->browse(function ($browser) use($user) {
            $browser->visit('/login')->assertSee("Forgot Your Password?")
                ->type('email', $user->email)
                ->type('password', 'password')
                ->press('Login');

            //$browser->pause(3000);

            //$browser->assertPathIs('/home'); // DA CAPIRE PERCHé NON VA
        });

    }

    public function testExampleResponseDatabases()
    {

        $faker = Faker::create();

        $dataToVerify = [
            'name' => $faker->name,
            'email' => $faker->email,
        ];
        $otherData = [
            'password' => 'password',
            'password_confirmation' => 'password'
        ];

        $this->post('/register', array_merge($dataToVerify, $otherData));

        $this->assertDatabaseHas('users', $dataToVerify);

    }

}
