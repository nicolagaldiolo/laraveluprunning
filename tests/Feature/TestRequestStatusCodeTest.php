<?php

namespace Tests\Feature;

use App\Task;
use App\User;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TestRequestStatusCodeTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_request_status_code()
    {
        $response = $this->get('api/users');
        $response->assertStatus(200);

        $response = $this->call('delete', 'users'); // la rotta esista ma non per quel verbo HTTP
        $response->assertStatus(405); // Method not allowed

    }

    public function test_method_authorization()
    {
        $user = factory(User::class)->create([
            'email' => 'taylor@laravel30.com',
        ]);

        // chiamo una rotta protetta da middleware dove viene controllata l'abilità create-person
        // non essendo loggato non posso avere questa abilità quindi ricevo errore 403
        $request = $this->call('get', 'people/create');
        $request->assertStatus(403);

        auth()->login($user);

        // dopo essermi loggato (dato che nel AppServiceProvider metodo boot() tutti gli utenti a prescindere hanno questa abilità)
        // verifico che lo stato sia 200
        $request = $this->call('get', 'people/create');
        $request->assertStatus(200);

    }

}
