<?php

/*
 * GUARD TUTORIAL
 * https://medium.com/@JinoAntony/multi-user-authentication-using-guards-in-laravel-5-6-f18b4e61bdde
 */

Route::group(['middleware' => 'auth'], function(){
    Route::get('guards', 'AuthenticationController@guards');
    Route::get('authentication', 'AuthenticationController@index');
    Route::get('authenticationManual', 'AuthenticationController@authenticationManual');
});

Route::get('trainees/login', 'Auth\TraineesLoginController@showLoginForm')->name('trainees.showloginform');
Route::post('trainees/login', 'Auth\TraineesLoginController@login')->name('trainees.login');

Route::get('trainees/register', 'Auth\TraineesRegisterController@showRegistrationForm')->name('trainees.showregistrationform');
Route::post('trainees/register', 'Auth\TraineesRegisterController@register')->name('trainees.register');

Route::group(['prefix'=>'trainees', 'middleware' => 'auth.custom:trainees,trainees/login'], function(){
    Route::get('home', function(){
        return view('traineeshome');
    });
});
