<?php

namespace Tests\Feature;

use App\Task;
use http\Env\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TestRedirectToTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function test_redirect()
    {
        $this->get('redirect-to')
            ->assertStatus(302)
            //->assertRedirectTo('login') // punto alla rotta || SEMBRA NON PIù VALIDO
            //->assertRedirectToAction('LoginController@login') // punto al metodo del controller || SEMBRA NON PIù VALIDO
            ->assertRedirect('login'); // punto all'uri
    }
}
