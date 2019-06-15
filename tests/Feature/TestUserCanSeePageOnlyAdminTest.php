<?php

namespace Tests\Feature;

use App\Task;
use App\User;
use http\Env\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TestUserCanSeePageOnlyAdminTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function test_user_only_admin()
    {

        $user = factory(User::class)->create([
            'admin' => 1
        ]);

        $this->get('/onlyAdmin')
            ->assertStatus(403);

        $this->be($user); // mi loggo con l'utente

        $this->get('/onlyAdmin')
            ->assertStatus(200);
    }
}
