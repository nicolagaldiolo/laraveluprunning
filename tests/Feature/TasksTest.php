<?php

namespace Tests\Feature;

use App\Task;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TasksTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_post_create_new_task()
    {

        $title = 'test data title';
        $description = 'test data description';

        $this->post('tasks', [
            'title' => $title,
            'description' => $description,
        ]);

        $this->assertDatabaseHas('tasks', [
            'title' => $title,
            'description' => $description,
        ]);
    }

    public function test_list_page_shows_all_tasks()
    {
        $title = 'Fare compere';
        $description = 'Compra latte, caffè, cioccolato';
        $tasks = Task::create([
            'title' => $title,
            'description' => $description,
        ]);

        $this->get('tasks')
            ->assertSee($title)
            ->assertSee($description);
    }
}
