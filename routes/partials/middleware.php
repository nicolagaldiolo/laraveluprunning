<?php

Route::get('call_delete_method', function(){
    return view('middleware.form');
});

Route::delete('delete_method_action', function(){ // c'è un middleware generale caricato a livello di Route Group definito nel App\Http\Kernel.php file
    dd("Record cancellato");
});

Route::get('middleware_with_params', function(){
    return "middleware with params";
})->middleware('middlewareWithParams:true,fakeParam');

Route::get('onlyAdmin', function(){
    return "Si, sei un admin";
})->middleware('isAdmin');