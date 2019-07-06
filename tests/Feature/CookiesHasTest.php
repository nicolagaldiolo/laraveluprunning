<?php

namespace Tests\Feature;

use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use phpDocumentor\Reflection\Types\Object_;
use Tests\TestCase;

class CookiesHasTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */


    /*
     * TEST 1 (test di iniezioone)
     * Iniettiamo un cookie nella richiesta, la rotta dell'applicazione non avrebbe quel cookie
     */

    /*
     * DISABILITARE CRIPTAZIONE DEL COOKIE
     * Laravel rimuove tutti i cookie il cui valore non è criptato.
     * Nei test per lavorare con i cookie occorre istruire laravel di non criptare il cookie che stiamo testando,
     * in questo modo il cookie viene tramesso in chiaro
     */

    public function test_cookie_crypted_via_middleware()
    {

        $this->app->resolving(EncryptCookies::class, function ($object) {
            $object->disableFor('cookie_test');
        });

        $cookies = ['cookie_test' => 'cookie_value'];

        $this->call('get', 'cookie', [], $cookies);
        $this->assertEquals('cookie_value', Cookie::get('cookie_test'));

    }


    /*
     * CRIPTAZIONE DEL VALORE COOKIE
     * Se non disabilitiamo il cookie allora dobbiamo criptare il valore che passiamo.
     * NB: quando tentiamo di leggere il valore del cookie es: Request::cookie('cookie_test_no_cripted') il valore ci
     * viene passato in chiaro
     */
    public function test_cookie_crypted_only_value()
    {
        $encrypredValue = app(\Illuminate\Contracts\Encryption\Encrypter::class)->encrypt('cookie_value');
        $cookies = ['cookie_test_no_cripted' => $encrypredValue];
        $this->call('get', 'cookie_not_crypted', [], $cookies)
            ->assertSee('cookie_value');
    }

    /*
     * TEST 2 (test naturale)
     * Non iniettiamo il cookie nella richiesta, l'inserimento del cookie lo fa già la rotta,
     * testiamo che il cookie ci sia nella response
     */
    public function test_cookie_response()
    {
        $this->call('get', 'cookie')
            ->assertCookie('dismissed-popup')
            ->assertCookieNotExpired('dismissed-popup')
            ->assertCookie('saw-dashboard')
            ->assertCookieNotExpired('saw-dashboard');
    }
}
