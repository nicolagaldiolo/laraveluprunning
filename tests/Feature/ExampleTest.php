<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/*
 * DEFINIZIONE DEI METODI
 * Perchè i nostri metodi vengano automaticamente lanciati devono essere definiti con la test, oppure con un commento particolare, es:
 *
 * public function test_it_names_things_well()
 * public function testItNamesThingsWell()
 * /** @test */
/* public function it_names_things_well()
   public function it_names_things_well() // non verrà lanciato
 *
 * .ENV FILE, FILE DI CONFIGURAZIONE
 * la configurazione dei test non è il file .env ma il file phpunit.xml
 */

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {

        //logger("Enviroment: " . app()->environment());
        //logger("APP_NAME: " . env('APP_NAME'));
        //logger("APP_ENV: " . env('APP_ENV'));

        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
