<?php


namespace App\MyClasses\TestClass;


class Notifier
{
    private $slack;

    public function __construct(SlackClient $slack)
    {
        $this->slack = $slack;
    }

    public function notifyAdmin($message)
    {
        $this->slack->send($message, 'admins');
    }

}