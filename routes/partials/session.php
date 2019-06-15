<?php

Route::get('session', function(\Illuminate\Http\Request $request){

    $request->session()->flash('foo', 'bar');
    $request->session()->flash('baz', 'qux');

    return 'Hello World';
});
