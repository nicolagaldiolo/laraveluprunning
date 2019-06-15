<?php

Route::get('eloquent_relation/onetoone', 'EloquentRelationsController@onetoone');
Route::get('eloquent_relation/onetomany', 'EloquentRelationsController@onetomany');
Route::get('eloquent_relation/onetomany_example', 'EloquentRelationsController@onetomanyExample');
Route::get('eloquent_relation/hasmanythrough', 'EloquentRelationsController@hasmanythrough');
Route::get('eloquent_relation/manytomany', 'EloquentRelationsController@manytomany');
Route::get('eloquent_relation/polymorphic', 'EloquentRelationsController@polymorphic');
Route::get('eloquent_relation/polymorphic_many2many', 'EloquentRelationsController@polymorphicMany2Many');