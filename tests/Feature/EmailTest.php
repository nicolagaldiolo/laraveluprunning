<?php

namespace Tests\Feature;

use App\Dog;
use App\Mail\Assignment;
use App\Mail\UserMailer;
use App\Task;
use App\Trainee;
use App\User;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EmailTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_email()
    {

        // Trasformo la facade mail in un fake (faccio mocking)
        Mail::fake();

        // creo utente e trainee
        $user = factory(User::class)->create();
        $trainee = factory(Trainee::class)->create();

        // Verifico che nessuna mail sia stata ancora spedita
        Mail::assertNothingSent();

        // invio la mail
        Mail::to('me@app.com')->cc($user)->send(new Assignment($trainee));

        // Verifico che una mail sia stata spedita
        Mail::assertSent(Assignment::class, 1);

        // Verifico che la mail inviata abbia i dati che mi aspetto
        Mail::assertSent(Assignment::class, function ($mail) use ($trainee, $user){
            return $mail->trainee->name === $trainee->name &&
                $mail->cc[0]['address'] === $user->email;
        });

        // Verifico che nessuna mail sia inviata con la classe UserMailer
        Mail::assertNotSent(UserMailer::class);
    }
}
