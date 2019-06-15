<?php

namespace Tests\Feature;

use App\Task;
use App\Trainee;
use http\Env\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SeedCommandTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function test_seed_command()
    {
        // lancio il seeder (dietro le quinte lancia un comando artisan)
        $this->seed(); // lancio tutti i seeder
        $this->seed('TraineeSeeder'); // lancio solo quello specificato

        // torno il primo elemento
        $trainess = Trainee::first()->toArray();

        // verifico se è presente a db
        $this->assertDatabaseHas("trainees", $trainess);
    }

    public function test_seed_with_artisan_command()
    {

        $this->artisan('db:seed --class=TraineeSeeder')
            ->assertExitCode(0); // l'exit code è il numero di errori ritornati.

        // torno il primo elemento
        $trainess = Trainee::first()->toArray();

        // verifico se è presente a db
        $this->assertDatabaseHas("trainees", $trainess);
    }
}
