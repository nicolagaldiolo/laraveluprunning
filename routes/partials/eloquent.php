<?php

Route::get('queryBuilder', 'QueryBuilderExampleController@index');
Route::get('eloquent', 'EloquentExampleController@index');
Route::get('eloquent/show', 'EloquentExampleController@show');
Route::get('eloquent/create', 'EloquentExampleController@create');
Route::get('eloquent/update', 'EloquentExampleController@update');
Route::get('eloquent/destroy', 'EloquentExampleController@destroy');
Route::get('eloquent/softdelete', 'EloquentExampleController@softdelete');
Route::get('eloquent/scope', 'EloquentExampleController@scope');
Route::get('eloquent/accessors', 'EloquentExampleController@accessors');
Route::get('eloquent/attributeCasting', 'EloquentExampleController@attributeCasting');
Route::get('eloquent/collections', 'EloquentExampleController@collections');
Route::get('eloquent/serialization', 'EloquentExampleController@serialization');
Route::get('eloquent/updateParentTimestamp', 'EloquentExampleController@updateParentTimestamp');
Route::get('eloquent/eagerLoading', 'EloquentExampleController@eagerLoading');
Route::get('eloquent-test/active-contacts', function() {
    return \App\Contact::all();
});
Route::post('eloquent-test/create-events', function(\Illuminate\Http\Request $request) {
    \App\Event::create(['name' => $request->input('name')]);
});