<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

//DEFINISCO LE REGOLE CON CUI UN UTENTE PUò ACCEDERE A UN CANALE PRIVATO,
//IN QUESTO CASO SE è LUI STESSO

// Canale usato per le notifiche che vengono sparate tramite broadcast
Broadcast::channel('App.User.{id}', function ($user, $id) {
    logger($user);
    logger($id);
    return true; //(int) $user->id === (int) $id;
});


Broadcast::channel('teams.{id}', function ($user, $id){
    return (int) $user->id === (int) $id;
});

//DEFINISCO LE REGOLE CON CUI UN UTENTE PUò ACCEDERE A UN CANALE PRESENCE,
//IN QUESTO CASO SE ne fa parte

Broadcast::channel('rooms.{id}', function ($user, $id){
    //if($user->rooms->contains($id)){
    if(true){ // setto sempre a true perchè non ho il concetto di rooms all'interno dell'applicazione e fallirebbe sempre
        return [
            'name' => $user
        ];
    };
});

