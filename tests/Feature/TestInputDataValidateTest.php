<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TestInputDataValidateTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_input_missing_data()
    {
        $this->post('actions_manual', [
            'body' => 'This is the body generate with test'
        ])->assertRedirect('actions/create')
            ->assertSessionHasErrors();
            //->assertHasOldInput();
    }

    public function test_input_all_data()
    {
        $data = [
            'title' => 'This is the title generate with test',
            'body' => 'This is the body generate with test',
        ];

        $this->post('actions_manual', $data);
        $this->assertDatabaseHas('actions', $data);
    }
}
