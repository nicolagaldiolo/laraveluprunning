<?php

namespace Tests\Feature;

use App\Task;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TestUserRegisterMissingFieldTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_register_missing_field() // tentiamo la registrazione e ci accertiamo che ci sia un errore
        // di validazione sul campo email
    {

        $response = $this->post('register', ['name' => 'Nicola']);
        $response->assertSessionHasErrors(['email', 'password']);
        $response->assertSessionHasErrors('email', 'The email field is required.');
    }

    public function test_show_has_message_errors() // qui facciamo la stessa cosa però viene fatto un redirect in quanto
        // la rotta generate_errors fa un redirect
    {

        $this->get('generate_errors')
            ->assertSessionHasErrors('errors')
            ->assertSessionHasErrors('messages')
            ->assertSessionHasErrors('pluto');
    }

}
