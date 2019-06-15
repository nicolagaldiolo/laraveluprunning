<?php

namespace Tests\Feature;

use App\Task;
use http\Env\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArtisanCommandTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function test_artisan_command()
    {
        $this->artisan('email:newusers')
            ->assertExitCode(0); // l'exit code è il numero di errori ritornati.


        $this->artisan('password:reset', ['userId' => 1, '--sendEmail' => true])
            ->assertExitCode(0);
    }
}
