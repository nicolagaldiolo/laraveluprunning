<?php

namespace Tests\Feature;

use App\Task;
use http\Env\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TestViewHasTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function test_list_page_shows_all_tasks()
    {

        // creiamo dei task e ci assicuriamo che nella vista ci siano un certo particolare set di dati
        // i assicuro che alla vista venga passata la ariabile dal controller ma non che sia stampata nella vista

        $task1 = factory(Task::class)->create();
        $task2 = factory(Task::class)->create();

        $this->get('tasks')
            ->assertViewHas('tasks')
            ->assertViewHas('tasks', Task::with('actions')->get())
            ->assertViewHasAll([
                'tasks' => Task::with('actions')->get(),
                'title' => 'Tasks page'
            ])->assertViewMissing('dogs');
    }


    public function test_list_page_check_data_structured() // esattamente come sopra ma utilizziamo una clousure per poter effettuare dei controlli più specifici
    {

        $task1 = factory(Task::class)->create();

        $this->get('tasks/' . $task1->id)
            ->assertViewHas('task', function($task) use($task1){
                return $task->id === $task1->id;
            });
    }

    public function test_list_page_check_data_structured2() // esattamente come sopra ma utilizziamo una clousure per poter effettuare dei controlli più specifici
    {

        $task1 = factory(Task::class)->create();

        $this->get('tasks')
            ->assertViewHas('tasks', function($tasks) use($task1){
                return $myTasks = $tasks->reject(function($task) use($task1){
                    return $task->id === $task1->id;
                })->isEmpty();
            });
    }
}
