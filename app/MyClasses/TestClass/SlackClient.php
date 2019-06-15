<?php


namespace App\MyClasses\TestClass;

/*
 * Classe SlackClient che dovrebbe servire per mandare notifiche slack,
 * la usiamo solo per scopi di test quindi implementa solo un metodo send
 */

class SlackClient
{
    public function send($message, $channel)
    {
        // Should send a Slack Message
        $this->log("Invocato metodo log");
    }

    public function log($message)
    {
        logger($message);
    }

}