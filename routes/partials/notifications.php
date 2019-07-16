<?php

/*
 * USE Notifiable TRAITS SUL MODELLO
 * Affinchè un entità sia notificabile il modello deve usare il traits Notifiable così posso usare i metodo notify() sul modello
 *
 * QUEUE
 * Affichè la notifica venga messa in coda occorre implementare l'interfaccia ShouldQueue nella classe
 * Inoltre usando il metodo ->delay() posso impostare un ritardo sull'invio
 *
 * WorkoutAvailable è una classe di notifica che si può creare con:
 * php artisan make:notification WorkoutAvailable
 *
 * // CANALI DI NOTIFICA (third-parts)
 * http://laravel-notification-channels.com/
 */

Route::get('notification', function(){

    //PUO ESSERE USATA DIRETTAMENTE SUL MODELLO NOTIFICABILE
    $user = \App\User::first();
    $workout = 'Fare un ora di corsa';
    $when = \Carbon\Carbon::now()->addMinutes(0);

    $user->notify((new \App\Notifications\WorkoutAvailable($workout))->delay($when));

    //PUO ESSERE USATA CON LA FACADE CHE MI PERMETTE DI LANCIARE LA NOTIFICA SU PIù UTENTI CONTEMPORANEAMENTE
    $users = \App\User::take(1)->get();
    \Illuminate\Support\Facades\Notification::send($users, (new \App\Notifications\WorkoutAvailable($workout))->delay($when));
});

Route::get('notification_broadcasted', function(){

    //PUO ESSERE USATA DIRETTAMENTE SUL MODELLO NOTIFICABILE
    $user = Auth::user();
    $workout = 'Fare un ora di corsa';
    $when = \Carbon\Carbon::now()->addMinutes(0);

    $user->notify((new \App\Notifications\WorkoutAvailable($workout))->delay($when));

    //PUO ESSERE USATA CON LA FACADE CHE MI PERMETTE DI LANCIARE LA NOTIFICA SU PIù UTENTI CONTEMPORANEAMENTE
    //$users = \App\User::all();
    //\Illuminate\Support\Facades\Notification::send($users, (new \App\Notifications\WorkoutAvailable($workout))->delay($when));
})->middleware('auth');

// OTTENERE TUTTE LE NOTIFICHE SALVATE A DB
Route::get('get_database_notification', function(){

    $user = \App\User::first();
    // Ottenere una la collection di tutte le notifiche
    $notifications = $user->notifications;
    $unread_notifications = $user->unreadNotifications;

    // marcare le notidiche come lette
    // UNO
    $user->notifications->each(function($notification){
        if(is_null($notification->read_at)){
            $notification->markAsRead();
        }
    });

    // TUTTE
    $user->unreadNotifications->markAsRead();

    return [
        'notifications' => $notifications,
        'unread_notifications' => $unread_notifications
    ];
});