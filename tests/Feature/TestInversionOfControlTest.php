<?php

namespace Tests\Feature;

use App\MyClasses\LoremIpsum;
use App\MyClasses\LoremIpsumInterface;
use App\MyClasses\TestLoremIpsum;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TestInversionOfControlTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    /*
     * BINDARE OGGETTI ALL'INTERNO DEL CONTAINER
     * QUESTO HA VALIDITA' LOCALE ALL'INTERNO DI TUTTA LA CLASSE, SE VOGLIO ESTENDERMI A TUTTI I TEST DEVO SETTARE IL METODO setUp() NEL FILE /tests/TestCase.php
     */

    //public function setUp(): void
    //{
    //    parent::setUp();
    //    app()->bind(LoremIpsumInterface::class, function (){
    //        return new TestLoremIpsum();
    //    });
    //}

    public function testExample()
    {

        // All'interno dell'app la classe loremIpsum è la classe LoremIpsum che invocata sul metodo ->sentence() genera una stringa casuale
        // mentre qui bindo la classe loremIpsum con la classe TestLoremIpsum
        app()->bind('loremIpsum', TestLoremIpsum::class);

        // chiamo il metodo test del controller FacadeController
        $response = app()->call('App\Http\Controllers\FacadeController@test');

        // e mi assicuro che l'oggetto che mi risponde sia di tipo TestLoremIpsum
        $this->assertTrue($response == app(TestLoremIpsum::class)->sentence());
    }

    public function testExampleInterface()
    {
        /*
         * BINDARE OGGETTI ALL'INTERNO DEL CONTAINER
         * QUESTO HA VALIDITA' LOCALE ALL'INTERNO DI QUESTO METODO, SE VOGLIO ESTENDERMI A TUTTI I METODI DELLA CLASSE SETTARE IL METODO setUp()
         */

        // All'interno dell'app il metodo test_interface del controller FacadeController implementa l'interfaccia LoremIpsumInterface
        // che è bindata sulla classe LoremIpsum::class che quando invocata sul metodo ->sentence() genera una stringa casuale
        // mentre qui bindo l'interfaccia LoremIpsumInterface sulla classe TestLoremIpsum
        app()->bind(LoremIpsumInterface::class, function (){
            return new TestLoremIpsum();
        });

        // chiamo il metodo test_interface del controller FacadeController
        $response = app()->call('App\Http\Controllers\FacadeController@test_interface');

        // e mi assicuro che l'oggetto che mi risponde sia di tipo TestLoremIpsum
        $this->assertTrue($response == app(TestLoremIpsum::class)->sentence());
    }
}
