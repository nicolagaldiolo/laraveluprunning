<?php

namespace App\Listeners;

use App\Events\UserSubscribed;
use App\Mail\OwnerSubscriptionEmail;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailOwnerAboutSubscription
{
    /**
     * Create the event listener.
     *
     * @return void
     */

    protected $mailer;
    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Handle the event.
     *
     * @param  UserSubscribed  $event
     * @return void
     */
    public function handle(UserSubscribed $event)
    {
        $this->mailer->send(
            new OwnerSubscriptionEmail($event->user)
        );
    }
}
