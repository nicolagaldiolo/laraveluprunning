<?php

namespace Tests;

use App\MyClasses\LoremIpsumInterface;
use App\MyClasses\TestLoremIpsum;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

/*
 * TEST CASE BASE
 * Ogni test estende questa classe che a sua volta estende la classe BaseTestCase
 * che fa il bootstrap dell'applicazione, refresha l'applicazione ad ogni test in modo da non avere dati sporchi, e ci mette a disposizione
 * degli hook che vengono lanciati prima e dopo ogni test, dei trait come InteractWithContainer, MakeHttpRequest, InteractswithClonsole, ecc
 * Infine ci mette a disposizione anche un DOM crawler
 */

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /*
     * DISATTIVAZIONE DEI MIDDLEWARE
     * disattivo i middleware nei test (facendolo qui lo sto facendo a livello globale, posso intenvenire anche sul singolo metodo)
     */
    //use WithoutMiddleware;

    /*
     * USO DELLE DATABASE MIGRATION
     * viene lanciata la migrazione del db ad ogni test e viene fatta la roolback dopo ogni test
     * vengono sfruttati i metodi setUp() e tearDown() di ogni classe
     */
    //use DatabaseMigrations;

    /*
     * USO DELLE DATABASE TRANSACTION
     * wrappa ogni test in una transazione e alla fine del test fa la roolback. in questo modo il db è sempre pulito.
     * Ma si aspetta di trovare il database con tutte le migration applicate
     */
    //use DatabaseTransactions;

    /*
     * USO DI REFRESH DATABASE
     * Questo mette insieme DatabaseMigrations e DatabaseTransactions se non trova il db migrate lancia le migrazioni e svuota i dati alla fine di ogni test
     *
     */
    use RefreshDatabase;



    /*
     * BINDARE OGGETTI ALL'INTERNO DEL CONTAINER
     * QUESTO HA VALIDITA' PER TUTTE LE CLASSI TEST
     */

    /*
    public function setUp(): void
    {
        parent::setUp();
        app()->bind(LoremIpsumInterface::class, function (){
            return new TestLoremIpsum();
        });
    }
    */
}
