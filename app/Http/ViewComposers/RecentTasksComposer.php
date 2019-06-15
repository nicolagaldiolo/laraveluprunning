<?php

namespace App\Http\ViewComposers;

use App\Task;
use Illuminate\View\View;

class RecentTasksComposer
{
    private $tasks;

    public function __construct(Task $tasks)
    {
        $this->tasks = $tasks;
    }

    public function compose(View $view)
    {
        $view->with('tasks', $this->tasks->latest()->get());
        // avremmo anche potuto evitare di creare la variabile $tasks e creare il costruttore, bastava chiamare direttamente la facade
        //$view->with('tasks', Task::latest()->get());
    }
}