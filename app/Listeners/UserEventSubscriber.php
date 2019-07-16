<?php

namespace App\Listeners;

use App\Events\UserCancelled;
use App\Events\UserSubscribed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserEventSubscriber
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function onUserSubscription($event)
    {
        logger('User Subscription');
    }

    public function onUserCancellation($event)
    {
        logger('User Cencallation');
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function subscribe($events)
    {

        //l'associazione tra eento e listener la faccio qui dove ogni listener è un metodo della classe

        $events->listen(
            UserSubscribed::class,
            'App\Listeners\UserEventSubscriber@onUserSubscription'
        );

        $events->listen(
            UserCancelled::class,
            'App\Listeners\UserEventSubscriber@onUserCancellation'
        );
    }
}
