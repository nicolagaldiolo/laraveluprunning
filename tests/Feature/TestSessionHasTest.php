<?php

namespace Tests\Feature;

use App\Task;
use http\Env\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TestSessionHasTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function test_session_has_test()
    {

        $this->get('session')
            ->assertSessionHas('foo')
            ->assertSessionHas('foo', 'bar')
            ->assertSessionHasAll([
                'foo' => 'bar',
                'baz' => 'qux'
            ]);
    }

    public function test_session_has_error_test(){
        $this->post('actions', [])
            ->assertSessionHasErrors() // senza parametri si assicura che ci sia almeno un errore
            ->assertSessionHasErrors(['title' => 'The title field is required.', 'body' => 'The body field is required.'])
            // indica il formato del messaggio di errore, se la stringa di errore fosse <p>The title field is required.</p> il formato dovrà essere <p>:message</p>
            ->assertSessionHasErrors([
                'title' => 'The title field is required.',
                'body' => 'The body field is required.'
            ],':message');
    }

    public function test_add_to_session(){ // test aggiunta dati in sessione

        $this->session(['simple_data_session' => 'value' ]);

        // testo di avere dati in sessione
        $this->get('/')
            ->assertStatus(200)
            ->assertSessionHas('simple_data_session')
            ->assertSessionHas('simple_data_session', 'value');

        // svuoto la sessione
        $this->flushSession();

        // testo di NON avere dati in sessione
        $this->get('/')
            ->assertStatus(200)
            ->assertSessionMissing('simple_data_session');
    }
}
