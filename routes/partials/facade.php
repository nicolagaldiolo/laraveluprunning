<?php

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

Route::get('facade', 'FacadeController@index');
Route::get('facade_test', 'FacadeController@test');
Route::get('facade_test_interface', 'FacadeController@test_interface');