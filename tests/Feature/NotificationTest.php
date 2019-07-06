<?php

namespace Tests\Feature;

use App\Dog;
use App\Mail\Assignment;
use App\Notifications\WorkoutAvailable;
use App\Task;
use App\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NotificationTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_notification()
    {

        // Trasformo la facade Notification in un fake (faccio mocking)
        Notification::fake();

        // Verifica che nessuna notifica sia partita
        Notification::assertNothingSent();

        // creo utente e definisco workout
        $user = factory(User::class)->create();
        $user2 = factory(User::class)->create();
        $workout = "Giro in bici";

        // Invio la notifica
        Notification::send($user, new WorkoutAvailable($workout));

        // Verifico che la notifica sia sta inviata all'utente passato
        Notification::assertSentTo( [$user], WorkoutAvailable::class);
        Notification::assertNotSentTo( [$user2], WorkoutAvailable::class);

        // Verifico che la notiifica inviata abbia i dati che mi aspetto
        Notification::assertSentTo( $user, WorkoutAvailable::class, function($notification, $channel) use ($workout){
                return $notification->workout === $workout && in_array('mail', $channel);
            }
        );
    }
}
