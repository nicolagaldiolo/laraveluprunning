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
        ->assertSessionHas('foo') // ha questa chiave
        ->assertSessionHas('foo', 'bar') // ha questa chiave con questo valore
        ->assertSessionHasAll([
                'baz', // posso verificare anche solo la chiave (senza specificare alcun valore, in questo caso si assicura solo che la chiave esista ma se ne frega del valore)
                'foo' => 'bar',
                'baz' => 'qux'
        ]); // ha queste chiavi con questi valori
    }

    public function test_session_has_error_test(){
        $this->post('actions', []) // non passo alcun parametro, mentre la request si aspetta 'title' e 'body'
            ->assertSessionHasErrors() // senza parametri si assicura che ci sia almeno un errore
            ->assertSessionHasErrors(['title' => 'The title field is required.', 'body' => 'The body field is required.']) //verifica che ci siano errori con quei chiave/valore
            ->assertSessionHasErrors(['title', 'body']) //verifica che ci siano errori con quelle chiavi, ignorando gli eventuali valori
            ->assertSessionHasErrors([
                'title' => 'The title field is required.',
                'body' => 'The body field is required.'
            ],':message'); //verifica che ci siano errori con quei chiave/valore e che i valori rispecchino quel formato (se la stringa di errore fosse <p>The title field is required.</p> il formato dovrà essere <p>:message</p>)

    }


    // Verifico che in sessione ci siano i vecchi valori del campo input
    public function test_session_has_old_inputt(){
        $this->post('actions', ['title' => 'pippo']);
        $this->assertTrue(session()->hasOldInput('title'));
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

        // testo di NON avere dati in sessione (ci può essere la chiave, ma non deve essere valorizzata, quindi non
        // deve essere presente la chiave o al max valorizzata a null)
        $this->get('session')
            ->assertStatus(200)
            ->assertSessionMissing('simple_data_session')
            ->assertSessionMissing('keyNull');
    }
}
