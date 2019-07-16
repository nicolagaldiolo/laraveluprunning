<?php

namespace Tests\Feature;

use App\Events\UserSubscribed;
use App\User;
use Tests\TestCase;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EventTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        Event::fake();

        $user = factory(User::class)->create();
        $plan = 'Test';

        Event::dispatch(new UserSubscribed($user, $plan));

        Event::assertDispatched(UserSubscribed::class, function($event) use ($user, $plan){
            return $event->user->id === $user->id && $event->plan === $plan;
        });


    }
}
