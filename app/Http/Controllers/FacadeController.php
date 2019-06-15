<?php

namespace App\Http\Controllers;

use App\MyClasses\LoremIpsum;
use App\MyClasses\LoremIpsumFacade;
use App\MyClasses\LoremIpsumInterface;
use Illuminate\Http\Request;

/*
 * FACADE
 * Le facade sono delle classi che forniscono un accesso "statico" ad altre classi che non sono statiche.
 * Non sono gratis ma necessitano di essere create di volta in volta per ogni classe e registrate in config->app->aliases
 * Laravel ci fornisce una serie di facade: https://laravel.com/docs/5.8/facades#facade-class-reference
 *
 * Le facade fanno da proxi alle classi a cui fanno riferimento, ossia quando viene chiamata una facade, es: facade:method()
 * dietro le quinte laravel crea un instanza della classe utilizzando il container e chiama il metodo che noi abbiamo chiamato sulla facade, es:
 *
 *      Log::error('Help!')
 *
 *      // è l'equivalente di
 *
 *      app('Log::class')->error('Help!')
 */

class FacadeController extends Controller
{

    public function index(LoremIpsum $loremIpsumIstance)
    {
        // Possiamo accedere alla classe LoremIpsum in vari modi:

        //1 Utilizzando la facade
        $data = LoremIpsumFacade::sentence();

        //2 Creando un istanza della classe utilizzando l'helper app() sia utilizzando l'alias della classe registrato
        // nel service Provider o utilizzando il FQCN
        $data = app('loremIpsum')->sentence();
        $data = app(LoremIpsum::class)->sentence();

        //3 Utilizzando la dependency injection
        $data = $loremIpsumIstance->sentence();

        dd($data);

    }

    public function test()
    {
        $loremIpsumIstance = app('loremIpsum');
        return $loremIpsumIstance->sentence();

    }

    public function test_interface(LoremIpsumInterface $loremIpsum)
    {
        return $loremIpsum->sentence();
    }
}
