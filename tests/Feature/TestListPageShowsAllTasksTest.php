<?php

namespace Tests\Feature;

use App\Task;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TestListPageShowsAllTasksTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_list_page_shows_task_field() // creiamo dei task e ci assicuriamo che nella vista ci siano basandoci sul campo titolo
    {

        $task1 = factory(Task::class)->create();
        $task2 = factory(Task::class)->create();

        $this->get('tasks')
            ->assertSee($task1->title)
            ->assertSee($task2->title);
    }

    public function test_list_page_shows_all_tasks() // creiamo dei task e ci assicuriamo che nella vista ci siano un certo particolare set di dati
    {

        $task1 = factory(Task::class)->create();
        $task2 = factory(Task::class)->create();

        $this->get('tasks')
            ->assertViewHas('tasks', Task::with('actions')->get())
            ->assertViewHasAll([
                'tasks' => Task::with('actions')->get(),
                'title' => 'Tasks page'
            ])->assertViewMissing('dogs');
    }


    public function test_list_page_check_data_structured() // esattamente come sopra ma utilizziamo una clousure per poter effettuare dei controlli più specifici
    {

        $task1 = factory(Task::class)->create();
        //logger("mio: " . $task1);
        //logger("mio->id: " . $task1->id);

        $this->get('tasks/' . $task1->id)
            ->assertViewHas('task', function($task) use($task1){
                return $task->id === $task1->id;
            });
    }
}
