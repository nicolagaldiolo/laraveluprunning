<?php

namespace Tests\Feature;

use App\Task;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TestJsonMethodTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_api_returns_certain_json()
    {
        $email2Check = 'taylor@laravel20.com';
        factory(User::class)->create([
            'email' => $email2Check,
        ]);

        $response = $this->json('get', 'api/users');
        $response->assertJsonFragment(['email' => $email2Check]);

    }

}
